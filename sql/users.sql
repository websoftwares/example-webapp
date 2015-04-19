CREATE SCHEMA `example` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;


CREATE TABLE `users` (
	`id` INT(11) NOT NULL,
	`name` VARCHAR(255) NULL DEFAULT NULL,
	`email` VARCHAR(255) NULL DEFAULT NULL,
	`password` CHAR(60) NULL DEFAULT NULL COLLATE 'latin1_bin',
	`active` TINYINT(1) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `email` (`email`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `user__activations` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`userId` INT(11) NULL DEFAULT NULL,
	`token` VARCHAR(255) NULL DEFAULT NULL,
	`created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `FK_users__activation_users` (`userId`),
	CONSTRAINT `FK_users__activation_users` FOREIGN KEY (`userId`) REFERENCES `users` (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
