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
    date_naissance DATE NOT NULL,
    verify BOOLEAN DEFAULT 0,
    
    CONSTRAINT chk_telephone CHECK (telephone REGEXP '^[0-9]{10,15}$'),
    CONSTRAINT chk_email CHECK (email REGEXP '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,}$')
);

-- Attribution des permissions
GRANT SELECT, UPDATE, INSERT, DELETE ON Reservation.users TO 'reservation_web'@'localhost';

CREATE TABLE creneau (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    date_jour DATE NOT NULL,
    heure TIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    
    INDEX idx_date_jour (date_jour),
    INDEX idx_heure (heure)
);

-- Attribution des permissions
GRANT SELECT, UPDATE, INSERT, DELETE ON Reservation.creneau TO 'reservation_web'@'localhost';