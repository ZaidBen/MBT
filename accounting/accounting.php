<!DOCTYPE html>
<html lang="en">
<?php
include '../db.php';

// Query to calculate the total achat
$result = $conn->query("SELECT SUM(prix_achat * quantite) as total_achat FROM achat");
$row = $result->fetch_assoc();
$total_achat = $row['total_achat'];

$result1 = $conn->query("SELECT SUM(total_ttc) as total_ventes FROM bons_de_livraison");
$row1 = $result1->fetch_assoc();
$total_ventes = $row1['total_ventes'];
// Query to calculate the total payé
$result2 = $conn->query("SELECT SUM(montant_paye) as total_paye FROM status_paiement");
$row2 = $result2->fetch_assoc();
$total_paye = $row2['total_paye'];

// Query to calculate the total non payé
$result3 = $conn->query("SELECT SUM(montant_restant) as total_non_paye FROM status_paiement");
$row3 = $result3->fetch_assoc();
$total_non_paye = $row3['total_non_paye'];

// Calculate caisse (ventes - montant payé)
$caisse = $total_ventes - $total_non_paye;
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Stock</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- DataTables CSS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> <!-- jQuery -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> <!-- DataTables JS -->
</head>

<body>
    <h1>Accounting</h1>
    <div class="header-buttons">
        <button class="home-btn" onclick="window.location.href='../index.php'">
            <i class="fas fa-home"></i> Accueil
        </button>
        <button class="ajouter-achat-btn" onclick="toggleForm('achat')">
            <i class="fas fa-plus"></i> Ajouter P.A
        </button>
        <button class="ajouter-vente-btn" onclick="toggleForm('vente')">
            <i class="fas fa-plus"></i> Ajouter P.V
        </button>
        <button class="ajouter-fournisseur-btn" onclick="toggleForm('fournisseur')">
            <i class="fas fa-plus"></i> Ajouter Fournisseur
        </button>
        <button class="vente-btn" onclick="toggleForm('charge')">
            <i class="fas fa-receipt"></i> Charges
        </button>
    </div>
    <div class="table-container">
        <div class="grid-container">
            <div class="grid-item grid-item-red">Total Achat: <br>
                <h3><b><?php echo number_format($total_achat, 2); ?> DH</b></h3>
            </div>
            <div class="grid-item grid-item-green">Ventes: <br>
                <h3><b><?php echo number_format($total_ventes, 2); ?> DH</b></h3>
            </div>
            <div class="grid-item grid-item-blue">Total Payer: <br>
                <h3><b><?php echo number_format($total_paye, 2); ?> DH</b></h3>
            </div>
            <div class="grid-item grid-item-yellow">Total Non Payer: <br>
                <h3><b><?php echo number_format($total_non_paye, 2); ?> DH</b></h3>
            </div>
            <div class="grid-item grid-item-purple">Caisse: <br>
                <h3><b><?php echo number_format($caisse, 2); ?> DH</b></h3>
            </div>
        </div>
    </div>

    <h2>Status des Paiements</h2>
    <div class="table-container">
        <table id="statusPaiementTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Numéro</th>
                    <th>Type Document</th>
                    <th>Montant Payé</th>
                    <th>Montant Dû</th>
                    <th>Montant Restant</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM status_paiement");

                while ($row = $result->fetch_assoc()) {
                    $badgeColor = $row['montant_restant'] == 0 ? 'bg-green-500' : 'bg-red-500';
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['numero'] . "</td>";
                    echo "<td>" . $row['type_document'] . "</td>";
                    echo "<td>" . $row['montant_paye'] . "</td>";
                    echo "<td>" . $row['montant_du'] . "</td>";
                    echo "<td><span class='badge " . $badgeColor . "'>" . $row['montant_restant'] . "</span></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>

        </table>
    </div>
    <h2>Prix D'Achat</h2>
    <div class="table-container">
        <table id="achatTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produit</th>
                    <th>Prix d'Achat</th>
                    <th>Fournisseur</th>
                    <th>Quantité</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Les données seront insérées ici par PHP -->
                <?php
                include '../db.php';

                $result = $conn->query("SELECT * FROM achat");

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['produit'] . "</td>";
                    echo "<td>" . $row['prix_achat'] . "</td>";
                    echo "<td>" . $row['fournisseur'] . "</td>";
                    echo "<td>" . $row['quantite'] . "</td>";
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td>";
                    echo "<a href='javascript:void(0)' onclick='afficherFormulaireModifierAchat(" . $row['id'] . ")' class='btn-modifier'><i class='fas fa-edit'></i></a>&nbsp&nbsp&nbsp"; // Bouton Modifier avec icône
                    echo "<a href='javascript:supprimerAchat(" . $row['id'] . ")' class='btn-supprimer'><i class='fas fa-trash'></i></a>"; // Bouton Supprimer avec icône
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <h2>Prix De Vente</h2>
    <div class="table-container">
        <table id="venteTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produit</th>
                    <th>Prix de Vente</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Les données seront insérées ici par PHP -->
                <?php
                include '../db.php';

                $result = $conn->query("SELECT * FROM vente");

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['produit'] . "</td>";
                    echo "<td>" . $row['prix_vente'] . "</td>";
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td>";
                    echo "<a href='javascript:void(0)' onclick='afficherFormulaireModifierVente(" . $row['id'] . ")' class='btn-modifier'><i class='fas fa-edit'></i></a>&nbsp&nbsp&nbsp"; // Bouton Modifier avec icône
                    echo "<a href='javascript:supprimerVente(" . $row['id'] . ")' class='btn-supprimer'><i class='fas fa-trash'></i></a>"; // Bouton Supprimer avec icône
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Formulaire d'ajout d'achat -->
    <div id="ajoutAchatForm" class="form-popup">
        <form action="ajouter_achat.php" method="POST" class="form-container">
            <h2>Ajouter Achat</h2>
            <div id="productContainer">
                <div class="product-row">
                    <label for="produit">Produit:</label>
                    <select id="produit" name="produit[]" required>
                        <?php
                        $produits = $conn->query("SELECT nom FROM produits");
                        while ($produit = $produits->fetch_assoc()) {
                            echo "<option value='" . $produit['nom'] . "'>" . $produit['nom'] . "</option>";
                        }
                        ?>
                    </select>
                    <label for="prix_achat">Prix d'Achat:</label>
                    <input type="text" id="prix_achat" name="prix_achat[]" required>
                    <label for="fournisseur">Fournisseur:</label>
                    <select id="fournisseur" name="fournisseur[]" required>
                        <?php
                        $fournisseurs = $conn->query("SELECT nom FROM fournisseur");
                        while ($fournisseur = $fournisseurs->fetch_assoc()) {
                            echo "<option value='" . $fournisseur['nom'] . "'>" . $fournisseur['nom'] . "</option>";
                        }
                        ?>
                    </select> <label for="quantite">Quantité:</label>
                    <input type="number" id="quantite" name="quantite[]" required>
                </div>
            </div>
            <button type="button" class="btn" onclick="ajouterProduit()">Ajouter Produit</button><br><br><br>
            <label for="date"><b>Date:</b></label>
            <input type="date" id="date" name="date" required>
            <button type="submit" class="btn">Ajouter Achat</button>
            <button type="button" class="btn cancel" onclick="closeForm('achat')">Fermer</button>
        </form>
    </div>

    <!-- Formulaire de modification d'achat -->
    <div id="modifierAchatForm" class="form-popup">
        <form action="modifier_achat.php" method="POST" class="form-container">
            <h2>Modifier Achat</h2>
            <input type="hidden" id="idAchat" name="id">
            <div id="productContainerModifier">
                <div class="product-row">
                    <label for="produit">Produit:</label>
                    <select id="produitModifier" name="produit" required>
                        <?php
                        $produits = $conn->query("SELECT nom FROM produits");
                        while ($produit = $produits->fetch_assoc()) {
                            echo "<option value='" . $produit['nom'] . "'>" . $produit['nom'] . "</option>";
                        }
                        ?>
                    </select>
                    <label for="prix_achat">Prix d'Achat:</label>
                    <input type="text" id="prix_achatModifier" name="prix_achat" required>
                    <label for="fournisseur">Fournisseur:</label>
                    <select id="fournisseurModifier" name="fournisseur" required>
                        <?php
                        $fournisseurs = $conn->query("SELECT nom FROM fournisseur");
                        while ($fournisseur = $fournisseurs->fetch_assoc()) {
                            echo "<option value='" . $fournisseur['nom'] . "'>" . $fournisseur['nom'] . "</option>";
                        }
                        ?>
                    </select>
                    <label for="quantite">Quantité:</label>
                    <input type="number" id="quantiteModifier" name="quantite" required>
                </div>
            </div>
            <label for="date"><b>Date:</b></label>
            <input type="date" id="dateModifier" name="date" required>
            <button type="submit" class="btn">Modifier Achat</button>
            <button type="button" class="btn cancel" onclick="closeForm('modifierAchat')">Fermer</button>
        </form>
    </div>

    <!-- Formulaire d'ajout de vente -->
    <div id="ajoutVenteForm" class="form-popup">
        <form action="ajouter_vente.php" method="POST" class="form-container">
            <h2>Ajouter Vente</h2>
            <div id="productContainerVente">
                <div class="product-row">
                    <label for="produit">Produit:</label>
                    <select id="produit" name="produit[]" required>
                        <?php
                        $produits = $conn->query("SELECT nom FROM produits");
                        while ($produit = $produits->fetch_assoc()) {
                            echo "<option value='" . $produit['nom'] . "'>" . $produit['nom'] . "</option>";
                        }
                        ?>
                    </select>
                    <label for="prix_vente">Prix de Vente:</label>
                    <input type="text" id="prix_vente" name="prix_vente[]" required>
                    <!--  <button type="button" class="btn-supprimer" onclick="supprimerProduit(this)">-</button>-->
                </div>
            </div>
            <button type="button" class="btn" onclick="ajouterProduitVente()">Ajouter Produit</button><br><br><br>
            <label for="date"><b>Date:</b></label>
            <input type="date" id="date" name="date" required>
            <button type="submit" class="btn">Ajouter Vente</button>
            <button type="button" class="btn cancel" onclick="closeForm('vente')">Fermer</button>
        </form>
    </div>

    <!-- Formulaire de modification de vente -->
    <div id="modifierVenteForm" class="form-popup">
        <form action="modifier_vente.php" method="POST" class="form-container">
            <h2>Modifier Vente</h2>
            <input type="hidden" id="idVente" name="id">
            <div id="productContainerModifierVente">
                <div class="product-row">
                    <label for="produit">Produit:</label>
                    <select id="produitModifierVente" name="produit" required>
                        <?php
                        $produits = $conn->query("SELECT nom FROM produits");
                        while ($produit = $produits->fetch_assoc()) {
                            echo "<option value='" . $produit['nom'] . "'>" . $produit['nom'] . "</option>";
                        }
                        ?>
                    </select>
                    <label for="prix_vente">Prix de Vente:</label>
                    <input type="text" id="prix_venteModifier" name="prix_vente" required>
                </div>
            </div>
            <label for="date"><b>Date:</b></label>
            <input type="date" id="dateModifierVente" name="date" required>
            <button type="submit" class="btn">Modifier Vente</button>
            <button type="button" class="btn cancel" onclick="closeForm('modifierVente')">Fermer</button>
        </form>
    </div>

    <!-- Formulaire d'ajout de fournisseur -->
    <div id="ajoutFournisseurForm" class="form-popup">
        <form action="ajouter_fournisseur.php" method="POST" class="form-container">
            <h2>Ajouter Fournisseur</h2>
            <label for="nom"><b>Nom:</b></label>
            <input type="text" id="nom" name="nom" required>
            <label for="ville"><b>Ville:</b></label>
            <input type="text" id="ville" name="ville" required>
            <label for="contact"><b>Contact:</b></label>
            <input type="text" id="contact" name="contact" required>
            <button type="submit" class="btn">Ajouter</button>
            <button type="button" class="btn cancel" onclick="closeForm('fournisseur')">Fermer</button>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            $('#achatTable').DataTable({
                "pageLength": 10 // Limite à 10 lignes par page
            });

            $('#venteTable').DataTable({
                "pageLength": 10 // Limite à 10 lignes par page
            });

            window.supprimerAchat = function (id) {
                if (confirm("Êtes-vous sûr de vouloir supprimer cet achat ?")) {
                    window.location.href = "supprimer_achat.php?id=" + id;
                }
            }

            window.supprimerVente = function (id) {
                if (confirm("Êtes-vous sûr de vouloir supprimer cette vente ?")) {
                    window.location.href = "supprimer_vente.php?id=" + id;
                }
            }

            window.afficherFormulaireModifierAchat = function (id) {
                fetch('get_achat.php?id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('idAchat').value = data.id;
                        document.getElementById('produitModifier').value = data.produit;
                        document.getElementById('prix_achatModifier').value = data.prix_achat;
                        document.getElementById('fournisseurModifier').value = data.fournisseur;
                        document.getElementById('quantiteModifier').value = data.quantite;
                        document.getElementById('dateModifier').value = data.date;
                        document.getElementById('modifierAchatForm').style.display = 'block';
                    })
                    .catch(error => console.error('Erreur :', error));
            }

            window.afficherFormulaireModifierVente = function (id) {
                fetch('get_vente.php?id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('idVente').value = data.id;
                        document.getElementById('produitModifierVente').value = data.produit;
                        document.getElementById('prix_venteModifier').value = data.prix_vente;
                        document.getElementById('dateModifierVente').value = data.date;
                        document.getElementById('modifierVenteForm').style.display = 'block';
                    })
                    .catch(error => console.error('Erreur :', error));
            }

            window.toggleForm = function (type) {
                if (type === 'achat') {
                    document.getElementById('ajoutAchatForm').style.display = 'block';
                    document.getElementById('ajoutVenteForm').style.display = 'none';
                    document.getElementById('ajoutFournisseurForm').style.display = 'none';
                    document.getElementById('modifierAchatForm').style.display = 'none';
                    document.getElementById('modifierVenteForm').style.display = 'none';
                } else if (type === 'vente') {
                    document.getElementById('ajoutVenteForm').style.display = 'block';
                    document.getElementById('ajoutAchatForm').style.display = 'none';
                    document.getElementById('ajoutFournisseurForm').style.display = 'none';
                    document.getElementById('modifierAchatForm').style.display = 'none';
                    document.getElementById('modifierVenteForm').style.display = 'none';
                } else if (type === 'fournisseur') {
                    document.getElementById('ajoutFournisseurForm').style.display = 'block';
                    document.getElementById('ajoutAchatForm').style.display = 'none';
                    document.getElementById('ajoutVenteForm').style.display = 'none';
                    document.getElementById('modifierAchatForm').style.display = 'none';
                    document.getElementById('modifierVenteForm').style.display = 'none';
                } else if (type === 'modifierAchat') {
                    document.getElementById('modifierAchatForm').style.display = 'block';
                    document.getElementById('ajoutAchatForm').style.display = 'none';
                    document.getElementById('ajoutVenteForm').style.display = 'none';
                    document.getElementById('ajoutFournisseurForm').style.display = 'none';
                    document.getElementById('modifierVenteForm').style.display = 'none';
                } else if (type === 'modifierVente') {
                    document.getElementById('modifierVenteForm').style.display = 'block';
                    document.getElementById('ajoutAchatForm').style.display = 'none';
                    document.getElementById('ajoutVenteForm').style.display = 'none';
                    document.getElementById('ajoutFournisseurForm').style.display = 'none';
                    document.getElementById('modifierAchatForm').style.display = 'none';
                }
            }

            window.closeForm = function (type) {
                if (type === 'achat') {
                    document.getElementById('ajoutAchatForm').style.display = 'none';
                } else if (type === 'vente') {
                    document.getElementById('ajoutVenteForm').style.display = 'none';
                } else if (type === 'fournisseur') {
                    document.getElementById('ajoutFournisseurForm').style.display = 'none';
                } else if (type === 'modifierAchat') {
                    document.getElementById('modifierAchatForm').style.display = 'none';
                } else if (type === 'modifierVente') {
                    document.getElementById('modifierVenteForm').style.display = 'none';
                }
            }
        });

        function ajouterProduit() {
            var container = document.getElementById('productContainer');
            var div = document.createElement('div');
            div.classList.add('product-row');
            div.innerHTML = `
                <label for="produit">Produit:</label>
                <select name="produit[]" required>
                    <?php
                    $produits = $conn->query("SELECT nom FROM produits");
                    while ($produit = $produits->fetch_assoc()) {
                        echo "<option value='" . $produit['nom'] . "'>" . $produit['nom'] . "</option>";
                    }
                    ?>
                </select>
                <label for="prix_achat">Prix d'Achat:</label>
                <input type="text" name="prix_achat[]" required>
                <label for="fournisseur">Fournisseur:</label>
                <input type="text" name="fournisseur[]" required>
                <label for="quantite">Quantité:</label>
                <input type="number" name="quantite[]" required>
                <button type="button" class="btn-supprimer" onclick="supprimerProduit(this)">-</button>
            `;
            container.appendChild(div);
        }

        function ajouterProduitVente() {
            var container = document.getElementById('productContainerVente');
            var div = document.createElement('div');
            div.classList.add('product-row');
            div.innerHTML = `
                <label for="produit">Produit:</label>
                <select name="produit[]" required>
                    <?php
                    $produits = $conn->query("SELECT nom FROM produits");
                    while ($produit = $produits->fetch_assoc()) {
                        echo "<option value='" . $produit['nom'] . "'>" . $produit['nom'] . "</option>";
                    }
                    ?>
                </select>
                <label for="prix_vente">Prix de Vente:</label>
                <input type="text" name="prix_vente[]" required>
                <button type="button" class="btn-supprimer" onclick="supprimerProduit(this)">-</button>
            `;
            container.appendChild(div);
        }

        function supprimerProduit(button) {
            button.parentElement.remove();
        }
    </script>

</body>

</html>