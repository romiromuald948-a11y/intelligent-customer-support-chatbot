<?php
include "config.php";
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}


$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM messages 
        WHERE outgoing_msg_id = $user_id OR incoming_msg_id = $user_id 
        ORDER BY created_at ASC";
$query = mysqli_query($conn, $sql);
?>

<h2>Historique des conversations</h2>
<ul>
<?php while($row = mysqli_fetch_assoc($query)): ?>
    <li>
        <strong><?php echo ($row['outgoing_msg_id'] == $user_id) ? 'Moi' : 'Green Chat'; ?>:</strong>
        <?php echo $row['msg'] ?? '[Image envoyée]'; ?>
        <br><small><?php echo date("d/m/Y H:i", strtotime($row['created_at'])); ?></small>
    </li>
<?php endwhile; ?>
</ul>