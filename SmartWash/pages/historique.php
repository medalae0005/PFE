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

// Message de recherche
$searchMessage = '';

// Supprimer un lavage de l'historique
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM lavages WHERE id_lavage = ?");
    $stmt->execute([$id]);

    header("Location: historique.php");
    exit();
}

// Recherche dans l'historique
$search = '';

if (isset($_GET['search'])) {

    $search = trim($_GET['search']);

    if ($search == '') {
        $searchMessage = "Veuillez saisir un mot-clé pour effectuer une recherche.";
    }
}

// Récupérer les lavages terminés
if ($search != '') {

    $sql = "SELECT lavages.*,
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
            WHERE lavages.statut = 'Terminé'
            AND (
                clients.nom LIKE ?
                OR voitures.marque LIKE ?
                OR voitures.modele LIKE ?
                OR voitures.immatriculation LIKE ?
                OR services.nom_service LIKE ?
            )
            ORDER BY lavages.date_fin DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$search%", "%$search%", "%$search%", "%$search%", "%$search%"]);

    $lavages = $stmt;

} else {

    $sql = "SELECT lavages.*,
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
            WHERE lavages.statut = 'Terminé'
            ORDER BY lavages.date_fin DESC";

    $lavages = $pdo->query($sql);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Historique - SmartWash</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css?v=11">
</head>

<body>

<div class="container">

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

    <main class="main">

        <header class="top-bar">

            <h2>Historique des lavages</h2>

            <div class="admin-info">

                <span>
                    <?php echo htmlspecialchars($_SESSION['admin'], ENT_QUOTES, 'UTF-8'); ?>
                </span>

                <a href="logout.php" class="logout-btn">
                    Déconnexion
                </a>

            </div>

        </header>

        <!-- Barre de recherche -->
        <section class="form-box">

            <h3>Rechercher dans l'historique</h3>

            <form method="GET" class="search-form">

                <input type="text"
                       name="search"
                       placeholder="Rechercher par client, véhicule ou service"
                       value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">

                <button type="submit">
                    Rechercher
                </button>

                <a href="historique.php" class="btn-back">
                    Réinitialiser
                </a>

            </form>

            <?php
            if ($searchMessage != '') {
                echo "<div class='error'>" . htmlspecialchars($searchMessage, ENT_QUOTES, 'UTF-8') . "</div>";
            }
            ?>

        </section>

        <section class="table-container" id="resultats">

            <table>

                <tr>
                    <th>Client</th>
                    <th>Véhicule</th>
                    <th>Immatriculation</th>
                    <th>Service</th>
                    <th>Prix</th>
                    <th>Date arrivée</th>
                    <th>Date fin</th>
                    <th>Actions</th>
                </tr>

                <?php
                while ($lavage = $lavages->fetch()) {

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($lavage['nom'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($lavage['marque'], ENT_QUOTES, 'UTF-8') . " " . htmlspecialchars($lavage['modele'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($lavage['immatriculation'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($lavage['nom_service'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . rtrim(rtrim($lavage['prix'], '0'), '.') . " DH</td>";
                    echo "<td>" . $lavage['date_arrivee'] . "</td>";
                    echo "<td>" . $lavage['date_fin'] . "</td>";

                    echo "<td><div class='actions'>
                            <a class='btn-delete' href='?delete=" . htmlspecialchars($lavage['id_lavage'], ENT_QUOTES, 'UTF-8') . "'>
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
