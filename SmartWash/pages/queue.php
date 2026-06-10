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

// Ajouter un lavage à la file d'attente
if (isset($_POST['ajouter'])) {

    $id_voiture = $_POST['id_voiture'];
    $id_service = $_POST['id_service'];

    $sql = "INSERT INTO lavages(id_voiture, id_service) VALUES(?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_voiture, $id_service]);

    header("Location: queue.php");
    exit();
}

// Modifier le statut d'un lavage
if (isset($_GET['status']) && isset($_GET['id'])) {

    $status = $_GET['status'];
    $id = $_GET['id'];

    // Marquer le lavage comme terminé
    if ($status == 'terminee') {

        $sql = "UPDATE lavages SET statut = 'Terminé', date_fin = NOW() WHERE id_lavage = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);

        // Simulation de notification WhatsApp
        $_SESSION['message'] = "Notification WhatsApp envoyée au client.";

    } else {

        // Mettre le lavage en cours
        $sql = "UPDATE lavages SET statut = ? WHERE id_lavage = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$status, $id]);
    }

    header("Location: queue.php");
    exit();
}

// Supprimer un lavage
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM lavages WHERE id_lavage = ?");
    $stmt->execute([$id]);

    header("Location: queue.php");
    exit();
}

// Récupérer la liste des voitures avec le client
$sqlVoitures = "SELECT voitures.*, clients.nom
                FROM voitures
                INNER JOIN clients ON voitures.id_client = clients.id_client
                ORDER BY voitures.id_voiture DESC";

$voitures = $pdo->query($sqlVoitures);

// Récupérer la liste des services
$services = $pdo->query("SELECT * FROM services ORDER BY nom_service ASC");

// Récupérer la liste des lavages avec client, voiture et service
$sqlLavages = "SELECT lavages.*,
                      voitures.marque,
                      voitures.modele,
                      voitures.immatriculation,
                      clients.nom,
                      services.nom_service,
                      services.prix
               FROM lavages
               INNER JOIN voitures ON lavages.id_voiture = voitures.id_voiture
               INNER JOIN clients ON voitures.id_client = clients.id_client
               INNER JOIN services ON lavages.id_service = services.id_service
               ORDER BY lavages.date_arrivee DESC";

$lavages = $pdo->query($sqlLavages);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>File d'attente - SmartWash</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css?v=11">
</head>

<body>

<div class="container">

    <!-- Barre latérale de navigation -->
    <aside class="sidebar">

        <div class="logo">SmartWash</div>

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

            <h2>File d'attente</h2>

            <div class="admin-info">
                <span>Admin : <?php echo $_SESSION['admin']; ?></span>
                <a href="logout.php" class="logout-btn">Déconnexion</a>
            </div>

        </header>

        <!-- Message de notification WhatsApp simulée -->
        <?php
        if (isset($_SESSION['message'])) {

            echo "<div class='success-message'>";
            echo $_SESSION['message'];
            echo "</div>";

            unset($_SESSION['message']);
        }
        ?>

        <!-- Formulaire d'ajout à la file d'attente -->
        <section class="form-box">

            <h3>Ajouter à la file d'attente</h3>

            <form method="POST">

                <select name="id_voiture" required>
                    <option value="">Choisir un véhicule</option>

                    <?php
                    while ($voiture = $voitures->fetch()) {
                        echo "<option value='" . $voiture['id_voiture'] . "'>";
                        echo $voiture['nom'] . " - ";
                        echo $voiture['marque'] . " ";
                        echo $voiture['modele'] . " (";
                        echo $voiture['immatriculation'] . ")";
                        echo "</option>";
                    }
                    ?>

                </select>

                <select name="id_service" required>
                    <option value="">Choisir un service</option>

                    <?php
                    while ($service = $services->fetch()) {
                        echo "<option value='" . $service['id_service'] . "'>";
                        echo $service['nom_service'] . " - ";
                        echo rtrim(rtrim($service['prix'], '0'), '.') . " DH";
                        echo "</option>";
                    }
                    ?>

                </select>

                <button type="submit" name="ajouter">
                    Ajouter à la file
                </button>

            </form>

        </section>

        <!-- Tableau de la file d'attente -->
        <section class="table-container">

            <table>

                <tr>
                    <th>Client</th>
                    <th>Véhicule</th>
                    <th>Immatriculation</th>
                    <th>Service</th>
                    <th>Prix</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>

                <?php
                while ($lavage = $lavages->fetch()) {

                    echo "<tr>";

                    echo "<td>" . $lavage['nom'] . "</td>";

                    echo "<td>" . $lavage['marque'] . " " . $lavage['modele'] . "</td>";

                    echo "<td>" . $lavage['immatriculation'] . "</td>";

                    echo "<td>" . $lavage['nom_service'] . "</td>";

                    echo "<td>" . rtrim(rtrim($lavage['prix'], '0'), '.') . " DH</td>";

                    echo "<td>";

                    if ($lavage['statut'] == 'En attente') {
                        echo "<span class='status-attente'>En attente</span>";
                    } elseif ($lavage['statut'] == 'En cours') {
                        echo "<span class='status-cours'>En cours</span>";
                    } else {
                        echo "<span class='status-termine'>Terminé</span>";
                    }

                    echo "</td>";

                    echo "<td>" . $lavage['date_arrivee'] . "</td>";

                    echo "<td>";
                    echo "<div class='actions'>";

                    if ($lavage['statut'] == 'En attente') {
                        echo "<a class='btn-back' href='?status=En cours&id=" . $lavage['id_lavage'] . "'>Commencer</a>";
                    } elseif ($lavage['statut'] == 'En cours') {
                        echo "<a class='btn-back' href='?status=terminee&id=" . $lavage['id_lavage'] . "'>Terminer</a>";
                    }

                    echo "<a class='btn-delete' href='?delete=" . $lavage['id_lavage'] . "'>Supprimer</a>";

                    echo "</div>";
                    echo "</td>";

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
