-- Init MySQL (schema + données) pour ce projet
-- Base attendue par le code : fooddb (voir configuration/mysql.php)

SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE DATABASE IF NOT EXISTS `fooddb`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

-- User attendu par le code (MYSQL_USER / MYSQL_PASSWORD)
CREATE USER IF NOT EXISTS 'tpweb'@'%' IDENTIFIED BY 'TyTy1234';
GRANT ALL PRIVILEGES ON `fooddb`.* TO 'tpweb'@'%';
FLUSH PRIVILEGES;

USE `fooddb`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `recipes`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `photo`;
DROP TABLE IF EXISTS `ingredients`;
DROP TABLE IF EXISTS `recette`;
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- Partie "Site de recettes" (tables: users / recipes / comments)
-- ============================================================

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
  (1, 34, 'mickael.andrieu@exemple.com', 'Mickaël Andrieu', 'devine'),
  (2, 34, 'mathieu.nebra@exemple.com', 'Mathieu Nebra', 'MiamMiam'),
  (3, 28, 'laurene.castor@exemple.com', 'Laurène Castor', 'laCasto28'),
  (4, 28, 'jordan', 'jordan@mail.com', '');

INSERT INTO `recipes` (`recipe_id`, `author`, `is_enabled`, `recipe`, `title`) VALUES
  (1, 'mickael.andrieu@exemple.com', 1, 'Le cassoulet est une spécialité régionale du Languedoc, à base de haricots secs, généralement blancs, et de viande.', 'Cassoulet'),
  (2, 'mickael.andrieu@exemple.com', 0, 'Le couscous est une semoule de blé dur, et une spécialité culinaire issue de la cuisine berbère.', 'Couscous'),
  (3, 'mathieu.nebra@exemple.com', 1, 'L''escalope à la milanaise est une escalope panée, traditionnellement prise dans le faux-filet.', 'Escalope milanaise'),
  (4, 'laurene.castor@exemple.com', 0, 'La salade César est une salade composée, à base de laitue romaine, croûtons, parmesan et sauce César.', 'Salade Romaine');

INSERT INTO `comments` (`comment_id`, `comment`, `recipe_id`, `user_id`, `review`, `created_at`) VALUES
  (1, 'Très bonne recette, merci !', 1, 2, 5, '2026-02-06 12:00:00'),
  (2, 'Simple et efficace.', 1, 3, 4, '2026-02-06 13:00:00');

-- ============================================================
-- Partie "Simply recipes" (tables: recette / ingredients / photo)
-- ============================================================

CREATE TABLE `recette` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(255) NOT NULL,
  `type` VARCHAR(100) NOT NULL,
  `temps_preparation` INT NOT NULL,
  `temps_cuisson` INT NOT NULL,
  `instruction_cuisson` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `recette_type_idx` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `ingredients` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_recette` INT NOT NULL,
  `nom` VARCHAR(255) NOT NULL,
  `origine` VARCHAR(255) NOT NULL DEFAULT '',
  `quantite` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ingredients_id_recette_idx` (`id_recette`),
  CONSTRAINT `ingredients_recette_fk` FOREIGN KEY (`id_recette`) REFERENCES `recette` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `photo` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_recette` INT NOT NULL,
  `nom` VARCHAR(255) NOT NULL,
  `lien` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `photo_id_recette_idx` (`id_recette`),
  CONSTRAINT `photo_recette_fk` FOREIGN KEY (`id_recette`) REFERENCES `recette` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `recette` (`id`, `nom`, `type`, `temps_preparation`, `temps_cuisson`, `instruction_cuisson`) VALUES
  (1, 'recipe-1', 'Breakfast', 15, 10, 'Mélanger, cuire, puis servir.'),
  (2, 'recipe-2', 'Lunch', 20, 25, 'Préparer les ingrédients, assembler, puis cuire.'),
  (3, 'recipe-3', 'Dinner', 10, 30, 'Assaisonner, enfourner, puis laisser refroidir 5 minutes.'),
  (4, 'recipe-4', 'Dessert', 25, 35, 'Mélanger la pâte, cuire au four, puis laisser reposer.');

INSERT INTO `photo` (`id`, `id_recette`, `nom`, `lien`) VALUES
  (1, 1, 'recipe-1', 'final/assets/recipes'),
  (2, 2, 'recipe-2', 'final/assets/recipes'),
  (3, 3, 'recipe-3', 'final/assets/recipes'),
  (4, 4, 'recipe-4', 'final/assets/recipes');

INSERT INTO `ingredients` (`id`, `id_recette`, `nom`, `origine`, `quantite`) VALUES
  (1, 1, 'Œufs', 'France', '2'),
  (2, 1, 'Pain', 'France', '2 tranches'),
  (3, 1, 'Beurre', 'France', '10 g'),
  (4, 2, 'Poulet', 'France', '200 g'),
  (5, 2, 'Riz', 'Italie', '150 g'),
  (6, 2, 'Sel', '', '1 pincée'),
  (7, 3, 'Tomates', 'Espagne', '3'),
  (8, 3, 'Huile d''olive', 'Italie', '2 c. à soupe'),
  (9, 3, 'Ail', 'France', '1 gousse'),
  (10, 4, 'Farine', 'France', '250 g'),
  (11, 4, 'Sucre', 'France', '80 g'),
  (12, 4, 'Lait', 'France', '20 cl');

