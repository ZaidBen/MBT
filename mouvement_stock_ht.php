<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mouvement de Stock</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Inclure SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- DataTables CSS -->

</head>

<body>
    <h1>Mouvement de Stock</h1>
    <div class="header-buttons">
        <button class="home-btn" onclick="window.location.href='index.php'">
            <i class="fas fa-home"></i> Acceuil
        </button>
        <button class="mouvement-btn" onclick="window.location.href='ajouter_produit.html'">
            <i class="fas fa-plus"></i> Nouveau Produit
        </button>
        <button class="fournisseur-btn" onclick="window.location.href='accounting/accounting.php'">
            <i class="fas fa-receipt"></i> Accounting
        </button>
        <button class="client-btn" onclick="window.location.href='client/clients.php'">
            <i class="fas fa-users"></i> Clients
        </button>
        <button class="facture-btn" onclick="window.location.href='facture/gestion_documents.php'">
            <i class="fas fa-file-invoice"></i> Facture
        </button>
    </div>
    <div class="container">
        <form id="mouvementForm">
            <div id="productRows">
                <div class="product-row">
                    <div class="form-group">
                        <label for="produit_nom">Nom du Produit:</label>
                        <select id="produit_nom" name="produit_nom[]" required>
                            <option value="">Sélectionner un produit</option>
                            <?php
                            include 'db.php';
                            $result = $conn->query("SELECT id, nom FROM produits");
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['id'] . "'>" . $row['nom'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type_mouvement">Mouvement:</label>
                        <select id="type_mouvement" name="type_mouvement[]" required>
                            <option value="entree">Entrée</option>
                            <option value="sortie">Sortie</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantite">Quantité:</label>
                        <input type="number" id="quantite" name="quantite[]" required>
                    </div>
                    <button type="button" class="btn btn-danger" onclick="supprimerLigneProduit(this)"><i
                            class="fas fa-trash-alt"></i></button>
                </div>
            </div>
            <button type="button" id="add-pr" class="btn btn-success" onclick="ajouterLigneProduit()">Ajouter
                Produit</button>
            <input type="submit" class="btn btn-primary" value="Enregistrer">
        </form>

        <h2>Historique des Mouvements de Stock</h2>
        <div class="table-container">
            <table id="stockMovementsTable" class="display">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Produit</th>
                        <th>Type de Mouvement</th>
                        <th>Quantité</th>
                        <th>Date de Mouvement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT sm.id, p.nom AS produit, sm.type_mouvement, sm.quantite, sm.date_mouvement 
                                            FROM stock_movements sm 
                                            JOIN produits p ON sm.produit_id = p.id");

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['produit'] . "</td>";
                        echo "<td>" . $row['type_mouvement'] . "</td>";
                        echo "<td>" . $row['quantite'] . "</td>";
                        echo "<td>" . $row['date_mouvement'] . "</td>";
                        echo "<td>";
                        echo "<button class='btn btn-upd' onclick='modifierMouvement(" . $row['id'] . ")'><i class='fas fa-edit'></i></button> ";
                        echo "<button class='btn btn-supprimer' onclick='supprimerMouvement(" . $row['id'] . ")'><i class='fas fa-trash-alt'></i></button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- Conteneur pour le graphique -->
        <div class="chart-container">
            <canvas id="mouvementChart"></canvas>
        </div>

    </div>
    <!-- Inclure jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Inclure SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Inclure DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script src="js/chart.min.js"></script>
    <script src="js/chartjs-adapter-date-fns.bundle.min.js"></script>

    <script type="text/javascript">
        function ajouterLigneProduit() {
            const productRows = document.getElementById('productRows');
            const row = document.createElement('div');
            row.classList.add('product-row');
            row.innerHTML = `
            <div class="form-group">
                <label for="produit_nom">Nom du Produit:</label>
                <select id="produit_nom" name="produit_nom[]" required>
                    <option value="">Sélectionner un produit</option>
                    <?php
                    $result = $conn->query("SELECT id, nom FROM produits");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nom'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="type_mouvement">Mouvement:</label>
                <select id="type_mouvement" name="type_mouvement[]" required>
                    <option value="entree">Entrée</option>
                    <option value="sortie">Sortie</option>
                </select>
            </div>
            <div class="form-group">
                <label for="quantite">Quantité:</label>
                <input type="number" id="quantite" name="quantite[]" required>
            </div>
            <button type="button" class="btn btn-danger" onclick="supprimerLigneProduit(this)"><i class="fas fa-trash-alt"></i></button>
        `;
            productRows.appendChild(row);
        }

        function supprimerLigneProduit(button) {
            button.parentElement.remove();
        }

        function modifierMouvement(id) {
            Swal.fire({
                title: 'Modifier Mouvement',
                html: `<input type="number" id="newQuantite" class="swal2-input" placeholder="Nouvelle Quantité">`,
                showCancelButton: true,
                confirmButtonText: 'Modifier',
                preConfirm: () => {
                    const newQuantite = Swal.getPopup().querySelector('#newQuantite').value;
                    if (!newQuantite) {
                        Swal.showValidationMessage(`Veuillez entrer une nouvelle quantité`);
                    }
                    return { newQuantite: newQuantite };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: 'modifier_mouvement.php',
                        data: { id: id, quantite: result.value.newQuantite },
                        success: function (response) {
                            Swal.fire({
                                icon: response.status === 'success' ? 'success' : 'error',
                                title: response.status === 'success' ? 'Succès' : 'Erreur',
                                text: response.message
                            }).then((result) => {
                                if (response.status === 'success') {
                                    location.reload();
                                }
                            });
                        },
                        error: function (xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: 'Une erreur s\'est produite lors de la modification du mouvement de stock.'
                            });
                        }
                    });
                }
            });
        }

        function supprimerMouvement(id) {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: 'supprimer_mouvement.php',
                        data: { id: id },
                        success: function (response) {
                            Swal.fire({
                                icon: response.status === 'success' ? 'success' : 'error',
                                title: response.status === 'success' ? 'Succès' : 'Erreur',
                                text: response.message
                            }).then((result) => {
                                if (response.status === 'success') {
                                    location.reload();
                                }
                            });
                        },
                        error: function (xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: 'Une erreur s\'est produite lors de la suppression du mouvement de stock.'
                            });
                        }
                    });
                }
            });
        }

        $(document).ready(function () {
            if (!$.fn.DataTable.isDataTable('#stockMovementsTable')) {
                $('#stockMovementsTable').DataTable({
                    "pageLength": 10
                });
            }

            $('#mouvementForm').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: 'mouvement_stock.php',
                    data: $(this).serialize(),
                    success: function (response) {
                        Swal.fire({
                            icon: response.status === 'success' ? 'success' : 'error',
                            title: response.status === 'success' ? 'Succès' : 'Erreur',
                            text: response.message
                        }).then((result) => {
                            if (response.status === 'success') {
                                location.reload();
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: 'Une erreur s\'est produite lors de l\'enregistrement du mouvement de stock.'
                        });
                    }
                });
            });

            // Fetch data and create the chart
            $.ajax({
                type: 'GET',
                url: 'get_movement_data.php',
                success: function (data) {
                    const groupedData = {};
                    data.forEach(item => {
                        if (!groupedData[item.produit]) {
                            groupedData[item.produit] = [];
                        }
                        groupedData[item.produit].push({
                            x: item.produit,
                            y: item.quantite
                        });
                    });

                    const datasets = Object.keys(groupedData).map(produit => ({
                        label: produit,
                        data: groupedData[produit],
                        borderColor: getRandomColor(),
                        backgroundColor: getRandomColor(),
                        borderWidth: 1,
                        barPercentage: 2,
                        categoryPercentage: 0.8
                    }));

                    const ctx = document.getElementById('mouvementChart').getContext('2d');
                    const mouvementChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: Object.keys(groupedData),
                            datasets: datasets
                        },
                        options: {
                            scales: {
                                x: {
                                    type: 'category',
                                    labels: Object.keys(groupedData),
                                    title: {
                                        display: true,
                                        text: 'Produit'
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Quantité'
                                    },
                                    grid: {
                                        drawBorder: true,
                                        color: function (context) {
                                            if (context.tick.value === 0) {
                                                return 'rgba(0, 0, 0, 1)';  // Zero line color
                                            }
                                            return 'rgba(0, 0, 0, 0.1)';  // Other grid lines color
                                        },
                                        lineWidth: function (context) {
                                            if (context.tick.value === 0) {
                                                return 3;  // Zero line width
                                            }
                                            return 1;  // Other grid lines width
                                        }
                                    }
                                }
                            }
                        }
                    });
                },
                error: function (xhr, status, error) {
                    console.error('Erreur lors de la récupération des données du graphique:', error);
                }
            });
        });

        function getRandomColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
    </script>

</body>

</html>
