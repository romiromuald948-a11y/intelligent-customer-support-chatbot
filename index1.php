<?php
include 'php/config.php';
session_start();
require 'vendor/autoload.php'; // Chemin vers PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['submit'])) {
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));
    $cpassword = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
    $langue = mysqli_real_escape_string($conn, $_POST['langue']);

    $image = 'default-avatar.png';
    $status = 'Active Now';
    $verified = 0; // Non vérifié par défaut
    $verification_code = sprintf("%06d", mt_rand(0, 999999)); // Code à 6 chiffres

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $select = mysqli_query($conn, "SELECT email FROM user_form WHERE email = '$email'");
        if (mysqli_num_rows($select) > 0) {
            $alert[] = "Cet email est déjà utilisé!";
        } else {
            if ($password != $cpassword) {
                $alert[] = "Les mots de passe ne correspondent pas!";
            } else {
                $stmt = $conn->prepare("INSERT INTO user_form (fname, lname, email, password, image, status, langue, verification_code, verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssss", $fname, $lname, $email, $password, $image, $status, $langue, $verification_code, $verified);

                if ($stmt->execute()) {
                    $user_id = $conn->insert_id;

                    // Envoyer l'email de vérification
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'romualdombe8@gmail.com'; // Votre email
                        $mail->Password = 'oedh qmpq lmlo ezie'; // Mot de passe d'application
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        $mail->setFrom('romualdombe8@gmail.com', 'Green Engineering');
                        $mail->addAddress($email);
                        $mail->Subject = 'Vérification de votre compte';
                        $mail->Body = "Bonjour $fname,\n\nVotre code de vérification est : $verification_code\n\nVeuillez l'entrer pour activer votre compte.\n\nCordialement,\nGreen Engineering";

                        $mail->send();
                        $_SESSION['user_email'] = $email; // Stocker l'email pour la vérification
                        header('location: verify.php'); // Rediriger vers la page de vérification
                        exit();
                    } catch (Exception $e) {
                        $alert[] = "Erreur d'envoi de l'email : {$mail->ErrorInfo}";
                    }
                } else {
                    $alert[] = "Échec de l'inscription : " . $conn->error;
                }
                $stmt->close();
            }
        }
    } else {
        $alert[] = "$email n'est pas une adresse email valide!";
    }
}

if (isset($_SESSION['user_id'])) {
    header("location: chat.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="NEW_CSS/style.css">
    <title>Créer un compte</title>
</head>
<body>
    <div class="form-container">
        <form action="" method="post">
            <h3>ENREGISTREMENT</h3>
            <?php 
                if (isset($alert)) {
                    foreach ($alert as $alert) {
                        echo '<div class="alert">' . $alert . '</div>';
                    }
                }
            ?>
            <input type="text" name="fname" placeholder="Entrer votre Nom" class="box" required>
            <input type="text" name="lname" placeholder="Entrer votre Prénom" class="box" required>
            <input type="email" name="email" placeholder="Entrer l'email" class="box" required>
            <input type="password" name="password" placeholder="Entrer le mot de passe" class="box" required>
            <input type="password" name="cpassword" placeholder="Confirmer le mot de passe" class="box" required>
            <select name="langue" class="box" required>
                <option value="">-- Sélectionnez votre langue --</option>
                <option value="fr">Français</option>
                <option value="en">English</option>
                <option value="es">Español</option>
                <option value="de">Deutsch</option>
            </select>
            <input type="submit" name="submit" class="btn" value="Envoyer">
            <p>Vous avez déjà un compte? <a href="login.php">Cliquez ici!</a></p>
        </form>
    </div>
</body>
</html>