<?php
include 'php/config.php';
session_start();

$is_logged_in = isset($_SESSION['user_id']);
$user_data = null;

if ($is_logged_in) {
  $user_id = $_SESSION['user_id'];
  $select = mysqli_query($conn, "SELECT * FROM user_form WHERE user_id = '$user_id'");
  $user_data = mysqli_fetch_assoc($select);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Chat | Green Engineering</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="feedback.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f5f5f5;
    }

    .container {
      max-width: 800px;
      margin: 30px auto;
      border: 1px solid #ddd;
      border-radius: 10px;
      overflow: hidden;
      background-color: #ffffff;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 15px;
      background-color: #006400;
      color: #fff;
    }

    .header-left {
      display: flex;
      align-items: center;
    }

    .back-icon img {
      width: 24px;
      margin-right: 10px;
    }

    header img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      margin-right: 15px;
    }

    .details span {
      font-size: 18px;
      font-weight: bold;
    }

    .details p {
      margin: 2px 0 0;
      font-size: 14px;
    }

    .auth-btn {
      background: #fff;
      color: #006400;
      border: none;
      padding: 6px 12px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }

    .chat-box {
      height: 400px;
      overflow-y: auto;
      padding: 15px;
      background: #f0f8f4;
    }

    .chat {
      margin-bottom: 15px;
      display: flex;
    }

    .chat.incoming img {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      margin-right: 10px;
    }

    .chat .details {
      max-width: 70%;
    }

    .chat .details p {
      background: #e0e0e0;
      padding: 10px;
      border-radius: 10px;
      word-wrap: break-word;
    }

    .chat.outgoing {
      justify-content: flex-end;
    }

    .chat.outgoing .details p {
      background: #006400;
      color: #fff;
    }

    .typing-area {
      display: flex;
      align-items: center;
      padding: 10px 15px;
      border-top: 1px solid #ccc;
      background-color: #fff;
    }

    .input-field {
      flex: 1;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 20px;
      outline: none;
      font-size: 15px;
    }

    .send_btn,
    .image {
      background: none;
      border: none;
      margin-left: 10px;
      cursor: pointer;
    }

    .send_btn img,
    .image img {
      width: 30px;
    }

    .upload_img {
      display: none;
    }

    @keyframes blink {
      0%, 100% { opacity: 0.2; }
      50% { opacity: 1; }
    }

    .dots {
      display: inline-block;
      margin-left: 5px;
      animation: blink 1.5s infinite ease-in-out;
    }

    /* Messages utilisateur */
    .chat.outgoing .details p {
      background: #009e60;
      color: white;
      border-radius: 10px 0 10px 10px;
    }

    /* Messages bot */
    .chat.incoming .details p {
      background: #e0e0e0;
      color: #333;
      border-radius: 0 10px 10px 10px;
    }

    #typing-indicator {
      min-height: 40px;
      align-items: center;
    }

    .feedback-form-popup {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 1000;
      display: block; /* Assure que l'élément est visible */
    }

    .feedback-form {
      background-color: #f0f8f4;
      border: 2px solid #006400;
      border-radius: 10px;
      padding: 15px;
      max-width: 400px;
      margin: 0 auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      color: #333;
      font-family: 'Segoe UI', sans-serif;
    }

    .feedback-form p {
      font-weight: bold;
      color: #006400;
    }

    .feedback-form textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      margin-top: 10px;
      resize: vertical;
    }

    .feedback-form-buttons {
      display: flex;
      gap: 10px;
      margin-top: 10px;
    }

    .feedback-form-buttons button {
      padding: 8px 15px;
      border-radius: 5px;
      cursor: pointer;
    }

    .feedback-form-buttons button:first-child {
      background-color: #006400;
      color: white;
      border: none;
    }

    .feedback-form-buttons button:last-child {
      background-color: #fff;
      color: #006400;
      border: 1px solid #006400;
    }

    /* ... (reste du CSS existant inchangé jusqu'à .feedback-form-popup) ... */

.feedback-form-popup {
    position: fixed;
    top: 20%; /* Marge supérieure pour éviter le chevauchement */
    left: 50%;
    transform: translateX(-50%);
    z-index: 1000;
    display: none; /* Masqué par défaut, affiché via JS */
    max-width: 90%; /* Responsive */
    width: 450px; /* Largeur confortable */
}

.feedback-form {
    background: linear-gradient(135deg, #e6f3e6, #ffffff); /* Dégradé vert clair */
    border: 2px solid #006400;
    border-radius: 15px; /* Bordures plus arrondies */
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0, 100, 0, 0.2); /* Ombre douce */
    color: #333;
    font-family: 'Segoe UI', sans-serif;
    text-align: center;
}

.feedback-form p {
    font-weight: bold;
    color: #006400;
    margin-bottom: 15px;
    font-size: 16px;
}

.feedback-form textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    margin-top: 10px;
    resize: vertical;
    font-size: 14px;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
}

.feedback-form-buttons {
    display: flex;
    gap: 12px;
    margin-top: 15px;
    justify-content: center;
}

.feedback-form-buttons button {
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    font-size: 14px;
    transition: background-color 0.3s, color 0.3s;
}

.feedback-form-buttons button:first-child {
    background-color: #006400;
    color: white;
    border: none;
}

.feedback-form-buttons button:first-child:hover {
    background-color: #004d00;
}

.feedback-form-buttons button:last-child {
    background-color: #fff;
    color: #006400;
    border: 1px solid #006400;
}

.feedback-form-buttons button:last-child:hover {
    background-color: #f0f8f4;
}

/* Popup initiale pour demander l'avis */
.feedback-popup {
    position: fixed;
    top: 25%;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1000;
    display: none; /* Masqué par défaut, affiché via JS */
    max-width: 90%;
    width: 400px;
    background: #ffffff;
    border: 2px solid #006400;
    border-radius: 15px;
    padding: 15px;
    box-shadow: 0 4px 15px rgba(0, 100, 0, 0.2);
    text-align: center;
}

.feedback-popup .feedback-content p {
    font-weight: bold;
    color: #006400;
    margin-bottom: 10px;
    font-size: 15px;
}

.feedback-popup .feedback-content div {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.feedback-popup .feedback-content button {
    padding: 8px 15px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    font-size: 13px;
    transition: background-color 0.3s, color 0.3s;
}

.feedback-popup .feedback-content .feedback-btn {
    background-color: #006400;
    color: white;
    border: none;
}

.feedback-popup .feedback-content .feedback-btn:hover {
    background-color: #004d00;
}

.feedback-popup .feedback-content .feedback-close {
    background-color: #fff;
    color: #006400;
    border: 1px solid #006400;
}

.feedback-popup .feedback-content .feedback-close:hover {
    background-color: #f0f8f4;
}

/* ... (reste du CSS existant après .feedback-form-buttons) ... */
  </style>
</head>
<body>

<div class="container">
  <section class="chat-area">
    <header>
      <div class="header-left">
        <a href="index.php" class="back-icon"><img src="images/arrow.svg" alt="Retour"></a>
        <img src="uploaded_img/Logo Green Engineering OK.png" alt="Green Chat">
        <div class="details">
          <span>GREEN CHAT</span>
          <p>Assistant virtuel de Green Engineering</p>
        </div>
      </div>

      <div class="header-right">
        <?php if ($is_logged_in): ?>
          <form action="logout.php" method="post" style="display:inline;">
            <button type="submit" class="auth-btn">Déconnexion</button>
          </form>
        <?php else: ?>
          <button class="auth-btn" onclick="window.location.href='login.php'">Connexion</button>
        <?php endif; ?>
      </div>
    </header>

    <div class="chat-box">
     
    </div>

    <form action="#" class="typing-area" method="POST">
      <input type="text" name="incoming_id" value="9999" class="incoming_id" hidden>
      <input type="text" name="message" class="input-field" placeholder="Écris ton message ici..." autocomplete="off">
      <button type="button" class="image"><img src="images/camera.svg" alt="Image"></button>
      <input type="file" name="send_image" accept="image/*" class="upload_img">
      <button type="submit" class="send_btn"><img src="uploaded_img/send.svg" alt="Envoyer"></button>
    </form>
  </section>
</div>

<!-- Inclusion de marked.js avant chat.js -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="java/chat.js"></script>
</body>
</html>