
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `login` VARCHAR(40)  CHARACTER SET utf8 COLLATE utf8_general_ci,
  `email` VARCHAR(80)  CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` CHAR(32)  CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `hash` CHAR(32)  CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login_index`(`login`),
  UNIQUE KEY `email_index`(`email`),
)
ENGINE = MyISAM
CHARACTER SET utf8 COLLATE utf8_general_ci;