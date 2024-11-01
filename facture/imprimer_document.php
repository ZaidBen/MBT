<?php
include '../db.php';

$id = $_GET['id'];
$type = $_GET['type'];

if ($type == 'Bon de Livraison') {
    $sql = "SELECT b.numero, c.nom AS client, c.adresse, c.contact, c.ice, b.date, b.total_ht, b.total_tva, b.total_ttc, d.produit_nom, d.quantite, d.prix_ht, ROUND(d.prix_ht * 1.2, 2) AS prix_ttc 
            FROM bons_de_livraison b 
            JOIN clients c ON b.client_id = c.id 
            JOIN bon_livraison_details d ON b.id = d.bon_livraison_id 
            WHERE b.id='$id'";
    $phrase = "Arrêté le présent bon de livraison à la somme de :";
    $document_type = 'bon_de_livraison';
} else if ($type == 'Facture') {
    $sql = "SELECT f.numero, c.nom AS client, c.adresse, c.contact, c.ice, f.date, f.total_ht, f.total_tva, f.total_ttc, d.produit_nom, d.quantite, d.prix_ht, ROUND(d.prix_ht * 1.2, 2) AS prix_ttc 
            FROM factures f 
            JOIN clients c ON f.client_id = c.id 
            JOIN facture_details d ON f.id = d.facture_id 
            WHERE f.id='$id'";
    $phrase = "Arrêtée la présente facture à la somme de :";
    $document_type = 'facture';
} else if ($type == 'Devis') {
    $sql = "SELECT d.numero, c.nom AS client, c.adresse, c.contact, c.ice, d.date, d.total_ht, d.total_tva, d.total_ttc, dd.produit_nom, dd.quantite, dd.prix_ht, ROUND(dd.prix_ht * 1.2, 2) AS prix_ttc 
            FROM devis d 
            JOIN clients c ON d.client_id = c.id 
            JOIN devis_details dd ON d.id = dd.devis_id 
            WHERE d.id='$id'";
    $phrase = "Arrêté le présent devis à la somme de :";
    $document_type = 'devis';
}

$result = $conn->query($sql);

if ($result !== false && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Enregistrer les valeurs des totaux avant la boucle
    $numero = $row['numero'];
    $client = $row['client'];
    $adresse = $row['adresse'];
    $contact = $row['contact'];
    $ice = $row['ice'];
    $date = $row['date'];
    $total_ht = $row['total_ht'];
    $total_tva = $row['total_tva'];
    $total_ttc = $row['total_ttc'];

    // Récupérer les montants de paiement
    $sql_paiement = "SELECT montant_du, montant_paye, montant_restant FROM status_paiement WHERE numero='$numero' AND type_document='$document_type'";
    $result_paiement = $conn->query($sql_paiement);

    // Debugging step to check if the payment query returns any results
    if ($result_paiement === false) {
        echo "Error: " . $conn->error;
        exit;
    }

    if ($result_paiement !== false && $result_paiement->num_rows > 0) {
        $row_paiement = $result_paiement->fetch_assoc();
        $montant_du = $row_paiement['montant_du'];
        $montant_paye = $row_paiement['montant_paye'];
        $montant_restant = $row_paiement['montant_restant'];
    } else {
        $montant_du = $total_ttc;
        $montant_paye = 0;
        $montant_restant = $total_ttc;
    }

    // Fonction pour convertir les nombres en lettres
    function convertir_nombre_en_lettres($nombre)
    {
        $formatter = new NumberFormatter("fr", NumberFormatter::SPELLOUT);
        return $formatter->format($nombre);
    }

    $total_ttc_lettres = convertir_nombre_en_lettres($total_ttc);
    ?>

    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            <?php echo ($type == 'Facture' ? 'Facture' : ($type == 'Bon de Livraison' ? 'Bon de Livraison' : 'Devis')) . ' #' . $numero; ?>
        </title>
        <link rel="stylesheet" href="styles-f.css">
        <style>
            .summary-row {
                text-align: right;
            }

            .summary-row td {
                text-align: left;
            }

            .summary-row th,
            .summary-row td {
                border-left: none !important;
            }

            .payment-status {
                margin-top: 20px;
                border-collapse: collapse;
                width: 60%;
                margin-left: auto;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <?php if ($type == 'Facture' || $type == 'Bon de Livraison' || $type == 'Devis') { ?>
                <div class="header"
                    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <div class="logo" style="flex: 1;">
                        <img src="logo.png" alt="Logo" style="max-width: 100%; height: auto;">
                    </div>
                    <div class="installation" style="flex: 2; text-align: left; margin-left: 12%; ">
                        <h3>Installation :</h3>
                        <ul>
                            <li>Installation caméra</li>
                            <li>Système réseau</li>
                            <li>Équipements informatiques</li>
                            <li>Applications & logiciels</li>
                        </ul>
                    </div>
                    <div class="vente" style="flex: 2; text-align: left; margin-left: 12%; ">
                        <h3>Vente :</h3>
                        <ul>
                            <li>Caméra de surveillance</li>
                            <li>Imprimante</li>
                            <li>PC portable & bureau</li>
                            <li>Logiciels</li>
                        </ul>
                    </div>
                </div>
            <?php } ?>
            <br><br><br>
            <div class="details-container">
                <div class="client-details">
                    <p><strong>Client:</strong> <?php echo $client; ?></p>
                    <p><strong>Adresse:</strong> <?php echo $adresse; ?></p>
                    <p><strong>Contact:</strong> <?php echo $contact; ?></p>
                    <p><strong>ICE:</strong> <?php echo $ice; ?></p>
                </div>
                <div class="date-details">
                    <p><strong><?php echo ($type == 'Facture' ? 'Facture' : ($type == 'Bon de Livraison' ? 'Bon de Livraison' : 'Devis')) . ' Nº' ?>:</strong>
                        <?php echo $numero; ?></p>
                    <p><strong>Date:</strong> <?php echo $date; ?></p>
                    <p><strong>Ville:</strong> Tanger</p>
                </div>
            </div>
            <br><br><br>
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <?php if ($type == 'Facture') { ?>
                            <th>Prix HT</th>
                            <th>P.U TTC</th>
                            <th>Prix Total TTC</th>
                        <?php } else { ?>
                            <th>P.U</th>
                            <th>Prix Total</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_ttc_calculated = 0; // Initialiser le total TTC calculé
                    do {
                        $prix_ttc = round($row['prix_ttc'], 2); // Utiliser le prix TTC de la base de données
                        $prix_total_ttc = round($row['quantite'] * $prix_ttc, 2); // Calculer le prix total TTC
                        $total_ttc_calculated += $prix_total_ttc; // Ajouter au total TTC calculé
                        echo "<tr>";
                        echo "<td>" . $row['produit_nom'] . "</td>";
                        echo "<td>" . $row['quantite'] . "</td>";
                        if ($type == 'Facture') {
                            echo "<td>" . number_format($row['prix_ht'], 2) . "</td>";
                        }
                        echo "<td>" . number_format($prix_ttc, 2) . "</td>";
                        echo "<td>" . number_format($prix_total_ttc, 2) . "</td>";
                        echo "</tr>";
                    } while ($row = $result->fetch_assoc());
                    ?>
                </tbody>
                <tfoot>
                    <?php if ($type == 'Facture') { ?>
                        <tr class="summary-row">
                            <td colspan="3"></td>
                            <td class="tfoot-cell">Total HT</td>
                            <td class="tfoot-cell"><?php echo number_format($total_ht, 2); ?></td>
                        </tr>
                        <tr class="summary-row">
                            <td colspan="3"></td>
                            <td class="tfoot-cell">TVA (20%)</td>
                            <td class="tfoot-cell"><?php echo number_format($total_tva, 2); ?></td>
                        </tr>
                    <?php } ?>
                    <tr class="summary-row">
                        <td colspan="<?php echo ($type == 'Facture' ? '3' : '2'); ?>"></td>
                        <td class="tfoot-cell">Prix Total TTC</td>
                        <td class="tfoot-cell"><?php echo number_format($total_ttc_calculated, 2); ?></td>
                    </tr>
                </tfoot>
            </table>
            <?php if ($type != 'Devis') { ?>
                <table class="payment-status">
                    <thead>
                        <tr>
                            <th>Montant Dû</th>
                            <th>Montant Payé</th>
                            <th>Montant Restant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo number_format($montant_du, 2); ?></td>
                            <td><?php echo number_format($montant_paye, 2); ?></td>
                            <td><?php echo number_format($montant_restant, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php } ?>
            <br><br><br>
            <div class="footer">
                <p><strong><?php echo $phrase; ?></strong></p>
                <p><?php echo ucfirst($total_ttc_lettres); ?> dirhams.</p>
                <br><br>
                <p><strong>Conditions et Méthodes de Paiement :</strong></p>
                <p style="font-size:0.6em">Cas 1 : Pour les achats direct, le paiement total de 100% du montant est requis
                    soit en espèces, soit par virement bancaire.</p>
                <p style="font-size:0.6em">Cas 2 : Pour les achats non directs, un paiement de 30% par virement bancaire est
                    requis à l'avance, avec les 70% restants à payer en espèces à la livraison.</p>
                <p style="font-size:0.6em">Cas 3 : Pour les achats incluant la main-d'œuvre, un paiement de 30% d'avance est
                    requis, avec les 70% restants à payer après la livraison finale, soit en espèces, soit par virement
                    bancaire.</p>
                
                <!--<p style="text-align: right;"><strong>Signature :</strong></p>-->
            </div>
        </div>
    </body>
    <div class="footerx">
        <span class="line"></span>
        <span class="company-name">MegaBen Tanger sarl au</span>
        <span class="line"></span>
        <div class="details">
            <p>Av MLY Ismail 14 Res MLY Ismail 3ème étage N 9 Tanger - RC 149227 - IF : 65910696</p>
            <p>ICE : 003483955000032 - Patente 57229232 - Tel 1 : +212 658 078 619 – Tel 2 : +212 623 774 630 - RIB : 164
                640 2121169605240004 91</p>
        </div>
    </div>

    </html>

    <?php
} else {
    echo "Aucun document trouvé.";
}

$conn->close();
?>