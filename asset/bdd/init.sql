-- Création de la base de donnée
create database IF NOT EXISTS Reservation;

-- Création de l'utilisateur sans mot de passe
CREATE USER 'reservation_web'@'localhost';

USE Reservation;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    adresse VARCHAR(255),
    telephone VARCHAR(15) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    hashpassword VARCHAR(255) NOT NULL,
    verify BOOLEAN DEFAULT 0,
    
    CONSTRAINT chk_telephone CHECK (telephone REGEXP '^[0-9]{10,15}$'),
    CONSTRAINT chk_email CHECK (email REGEXP '^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,}$')
);

-- Attribution des permissions
GRANT SELECT, UPDATE, INSERT, DELETE ON Reservation.users TO 'reservation_web'@'localhost';