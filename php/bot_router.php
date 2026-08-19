<?php
function isKnownIntent($message) {
    $keywords = ['carburant', 'station', 'yaoundé', 'contact', 'vidange', 'lubrifiant', 'services', 'produits'];
    foreach ($keywords as $word) {
        if (stripos($message, $word) !== false) return true;
    }
    return false;
}

function getLocalResponse($message) {
    $message = strtolower($message);

    if (strpos($message, 'carburant') !== false || strpos($message, 'produit') !== false) {
        return "Nous proposons de l’essence, du diesel, du gaz domestique (Green Gaz) et des lubrifiants adaptés aux climats chauds.";
    } elseif (strpos($message, 'station') !== false || strpos($message, 'yaoundé') !== false) {
        return "Notre station à Yaoundé est située à Fouda, Route de Ngousso, après Total.";
    } elseif (strpos($message, 'contact') !== false || strpos($message, 'numéro') !== false) {
        return "Vous pouvez nous joindre au +237 694 14 00 70 ou par mail à greenoilsarl@yahoo.fr.";
    } elseif (strpos($message, 'vidange') !== false || strpos($message, 'service') !== false) {
        return "Oui, nos stations offrent des services de vidange, graissage, laverie auto et boutique.";
    } else {
        return "Merci pour votre message. Un agent vous répondra bientôt.";
    }
}