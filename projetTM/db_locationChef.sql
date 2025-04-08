CREATE DATABASE LocationChef;
USE LocationChef;

-- Table des utilisateurs (admin, chef, client)
CREATE TABLE Utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telephone VARCHAR(20) NOT NULL,
adresse VARCHAR(255),
    mot_de_passe VARCHAR(255) NOT NULL, -- Stocké de manière sécurisée (haché)
    role ENUM('admin', 'chef', 'client') NOT NULL
);

-- Table des chefs
CREATE TABLE Chefs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT UNIQUE NOT NULL,
    experience TEXT,
    specialites TEXT, -- Liste des spécialités (ex: "Italien, Japonais")
    tarif_horaire DECIMAL(10,2) NOT NULL,
    photo VARCHAR(255), -- Lien vers une image du chef
    note_moyenne DECIMAL(3,2) DEFAULT 0.0
);
 
-- Table des clients
CREATE TABLE Clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT UNIQUE NOT NULL
);

-- Table des réservations
CREATE TABLE Reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
chef_id INT NOT NULL,
    date_reservation DATETIME NOT NULL,
    nombre_heures INT NOT NULL,
    statut ENUM('En attente', 'Confirmée', 'Annulée', 'Terminée') DEFAULT 'En attente',
    prix_total DECIMAL(10,2) NOT NULL
);

-- Table des menus proposés par les chefs
CREATE TABLE Menus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chef_id INT NOT NULL,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    photo VARCHAR(255) -- Lien vers une image du plat
);
-- Table des avis laissés par les clients
CREATE TABLE Avis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    chef_id INT NOT NULL,
    reservation_id INT NOT NULL,
    note INT CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT,
    date_avis DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table des paiements
CREATE TABLE Paiements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    mode_paiement ENUM('Carte bancaire', 'PayPal', 'Espèces') NOT NULL,
    statut ENUM('En attente', 'Payé', 'Échoué') DEFAULT 'En attente',
    date_paiement DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Ajout des clés étrangères après la création des tables
ALTER TABLE Chefs ADD FOREIGN KEY (utilisateur_id) REFERENCES Utilisateurs(id) ON DELETE CASCADE;
ALTER TABLE Clients ADD FOREIGN KEY (utilisateur_id) REFERENCES Utilisateurs(id) ON DELETE CASCADE;ALTER TABLE Reservations ADD FOREIGN KEY (client_id) REFERENCES Clients(id) ON DELETE CASCADE;
ALTER TABLE Reservations ADD FOREIGN KEY (chef_id) REFERENCES Chefs(id) ON DELETE CASCADE;
ALTER TABLE Menus ADD FOREIGN KEY (chef_id) REFERENCES Chefs(id) ON DELETE CASCADE;
ALTER TABLE Avis ADD FOREIGN KEY (client_id) REFERENCES Clients(id) ON DELETE CASCADE;
ALTER TABLE Avis ADD FOREIGN KEY (chef_id) REFERENCES Chefs(id) ON DELETE CASCADE;
ALTER TABLE Avis ADD FOREIGN KEY (reservation_id) REFERENCES Reservations(id) ON DELETE CASCADE;
ALTER TABLE Paiements ADD FOREIGN KEY (reservation_id) REFERENCES Reservations(id) ON DELETE CASCADE;