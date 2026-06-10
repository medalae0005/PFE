<?php
// Démarrer la session
session_start();

// Vérifier si l'administrateur est connecté ou non
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
require '../config/database.php';

// Calculer le nombre total des clients
$totalClients = $pdo->query("SELECT COUNT(*) FROM clients")->fetchColumn();

// Calculer le nombre total des véhicules
$totalVoitures = $pdo->query("SELECT COUNT(*) FROM voitures")->fetchColumn();

// Calculer le nombre total des lavages
$totalLavages = $pdo->query("SELECT COUNT(*) FROM lavages")->fetchColumn();

// Calculer le nombre des lavages en attente
$waiting = $pdo->query("SELECT COUNT(*) FROM lavages WHERE statut = 'En attente'")->fetchColumn();

// Calculer le nombre des lavages en cours
$inProgress = $pdo->query("SELECT COUNT(*) FROM lavages WHERE statut = 'En cours'")->fetchColumn();

// Calculer le nombre des lavages terminés
$completed = $pdo->query("SELECT COUNT(*) FROM lavages WHERE statut = 'Terminé'")->fetchColumn();

// Calculer les revenus totaux des lavages terminés
$revenus = $pdo->query("SELECT SUM(services.prix)
                        FROM lavages
                        INNER JOIN services
                        ON lavages.id_service = services.id_service
                        WHERE lavages.statut = 'Terminé'")
                ->fetchColumn();

// Si aucun revenu n'existe, afficher 0
if (!$revenus) {
    $revenus = 0;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - SmartWash</title>

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

            <h2>Dashboard</h2>

            <div class="admin-info">

                <span>
                    Admin : <?php echo $_SESSION['admin']; ?>
                </span>

                <a href="logout.php" class="logout-btn">
                    Déconnexion
                </a>

            </div>

        </header>

        <!-- Cartes statistiques -->
        <section class="cards">

            <div class="card">
                <h4>Total Clients</h4>
                <h2><?php echo $totalClients; ?></h2>
            </div>

            <div class="card">
                <h4>Total Véhicules</h4>
                <h2><?php echo $totalVoitures; ?></h2>
            </div>

            <div class="card">
                <h4>Total Lavages</h4>
                <h2><?php echo $totalLavages; ?></h2>
            </div>

            <div class="card">
                <h4>En attente</h4>
                <h2><?php echo $waiting; ?></h2>
            </div>

            <div class="card">
                <h4>En cours</h4>
                <h2><?php echo $inProgress; ?></h2>
            </div>

            <div class="card">
                <h4>Terminés</h4>
                <h2><?php echo $completed; ?></h2>
            </div>

        </section>

        <!-- Revenus totaux -->
        <section class="box">

            <h3>Revenus Totaux</h3>

            <h1 class="revenue-text">
                <?php echo rtrim(rtrim($revenus, '0'), '.'); ?> DH
            </h1>

        </section>

    </main>

</div>

</body>
</html>
