<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Devis</title>
    <link rel="stylesheet" href="styles-add.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <div class="container">
        <a href="gestion_documents.php" class="back-link"><i class="fas fa-arrow-left"></i> Retour</a>
        <h1>Ajouter Devis</h1>
        <form action="ajouter_devis.php" method="POST">
            <div class="form-group">
                <label for="numero">Numéro de Devis:</label>
                <?php
                include '../db.php';
                $result = $conn->query("SELECT numero FROM devis ORDER BY id DESC LIMIT 1");
                $lastNumero = $result->fetch_assoc()['numero'];
                $newNumero = $lastNumero + 1;
                ?>
                <input type="text" id="numero" name="numero" value="<?php echo $newNumero; ?>" required readonly>
            </div>

            <div class="form-group">
                <label for="client_id">Client:</label>
                <div style="display: flex;">
                    <select id="client_id" name="client_id" required>
                        <?php
                        $clients = $conn->query("SELECT id, nom FROM clients");
                        while ($client = $clients->fetch_assoc()) {
                            echo "<option value='" . $client['id'] . "'>" . $client['nom'] . "</option>";
                        }
                        ?>
                    </select>
                    <button type="button" class="btn" onclick="openModal()"><i class="fas fa-plus"></i></button>
                </div>
            </div>

            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>
            </div>

            <h3>Détails du Devis</h3>
            <table id="details">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix TTC</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Les lignes de produits seront insérées ici -->
                </tbody>
            </table>
            <button type="button" class="btn" onclick="ajouterLigne()"><i class="fas fa-plus"></i> Ajouter
                Produit</button>

            <input type="submit" class="btn btn-primary" value="Ajouter Devis">
        </form>
    </div>

    <!-- Modal pour ajouter un nouveau client -->
    <div id="clientModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Ajouter Nouveau Client</h2>
            <form id="ajouterClientForm">
                <div class="form-group">
                    <label for="nouveau_nom">Nom du Client:</label>
                    <input type="text" id="nouveau_nom" name="nouveau_nom" required>
                </div>
                <div class="form-group">
                    <label for="nouveau_adresse">Adresse:</label>
                    <input type="text" id="nouveau_adresse" name="nouveau_adresse" required>
                </div>
                <div class="form-group">
                    <label for="nouveau_contact">Contact:</label>
                    <input type="text" id="nouveau_contact" name="nouveau_contact" required>
                </div>
                <div class="form-group">
                    <label for="nouveau_ice">ICE:</label>
                    <input type="text" id="nouveau_ice" name="nouveau_ice" required>
                </div>
                <button type="button" class="btn btn-primary" onclick="ajouterClient()">Ajouter Client</button>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById("clientModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("clientModal").style.display = "none";
        }

        function ajouterClient() {
            const form = document.getElementById("ajouterClientForm");
            const formData = new FormData(form);

            fetch("ajouter_client.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Ajouter le nouveau client à la liste des clients
                        const clientSelect = document.getElementById("client_id");
                        const option = document.createElement("option");
                        option.value = data.client_id;
                        option.text = data.client_nom;
                        clientSelect.add(option);
                        clientSelect.value = data.client_id;

                        closeModal();
                    } else {
                        alert("Erreur lors de l'ajout du client.");
                    }
                })
                .catch(error => {
                    console.error("Erreur:", error);
                    alert("Erreur lors de l'ajout du client.");
                });
        }

        function ajouterLigne() {
            const table = document.getElementById('details').querySelector('tbody');
            const row = table.insertRow();
            const produitCell = row.insertCell(0);
            const quantiteCell = row.insertCell(1);
            const prixCell = row.insertCell(2);
            const actionsCell = row.insertCell(3);

            produitCell.innerHTML = `<select name="produit_nom[]" required>
                        <?php
                        $produits = $conn->query("SELECT id, nom FROM produits");
                        while ($produit = $produits->fetch_assoc()) {
                            echo "<option value='" . $produit['nom'] . "'>" . $produit['nom'] . "</option>";
                        }
                        ?>
                    </select>`;
            quantiteCell.innerHTML = `<input type="number" name="quantite[]" required>`;
            prixCell.innerHTML = `<input type="number" step="0.01" name="prix_ttc[]" required>`;
            actionsCell.innerHTML = `<button type="button" class="btn btn-danger" onclick="supprimerLigne(this)"><i class="fas fa-trash-alt"></i></button>`;
        }

        function supprimerLigne(button) {
            const row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
        }
    </script>
</body>

</html>