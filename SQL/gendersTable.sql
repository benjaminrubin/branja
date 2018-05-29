DROP TABLE IF EXISTS `benjamfr_branja_db`.`genders` ;

CREATE TABLE IF NOT EXISTS `benjamfr_branja_db`.`genders` (
  `genderId` INT NOT NULL AUTO_INCREMENT,
  `gender` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`genderId`)
  )
ENGINE = InnoDB;