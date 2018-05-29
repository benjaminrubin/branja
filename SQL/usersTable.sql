DROP TABLE IF EXISTS `benjamfr_branja_db`.`users` ;

CREATE TABLE IF NOT EXISTS `benjamfr_branja_db`.`users` (
  `userId` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(64) NOT NULL,
  `primaryEmail` VARCHAR(255) NOT NULL,
  `secondaryEmail` VARCHAR(255) NULL,
  `fullName` VARCHAR(255) NOT NULL,
  `dateOfBirth` DATE NOT NULL,
  `genderId` INT NOT NULL,
  `currentCityId` INT NOT NULL,
  `dateCreated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateUpdated` DATETIME NOT NULL,
  `isActive` ENUM('1', '0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`userId`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  UNIQUE INDEX `email_UNIQUE` (`primaryEmail` ASC),
  INDEX `fk_users_genders1_idx` (`genderId` ASC),
  CONSTRAINT `fk_users_genders1`
    FOREIGN KEY (`genderId`)
    REFERENCES `benjamfr_branja_db`.`genders` (`gender_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;