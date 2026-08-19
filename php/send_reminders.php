<?php
include 'config.php';

function sendEmail($to, $subject, $message) {
    $headers = "From: no-reply@greenengineering.com\r\n";
    $headers .= "Reply-To: no-reply@greenengineering.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    return mail($to, $subject, $message, $headers);
}

// Récupérer les utilisateurs et leurs rappels
$current_date = date('Y-m-d');
$sql = "SELECT u.email, u.langue, v.* 
        FROM user_form u 
        JOIN vehicle_reminders v ON u.user_id = v.user_id 
        WHERE (v.next_oil_change_date IS NOT NULL AND v.next_oil_change_date <= DATE_ADD('$current_date', INTERVAL 7 DAY))
        OR (v.oil_reminder_date IS NOT NULL AND v.oil_reminder_date <= DATE_ADD('$current_date', INTERVAL 7 DAY))";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $email = $row['email'];
        $langue = $row['langue'];
        $next_oil_change_date = $row['next_oil_change_date'];
        $oil_reminder_date = $row['oil_reminder_date'];

        // Rappel vidange
        if ($next_oil_change_date && strtotime($next_oil_change_date) - strtotime($current_date) <= 7 * 86400) {
            $days_left = floor((strtotime($next_oil_change_date) - strtotime($current_date)) / 86400);
            $subject = "Rappel de vidange - Green Engineering";
            $message = ($langue == 'fr') ? 
                "Bonjour,\nVotre prochaine vidange est prévue le $next_oil_change_date. Il reste $days_left jours. Planifiez une vidange bientôt !\nGreen Engineering" :
                "Hello,\nYour next oil change is scheduled for $next_oil_change_date. $days_left days remain. Schedule an oil change soon!\nGreen Engineering";
            sendEmail($email, $subject, $message);
        }

        // Rappel ajout d'huile
        if ($oil_reminder_date && strtotime($oil_reminder_date) - strtotime($current_date) <= 7 * 86400) {
            $days_left = floor((strtotime($oil_reminder_date) - strtotime($current_date)) / 86400);
            $subject = "Rappel d'ajout d'huile - Green Engineering";
            $message = ($langue == 'fr') ? 
                "Bonjour,\nIl est temps d'ajouter de l'huile à votre véhicule. Date prévue : $oil_reminder_date. Il reste $days_left jours.\nGreen Engineering" :
                "Hello,\nIt's time to add oil to your vehicle. Scheduled date: $oil_reminder_date. $days_left days remain.\nGreen Engineering";
            sendEmail($email, $subject, $message);
        }
    }
}

mysqli_close($conn);
?>