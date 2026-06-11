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

    $id_voiture = trim($_POST['id_voiture']);
    $id_service = trim($_POST['id_service']);

    if ($id_voiture === '' || $id_service === '') {

        $_SESSION['message'] = "Veuillez choisir un véhicule et un service.";

    } else {

        try {

            $sql = "INSERT INTO lavages(id_voiture, id_service) VALUES(?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id_voiture, $id_service]);

            $_SESSION['message'] = "Le lavage a été ajouté à la file d'attente.";

            header("Location: queue.php");
            exit();

        } catch (PDOException $e) {

            $_SESSION['message'] = "Une erreur est survenue lors de l'ajout du lavage.";
        }
    }
}

// Modifier le statut d'un lavage
if (isset($_GET['status']) && isset($_GET['id'])) {

    $status = $_GET['status'];
    $id = $_GET['id'];

    if ($status == 'terminee') {

        $sql = "UPDATE lavages SET statut = 'Terminé', date_fin = NOW() WHERE id_lavage = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);

        $_SESSION['message'] = "Lavage terminé. Notification WhatsApp envoyée au client.";

    } elseif ($status == 'En cours') {

        $sql = "UPDATE lavages SET statut = 'En cours' WHERE id_lavage = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);

        $_SESSION['message'] = "Le lavage est maintenant en cours.";

    } else {

        $_SESSION['message'] = "Statut invalide.";
    }

    header("Location: queue.php");
    exit();
}

// Supprimer un lavage
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    try {

        $stmt = $pdo->prepare("DELETE FROM lavages WHERE id_lavage = ?");
        $stmt->execute([$id]);

        $_SESSION['message'] = "Le lavage a été supprimé.";

        header("Location: queue.php");
        exit();

    } catch (PDOException $e) {

        $_SESSION['message'] = "Impossible de supprimer ce lavage.";
    }
}

// Récupérer la liste des voitures avec le client
$sqlVoitures = "SELECT voitures.*, clients.nom
                FROM voitures
                INNER JOIN clients ON voitures.id_client = clients.id_client
                ORDER BY voitures.id_voiture DESC";

$voitures = $pdo->query($sqlVoitures);

// Récupérer la liste des services
$services = $pdo->query("SELECT * FROM services ORDER BY nom_service ASC");

// Recherche des lavages
$search = '';

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

// Récupérer la liste des lavages avec client, voiture et service
if ($search != '') {

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
                   WHERE clients.nom LIKE ?
                   OR voitures.marque LIKE ?
                   OR voitures.modele LIKE ?
                   OR voitures.immatriculation LIKE ?
                   OR services.nom_service LIKE ?
                   OR lavages.statut LIKE ?
                   ORDER BY lavages.date_arrivee DESC";

    $stmt = $pdo->prepare($sqlLavages);
    $stmt->execute([
        "%$search%",
        "%$search%",
        "%$search%",
        "%$search%",
        "%$search%",
        "%$search%"
    ]);

    $lavages = $stmt;

} else {

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
}
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
                <span>
                    <?php echo htmlspecialchars($_SESSION['admin'], ENT_QUOTES, 'UTF-8'); ?>
                </span>
                <a href="logout.php" class="logout-btn">Déconnexion</a>
            </div>

        </header>

        <!-- Message -->
        <?php
        if (isset($_SESSION['message'])) {

            echo "<div class='success-message'>";
            echo htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8');
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
                        echo "<option value='" . htmlspecialchars($voiture['id_voiture'], ENT_QUOTES, 'UTF-8') . "'>";
                        echo htmlspecialchars($voiture['nom'], ENT_QUOTES, 'UTF-8') . " - ";
                        echo htmlspecialchars($voiture['marque'], ENT_QUOTES, 'UTF-8') . " ";
                        echo htmlspecialchars($voiture['modele'], ENT_QUOTES, 'UTF-8') . " (";
                        echo htmlspecialchars($voiture['immatriculation'], ENT_QUOTES, 'UTF-8') . ")";
                        echo "</option>";
                    }
                    ?>

                </select>

                <select name="id_service" required>
                    <option value="">Choisir un service</option>

                    <?php
                    while ($service = $services->fetch()) {
                        echo "<option value='" . htmlspecialchars($service['id_service'], ENT_QUOTES, 'UTF-8') . "'>";
                        echo htmlspecialchars($service['nom_service'], ENT_QUOTES, 'UTF-8') . " - ";
                        echo htmlspecialchars(number_format($service['prix'], 0, '.', ' '), ENT_QUOTES, 'UTF-8') . " DH";
                        echo "</option>";
                    }
                    ?>

                </select>

                <button type="submit" name="ajouter">
                    Ajouter à la file
                </button>

            </form>

        </section>

        <!-- Barre de recherche -->
        <section class="form-box">

            <h3>Rechercher un lavage</h3>

            <form method="GET" class="search-form">

                <input type="text"
                       name="search"
                       placeholder="Client, véhicule, service ou statut"
                       value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">

                <button type="submit">
                    Rechercher
                </button>

                <a href="queue.php" class="btn-back">
                    Réinitialiser
                </a>

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

                    echo "<td>" . htmlspecialchars($lavage['nom'], ENT_QUOTES, 'UTF-8') . "</td>";

                    echo "<td>" . htmlspecialchars($lavage['marque'], ENT_QUOTES, 'UTF-8') . " " . htmlspecialchars($lavage['modele'], ENT_QUOTES, 'UTF-8') . "</td>";

                    echo "<td>" . htmlspecialchars($lavage['immatriculation'], ENT_QUOTES, 'UTF-8') . "</td>";

                    echo "<td>" . htmlspecialchars($lavage['nom_service'], ENT_QUOTES, 'UTF-8') . "</td>";

                    echo "<td>" . htmlspecialchars(number_format($lavage['prix'], 0, '.', ' '), ENT_QUOTES, 'UTF-8') . " DH</td>";

                    echo "<td>";

                    if ($lavage['statut'] == 'En attente') {
                        echo "<span class='status-attente'>En attente</span>";
                    } elseif ($lavage['statut'] == 'En cours') {
                        echo "<span class='status-cours'>En cours</span>";
                    } else {
                        echo "<span class='status-termine'>Terminé</span>";
                    }

                    echo "</td>";

                    echo "<td>" . htmlspecialchars($lavage['date_arrivee'], ENT_QUOTES, 'UTF-8') . "</td>";

                    echo "<td>";
                    echo "<div class='actions'>";

                    if ($lavage['statut'] == 'En attente') {
                        echo "<a class='btn-back' href='?status=En cours&id=" . htmlspecialchars($lavage['id_lavage'], ENT_QUOTES, 'UTF-8') . "'>Commencer</a>";
                    } elseif ($lavage['statut'] == 'En cours') {
                        echo "<a class='btn-back' href='?status=terminee&id=" . htmlspecialchars($lavage['id_lavage'], ENT_QUOTES, 'UTF-8') . "'>Terminer</a>";
                    }

                    echo "<a class='btn-delete' href='?delete=" . htmlspecialchars($lavage['id_lavage'], ENT_QUOTES, 'UTF-8') . "'>Supprimer</a>";

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

<script>
const searchInput = document.querySelector('input[name="search"]');

if (searchInput && searchInput.value.trim() !== '') {

    const results = document.querySelector('.table-container');

    if (results) {
        results.scrollIntoView({
            behavior: 'smooth'
        });
    }
}
</script>

</body>
</html>
