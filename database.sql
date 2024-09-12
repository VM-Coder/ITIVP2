CREATE DATABASE traffic_control;

USE traffic_control;

CREATE TABLE car (
    id INT PRIMARY KEY AUTO_INCREMENT,
    class CHAR(1) NULL,
    model VARCHAR(64) NOT NULL
);

CREATE TABLE user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(128) NOT NULL UNIQUE,
    password VARCHAR(128) NOT NULL,
    lastname VARCHAR(64) NOT NULL DEFAULT "",
    firstname VARCHAR(64) NOT NULL DEFAULT "",
    is_admin BOOLEAN NOT NULL DEFAULT 0,
    car_id INT NULL,
    FOREIGN KEY (car_id) REFERENCES car(id)
);

CREATE TABLE point (
    id INT PRIMARY KEY AUTO_INCREMENT,
    coords POINT NOT NULL
);

CREATE TABLE road (
    id INT PRIMARY KEY AUTO_INCREMENT,
    start_point INT NOT NULL,
    end_point INT NOT NULL,
    FOREIGN KEY (start_point) REFERENCES point(id),
    FOREIGN KEY (end_point) REFERENCES point(id)
);

CREATE TABLE traffic_light (
    id INT PRIMARY KEY AUTO_INCREMENT, 
    position INT NOT NULL, 
    direction INT NOT NULL,
    FOREIGN KEY (position) REFERENCES point(id),
    FOREIGN KEY (direction) REFERENCES road(id)
);

INSERT INTO user (id, email, password, lastname, firstname, is_admin, car_id)
VALUES
    (1, 'admin@example.com', 'admin', '', '', 1, NULL),
    (2, 'john.doe@example.com', 'password123', 'Doe', 'John', 0, NULL),
    (3, 'jane.smith@example.com', 'password456', 'Smith', 'Jane', 0, NULL),
    (4, 'alice.johnson@example.com', 'password789', 'Johnson', 'Alice', 0, NULL),
    (5, 'bob.brown@example.com', 'password101', 'Brown', 'Bob', 0, NULL),
    (6, 'charlie.davis@example.com', 'password102', 'Davis', 'Charlie', 0, NULL),
    (7, 'david.wilson@example.com', 'password103', 'Wilson', 'David', 0, NULL),
    (8, 'emma.miller@example.com', 'password104', 'Miller', 'Emma', 0, NULL),
    (9, 'frank.moore@example.com', 'password105', 'Moore', 'Frank', 0, NULL),
    (10, 'grace.taylor@example.com', 'password106', 'Taylor', 'Grace', 0, NULL);

UPDATE user SET password = SHA1(password);