<?php
include 'php/config.php';
session_start();

$email = $_SESSION['user_email'] ?? '';
$alert = [];

if (isset($_POST['submit_code'])) {
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $new_password = mysqli_real_escape_string($conn, md5($_POST['new_password']));
    $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm_password']));

    if ($new_password !== $confirm_password) {
        $alert[] = "Les mots de passe ne correspondent pas!";
    } else {
        $stmt = $conn->prepare("SELECT user_id FROM user_form WHERE email = ? AND verification_code = ?");
        $stmt->bind_param("ss", $email, $code);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row) {
            $update_stmt = $conn->prepare("UPDATE user_form SET password = ?, verification_code = NULL WHERE user_id = ?");
            $update_stmt->bind_param("si", $new_password, $row['user_id']);
            $update_stmt->execute();
            $update_stmt->close();

            $alert[] = "Mot de passe réinitialisé avec succès!";
            header('refresh:2;url=login.php');
        } else {
            $alert[] = "Code incorrect!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="NEW_CSS/style.css">
    <title>Réinitialiser le mot de passe</title>
</head>
<body>
    <div class="form-container">
        <form action="" method="post">
            <h3>Réinitialiser le mot de passe</h3>
            <?php 
                if (!empty($alert)) {
                    foreach ($alert as $msg) {
                        echo '<div class="alert">' . $msg . '</div>';
                    }
                }
            ?>
            <input type="text" name="code" placeholder="Entrer le code de réinitialisation" class="box" required maxlength="6">
            <input type="password" name="new_password" placeholder="Nouveau mot de passe" class="box" required>
            <input type="password" name="confirm_password" placeholder="Confirmer le nouveau mot de passe" class="box" required>
            <input type="submit" name="submit_code" class="btn" value="Réinitialiser">
            <p>Retour à la <a href="login.php">connexion</a></p>
        </form>
    </div>
</body>
</html>