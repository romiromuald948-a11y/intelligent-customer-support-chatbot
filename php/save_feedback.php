<?php
session_start();
include "config.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Utilisateur non connecté.']);
    exit();
}

$user_id = intval($_SESSION['user_id']);
$feedback = isset($_POST['feedback']) ? trim($_POST['feedback']) : '';

if ($feedback === '') {
    echo json_encode(['error' => 'Feedback vide.']);
    exit();
}

// Préparer et insérer
$stmt = $conn->prepare("INSERT INTO feedbacks (user_id, message) VALUES (?, ?)");
$stmt->bind_param("is", $user_id, $feedback);
$success = $stmt->execute();
$stmt->close();

if ($success) {
    echo json_encode(['ok' => true, 'message' => 'Merci pour votre avis !']);
} else {
    echo json_encode(['error' => 'Échec enregistrement feedback.']);
}
