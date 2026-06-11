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

// Modifier les informations d'un client
if (isset($_POST['modifier'])) {

    $id_client = $_POST['id_client'];
    $nom = trim($_POST['nom']);
    $telephone = trim($_POST['telephone']);

    if ($nom === '') {
        $message = "Le nom du client ne peut pas être vide.";
    } elseif ($telephone === '') {
        $message = "Le téléphone ne peut pas être vide.";
    } elseif (!preg_match('/^[0-9]{10}$/', $telephone)) {
        $message = "Le téléphone doit contenir exactement 10 chiffres.";
    } else {
        $sql = "UPDATE clients SET nom = ?, telephone = ? WHERE id_client = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $telephone, $id_client]);

        header("Location: clients.php");
        exit();
    }
}

// Ajouter un nouveau client
if (isset($_POST['ajouter'])) {

    $nom = trim($_POST['nom']);
    $telephone = trim($_POST['telephone']);

    if ($nom === '') {
        $message = "Le nom du client ne peut pas être vide.";
    } elseif ($telephone === '') {
        $message = "Le téléphone ne peut pas être vide.";
    } elseif (!preg_match('/^[0-9]{10}$/', $telephone)) {
        $message = "Le téléphone doit contenir exactement 10 chiffres.";
    } else {
        $sql = "INSERT INTO clients(nom, telephone) VALUES(?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $telephone]);

        header("Location: clients.php");
        exit();
    }
}

// Supprimer un client
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    try {
        $stmt = $pdo->prepare("DELETE FROM clients WHERE id_client = ?");
        $stmt->execute([$id]);

        header("Location: clients.php");
        exit();

    } catch (PDOException $e) {
        $message = "Impossible de supprimer ce client car il possède des véhicules ou des lavages enregistrés.";
    }
}

// Récupérer les informations du client à modifier
$editClient = null;

if (isset($_GET['edit'])) {

    $id = $_GET['edit'];

    $stmt = $pdo->prepare("SELECT * FROM clients WHERE id_client = ?");
    $stmt->execute([$id]);

    $editClient = $stmt->fetch();
}

// Recherche des clients
$search = '';

if (isset($_GET['search'])) {

    $search = trim($_GET['search']);

    if ($search == '') {
        $searchMessage = "Veuillez saisir un mot-clé pour effectuer une recherche.";
    }
}

// Récupérer la liste des clients
if ($search != '') {

    $sql = "SELECT * FROM clients
            WHERE nom LIKE ?
            OR telephone LIKE ?
            ORDER BY id_client DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$search%", "%$search%"]);

    $clients = $stmt;

} else {
    $clients = $pdo->query("SELECT * FROM clients ORDER BY id_client DESC");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Clients - SmartWash</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css?v=10">
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

            <h2>Gestion des Clients</h2>

            <div class="admin-info">

                <span>
                    <?php echo htmlspecialchars($_SESSION['admin'], ENT_QUOTES, 'UTF-8'); ?>
                </span>

                <a href="logout.php" class="logout-btn">
                    Déconnexion
                </a>

            </div>

        </header>

        <!-- Message d'erreur -->
        <?php
        if ($message != '') {
            echo "<div class='error'>" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "</div>";
        }
        ?>

        <!-- Formulaire d'ajout ou de modification -->
        <section class="form-box">

            <?php
            if ($editClient) {
                echo "<h3>Modifier un client</h3>";
            } else {
                echo "<h3>Ajouter un client</h3>";
            }
            ?>

            <form method="POST">

                <?php
                if ($editClient) {
                    echo "<input type='hidden' name='id_client' value='" . htmlspecialchars($editClient['id_client'], ENT_QUOTES, 'UTF-8') . "'>";
                }
                ?>

                <input type="text"
                       name="nom"
                       placeholder="Nom du client"
                       required
                       pattern="[a-zA-ZÀ-ÿ\s\-']+"
                       title="Veuillez saisir un nom valide (lettres, espaces, tirets, apostrophes)"
                       value="<?php
                       if ($editClient) {
                           echo htmlspecialchars($editClient['nom'], ENT_QUOTES, 'UTF-8');
                       }
                       ?>">

                <input type="text"
                       name="telephone"
                       minlength="10"
                       maxlength="10"
                       placeholder="Téléphone"
                       required
                       pattern="[0-9]{10}"
                       title="Veuillez saisir exactement 10 chiffres"
                       value="<?php
                       if ($editClient) {
                           echo htmlspecialchars($editClient['telephone'], ENT_QUOTES, 'UTF-8');
                       }
                       ?>">

                <?php
                if ($editClient) {
                    echo "<button type='submit' name='modifier'>Modifier</button>";
                } else {
                    echo "<button type='submit' name='ajouter'>Ajouter</button>";
                }
                ?>

            </form>

        </section>

        <!-- Barre de recherche -->
        <section class="form-box">

            <h3>Rechercher un client</h3>

            <form method="GET" class="search-form">

                <input type="text"
                       name="search"
                       placeholder="Rechercher par nom ou téléphone"
                       value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">

                <button type="submit">
                    Rechercher
                </button>

                <a href="clients.php" class="btn-back">
                    Réinitialiser
                </a>

            </form>

            <?php
            if ($searchMessage != '') {
                echo "<div class='error'>" . htmlspecialchars($searchMessage, ENT_QUOTES, 'UTF-8') . "</div>";
            }
            ?>

        </section>

        <!-- Tableau des clients -->
        <section class="table-container" id="resultats">

            <table>

                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th>Actions</th>
                </tr>

                <?php
                while ($client = $clients->fetch()) {

                    echo "<tr>";

                    echo "<td>" . htmlspecialchars($client['id_client'], ENT_QUOTES, 'UTF-8') . "</td>";

                    echo "<td>" . htmlspecialchars($client['nom'], ENT_QUOTES, 'UTF-8') . "</td>";

                    echo "<td>" . htmlspecialchars($client['telephone'], ENT_QUOTES, 'UTF-8') . "</td>";

                    echo "<td><div class='actions'>

                            <a class='btn-back' href='?edit=" . htmlspecialchars($client['id_client'], ENT_QUOTES, 'UTF-8') . "'>
                                Modifier
                            </a>

                            <a class='btn-delete' href='?delete=" . htmlspecialchars($client['id_client'], ENT_QUOTES, 'UTF-8') . "'>
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
