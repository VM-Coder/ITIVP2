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

-- Lab2

ALTER TABLE `user` CHANGE `is_admin` `role` VARCHAR(1) NOT NULL DEFAULT 'U';

UPDATE `user` SET `role` = 'A' WHERE `user`.`id` = 1;
UPDATE `user` SET `role` = 'U' WHERE `user`.`id` > 1;

INSERT INTO user (id, email, password, lastname, firstname, role, car_id)
VALUES
    (11, 'john.smith@example.com', 'password123', 'Smith', 'John', 'C', NULL),
    (12, 'jane.johnson@example.com', 'password456', 'Johnson', 'Jane', 'C', NULL),
    (13, 'alice.brown@example.com', 'password789', 'Brown', 'Alice', 'C', NULL),
    (14, 'bob.davis@example.com', 'password101', 'Davis', 'Bob', 'C', NULL),
    (15, 'charlie.doe@example.com', 'password101', 'Doe', 'Charlie', 'C', NULL);

UPDATE user SET password = SHA1(password) WHERE id > 10;

INSERT INTO point (id, coords) 
VALUES 
    (1, POINT(-340, -180)), 
    (2, POINT(-60, -180)),
    (3, POINT(240, -180)),
    (4, POINT(240, 100)),
    (5, POINT(-60, 100)),
    (6, POINT(340, 280)),
    (7, POINT(140, 280)),
    (8, POINT(430, -40)),
    (9, POINT(-60, 280)),
    (10, POINT(340, 420));

INSERT INTO road (start_point, end_point) 
VALUES 
    (4, 7), (7, 4), (1, 2), (2, 1), 
    (1, 5), (5, 1), (2, 5), (5, 2), 
    (2, 3), (3, 2), (4, 3), (3, 4), 
    (4, 5), (5, 4), (4, 8), (8, 4), 
    (3, 8), (8, 3), (10, 7), (7, 10), 
    (4, 6), (6, 4), (6, 10), (10, 6), 
    (7, 9), (9, 7), (9, 5), (5, 9);

INSERT INTO traffic_light (position, direction) 
VALUES 
    (2, 4),
    (2, 7),
    (2, 9),
    (3, 10),
    (3, 12),
    (3, 17),
    (4, 1),
    (4, 11),
    (4, 13),
    (4, 15),
    (4, 21),
    (5, 6),
    (5, 8),
    (5, 14),
    (5, 28),
    (7, 2),
    (7, 20),
    (7, 25)

ALTER TABLE `car` ADD `position` POINT NULL DEFAULT NULL AFTER `model`;

ALTER TABLE `user` ADD `point_id` INT NULL AFTER `car_id`;

ALTER TABLE `user` ADD FOREIGN KEY (`point_id`) REFERENCES `point`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

UPDATE `user` SET `point_id` = '2' WHERE `user`.`id` = 11;
UPDATE `user` SET `point_id` = '3' WHERE `user`.`id` = 12;
UPDATE `user` SET `point_id` = '4' WHERE `user`.`id` = 13;
UPDATE `user` SET `point_id` = '5' WHERE `user`.`id` = 14;
UPDATE `user` SET `point_id` = '7' WHERE `user`.`id` = 15;

-- Lab3

UPDATE car 
SET position = ST_GeomFromText('POINT(340 340)') 
WHERE id = 18;

ALTER TABLE car
ADD COLUMN movement_count INT DEFAULT 0;

DELIMITER //

CREATE TRIGGER update_movement_count
BEFORE UPDATE ON car
FOR EACH ROW
BEGIN
    IF ST_Equals(OLD.position, NEW.position) = 0 THEN
        SET NEW.movement_count = OLD.movement_count + 1;
    END IF;
END;

//

DELIMITER ;