<?php
    include 'php/config.php';
    session_start();
    if(isset($_POST['submit'])){

        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, md5($_POST['password']));

        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                
            $select = mysqli_query($conn, "SELECT * FROM user_form WHERE email = '$email' AND password = '$password'");

            if(mysqli_num_rows($select) > 0){
                $row = mysqli_fetch_assoc($select);
                if ($row['verified'] == 0) {
                    $alert[] = "Veuillez vérifier votre email avant de vous connecter!";
                } else {
                    $status = 'En ligne';
                    $update = mysqli_query($conn, "UPDATE user_form SET status = '$status' WHERE user_id = '{$row['user_id']}'");

                    if($update){
                        $_SESSION['user_id'] = $row['user_id'];
                        header('location: home.php');
                    }
                }
            }else{
                $alert[] = "Mot de passe ou Email pas correct!";
            }
        }else{
            $alert[] = "$email n'est pas valide!" ;
        }
    }
if(isset($_SESSION['user_id'])){
    header("location: chat.php");
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="NEW_CSS/style.css">
    <title>CONNEXION</title>
</head>
<body>
    <div class="form-container">
        <form action="" method="post">
            <h3>CONNEXION</h3>
            <?php 
                if(isset($alert)){
                    foreach($alert as $alert){
                        echo '<div class="alert">'.$alert.'</div>';
                    }
                }
            ?>
            <input type="email" name="email" placeholder="Entrer l'email" class="box" required>
            <input type="password" name="password" placeholder="Entrer le Mot de passe" class="box" required>
            <input type="submit" name="submit" class="btn" value="COMMENCER">
            <p>Mot de passe oublié? <a href="forgot_password.php">Cliquez ici!</a></p>
            <p>Vous n'avez pas de compte? <a href="index1.php">Cliquez ici!</a></p>
        </form>
    </div>
</body>
</html>