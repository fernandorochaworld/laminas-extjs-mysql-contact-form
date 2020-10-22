--- Database name: laminas-extjs-mysql-contact-form

----- Module Image
CREATE TABLE `images` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `deleted_at` datetime,
  `created_at` datetime NOT NULL,
  `updated_at` datetime,
  PRIMARY KEY (`id`)
) ENGINE = INNODB;


----- Module Form
CREATE TABLE `priorities` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = INNODB;


INSERT INTO `priorities` VALUES (1,'Low'),(2,'Medium'),(3,'High');

CREATE TABLE `forms` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `priority_id` int unsigned NOT NULL,
  `email` VARCHAR(100),
  `birthdate` DATE,
  `company` VARCHAR(100),
  `profession` VARCHAR(100),
  `notes` TEXT,
  `phone_home` VARCHAR(30),
  `phone_business` VARCHAR(30),
  `phone_mobile` VARCHAR(30),
  `fax` VARCHAR(30),
  `biography` TEXT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`priority_id`) REFERENCES `priorities`(`id`)
) ENGINE = INNODB;

INSERT INTO forms(name, priority_id, created_at)
VALUES 
('Contact 1', 3, now()),
('Contact 2', 2, now()),
('Contact 3', 1, now()),
('Contact 4', 3, now());
