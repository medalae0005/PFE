# Cahier de Charge — Application SmartWash

---

## 1. Introduction simple :

Dans le cadre de notre projet de développement web, nous avons choisi de réaliser une application nommée SmartWash.

Cette application a pour objectif de faciliter la gestion d’une station de lavage automobile en digitalisant les opérations quotidiennes.

Actuellement, plusieurs stations utilisent encore une gestion manuelle (papier ou communication directe), ce qui provoque parfois des erreurs, une mauvaise organisation des files d’attente et un manque de suivi des clients.

***Le projet SmartWash propose donc une solution simple permettant :***

1. la gestion des clients.
2. l’organisation des lavages.
3. le suivi des voitures en cours.
4. l’envoi automatique de notifications lorsque le lavage est terminé.

---

## 2. Objectifs du Projet :

*Les principaux objectifs sont :*

* Digitaliser le fonctionnement d’une station de lavage.
* Réduire le temps d’attente des clients.
* Permettre au gérant de suivre l’activité en temps réel.
* Informer automatiquement le client quand sa voiture est prête.

---

## 3. Charte Graphique :

Afin de garantir une identité visuelle cohérente, une charte graphique simple a été définie pour l’application SmartWash.

### Police :
* Poppins (ou Arial)

### Couleurs principales :
* Bleu : #2563EB
* Vert : #10B981
* Orange : #F59E0B

### Couleurs de fond :
* Blanc : #FFFFFF
* Gris clair : #F8FAFC

### Style général :
* Interface simple et claire
* Design moderne
* Navigation facile
* Bonne lisibilité

---

## 4. Utilisateurs du Système :

*L’application comporte deux types d’utilisateurs :*

**[\*] Client**

* Enregistrer sa voiture  
* Demander un service de lavage  
* Suivre l’état du lavage  
* Recevoir une notification WhatsApp lorsque la voiture est prête  

**[\*] Administrateur (Gérant)**

* Gérer les clients  
* Gérer les services de lavage  
* Organiser la file d’attente  
* Modifier le statut des voitures  
* Consulter les statistiques  

---

## 5. Fonctionnalités Principales :

Gestion des clients  
* Ajouter un client  
* Modifier ou supprimer un client  
* Associer une voiture à chaque client  

Gestion des services  
* Création des types de lavage :
  - Lavage simple  
  - Lavage complet  
  - Lavage premium  

* Gestion des prix  

Gestion de la file d’attente  
* Ajout automatique des voitures dans une file  
* Affichage des voitures en attente  
* Mise à jour du statut :
  - En attente  
  - En cours  
  - Terminé  

Notification WhatsApp  
* Envoi automatique d’un message lorsque le lavage est terminé  

---

## 6. Technologies Utilisées :

### Frontend :
* HTML5  
* CSS3  
* JavaScript  

### Backend :
* PHP  
* PDO (connexion sécurisée à la base de données)  

### Base de Données :
* MySQL  

### API :
* API WhatsApp pour les notifications automatiques  

---

## 7. Base de Données :

La base de données contiendra principalement :

* clients  
* voitures  
* services  
* reservations  
* statuts  

---

## 8. Interfaces de l’Application :

* Page d’accueil  
* Tableau de bord administrateur  
* Gestion clients  
* Gestion services  
* File d’attente  
* Historique des lavages  

---

## 9. Contraintes Techniques :

* Application accessible via navigateur web  
* Interface simple et intuitive  
* Sécurisation des données utilisateurs  
* Connexion sécurisée à la base de données  

---

## 10. Résultat Attendu :

*À la fin du projet, SmartWash permettra :*

* Une meilleure organisation de la station  
* Un gain de temps pour le gérant et les clients  
* Une meilleure expérience utilisateur grâce aux notifications automatiques  
