<?php

// Configuration de la base de données
$host = "localhost";
$dbname = "smartwash";
$user = "root";
$password = "";

try {

    // Création de la connexion PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);

    // Activation du mode exception pour gérer les erreurs SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}   catch(PDOException $e) {

        // Gestion des erreurs de connexion
        die("Erreur : " . $e->getMessage());
}