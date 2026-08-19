<?php
include 'php/config.php';
session_start();
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$alert = [];

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $select = mysqli_query($conn, "SELECT * FROM user_form WHERE email = '$email'");
    if (mysqli_num_rows($select) > 0) {
        $row = mysqli_fetch_assoc($select);
        $reset_code = sprintf("%06d", mt_rand(0, 999999));

        $stmt = $conn->prepare("UPDATE user_form SET verification_code = ? WHERE email = ?");
        $stmt->bind_param("ss", $reset_code, $email);
        $stmt->execute();
        $stmt->close();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'romualdombe8@gmail.com';
            $mail->Password = 'oedh qmpq lmlo ezie';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('romualdombe8@gmail.com', 'Green Engineering');
            $mail->addAddress($email);
            $mail->Subject = 'Reinitialisation de mot de passe';
            $mail->Body = "Bonjour,\n\nVotre code de réinitialisation est : $reset_code\n\nUtilisez-le pour changer votre mot de passe.\n\nCordialement,\nGreen Engineering";

            $mail->send();
            $_SESSION['user_email'] = $email;
            header('location: reset_password.php');
            exit();
        } catch (Exception $e) {
            $alert[] = "Erreur d'envoi de l'email : {$mail->ErrorInfo}";
        }
    } else {
        $alert[] = "Cet email n'existe pas!";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="NEW_CSS/style.css">
    <title>Mot de passe oublié</title>
</head>
<body>
    <div class="form-container">
        <form action="" method="post">
            <h3>Mot de passe oublié</h3>
            <?php 
                if (!empty($alert)) {
                    foreach ($alert as $msg) {
                        echo '<div class="alert">' . $msg . '</div>';
                    }
                }
            ?>
            <input type="email" name="email" placeholder="Entrer votre email" class="box" required>
            <input type="submit" name="submit" class="btn" value="Envoyer le code">
            <p>Retour à la <a href="login.php">connexion</a></p>
        </form>
    </div>
</body>
</html>