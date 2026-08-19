<?php
session_start();
include "config.php";

// Désactiver l'affichage direct des erreurs pour ne pas casser le JSON
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Démarrer un buffer pour capturer toute sortie accidentelle
ob_start();

// Définir le type de contenu JSON
header('Content-Type: application/json; charset=utf-8');

// Vérifier que le message n'est pas vide
if (!isset($_POST['message']) || trim($_POST['message']) === '') {
    echo json_encode(['success' => false, 'error' => 'Message vide']);
    exit;
}

$message = trim($_POST['message']);

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    $output = "⚠️ Vous n'êtes pas connecté. Veuillez vous connecter ou créer un compte pour profiter pleinement de Green Chat.";
    echo json_encode(['success' => true, 'messages' => [
        ['sender' => 'bot', 'text' => $output, 'avatar' => 'uploaded_img/Logo Green Engineering OK.png']
    ]]);
    exit;
}

$user_id = $_SESSION['user_id'];

// --- Logique du bot migrée en PHP ---

// Fonction pour obtenir la langue de l'utilisateur
function get_user_language($user_id, $conn) {
    $stmt = $conn->prepare("SELECT langue FROM user_form WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row ? $row['langue'] : 'fr';
}

// Nettoyage texte
function clean_text($text) {
    return strtolower(trim(preg_replace('/\s+/', ' ', $text)));
}

// Tokenization des phrases (simple split sur . ? !)
function sent_tokenize($text) {
    return preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
}

// Calcul TF-IDF simple
function compute_tfidf($documents) {
    $num_docs = count($documents);
    $all_terms = [];
    $doc_freq = [];
    $tfidf = [];

    foreach ($documents as $doc) {
        $terms = array_count_values(str_word_count($doc, 1));
        foreach ($terms as $term => $count) {
            $all_terms[$term] = true;
            $doc_freq[$term] = ($doc_freq[$term] ?? 0) + 1;
        }
    }

    foreach ($documents as $idx => $doc) {
        $terms = array_count_values(str_word_count($doc, 1));
        $tfidf[$idx] = [];
        foreach ($terms as $term => $tf) {
            $idf = log($num_docs / ($doc_freq[$term] ?? 1));
            $tfidf[$idx][$term] = $tf * $idf;
        }
    }

    return [$tfidf, array_keys($all_terms), $doc_freq];
}

// Cosine similarity simple
function cosine_similarity($vec1, $vec2, $all_terms) {
    $dot_product = 0;
    $norm1 = 0;
    $norm2 = 0;

    foreach ($all_terms as $term) {
        $v1 = $vec1[$term] ?? 0;
        $v2 = $vec2[$term] ?? 0;
        $dot_product += $v1 * $v2;
        $norm1 += $v1 * $v1;
        $norm2 += $v2 * $v2;
    }

    $norm1 = sqrt($norm1);
    $norm2 = sqrt($norm2);

    return ($norm1 * $norm2) > 0 ? $dot_product / ($norm1 * $norm2) : 0;
}

// Recherche des extraits les plus similaires
function recherche_similaire($question, $phrases, $tfidf_matrix, $all_terms, $doc_freq, $top_n = 3) {
    $question_clean = clean_text($question);
    $vecteur_question_terms = array_count_values(str_word_count($question_clean, 1));
    $vecteur_question = [];
    foreach ($vecteur_question_terms as $term => $tf) {
        $idf = log(count($phrases) / ($doc_freq[$term] ?? 1));
        $vecteur_question[$term] = $tf * $idf;
    }

    $similarites = [];
    foreach ($tfidf_matrix as $idx => $vec) {
        $similarites[$idx] = cosine_similarity($vecteur_question, $vec, $all_terms);
    }

    arsort($similarites);
    $indices_top = array_slice(array_keys($similarites), 0, $top_n);
    return array_unique(array_map(fn($i) => $phrases[$i], $indices_top));
}

// Reformulation avec Cohere via cURL
function reformuler_texte($contenu, $question, $langue = "fr") {
    $api_key = "VOTRE_CLE_API";
    $prompt = "
Tu es Green Chat, assistant virtuel de Green Engineering SARL. Voici des informations de l'entreprise :
\"$contenu\"

Réponds à la question ci-dessous de manière claire et professionnelle, uniquement en " . strtoupper($langue) . " :

Question : $question
";

    $data = json_encode([
        'model' => 'command-r',
        'message' => $prompt,
        'temperature' => 0.6,
        'max_tokens' => 200
    ]);

    $ch = curl_init('https://api.cohere.ai/v1/chat');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $api_key,
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return "Erreur IA : " . curl_error($ch);
    }
    curl_close($ch);

    $json = json_decode($response, true);
    return trim($json['text'] ?? "Erreur lors de la réponse IA.");
}

// Fonction pour structurer la réponse avec Hugging Face API
function structure_response_with_hf($raw_response, $langue) {
    $hf_api_key = "VOTRE_CLE_API"; // Remplacez par votre clé API Hugging Face
    $prompt = "
Structure la réponse suivante en Markdown pour un chat, en $langue :
- Utilise des **mots-clés en gras**.
- Des - tirets pour les listes.
- Des retours à la ligne pour la lisibilité.
- Une section **Réponse principale** au début.
- Si pertinent, une section **Conseils** à la fin avec des exemples.

Réponse brute : $raw_response
";

    $data = json_encode([
        'inputs' => $prompt,
        'parameters' => ['max_length' => 300, 'temperature' => 0.3],
        'options' => ['wait_for_model' => true]
    ]);

    $ch = curl_init('https://api-inference.huggingface.co/models/facebook/bart-large');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $hf_api_key,
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return $raw_response; // Fallback à la réponse brute si erreur
    }
    curl_close($ch);

    $json = json_decode($response, true);
    return trim($json[0]['generated_text'] ?? $raw_response);
}

// Détection de commande
function detect_command($msg) {
    if (preg_match_all('/(?:commande de|je veux|je voudrais|j(?:\'|e )?aimerais|il me faut)\s+(?:un|une|\d+)\s+([\w\s\'\-]+(?: de [\w\s\d]+)?)/i', $msg, $matches, PREG_SET_ORDER)) {
        $commands = [];
        foreach ($matches as $match) {
            $quantite_str = preg_match('/(?:un|une|\d+)/i', $match[0], $qty) ? $qty[0] : '1';
            $quantite = (strtolower($quantite_str) === 'un' || strtolower($quantite_str) === 'une') ? 1 : intval($quantite_str);
            $produit = trim($match[1]);
            $commands[] = ["quantite" => $quantite, "produit" => $produit, "unite" => "unité"];
        }
        return $commands ?: null;
    }
    return null;
}

// Détection de négociation
function detecter_negociation($msg) {
    $msg = strtolower($msg);
    $mots_negociation = [
        "réduction", "remise", "baisser", "négocier", "moins cher", "prix trop élevé", "rabais", "peux-tu faire un effort",
        "can you lower the price", "discount", "cheaper", "negotiate", "negociar", "rebaja", "más barato"
    ];
    foreach ($mots_negociation as $mot) {
        if (strpos($msg, $mot) !== false) {
            return true;
        }
    }
    return false;
}

// Fonctions BDD pour commandes temp
function insert_temp_command($user_id, $cmd, $conn) {
    $stmt = $conn->prepare("DELETE FROM commandes_temp WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    $valeur = json_encode(["produit" => $cmd["produit"], "quantite" => $cmd["quantite"], "unite" => $cmd["unite"]]);
    $stmt = $conn->prepare("INSERT INTO commandes_temp (user_id, etape, valeur) VALUES (?, 'nom_client', ?)");
    $stmt->bind_param("is", $user_id, $valeur);
    $stmt->execute();
    $stmt->close();
}

function update_temp_info($user_id, $field, $value, $conn) {
    $etape = $field === "nom_client" ? "lieu_livraison" : ($field === "lieu_livraison" ? "telephone" : ($field === "telephone" ? "email" : "fini"));
    $data = get_temp_command($user_id, $conn);
    $current_data = json_decode($data["valeur"], true);
    $current_data[$field] = $value;
    $valeur = json_encode($current_data);
    $stmt = $conn->prepare("UPDATE commandes_temp SET etape = ?, valeur = ? WHERE user_id = ?");
    $stmt->bind_param("ssi", $etape, $valeur, $user_id);
    $stmt->execute();
    $stmt->close();
}

function get_temp_command($user_id, $conn) {
    $stmt = $conn->prepare("SELECT * FROM commandes_temp WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row;
}

function finalize_command($user_id, $data, $conn) {
    $valeur = json_decode($data["valeur"], true);
    $date = date('Y-m-d H:i:s');
    $statut = "Nouveau";

    $stmt = $conn->prepare(
        "INSERT INTO commandes (user_id, produit, quantite, unite, date_commande, statut, nom_client, lieu_livraison, telephone, email) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
        "isisssssss",
        $user_id,
        $valeur["produit"],
        $valeur["quantite"],
        $valeur["unite"],
        $date,
        $statut,
        $valeur["nom_client"],
        $valeur["lieu_livraison"],
        $valeur["telephone"],
        $valeur["email"]
    );
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM commandes_temp WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
}

// Fonction pour détecter et traiter les rappels basés sur des dates
function process_reminder($message, $user_id, $conn, $langue) {
    $message_clean = clean_text($message);
    $responses = [
        'fr' => [
            'success' => "Rappel défini avec succès !",
            'invalid' => "Format invalide. Utilisez : 'Définir rappel vidange au YYYY-MM-DD' ou 'Rappel huile au YYYY-MM-DD' (ex: 2025-09-01).",
            'exists' => "Un rappel existe déjà. Veuillez mettre à jour avec une nouvelle commande.",
            'invalid_date' => "Date invalide. Utilisez le format YYYY-MM-DD (ex: 2025-09-01)."
        ],
        'en' => [
            'success' => "Reminder set successfully!",
            'invalid' => "Invalid format. Use: 'Set oil change reminder to YYYY-MM-DD' or 'Oil reminder to YYYY-MM-DD' (e.g., 2025-09-01).",
            'exists' => "A reminder already exists. Please update with a new command.",
            'invalid_date' => "Invalid date. Use format YYYY-MM-DD (e.g., 2025-09-01)."
        ]
    ];

    $check = $conn->prepare("SELECT reminder_id FROM vehicle_reminders WHERE user_id = ?");
    $check->bind_param("i", $user_id);
    $check->execute();
    $result = $check->get_result();
    $exists = $result->fetch_assoc();
    $check->close();

    if ($exists) {
        return $responses[$langue]['exists'];
    }

    if (preg_match('/définir rappel vidange au (\d{4}-\d{2}-\d{2})/i', $message, $matches) || 
        preg_match('/set oil change reminder to (\d{4}-\d{2}-\d{2})/i', $message, $matches)) {
        $date = $matches[1];
        if (DateTime::createFromFormat('Y-m-d', $date) === false) {
            return $responses[$langue]['invalid_date'];
        }
        $stmt = $conn->prepare("INSERT INTO vehicle_reminders (user_id, next_oil_change_date) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $date);
        $stmt->execute();
        $stmt->close();
        return $responses[$langue]['success'];
    } elseif (preg_match('/rappel huile au (\d{4}-\d{2}-\d{2})/i', $message, $matches) || 
              preg_match('/oil reminder to (\d{4}-\d{2}-\d{2})/i', $message, $matches)) {
        $date = $matches[1];
        if (DateTime::createFromFormat('Y-m-d', $date) === false) {
            return $responses[$langue]['invalid_date'];
        }
        $stmt = $conn->prepare("INSERT INTO vehicle_reminders (user_id, oil_reminder_date) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $date);
        $stmt->execute();
        $stmt->close();
        return $responses[$langue]['success'];
    } else {
        return $responses[$langue]['invalid'];
    }
}

// Fonction pour détecter l'intention et proposer une guidance
function detect_intent_and_guide($message, $langue) {
    $message_clean = clean_text($message);
    $guidance = [
        'fr' => [
            'order' => "\n\n**Conseils:**\n- Utilisez **'Je veux 10 litres de 15W40'**\n- Ou **'Commande de 5 bidons d'huile hydraulique'**\n- Ou **'J'aimerais 2 cartons de SAE40'**\nJe vous guiderai ensuite !",
            'reminder' => "\n\n**Conseils:**\n- Utilisez **'Définir rappel vidange au YYYY-MM-DD'** (ex: 2025-09-01)\n- Ou **'Rappel huile au YYYY-MM-DD'** (ex: 2025-09-01)\nIndiquez-moi la date !"
        ],
        'en' => [
            'order' => "\n\n**Tips:**\n- Use **'I want 10 liters of 15W40'**\n- Or **'Order of 5 cans of hydraulic oil'**\n- Or **'I'd like 2 cartons of SAE40'**\nI'll guide you next!",
            'reminder' => "\n\n**Tips:**\n- Use **'Set oil change reminder to YYYY-MM-DD'** (e.g., 2025-09-01)\n- Or **'Oil reminder to YYYY-MM-DD'** (e.g., 2025-09-01)\nTell me the date!"
        ]
    ];

    $order_keywords = ['commande', 'acheter', 'order', 'buy', 'achat', 'produit', 'produits', 'purchase'];
    $reminder_keywords = ['rappel', 'reminder', 'vidange', 'huile', 'oil', 'change', 'date'];

    if (preg_match('/\b(' . implode('|', $order_keywords) . ')\b/i', $message_clean)) {
        return $guidance[$langue]['order'];
    } elseif (preg_match('/\b(' . implode('|', $reminder_keywords) . ')\b/i', $message_clean)) {
        return $guidance[$langue]['reminder'];
    }
    return "";
}

// Chargement du document
$raw = file_get_contents("document_entreprise.txt");
$phrases = array_map('clean_text', sent_tokenize($raw));

// Calcul TF-IDF
list($tfidf_matrix, $all_terms, $doc_freq) = compute_tfidf($phrases);

$langue_utilisateur = get_user_language($user_id, $conn);

// Négociation détectée
if (detecter_negociation($message)) {
    $reponse_negociation = [
        "fr" => "Merci pour votre intérêt. Les prix sont déjà compétitifs. Vous pourrez discuter de toute négociation avec un agent lorsqu’il vous contactera.",
        "en" => "Thank you for your interest. The prices are already competitive. You will be able to discuss any negotiation with an agent when they contact you.",
        "es" => "Gracias por su interés. Los precios ya son competitivos. Podrá hablar de cualquier negociación con un agente cuando se comunique con usted."
    ];
    $raw_output = $reponse_negociation[$langue_utilisateur] ?? $reponse_negociation["fr"];
    $output = structure_response_with_hf($raw_output, $langue_utilisateur);
} else {
    // Suite logique de commande
    $data = get_temp_command($user_id, $conn);
    if ($data) {
        $valeur = json_decode($data["valeur"], true);
        if ($data["etape"] === "nom_client" && (!isset($valeur["nom_client"]) || empty($valeur["nom_client"]))) {
            update_temp_info($user_id, "nom_client", $message, $conn);
            $raw_output = "📍 Merci. Quel est le lieu de livraison ?";
            $output = structure_response_with_hf($raw_output, $langue_utilisateur);
        } elseif ($data["etape"] === "lieu_livraison" && (!isset($valeur["lieu_livraison"]) || empty($valeur["lieu_livraison"]))) {
            update_temp_info($user_id, "lieu_livraison", $message, $conn);
            $raw_output = "📞 Très bien. Quel est votre numéro de téléphone ?";
            $output = structure_response_with_hf($raw_output, $langue_utilisateur);
        } elseif ($data["etape"] === "telephone" && (!isset($valeur["telephone"]) || empty($valeur["telephone"]))) {
            update_temp_info($user_id, "telephone", $message, $conn);
            $raw_output = "📧 Parfait. Enfin, indiquez-moi votre adresse email :";
            $output = structure_response_with_hf($raw_output, $langue_utilisateur);
        } elseif ($data["etape"] === "email" && (!isset($valeur["email"]) || empty($valeur["email"]))) {
            update_temp_info($user_id, "email", $message, $conn);
            $temp_data = get_temp_command($user_id, $conn);
            finalize_command($user_id, $temp_data, $conn);
            $raw_output = "✅ Merci ! Votre commande est enregistrée. Un conseiller vous contactera bientôt.";
            $output = structure_response_with_hf($raw_output, $langue_utilisateur);
        }
    } else {
        $cmd = detect_command($message);
        if ($cmd) {
            $first_cmd = is_array($cmd) ? $cmd[0] : $cmd;
            insert_temp_command($user_id, $first_cmd, $conn);
            $raw_output = "📝 Pour finaliser la commande de {$first_cmd['quantite']} {$first_cmd['produit']}, merci de fournir les informations suivantes.\n🔹 Nom du client :";
            $output = structure_response_with_hf($raw_output, $langue_utilisateur);
        } else {
            // Logique pour les rappels
            if (preg_match('/(définir rappel|set reminder|rappel huile|oil reminder)/i', $message)) {
                $raw_output = process_reminder($message, $user_id, $conn, $langue_utilisateur);
                $output = structure_response_with_hf($raw_output, $langue_utilisateur);
            } else {
                // Réponse standard (toujours fournie)
                $extraits = recherche_similaire($message, $phrases, $tfidf_matrix, $all_terms, $doc_freq);
                $contenu = implode(" ", $extraits);
                $raw_output = reformuler_texte($contenu, $message, $langue_utilisateur);

                // Ajouter une guidance si une intention est détectée
                $intent_guidance = detect_intent_and_guide($message, $langue_utilisateur);
                if (!empty($intent_guidance)) {
                    $raw_output .= $intent_guidance;
                }
                $output = structure_response_with_hf($raw_output, $langue_utilisateur);
            }
        }
    }
}

// Sauvegarder le message utilisateur en base
$stmt_user = $conn->prepare("INSERT INTO messages (outgoing_msg_id, incoming_msg_id, msg) VALUES (?, 9999, ?)");
$stmt_user->bind_param("is", $user_id, $message);
$stmt_user->execute();
$stmt_user->close();

// Sauvegarder la réponse du bot en base
$stmt_bot = $conn->prepare("INSERT INTO messages (outgoing_msg_id, incoming_msg_id, msg) VALUES (9999, ?, ?)");
$stmt_bot->bind_param("is", $user_id, $output);
$stmt_bot->execute();
$stmt_bot->close();

// Capturer toute sortie accidentelle PHP
$php_output = ob_get_clean();
if (!empty($php_output)) {
    file_put_contents('debug_php_output.log', $php_output, FILE_APPEND);
}

// Renvoi JSON avec les messages à afficher
echo json_encode(['success' => true, 'messages' => [
    ['sender' => 'user', 'text' => $message],
    ['sender' => 'bot', 'text' => $output, 'avatar' => 'uploaded_img/Logo Green Engineering OK.png']
]], JSON_UNESCAPED_UNICODE);
exit;
?>
