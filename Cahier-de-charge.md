# Cahier de Charge — Application SmartWash

---

## 1. Introduction

Dans le cadre de notre projet de développement web, nous avons choisi de réaliser une application nommée **SmartWash**.

Cette application vise à moderniser la gestion d’une station de lavage automobile en digitalisant les opérations quotidiennes. Actuellement, plusieurs stations de lavage utilisent encore des méthodes traditionnelles basées sur le papier ou la communication directe entre employés, ce qui peut entraîner des erreurs de gestion, une mauvaise organisation des files d’attente et un manque de suivi des activités.

Le projet SmartWash propose donc une solution informatique simple permettant d’améliorer l’organisation interne et d’optimiser le service offert aux clients.

L’application permettra principalement :

- la gestion des clients par l’administrateur,
- l’enregistrement des voitures arrivant à la station,
- l’organisation automatique de la file d’attente,
- le suivi en temps réel des lavages,
- l’envoi automatique d’une notification lorsque le lavage est terminé.

---

## 2. Objectifs du Projet

Les principaux objectifs sont :

- Digitaliser le fonctionnement d’une station de lavage automobile.
- Réduire le temps d’attente et améliorer l’organisation.
- Permettre au gérant de suivre l’activité en temps réel.
- Centraliser toutes les informations dans une seule application.
- Informer automatiquement le client lorsque sa voiture est prête.

---

## 3. Charte Graphique

Afin de garantir une identité visuelle cohérente, une charte graphique simple a été définie pour l’application SmartWash.

### Police
- Poppins (ou Arial)

### Couleurs principales
- Bleu : `#2563EB`
- Vert : `#10B981`
- Orange : `#F59E0B`

### Couleurs de fond
- Blanc : `#FFFFFF`
- Gris clair : `#F8FAFC`

### Style général
- Interface simple et claire
- Design moderne
- Navigation intuitive
- Bonne lisibilité

---

## 4. Utilisateurs du Système

L’application comporte **un seul type d’utilisateur principal** :

### Administrateur (Gérant)

- Enregistrer les clients lors de leur arrivée
- Ajouter et gérer les voitures
- Gérer les services de lavage
- Organiser la file d’attente
- Modifier le statut des lavages
- Consulter l’historique
- Accéder aux statistiques d’activité
- Envoyer automatiquement une notification WhatsApp au client

> Le client ne possède pas de compte dans le système.  
> Toutes les opérations sont réalisées par l’administrateur.

---

## 5. Fonctionnalités Principales

### Gestion des clients
- Ajouter un client
- Modifier ou supprimer un client
- Associer une voiture à un client

### Gestion des services
- Création des types de lavage :
  - Lavage simple
  - Lavage complet
  - Lavage premium
- Gestion des prix

### Gestion de la file d’attente
- Ajout d’une voiture dans la file d’attente
- Affichage des voitures en attente
- Mise à jour du statut :
  - En attente
  - En cours
  - Terminé

### Notifications WhatsApp
- Envoi automatique d’un message lorsque le lavage est terminé

---

## 6. Technologies Utilisées

### Frontend
- HTML5
- CSS3
- JavaScript

### Backend
- PHP
- PDO (connexion sécurisée)

### Base de données
- MySQL

### API
- API WhatsApp pour les notifications automatiques

---

## 7. Interfaces de l’Application

- Page d’accueil
- Tableau de bord administrateur
- Gestion des clients
- Gestion des services
- File d’attente
- Historique des lavages

---

## 8. Contraintes Techniques

- Application accessible via navigateur web
- Interface simple et intuitive
- Sécurisation des données
- Connexion sécurisée à la base de données
- Temps de réponse rapide

---

## 9. Résultat Attendu

À la fin du projet, SmartWash permettra :

- Une meilleure organisation de la station de lavage
- Une gestion centralisée des opérations
- Un gain de temps pour le gérant
- Une amélioration de la qualité de service
- Une meilleure expérience utilisateur grâce aux notifications automatiques
