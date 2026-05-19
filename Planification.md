# Planification du Projet — SmartWash

## Introduction

Après la validation du cahier de charge, une phase de réflexion et de préparation du projet **SmartWash** a débuté vers la mi-avril.

La période globale du projet s’étend donc de **mi-avril au 12 juin**, tandis que le développement officiel de l’application a commencé le **16 mai**.

Le travail est organisé progressivement :  
**maquettage → conception base de donnée → back-end → front-end → finalisation**.

---

## Phase 1 : Maquettage

**Période :** 14 mai – 20 mai  

**Objectif :** définir l’organisation visuelle et l’expérience utilisateur de l’application.

**Tâches réalisées :**

- analyse des besoins fonctionnels  
- création des maquettes des interfaces principales  
- définition de la navigation entre les pages  
- conception du tableau de bord administrateur  
- choix des couleurs principales et du style graphique  
- préparation de la structure générale des pages  

---

## Phase 2 : Conception de la Base de Données

**Période :** 21 mai – 22 mai  

**Objectif :** structurer les données nécessaires au fonctionnement du système.

**Tâches réalisées :**

- identification des entités principales  
- conception du modèle de données  
- création des tables :
  - clients  
  - voitures  
  - services  
  - réservations  
  - statuts  
- définition des relations entre les tables  
- préparation de la base MySQL  

---

## Phase 3 : Développement Back-end

**Période :** 23 mai – 29 mai  

**Objectif :** développer la logique fonctionnelle de l’application.

**Tâches réalisées :**

- mise en place de la connexion sécurisée PHP PDO  
- développement des opérations CRUD :
  - gestion des clients  
  - gestion des véhicules  
  - gestion des services  
- création du système de file d’attente  
- gestion des statuts des lavages :
  - en attente  
  - en cours  
  - terminé  
- organisation de la logique métier côté serveur  

---

## Phase 4 : Développement Front-end

**Période :** 30 mai – 5 juin  

**Objectif :** création de l’interface utilisateur.

**Tâches réalisées :**

- intégration des pages en HTML / CSS  
- application de la charte graphique  
- création du tableau de bord administrateur  
- développement des formulaires d’ajout client et véhicule  
- utilisation de JavaScript pour :
  - validation des formulaires  
  - interactions dynamiques  
  - amélioration de l’expérience utilisateur  

---

## Phase 5 : Finalisation et Tests

**Période :** 6 juin – 12 juin  

**Objectif :** stabiliser et finaliser l’application.

**Tâches réalisées :**

- intégration des notifications WhatsApp via API  
- tests d’envoi automatique des messages  
- correction des bugs détectés  
- optimisation du code  
- tests complets du workflow  
- préparation de la démonstration pour la soutenance  

---

## Organisation du Travail

Le développement du projet est suivi par des commits réguliers sur GitHub, permettant de montrer l’évolution progressive du travail depuis la conception jusqu’à la version finale de l’application.
