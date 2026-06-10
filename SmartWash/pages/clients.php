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

// Modifier les informations d'un client
if (isset($_POST['modifier'])) {

    $id_client = $_POST['id_client'];
    $nom = $_POST['nom'];
    $telephone = $_POST['telephone'];

    $sql = "UPDATE clients SET nom = ?, telephone = ? WHERE id_client = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $telephone, $id_client]);

    header("Location: clients.php");
    exit();
}

// Ajouter un nouveau client
if (isset($_POST['ajouter'])) {

    $nom = $_POST['nom'];
    $telephone = $_POST['telephone'];

    $sql = "INSERT INTO clients(nom, telephone) VALUES(?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $telephone]);

    header("Location: clients.php");
    exit();
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

// Récupérer la liste des clients
$clients = $pdo->query("SELECT * FROM clients ORDER BY id_client DESC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Clients - SmartWash</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css?v=3">
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
                    echo "<input type='hidden' name='id_client' value='" . $editClient['id_client'] . "'>";
                }
                ?>

                <input type="text"
                       name="nom"
                       placeholder="Nom du client"
                       required
                       value="<?php
                       if ($editClient) {
                           echo $editClient['nom'];
                       }
                       ?>">

                <input type="text"
                       name="telephone"
                       placeholder="Téléphone"
                       required
                       value="<?php
                       if ($editClient) {
                           echo $editClient['telephone'];
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

        <!-- Tableau des clients -->
        <section class="table-container">

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

                    echo "<td>" . $client['id_client'] . "</td>";

                    echo "<td>" . $client['nom'] . "</td>";

                    echo "<td>" . $client['telephone'] . "</td>";

                    echo "<td><div class='actions'>

                            <a class='btn-back' href='?edit=" . $client['id_client'] . "'>
                                Modifier
                            </a>

                            <a class='btn-delete' href='?delete=" . $client['id_client'] . "'>
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