<?php
// Démarrer la session
session_start();

// Connexion à la base de données
require '../config/database.php';

// Variable pour afficher les erreurs
$error = '';

// Vérifier si le formulaire est envoyé
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Vérifier si l'utilisateur existe
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);

    $user = $stmt->fetch();

    // Vérifier les informations de connexion
    if ($user && $password === $user['password']) {

        $_SESSION['admin'] = $user['username'];

        header('Location: dashboard.php');
        exit();

    } else {

        $error = "Nom d'utilisateur ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Login - SmartWash</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css?v=3">

</head>

<body class="login-page">

<!-- Contenu principal -->
<main class="login-box">

    <!-- Titre -->
    <header>

        <h2>SmartWash Admin</h2>

        <p class="login-subtitle">
            Gestion intelligente de station lavage
        </p>

    </header>

    <!-- Message d'erreur -->
    <?php
    if ($error) {
        echo "<div class='error'>" . $error . "</div>";
    }
    ?>

    <!-- Formulaire de connexion -->
    <form method="POST">

        <input type="text"
               name="username"
               placeholder="Nom d'utilisateur"
               required>

        <input type="password"
               name="password"
               placeholder="Mot de passe"
               required>

        <button type="submit">
            Se connecter
        </button>

    </form>

</main>

</body>
</html>