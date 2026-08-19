<?php
session_start();
include 'php/config.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Green Engineering SARL</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f8f8;
      color: #333;
      scroll-behavior: smooth;
    }

    header {
      background-color: #009e60;
      color: white;
      padding: 15px 25px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: relative;
      z-index: 5;
    }

    .logo {
      display: flex;
      align-items: center;
    }

    .logo img {
      height: 45px;
      margin-right: 10px;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 25px;
    }

    nav ul li a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      transition: color 0.3s ease;
    }

    nav ul li a:hover {
      color: #ccf4cc;
    }

    .hero-video {
      position: relative;
      width: 100%;
      height: 100vh;
      overflow: hidden;
    }

    .hero-video video {
      position: absolute;
      top: 0; left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: 1;
    }

    .overlay-text {
      position: relative;
      z-index: 2;
      background-color: rgba(0,0,0,0.5);
      color: white;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 0 20px;
    }

    .overlay-text h1 {
      font-size: 38px;
      font-weight: bold;
      text-shadow: 2px 2px 6px rgba(0,0,0,0.6);
      margin-bottom: 15px;
    }

    .overlay-text p {
      font-size: 18px;
      max-width: 600px;
      margin-bottom: 20px;
    }

    #chatBtn {
      background-color: #009e60;
      color: white;
      border: none;
      padding: 12px 20px;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
    }

    #chatBtn:hover {
      background-color: #007a48;
    }

    .products {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
      padding: 60px 20px;
      background-color: white;
    }

    .product-card {
      background-color: #f0f0f0;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      width: 280px;
      text-align: center;
      padding: 25px;
      transition: transform 0.3s ease;
    }

    .product-card:hover {
      transform: translateY(-5px);
    }

    .product-card img {
      height: 80px;
      margin-bottom: 15px;
    }

    .product-card h3 {
      color: #009e60;
      font-size: 20px;
      margin-bottom: 10px;
    }

    .product-card p {
      font-size: 15px;
      color: #444;
      margin-bottom: 15px;
    }

    .product-card a {
      text-decoration: none;
      color: white;
      background-color: #009e60;
      padding: 8px 15px;
      border-radius: 6px;
      display: inline-block;
      font-weight: bold;
    }

    #chatContainer {
      display: none;
      max-width: 1000px;
      margin: 40px auto;
      padding: 0 20px;
    }

    footer {
      background-color: #eee;
      text-align: center;
      padding: 15px;
      font-size: 14px;
      color: #666;
    }

    @media (max-width: 768px) {
      nav ul { flex-direction: column; gap: 10px; }
      .overlay-text h1 { font-size: 26px; }
      .overlay-text p { font-size: 16px; }
      .product-card { width: 90%; }
    }

    #chatBtn {
  background-color: #009e60;
  color: white;
  text-decoration: none;
  padding: 12px 20px;
  font-size: 16px;
  border-radius: 8px;
  display: inline-block;
  transition: background 0.3s ease;
}

#chatBtn:hover {
  background-color: #007a48;
}

.intro-story {
  background-color: #ffffff;
  padding: 60px 20px;
  text-align: center;
  animation: fadeIn 1s ease-in;
}
.intro-story h2 {
  color: #009e60;
  font-size: 26px;
  margin-bottom: 15px;
}
.intro-story p {
  font-size: 16px;
  line-height: 1.6;
  color: #444;
  max-width: 800px;
  margin: 0 auto;
}
  </style>

</head>
<body>

  <header>
    <div class="logo">
      <img src="uploaded_img/green.jpg" alt="Logo Green Engineering">
      <h1>Green Engineering SARL</h1>
    </div>
    <nav>
      <ul>
        <li><a href="#accueil">Accueil</a></li>
        <li><a href="#activites">Activités</a></li>
        <li><a href="historique.php">Historique</a></li>
        <li><a href="#contact">Contact</a></li>
      </ul>
    </nav>
  </header>

  <section class="hero-video" id="accueil">
    <video autoplay muted loop playsinline>
      <source src="videos/huile.mp4" type="video/mp4">
      Votre navigateur ne supporte pas la vidéo HTML5.
    </video>
    <div class="overlay-text">
      <h1>Énergie maîtrisée,<br>sécurité assurée</h1>
      <p>Production d’huiles moteurs et fabrication de scellés de gaz. Industrie responsable, qualité certifiée, engagement local.</p>
      <a href="chat.php" id="chatBtn">💬 Ouvrir Green Chat</a>
    </div>
  </section>

<section class="intro-story">
  <div class="container">
    <h2>Notre entreprise</h2>
    <p>
      Fondée en 2024 à Afanayos (Yaoundé), Green Engineering SARL est spécialisée dans la production d’huiles moteurs et la fabrication de scellés de gaz. 
      Certifiée et responsable, l’entreprise incarne une vision industrielle camerounaise moderne et durable.
    </p>
  </div>
</section>

  <section class="products" id="activites">
    <div class="product-card">
      <img src="images/huile.png" alt="Huile moteur">
      <h3>Huiles de moteurs</h3>
      <p>Lubrifiants neufs et reconditionnés pour moteurs auto et industriels.</p>
      <a href="produits.php">Voir plus</a>
    </div>
    <div class="product-card">
      <img src="images/scelle.png" alt="Scellés de gaz">
      <h3>Scellés de gaz</h3>
      <p>Scellés certifiés pour bonbonnes de gaz 6kg et 12kg — sécurité garantie.</p>
      <a href="produits.php">Voir plus</a>
    </div>
<div style="text-align:center; margin-top:30px; font-size:14px; color:#555;">
  ℹ️ Vous avez des questions sur nos produits ou souhaitez effectuer une commande ?  
  <a href="chat.php" style="display:inline-block; background-color:#009e60; color:white; padding:6px 12px; border-radius:6px; text-decoration:none; font-weight:bold; margin-left:8px;">
    💬 Ouvrir Green Chat
  </a>
</div>
  </section>

  <div id="chatContainer">
    <iframe src="chat.php" width="100%" height="400" frameborder="0" style="border-radius:10px;"></iframe>
  </div>

  <section class="section" id="contact" style="text-align:center; padding:40px;">
    <h2>Nous contacter</h2>
    <p>📍 Afanayos, Yaoundé</p>
    <p>📞 +237 6XX XXX XXX &nbsp; | ✉️ info@greenengineering.cm</p>
    <p style="margin-top: 15px; font-size: 14px; color: #555; text-align: center;">
  Pour toute information supplémentaire ou pour effectuer une action, veuillez utiliser  
  <a href="chat.php" style="background-color:#009e60; color:white; padding:6px 12px; border-radius:6px; text-decoration:none;">
    💬 Green Chat
  </a>.
</p>
  </section>

  <footer>
    © 2025 Green Engineering SARL - Tous droits réservés
  </footer>

  <script>
    document.getElementById("chatBtn").onclick = function() {
      document.getElementById("chatContainer").style.display = "block";
      this.style.display = "none";
    };
  </script>

</body>
</html>