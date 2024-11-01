<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Stock</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css"> <!-- DataTables CSS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> <!-- jQuery -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> <!-- DataTables JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js -->
</head>

<body>
    <h1>Gestion de Stock</h1>
    <div class="header-buttons">
        <button class="home-btn" onclick="window.location.href='ajouter_produit.html'">
            <i class="fas fa-plus"></i> Nouveau Produit
        </button>
        <button class="mouvement-btn" onclick="window.location.href='mouvement_stock_ht.php'">
            <i class="fas fa-exchange-alt"></i> Mouvement De Stock
        </button>
        <button class="fournisseur-btn" onclick="window.location.href='accounting/accounting.php'">
            <i class="fas fa-receipt"></i> Accounting
        </button>
        <button class="client-btn" onclick="window.location.href='client/clients.php'">
            <i class="fas fa-users"></i> Clients
        </button>
        <button class="facture-btn" onclick="window.location.href='facture/gestion_documents.php'">
            <i class="fas fa-file-invoice"></i> Factures
        </button>
    </div>

    <div class="table-container">
        <table id="stockTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Quantité</th>
                    <th>Date d'Ajout</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Les données seront insérées ici par PHP -->
                <?php
                include 'db.php';

                $result = $conn->query("SELECT * FROM produits");

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['nom'] . "</td>";
                    echo "<td>" . $row['quantite'] . "</td>";
                    echo "<td>" . $row['date_ajout'] . "</td>";
                    echo "<td>";
                    echo "<a href='javascript:void(0)' onclick='afficherFormulaireModifier(" . $row['id'] . ")' class='btn-modifier'>Modifier</a>"; // Bouton Modifier
                    echo "<a href='javascript:supprimerProduit(" . $row['id'] . ")' class='btn-supprimer'>Supprimer</a>"; // Bouton Supprimer
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <br><br>

    <!-- Conteneur pour les graphiques -->
    <div class="charts-container">
        <div class="chart-row">
            <div class="chart-item">
                <h3>  "Cameras"</h3>
                <canvas id="chartCam"></canvas>
            </div>
            <div class="chart-item">
                <h3>  "DVR"</h3>
                <canvas id="chartDVR"></canvas>
            </div>
        </div>
        <div class="chart-row">
            <div class="chart-item">
                <h3>  "Cables"</h3>
                <canvas id="chartCable"></canvas>
            </div>
            <div class="chart-item">
                <h3>  "Disque Dur"</h3>
                <canvas id="chartDisque"></canvas>
            </div>
        </div>
        <div class="chart-row">
            <div class="chart-item">
                <h3>  "Bloc Alimentaion"</h3>
                <canvas id="chartBloc"></canvas>
            </div>
            <div class="chart-item">
                <h3>Autres Produits</h3>
                <canvas id="chartOthers"></canvas>
            </div>
        </div>
    </div>
</body>

<script>
    $(document).ready(function () {
        $('#stockTable').DataTable({
            "pageLength": 10 // Limite à 10 lignes par page
        });

        // Fetch product data for the charts
        $.ajax({
            url: 'get_chart_data.php',
            method: 'GET',
            success: function (data) {
                const productsCam = data.filter(item => item.nom.startsWith('Cam'));
                const productsDVR = data.filter(item => item.nom.startsWith('DVR'));
                const productsCable = data.filter(item => item.nom.startsWith('Cable'));
                const productsDisque = data.filter(item => item.nom.startsWith('Disque'));
                const productsBloc = data.filter(item => item.nom.startsWith('Bloc'));
                const productsOthers = data.filter(item =>
                    !item.nom.startsWith('Cam') &&
                    !item.nom.startsWith('DVR') &&
                    !item.nom.startsWith('Cable') &&
                    !item.nom.startsWith('Disque') &&
                    !item.nom.startsWith('Bloc')
                );

                createChart('chartCam', productsCam, 'Produits Commencant par "Cam"');
                createChart('chartDVR', productsDVR, 'Produits Commencant par "DVR"');
                createChart('chartCable', productsCable, 'Produits Commencant par "Cable"');
                createChart('chartDisque', productsDisque, 'Produits Commencant par "Disque"');
                createChart('chartBloc', productsBloc, 'Produits Commencant par "Bloc"');
                createChart('chartOthers', productsOthers, 'Autres Produits');
            },
            error: function (xhr, status, error) {
                console.error('Erreur lors de la récupération des données du graphique:', error);
            }
        });

        function createChart(canvasId, productData, label) {
            const productNames = productData.map(item => item.nom);
            const productQuantities = productData.map(item => item.quantite);

            const backgroundColors = productQuantities.map(() => getRandomColor());
            const borderColors = backgroundColors.map(color => color.replace('0.2', '1'));

            const ctx = document.getElementById(canvasId).getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: productNames,
                    datasets: [{
                        label: label,
                        data: productQuantities,
                        backgroundColor: backgroundColors,
                        borderColor: borderColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Quantité'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Produit'
                            }
                        }
                    }
                }
            });
        }

        function getRandomColor() {
            const letters = '0123456789ABCDEF';
            let color = 'rgba(';
            for (let i = 0; i < 3; i++) {
                color += Math.floor(Math.random() * 256) + ',';
            }
            color += '0.2)'; // Opacity
            return color;
        }

        window.supprimerProduit = function(id) {
            if (confirm("Êtes-vous sûr de vouloir supprimer ce produit ?")) {
                window.location.href = "supprimer_produit.php?id=" + id;
            }
        }

        window.afficherFormulaireModifier = function(id) {
            // Récupérer les détails du produit avec l'ID donné et remplir le formulaire de modification
            fetch('get_produit.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('idProduit').value = data.id;
                    document.getElementById('nom').value = data.nom;
                    document.getElementById('quantite').value = data.quantite;
                    // Afficher le pop-up
                    document.getElementById('popupForm').style.display = 'block';
                })
                .catch(error => console.error('Erreur :', error));
        }

        window.fermerPopup = function() {
            document.getElementById('popupForm').style.display = 'none';
        }
    });
</script>

<div id="popupForm" class="popup">
    <form action="modifier_produit.php" method="POST" class="popup-content">
        <span class="close-button" onclick="fermerPopup()">&times;</span> <!-- Bouton "X" pour fermer le formulaire -->
        <h2>Modifier Produit</h2>
        <input type="hidden" id="idProduit" name="idProduit" value="">
        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" value="">
        <label for="quantite">Quantité:</label>
        <input type="text" id="quantite" name="quantite" value="">
        <input type="submit" value="Modifier">
    </form>
</div>

<style>
    .charts-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .chart-row {
        display: flex;
        justify-content: center;
        width: 100%;
        margin-bottom: 20px;
    }

    .chart-item {
        width: 45%;
        margin: 0 2.5%;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background: #f9f9f9;
        text-align: center;
    }
</style>

</html>
