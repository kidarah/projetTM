


-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS Utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    adresse VARCHAR(255),
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('admin', 'chef', 'client') NOT NULL
);

-- Table des chefs
CREATE TABLE IF NOT EXISTS Chefs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT UNIQUE NOT NULL,
    experience TEXT,
    specialites TEXT,
    tarif_horaire DECIMAL(10,2) NOT NULL,
    photo VARCHAR(255),
    note_moyenne DECIMAL(3,2) DEFAULT 0.0
);

-- Table des clients
CREATE TABLE IF NOT EXISTS Clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT UNIQUE NOT NULL
);

-- Table des réservations
CREATE TABLE IF NOT EXISTS Reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    chef_id INT NOT NULL,
    date_reservation DATETIME NOT NULL,
    nombre_heures INT NOT NULL,
    statut ENUM('En attente', 'Confirmée', 'Annulée', 'Terminée') DEFAULT 'En attente',
    prix_total DECIMAL(10,2) NOT NULL
);

-- Table des menus
CREATE TABLE IF NOT EXISTS Menus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chef_id INT NOT NULL,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    photo VARCHAR(255)
);

-- Table des avis
CREATE TABLE IF NOT EXISTS Avis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    chef_id INT NOT NULL,
    reservation_id INT NOT NULL,
    note INT CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT,
    date_avis DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table des paiements
CREATE TABLE IF NOT EXISTS Paiements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    mode_paiement ENUM('Carte bancaire', 'PayPal', 'Espèces') NOT NULL,
    statut ENUM('En attente', 'Payé', 'Échoué') DEFAULT 'En attente',
    date_paiement DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Clés étrangères
ALTER TABLE Chefs ADD FOREIGN KEY (utilisateur_id) REFERENCES Utilisateurs(id) ON DELETE CASCADE;
ALTER TABLE Clients ADD FOREIGN KEY (utilisateur_id) REFERENCES Utilisateurs(id) ON DELETE CASCADE;
ALTER TABLE Reservations ADD FOREIGN KEY (client_id) REFERENCES Clients(id) ON DELETE CASCADE;
ALTER TABLE Reservations ADD FOREIGN KEY (chef_id) REFERENCES Chefs(id) ON DELETE CASCADE;
ALTER TABLE Menus ADD FOREIGN KEY (chef_id) REFERENCES Chefs(id) ON DELETE CASCADE;
ALTER TABLE Avis ADD FOREIGN KEY (client_id) REFERENCES Clients(id) ON DELETE CASCADE;
ALTER TABLE Avis ADD FOREIGN KEY (chef_id) REFERENCES Chefs(id) ON DELETE CASCADE;
ALTER TABLE Avis ADD FOREIGN KEY (reservation_id) REFERENCES Reservations(id) ON DELETE CASCADE;
ALTER TABLE Paiements ADD FOREIGN KEY (reservation_id) REFERENCES Reservations(id) ON DELETE CASCADE;

-- Insertion de données
INSERT INTO Utilisateurs (nom, prenom, email, telephone, adresse, mot_de_passe, role) VALUES
('Martin', 'Julie', 'julie.martin@email.com', '0487123456', 'Rue des Lilas 12, Bruxelles', 'hashed_pwd1', 'admin'),
('Dupont', 'Jean', 'jean.dupont@email.com', '0478123456', 'Avenue Louise 100, Bruxelles', 'hashed_pwd2', 'chef'),
('Nguyen', 'Sophie', 'sophie.nguyen@email.com', '0467123456', 'Rue Royale 24, Liège', 'hashed_pwd3', 'client'),
('Moreau', 'Alexandre', 'alex.moreau@email.com', '0499123456', 'Chaussée de Mons 99, Mons', 'hashed_pwd4', 'chef'),
('Leclercq', 'Camille', 'camille.leclercq@email.com', '0488123456', 'Boulevard Tirou 45, Charleroi', 'hashed_pwd5', 'client'),
('Lemoine', 'Claire', 'claire.lemoine@email.com', '0478123457', 'Rue des Fleurs 10, Namur', 'hashed_pwd6', 'chef'),
('Durand', 'Paul', 'paul.durand@email.com', '0467123457', 'Avenue des Champs 50, Bruxelles', 'hashed_pwd7', 'chef'),
('Bernard', 'Luc', 'luc.bernard@email.com', '0488123457', 'Place Verte 15, Charleroi', 'hashed_pwd8', 'chef');

INSERT INTO Chefs (utilisateur_id, experience, specialites, tarif_horaire, photo, note_moyenne) VALUES
(2, '10 ans expérience dans la cuisine française et italienne.', 'Française, Italienne', 45.00, 'images/chefs/jean.jpg', 4.5),
(4, 'Passionné de cuisine asiatique avec 5 ans expérience.', 'Thaï, Vietnamienne, Japonaise', 38.50, 'images/chefs/alex.jpg', 4.8),
(6, '8 ans expérience dans la cuisine végétarienne et bio.', 'Végétarienne, Bio', 40.00, 'images/chefs/claire.jpg', 4.7),
(7, 'Expert en cuisine française traditionnelle avec 12 ans expérience.', 'Française', 50.00, 'images/chefs/paul.jpg', 4.9),
(8, 'Spécialiste des grillades et barbecues avec 6 ans expérience.', 'Grillades, Barbecue', 35.00, 'images/chefs/luc.jpg', 4.6);

INSERT INTO Clients (utilisateur_id) VALUES
(3),
(5);

INSERT INTO Reservations (client_id, chef_id, date_reservation, nombre_heures, statut, prix_total) VALUES
(1, 1, '2025-04-10 19:00:00', 3, 'Confirmée', 135.00),
(2, 2, '2025-04-12 12:30:00', 2, 'En attente', 77.00);

INSERT INTO Menus (chef_id, titre, description, prix, photo) VALUES
(1, 'Menu Italien Gourmet', 'Entrée : Bruschetta, Plat : Lasagnes maison, Dessert : Tiramisu', 65.00, 'images/menus/menu_italien.jpg'),
(2, 'Saveurs Asie', 'Entrée : Nems, Plat : Pho, Dessert : Perles de coco', 55.00, 'images/menus/menu_asie.jpg');

INSERT INTO Avis (client_id, chef_id, reservation_id, note, commentaire) VALUES
(1, 1, 1, 5, 'Super expérience ! Chef très professionnel et repas délicieux.'),
(2, 2, 2, 4, 'Très bon repas, ponctuel et sympa, je recommande.');

INSERT INTO Paiements (reservation_id, montant, mode_paiement, statut) VALUES
(1, 135.00, 'Carte bancaire', 'Payé'),
(2, 77.00, 'PayPal', 'En attente');
