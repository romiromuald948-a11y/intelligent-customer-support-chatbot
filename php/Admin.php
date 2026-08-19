<?php
include 'php/config.php'; // adapte si ton fichier config est ailleurs
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Commandes | Green Chat Admin</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #F4F6F8;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            background-color: #FFFFFF;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #006633;
            margin-bottom: 20px;
            font-size: 26px;
        }
        table.dataTable {
            width: 100%;
            border-collapse: collapse;
            background-color: #FFF;
        }
        th {
            background-color: #006633;
            color: #FFFFFF;
            padding: 10px;
            text-align: left;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #eaeaea;
        }
        .statut-Nouveau {
            color: #0066cc;
            font-weight: bold;
        }
        .statut-Traité {
            color: #009900;
            font-weight: bold;
        }
        .statut-Rejeté {
            color: #cc0000;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>📦 Commandes reçues via Green Chat</h1>
    <table id="table-commandes" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Utilisateur</th>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Unité</th>
                <th>Date</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody id="commande-body">
            <?php
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
        </tbody>
    </table>
</div>

<!-- JS: jQuery + DataTables -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#table-commandes').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
            }
        });

        // Rafraîchissement AJAX du tbody
        setInterval(() => {
            $.ajax({
                url: 'php/get_commandes.php',
                method: 'GET',
                success: function(data) {
                    $('#commande-body').html(data);
                }
            });
        }, 5000); // toutes les 5 secondes
    });
</script>
</body>
</html>
