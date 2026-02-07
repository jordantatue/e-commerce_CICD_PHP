SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE DATABASE IF NOT EXISTS `fooddb`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'app_user'@'%' IDENTIFIED BY 'app_password';
GRANT ALL PRIVILEGES ON `fooddb`.* TO 'app_user'@'%';
FLUSH PRIVILEGES;

USE `fooddb`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `recipes`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `users` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(64) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `age` INT NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `users_email_uq` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `recipes` (
  `recipe_id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(128) NOT NULL,
  `recipe` TEXT NOT NULL,
  `author` VARCHAR(255) NOT NULL,
  `is_enabled` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`recipe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `comments` (
  `comment_id` INT NOT NULL AUTO_INCREMENT,
  `comment` TEXT NOT NULL,
  `recipe_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `review` TINYINT UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`comment_id`),
  KEY `comments_recipe_id_idx` (`recipe_id`),
  KEY `comments_user_id_idx` (`user_id`),
  CONSTRAINT `comments_recipe_fk` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`recipe_id`) ON DELETE CASCADE,
  CONSTRAINT `comments_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `comments_review_chk` CHECK (`review` BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`user_id`, `age`, `email`, `full_name`, `password`) VALUES
  (1, 34, 'mickael.andrieu@exemple.com', 'Mickael Andrieu', 'devine'),
  (2, 34, 'mathieu.nebra@exemple.com', 'Mathieu Nebra', 'MiamMiam'),
  (3, 28, 'laurene.castor@exemple.com', 'Laurene Castor', 'laCasto28');

INSERT INTO `recipes` (`recipe_id`, `author`, `is_enabled`, `recipe`, `title`) VALUES
  (1, 'mickael.andrieu@exemple.com', 1, 'Le cassoulet est une specialite regionale du Languedoc a base de haricots secs et de viande.', 'Cassoulet'),
  (2, 'mathieu.nebra@exemple.com', 1, 'L escaloppe milanaise est une escalope panee accompagnee de citron et de salade.', 'Escalope milanaise'),
  (3, 'laurene.castor@exemple.com', 1, 'La salade romaine avec parmesan et sauce Cesar est rapide et fraiche.', 'Salade romaine');

INSERT INTO `comments` (`comment_id`, `comment`, `recipe_id`, `user_id`, `review`, `created_at`) VALUES
  (1, 'Tres bonne recette, merci.', 1, 2, 5, '2026-02-06 12:00:00'),
  (2, 'Simple et efficace.', 1, 3, 4, '2026-02-06 13:00:00');
