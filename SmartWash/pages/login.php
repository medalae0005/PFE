<?php
// Démarrer la session
session_start();

// Connexion à la base de données
require '../config/database.php';

// Variable pour afficher les erreurs
$error = '';

// Vérifier si le formulaire est envoyé
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === '' || $password === '') {
        $error = "Veuillez remplir tous les champs.";
    } else {
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
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Login - SmartWash</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css?v=11">

</head>

<body class="login-page">

<main class="login-box">

    <header>

        <h2>SmartWash Admin</h2>

        <p class="login-subtitle">
            Gestion intelligente de station lavage
        </p>

    </header>

    <?php
    if ($error) {
        echo "<div class='error'>" . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . "</div>";
    }
    ?>

    <form method="POST">

        <input type="text"
               name="username"
               placeholder="Nom d'utilisateur"
               required
               pattern="[a-zA-ZÀ-ÿ0-9_\-]+"
               title="Veuillez saisir un nom d'utilisateur valide (lettres, chiffres, tirets, underscores)">

        <div class="password-box">

            <input type="password"
                   name="password"
                   id="password"
                   placeholder="Mot de passe"
                   required>

            <span class="toggle-password" onclick="togglePassword()">
                Show
            </span>

        </div>

        <button type="submit">
            Se connecter
        </button>

    </form>

</main>

<script src="../assets/js/script.js"></script>

</body>
</html>
