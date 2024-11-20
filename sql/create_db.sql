CREATE DATABASE gestion_taches;
USE gestion_taches;

CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    mot_de_passe VARCHAR(255)
);

CREATE TABLE Statut_tasks (
    id_statut INT AUTO_INCREMENT PRIMARY KEY,
    description_statut VARCHAR(100)
);

CREATE TABLE Tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    description TEXT,
    statut_id INT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id),
    FOREIGN KEY (statut_id) REFERENCES Statut_tasks(id_statut)
);

CREATE TABLE Permission (
    id_permission INT AUTO_INCREMENT PRIMARY KEY,
    description_permission VARCHAR(100)
);

CREATE TABLE Shared_Tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_id INT,
    id_permission INT,
    user_id INT,
    FOREIGN KEY (task_id) REFERENCES Tasks(id),
    FOREIGN KEY (id_permission) REFERENCES Permission(id_permission),
    FOREIGN KEY (user_id) REFERENCES Users(id)
);
