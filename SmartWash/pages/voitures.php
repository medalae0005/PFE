<?php
// Démarrer la session
session_start();

// Vérifier si l'administrateur est connecté
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
require '../config/database.php';

// Message d'erreur ou d'information
$message = '';
$searchMessage = '';

// Ajouter une nouvelle voiture
if (isset($_POST['ajouter'])) {

    $id_client = $_POST['id_client'];
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $immatriculation = $_POST['immatriculation'];
    $couleur = $_POST['couleur'];

    $sql = "INSERT INTO voitures(id_client, marque, modele, immatriculation, couleur)
            VALUES(?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_client, $marque, $modele, $immatriculation, $couleur]);

    header("Location: voitures.php");
    exit();
}

// Supprimer une voiture
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    try {

        $stmt = $pdo->prepare("DELETE FROM voitures WHERE id_voiture = ?");
        $stmt->execute([$id]);

        header("Location: voitures.php");
        exit();

    } catch (PDOException $e) {

        $message = "Impossible de supprimer cette voiture car elle possède des lavages enregistrés.";
    }
}

// Récupérer la liste des clients pour le formulaire
$clients = $pdo->query("SELECT * FROM clients ORDER BY nom ASC");

// Recherche des véhicules
$search = '';

if (isset($_GET['search'])) {

    $search = trim($_GET['search']);

    if ($search == '') {
        $searchMessage = "Veuillez saisir un mot-clé pour effectuer une recherche.";
    }
}

// Récupérer la liste des voitures avec le nom du client
if ($search != '') {

    $sql = "SELECT voitures.*, clients.nom 
            FROM voitures 
            INNER JOIN clients ON voitures.id_client = clients.id_client
            WHERE voitures.marque LIKE ?
            OR voitures.modele LIKE ?
            OR voitures.immatriculation LIKE ?
            OR clients.nom LIKE ?
            ORDER BY voitures.id_voiture DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$search%", "%$search%", "%$search%", "%$search%"]);

    $voitures = $stmt;

} else {

    $sql = "SELECT voitures.*, clients.nom 
            FROM voitures 
            INNER JOIN clients ON voitures.id_client = clients.id_client
            ORDER BY voitures.id_voiture DESC";

    $voitures = $pdo->query($sql);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Voitures - SmartWash</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css?v=11">
</head>

<body>

<div class="container">

    <!-- Barre latérale de navigation -->
    <aside class="sidebar">

        <div class="logo">
            SmartWash
        </div>

        <nav class="menu">
            <a href="dashboard.php">Dashboard</a>
            <a href="clients.php">Clients</a>
            <a href="voitures.php">Véhicules</a>
            <a href="queue.php">File d'attente</a>
            <a href="historique.php">Historique</a>
        </nav>

        <footer class="sidebar-footer">
            SmartWash © 2026
        </footer>

    </aside>

    <!-- Contenu principal -->
    <main class="main">

        <!-- En-tête de la page -->
        <header class="top-bar">

            <h2>Gestion des Véhicules</h2>

            <div class="admin-info">

                <span>
                    Admin : <?php echo $_SESSION['admin']; ?>
                </span>

                <a href="logout.php" class="logout-btn">
                    Déconnexion
                </a>

            </div>

        </header>

        <!-- Message d'erreur -->
        <?php
        if ($message != '') {
            echo "<div class='error'>" . $message . "</div>";
        }
        ?>

        <!-- Formulaire d'ajout d'un véhicule -->
        <section class="form-box">

            <h3>Ajouter un véhicule</h3>

            <form method="POST">

                <select name="id_client" required>
                    <option value="">Choisir un client</option>

                    <?php
                    while ($client = $clients->fetch()) {
                        echo "<option value='" . $client['id_client'] . "'>" . $client['nom'] . "</option>";
                    }
                    ?>

                </select>

                <input type="text" name="marque" placeholder="Marque" required>

                <input type="text" name="modele" placeholder="Modèle" required>

                <input type="text" name="immatriculation" placeholder="Immatriculation" required>

                <input type="text" name="couleur" placeholder="Couleur" required>

                <button type="submit" name="ajouter">
                    Ajouter
                </button>

            </form>

        </section>

        <!-- Barre de recherche -->
        <section class="form-box">

            <h3>Rechercher un véhicule</h3>

            <form method="GET" class="search-form">

                <input type="text"
                       name="search"
                       placeholder="Rechercher par client, marque ou immatriculation"
                       value="<?php echo $search; ?>">

                <button type="submit">
                    Rechercher
                </button>

                <a href="voitures.php" class="btn-back">
                    Réinitialiser
                </a>

            </form>

            <?php
            if ($searchMessage != '') {
                echo "<div class='error'>" . $searchMessage . "</div>";
            }
            ?>

        </section>

        <!-- Tableau des véhicules -->
        <section class="table-container">

            <table>

                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Immatriculation</th>
                    <th>Couleur</th>
                    <th>Actions</th>
                </tr>

                <?php

                while ($voiture = $voitures->fetch()) {

                    echo "<tr>";

                    echo "<td>" . $voiture['id_voiture'] . "</td>";

                    echo "<td>" . $voiture['nom'] . "</td>";

                    echo "<td>" . $voiture['marque'] . "</td>";

                    echo "<td>" . $voiture['modele'] . "</td>";

                    echo "<td>" . $voiture['immatriculation'] . "</td>";

                    echo "<td>" . $voiture['couleur'] . "</td>";

                    echo "<td><div class='actions'>
                            <a class='btn-delete' href='?delete=" . $voiture['id_voiture'] . "'>
                                Supprimer
                            </a>
                          </div></td>";

                    echo "</tr>";
                }

                ?>

            </table>

        </section>

    </main>

</div>

<script src="../assets/js/script.js"></script>

</body>
</html>
