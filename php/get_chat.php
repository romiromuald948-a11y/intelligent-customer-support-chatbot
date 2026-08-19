<?php
session_start();
include "config.php";

// Debug temporaire
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Définir le type de contenu JSON
header('Content-Type: application/json; charset=utf-8');

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Utilisateur non connecté']);
    exit;
}

// Vérifier que l'ID du destinataire est fourni
if (!isset($_POST['incoming_id'])) {
    echo json_encode(['success' => false, 'error' => 'Destinataire manquant']);
    exit;
}

$outgoing_id = $_SESSION['user_id'];
$incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);

// Récupérer tous les messages entre l'utilisateur et le destinataire
$sql = "SELECT messages.*, user_form.fname, user_form.lname, user_form.image 
        FROM messages
        LEFT JOIN user_form ON user_form.user_id = messages.outgoing_msg_id
        WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id})
        OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id})
        ORDER BY msg_id ASC";

$query = mysqli_query($conn, $sql);

if (!$query) {
    echo json_encode(['success' => false, 'error' => 'Erreur SQL : ' . mysqli_error($conn)]);
    exit;
}

// Construire un tableau JSON avec les messages
$messages = [];
while ($row = mysqli_fetch_assoc($query)) {
    $sender_id = $row['outgoing_msg_id'];
    $isOutgoing = $sender_id == $outgoing_id;
    $isBot = $sender_id == 9999;
    $avatar = !empty($row['image']) ? 'uploaded_img/' . $row['image'] : 'uploaded_img/default-avatar.png';
    
    $messages[] = [
        'sender' => $isOutgoing ? 'user' : 'bot',
        'text' => $row['msg'],
        'avatar' => $isBot ? 'uploaded_img/Logo Green Engineering OK.png' : $avatar
    ];
}

echo json_encode(['success' => true, 'messages' => $messages], JSON_UNESCAPED_UNICODE);
exit;
?>