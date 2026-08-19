<?php
function getHFResponse($message) {
    $token = "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"; // ← ton token Hugging Face
    $token = "TA_CLE_API"; // ← ton token Hugging Face
    $url = "https://api-inference.huggingface.co/models/mistralai/Mistral-7B-Instruct";

    $headers = [
        "Authorization: Bearer " . $token,
        "Content-Type: application/json"
    ];
    
    $data = [
        "inputs" => $message
    ];

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($curl);
    curl_close($curl);

    $result = json_decode($response, true);

    if (isset($result[0]['generated_text'])) {
        return $result[0]['generated_text'];
    } elseif (isset($result['error'])) {
        return "Erreur IA : " . $result['error'];
    } else {
        return "Je suis désolé, la réponse est vide.";
    }
}