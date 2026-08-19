<?php
include 'php/config.php';
session_start();
require 'vendor/autoload.php'; // Chemin vers PHPMailer (pour renvoi si besoin)

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = $_SESSION['user_email'] ?? '';
$alert = [];

if (isset($_POST['submit_code'])) {
    $code = mysqli_real_escape_string($conn, $_POST['code']);

    $stmt = $conn->prepare("SELECT user_id, verification_code FROM user_form WHERE email = ? AND verification_code = ?");
    $stmt->bind_param("ss", $email, $code);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if ($row) {
        $update_stmt = $conn->prepare("UPDATE user_form SET verified = 1, verification_code = NULL WHERE user_id = ?");
        $update_stmt->bind_param("i", $row['user_id']);
        $update_stmt->execute();
        $update_stmt->close();

        header('location: login.php');
        exit();
    } else {
        $alert[] = "Code de vérification incorrect!";
    }
}

if (isset($_POST['resend'])) {
    $new_code = sprintf("%06d", mt_rand(0, 999999));

    $stmt = $conn->prepare("UPDATE user_form SET verification_code = ? WHERE email = ?");
    $stmt->bind_param("ss", $new_code, $email);
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
        $mail->Subject = 'Verification de votre compte';
        $mail->Body = "Bonjour,\n\nVotre nouveau code de vérification est : $new_code\n\nVeuillez l'entrer pour activer votre compte.\n\nCordialement,\nGreen Engineering";

        $mail->send();
        $alert[] = "Code renvoyé avec succès!";
    } catch (Exception $e) {
        $alert[] = "Erreur d'envoi de l'email : {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="NEW_CSS/style.css">
    <title>Vérification d'email</title>
</head>
<body>
    <div class="form-container">
        <form action="" method="post">
            <h3>Vérification d'email</h3>
            <?php 
                if (!empty($alert)) {
                    foreach ($alert as $msg) {
                        echo '<div class="alert">' . $msg . '</div>';
                    }
                }
            ?>
            <p>Un code de vérification a été envoyé à <?php echo $email; ?>.</p>
            <input type="text" name="code" placeholder="Entrer le code à 6 chiffres" class="box" required maxlength="6">
            <input type="submit" name="submit_code" class="btn" value="Vérifier"> <br>
            <input type="submit" name="resend" class="btn" value="Renvoyer le code">
            <p>Retour à <a href="index1.php">l'inscription</a></p>
        </form>
    </div>
</body>
</html>