<?php
include 'config.php';

$sql = "SELECT * FROM commandes ORDER BY date_commande DESC";
$query = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($query)) {
    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>" . htmlspecialchars($row['utilisateur']) . "</td>";
    echo "<td>" . htmlspecialchars($row['produit']) . "</td>";
    echo "<td>{$row['quantite']}</td>";
    echo "<td>{$row['unite']}</td>";
    echo "<td>{$row['date_commande']}</td>";
    echo "<td class='statut-" . htmlspecialchars($row['statut']) . "'>{$row['statut']}</td>";
    echo "</tr>";
}
?>
