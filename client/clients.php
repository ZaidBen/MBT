<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Clients</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <h1>Gestion des Clients</h1>
    <div class="header-buttons">
        <button class="home-btn" onclick="window.location.href='../index.php'">Accueil</button>
        <button class="client-btn" onclick="window.location.href='ajouter_client.html'">
            <i class="fas fa-plus"></i> Nouveau Client
        </button>
        <button class="mouvement-btn" onclick="window.location.href='../mouvement_stock_ht.php'">
            <i class="fas fa-exchange-alt"></i> Mouvement De Stock
        </button>
        <button class="fournisseur-btn" onclick="window.location.href='../accounting/accounting.php'">
            <i class="fas fa-receipt"></i> Accounting
        </button>
        <button class="facture-btn" onclick="window.location.href='../facture/gestion_documents.php'">
            <i class="fas fa-file-invoice"></i> Factures
        </button>
    </div>

    <div class="table-container">
        <table id="clientTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>ICE</th>
                    <th>Contact</th>
                    <th>Adresse</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Les données seront insérées ici par PHP -->
                <?php
                include '../db.php';

                $result = $conn->query("SELECT * FROM clients");

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['nom'] . "</td>";
                    echo "<td>" . $row['ice'] . "</td>";
                    echo "<td>" . $row['contact'] . "</td>";
                    echo "<td>" . $row['adresse'] . "</td>";
                    echo "<td>";
                    echo "<a href='javascript:void(0)' onclick='afficherFormulaireModifier(" . $row['id'] . ")' class='btn-modifier'>Modifier</a>";
                    echo "<a href='javascript:supprimerClient(" . $row['id'] . ")' class='btn-supprimer'>Supprimer</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

<script>
    $(document).ready(function() {
        $('#clientTable').DataTable({
            "pageLength": 10
        });
    });

    function supprimerClient(id) {
        if (confirm("Êtes-vous sûr de vouloir supprimer ce client ?")) {
            window.location.href = "supprimer_client.php?id=" + id;
        }
    }

    function afficherFormulaireModifier(id) {
        fetch('get_client.php?id=' + id)
            .then(response => response.json())
            .then(data => {
                document.getElementById('idClient').value = data.id;
                document.getElementById('nom').value = data.nom;
                document.getElementById('ice').value = data.ice;
                document.getElementById('contact').value = data.contact;
                document.getElementById('adresse').value = data.adresse;
                document.getElementById('popupForm').style.display = 'block';
            })
            .catch(error => console.error('Erreur :', error));
    }

    function fermerPopup() {
        document.getElementById('popupForm').style.display = 'none';
    }
</script>

<div id="popupForm" class="popup">
    <form action="modifier_client.php" method="POST" class="popup-content">
        <span class="close-button" onclick="fermerPopup()">&times;</span>
        <h2>Modifier Client</h2>
        <input type="hidden" id="idClient" name="idClient" value="">
        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" value="">
        <label for="ice">ICE:</label>
        <input type="text" id="ice" name="ice" value="">
        <label for="contact">Contact:</label>
        <input type="text" id="contact" name="contact" value="">
        <label for="adresse">Adresse:</label>
        <input type="text" id="adresse" name="adresse" value="">
        <input type="submit" value="Modifier">
    </form>
</div>

</html>
