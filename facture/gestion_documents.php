<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Factures, Bons de Livraison et Devis</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <h1>Gestion des Factures, BL et Devis</h1>
    <div class="header-buttons">
        <button class="home-btn" onclick="window.location.href='../index.php'">Accueil</button>
        <button class="add-document-btn" onclick="window.location.href='ajouter_facture_ht.php'">
            <i class="fas fa-plus"></i> Ajouter Facture
        </button>
        <button class="document-btn" onclick="window.location.href='ajouter_bon_livraison.php'">
            <i class="fas fa-plus"></i> Ajouter Bon de Livraison
        </button>
        <button class="devis-btn" onclick="window.location.href='ajouter_devis_ht.php'">
            <i class="fas fa-plus"></i> Ajouter Devis
        </button>
    </div>

    <div class="table-container">
        <h2>Factures</h2>
        <table id="factureTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Numéro</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Total HT</th>
                    <th>Total TVA</th>
                    <th>Total TTC</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../db.php';
                $result = $conn->query("SELECT f.id, f.numero, c.nom AS client, f.date, f.total_ht, f.total_tva, f.total_ttc 
                                        FROM factures f 
                                        JOIN clients c ON f.client_id = c.id");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['numero'] . "</td>";
                    echo "<td>" . $row['client'] . "</td>";
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td>" . $row['total_ht'] . "</td>";
                    echo "<td>" . $row['total_tva'] . "</td>";
                    echo "<td>" . $row['total_ttc'] . "</td>";
                    echo "<td>";
                    echo "<a href='javascript:void(0)' onclick='afficherFormulaireModifier(" . $row['id'] . ", \"Facture\")' class='btn-modifier'>Modifier</a>";
                    echo "<a href='javascript:supprimerDocument(" . $row['id'] . ", \"Facture\")' class='btn-supprimer'>Supprimer</a>";
                    echo "<a href='imprimer_document.php?id=" . $row['id'] . "&type=Facture' class='btn-imprimer'>Imprimer</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="table-container">
        <h2>Bons de Livraison</h2>
        <table id="bonLivraisonTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Numéro</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Total HT</th>
                    <th>Total TVA</th>
                    <th>Total TTC</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT b.id, b.numero, c.nom AS client, b.date, b.total_ht, b.total_tva, b.total_ttc 
                                        FROM bons_de_livraison b 
                                        JOIN clients c ON b.client_id = c.id");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['numero'] . "</td>";
                    echo "<td>" . $row['client'] . "</td>";
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td>" . $row['total_ht'] . "</td>";
                    echo "<td>" . $row['total_tva'] . "</td>";
                    echo "<td>" . $row['total_ttc'] . "</td>";
                    echo "<td>";
                    echo "<a href='javascript:void(0)' onclick='afficherFormulaireModifier(" . $row['id'] . ", \"Bon de Livraison\")' class='btn-modifier'>Modifier</a>";
                    echo "<a href='javascript:supprimerDocument(" . $row['id'] . ", \"Bon de Livraison\")' class='btn-supprimer'>Supprimer</a>";
                    echo "<a href='imprimer_document.php?id=" . $row['id'] . "&type=Bon de Livraison' class='btn-imprimer'>Imprimer</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="table-container">
        <h2>Devis</h2>
        <table id="devisTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Numéro</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Total HT</th>
                    <th>Total TVA</th>
                    <th>Total TTC</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT d.id, d.numero, c.nom AS client, d.date, d.total_ht, d.total_tva, d.total_ttc 
                                        FROM devis d 
                                        JOIN clients c ON d.client_id = c.id");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['numero'] . "</td>";
                    echo "<td>" . $row['client'] . "</td>";
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td>" . $row['total_ht'] . "</td>";
                    echo "<td>" . $row['total_tva'] . "</td>";
                    echo "<td>" . $row['total_ttc'] . "</td>";
                    echo "<td>";
                    echo "<a href='javascript:void(0)' onclick='afficherFormulaireModifier(" . $row['id'] . ", \"Devis\")' class='btn-modifier'>Modifier</a>";
                    echo "<a href='javascript:supprimerDocument(" . $row['id'] . ", \"Devis\")' class='btn-supprimer'>Supprimer</a>";
                    echo "<a href='imprimer_document.php?id=" . $row['id'] . "&type=Devis' class='btn-imprimer'>Imprimer</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div id="popupForm" class="popup">
        <form action="modifier_document.php" method="POST" class="popup-content">
            <span class="close-button" onclick="fermerPopup()">&times;</span>
            <h2>Modifier Document</h2>
            <input type="hidden" id="idDocument" name="idDocument" value="">
            <input type="hidden" id="typeDocument" name="typeDocument" value="">

            <div class="form-group">
                <label for="numero">Numéro:</label>
                <input type="text" id="numero" name="numero" value="" readonly>
            </div>

            <div class="form-group">
                <label for="client_id">Client:</label>
                <select id="client_id" name="client_id">
                    <?php
                    $clients = $conn->query("SELECT id, nom FROM clients");
                    while ($client = $clients->fetch_assoc()) {
                        echo "<option value='" . $client['id'] . "'>" . $client['nom'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" value="">
            </div>

            <div id="factureFields" style="display: none;">
                <div class="form-group">
                    <label for="total_ht">Total HT:</label>
                    <input type="text" id="total_ht" name="total_ht" value="" readonly>
                </div>
                <div class="form-group">
                    <label for="total_tva">Total TVA:</label>
                    <input type="text" id="total_tva" name="total_tva" value="" readonly>
                </div>
                <div class="form-group">
                    <label for="total_ttc">Total TTC:</label>
                    <input type="text" id="total_ttc" name="total_ttc" value="" readonly>
                </div>
                <div class="form-group">
                    <label for="montant_du">Montant Dû:</label>
                    <input type="text" id="montant_du" name="montant_du" value="" readonly>
                </div>
                <div class="form-group">
                    <label for="montant_paye">Montant Payé:</label>
                    <input type="text" id="montant_paye" name="montant_paye" value=""
                        oninput="calculerMontantRestant()">
                </div>
                <div class="form-group">
                    <label for="montant_restant">Montant Restant:</label>
                    <input type="text" id="montant_restant" name="montant_restant" value="" readonly>
                </div>
            </div>

            <div id="bonLivraisonFields" style="display: none;">
                <div class="form-group">
                    <label for="total_ht_bl">Total HT:</label>
                    <input type="text" id="total_ht_bl" name="total_ht_bl" value="" readonly>
                </div>
                <div class="form-group">
                    <label for="total_tva_bl">Total TVA:</label>
                    <input type="text" id="total_tva_bl" name="total_tva_bl" value="" readonly>
                </div>
                <div class="form-group">
                    <label for="total_ttc_bl">Total TTC:</label>
                    <input type="text" id="total_ttc_bl" name="total_ttc_bl" value="" readonly>
                </div>
                <div class="form-group">
                    <label for="montant_du_bl">Montant Dû:</label>
                    <input type="text" id="montant_du_bl" name="montant_du_bl" value="" readonly>
                </div>
                <div class="form-group">
                    <label for="montant_paye_bl">Montant Payé:</label>
                    <input type="text" id="montant_paye_bl" name="montant_paye_bl" value=""
                        oninput="calculerMontantRestantBL()">
                </div>
                <div class="form-group">
                    <label for="montant_restant_bl">Montant Restant:</label>
                    <input type="text" id="montant_restant_bl" name="montant_restant_bl" value="" readonly>
                </div>
            </div>

            <div id="devisFields" style="display: none;">
                <div class="form-group">
                    <label for="total_ht_devis">Total HT:</label>
                    <input type="text" id="total_ht_devis" name="total_ht_devis" value="" readonly>
                </div>
                <div class="form-group">
                    <label for="total_tva_devis">Total TVA:</label>
                    <input type="text" id="total_tva_devis" name="total_tva_devis" value="" readonly>
                </div>
                <div class="form-group">
                    <label for="total_ttc_devis">Total TTC:</label>
                    <input type="text" id="total_ttc_devis" name="total_ttc_devis" value="" readonly>
                </div>
            </div>

            <div id="produitsContainer"></div> <!-- Container for products -->

            <button type="button" class="btn btn-primary" onclick="ajouterProduit()">Ajouter Produit</button>
            <input type="submit" class="btn btn-primary" value="Modifier">
        </form>
    </div>

    <script>
        $(document).ready(function () {
            $('#factureTable').DataTable({
                "pageLength": 10
            });
            $('#bonLivraisonTable').DataTable({
                "pageLength": 10
            });
            $('#devisTable').DataTable({
                "pageLength": 10
            });
        });

        function supprimerDocument(id, type) {
            if (confirm("Êtes-vous sûr de vouloir supprimer ce " + type + " ?")) {
                window.location.href = "supprimer_document.php?id=" + id + "&type=" + type;
            }
        }

        function afficherFormulaireModifier(id, type) {
        fetch(`get_document.php?id=${id}&type=${type}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    document.getElementById('idDocument').value = data.id;
                    document.getElementById('typeDocument').value = type;
                    document.getElementById('numero').value = data.numero;
                    document.getElementById('client_id').value = data.client_id;
                    document.getElementById('date').value = data.date;

                    if (type === 'Facture') {
                        document.getElementById('factureFields').style.display = 'block';
                        document.getElementById('bonLivraisonFields').style.display = 'none';
                        document.getElementById('devisFields').style.display = 'none';
                        document.getElementById('total_ht').value = data.total_ht;
                        document.getElementById('total_tva').value = data.total_tva;
                        document.getElementById('total_ttc').value = data.total_ttc;
                        document.getElementById('montant_du').value = data.montant_du;
                        document.getElementById('montant_paye').value = data.montant_paye;
                        document.getElementById('montant_restant').value = data.montant_restant;
                    } else if (type === 'Bon de Livraison') {
                        document.getElementById('factureFields').style.display = 'none';
                        document.getElementById('bonLivraisonFields').style.display = 'block';
                        document.getElementById('devisFields').style.display = 'none';
                        document.getElementById('total_ht_bl').value = data.total_ht;
                        document.getElementById('total_tva_bl').value = data.total_tva;
                        document.getElementById('total_ttc_bl').value = data.total_ttc;
                        document.getElementById('montant_du_bl').value = data.montant_du;
                        document.getElementById('montant_paye_bl').value = data.montant_paye;
                        document.getElementById('montant_restant_bl').value = data.montant_restant;
                    } else if (type === 'Devis') {
                        document.getElementById('factureFields').style.display = 'none';
                        document.getElementById('bonLivraisonFields').style.display = 'none';
                        document.getElementById('devisFields').style.display = 'block';
                        document.getElementById('total_ht_devis').value = data.total_ht;
                        document.getElementById('total_tva_devis').value = data.total_tva;
                        document.getElementById('total_ttc_devis').value = data.total_ttc;
                    }

                    const produitsContainer = document.getElementById('produitsContainer');
                    produitsContainer.innerHTML = ''; // Clear previous products

                    data.produits.forEach((produit, index) => {
                        const produitDiv = document.createElement('div');
                        produitDiv.classList.add('produit-group');
                        produitDiv.innerHTML = `
                            <label for="produit_nom_${index}">Nom:</label>
                            <input type="text" id="produit_nom_${index}" name="produit_nom[]" value="${produit.nom}" readonly>
                            <label for="quantite_${index}">Quantité:</label>
                            <input type="number" id="quantite_${index}" name="quantite[]" value="${produit.quantite}" oninput="recalculerTotaux()">
                            <label for="prix_ttc_${index}">Prix TTC:</label>
                            <input type="number" step="0.01" id="prix_ttc_${index}" name="prix_ttc[]" value="${parseFloat(produit.prix_ttc || (produit.prix_ht * 1.20)).toFixed(2)}" oninput="recalculerTotaux()">
                            <button type="button" class="btn btn-danger" onclick="supprimerProduit(this)"><i class="fas fa-trash-alt"></i></button>
                        `;
                        produitsContainer.appendChild(produitDiv);
                    });

                    recalculerTotaux();

                    document.getElementById('popupForm').style.display = 'block';
                }
            })
            .catch(error => console.error('Erreur :', error));
    }


        function ajouterProduit() {
            const produitsContainer = document.getElementById('produitsContainer');
            const index = produitsContainer.children.length;
            const produitDiv = document.createElement('div');
            produitDiv.classList.add('produit-group');
            produitDiv.innerHTML = `
                <label for="produit_nom_${index}">Nom:</label>
                <select name="produit_nom[]" required>
                    <?php
                    $produits = $conn->query("SELECT id, nom FROM produits");
                    while ($produit = $produits->fetch_assoc()) {
                        echo "<option value='" . $produit['nom'] . "'>" . $produit['nom'] . "</option>";
                    }
                    ?>
                </select>
                <label for="quantite_${index}">Quantité:</label>
                <input type="number" id="quantite_${index}" name="quantite[]" value="" oninput="recalculerTotaux()">
                <label for="prix_ttc_${index}">Prix TTC:</label>
                <input type="number" step="0.01" id="prix_ttc_${index}" name="prix_ttc[]" value="" oninput="recalculerTotaux()">
                <button type="button" class="btn btn-danger" onclick="supprimerProduit(this)"><i class="fas fa-trash-alt"></i></button>
            `;
            produitsContainer.appendChild(produitDiv);
            recalculerTotaux();
        }

        function supprimerProduit(button) {
            const produitGroup = button.closest('.produit-group');
            produitGroup.remove();
            recalculerTotaux();
        }

        function recalculerTotaux() {
            const quantites = document.querySelectorAll('[name="quantite[]"]');
            const prix_ttcs = document.querySelectorAll('[name="prix_ttc[]"]');

            let total_ht = 0;
            let total_ttc = 0;
            quantites.forEach((quantite, index) => {
                const prix_ttc_val = parseFloat(prix_ttcs[index].value) || 0;
                const quantite_val = parseFloat(quantite.value) || 0;
                total_ttc += quantite_val * prix_ttc_val;
                total_ht += (quantite_val * prix_ttc_val) / 1.20;
            });

            const total_tva = total_ht * 0.20;

            document.getElementById('total_ht').value = total_ht.toFixed(2);
            document.getElementById('total_tva').value = total_tva.toFixed(2);
            document.getElementById('total_ttc').value = total_ttc.toFixed(2);

            document.getElementById('total_ht_bl').value = total_ht.toFixed(2);
            document.getElementById('total_tva_bl').value = total_tva.toFixed(2);
            document.getElementById('total_ttc_bl').value = total_ttc.toFixed(2);

            document.getElementById('total_ht_devis').value = total_ht.toFixed(2);
            document.getElementById('total_tva_devis').value = total_tva.toFixed(2);
            document.getElementById('total_ttc_devis').value = total_ttc.toFixed(2);
        }

        function fermerPopup() {
            document.getElementById('popupForm').style.display = 'none';
        }
        function calculerMontantRestant() {
        const montantDu = parseFloat(document.getElementById('montant_du').value) || 0;
        const montantPaye = parseFloat(document.getElementById('montant_paye').value) || 0;
        const montantRestant = montantDu - montantPaye;
        document.getElementById('montant_restant').value = montantRestant.toFixed(2);
    }

    function calculerMontantRestantBL() {
        const montantDu = parseFloat(document.getElementById('montant_du_bl').value) || 0;
        const montantPaye = parseFloat(document.getElementById('montant_paye_bl').value) || 0;
        const montantRestant = montantDu - montantPaye;
        document.getElementById('montant_restant_bl').value = montantRestant.toFixed(2);
    }

        function recalculerTotaux() {
            const quantites = document.querySelectorAll('[name="quantite[]"]');
            const prix_ttcs = document.querySelectorAll('[name="prix_ttc[]"]');

            let total_ht = 0;
            let total_ttc = 0;
            quantites.forEach((quantite, index) => {
                const prix_ttc_val = parseFloat(prix_ttcs[index].value) || 0;
                const quantite_val = parseFloat(quantite.value) || 0;
                total_ttc += quantite_val * prix_ttc_val;
                total_ht += (quantite_val * prix_ttc_val) / 1.20;
            });

            const total_tva = total_ht * 0.20;

            document.getElementById('total_ht').value = total_ht.toFixed(2);
            document.getElementById('total_tva').value = total_tva.toFixed(2);
            document.getElementById('total_ttc').value = total_ttc.toFixed(2);

            document.getElementById('total_ht_bl').value = total_ht.toFixed(2);
            document.getElementById('total_tva_bl').value = total_tva.toFixed(2);
            document.getElementById('total_ttc_bl').value = total_ttc.toFixed(2);

            document.getElementById('total_ht_devis').value = total_ht.toFixed(2);
            document.getElementById('total_tva_devis').value = total_tva.toFixed(2);
            document.getElementById('total_ttc_devis').value = total_ttc.toFixed(2);

            document.getElementById('montant_du').value = total_ttc.toFixed(2);
            document.getElementById('montant_du_bl').value = total_ttc.toFixed(2);

            calculerMontantRestant();
            calculerMontantRestantBL();
        }

    </script>

</body>

</html>