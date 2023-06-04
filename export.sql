-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`users` (
                                              `id` INT NOT NULL,
                                              `naam` VARCHAR(45) NOT NULL,
    `account_type` TINYINT NULL,
    `wachtwoord` VARCHAR(45) NULL,
    `email` VARCHAR(45) NULL,
    `telefoonnummer` VARCHAR(45) NULL,
    `remember_token` VARCHAR(45) NULL,
    `status` TINYINT NULL,
    PRIMARY KEY (`id`))
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`toeslag`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`toeslag` (
                                                `id` INT NOT NULL,
                                                `toeslagsoort` VARCHAR(45) NULL,
    `toeslagbedrag` VARCHAR(45) NULL,
    PRIMARY KEY (`id`))
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`uur`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`uur` (
                                            `id` INT NOT NULL,
                                            `uursoort` INT NULL,
                                            `Datum` DATETIME NULL,
                                            `toeslag_id` INT NOT NULL,
                                            `user_id` INT NOT NULL,
                                            PRIMARY KEY (`id`),
    INDEX `fk_uur_toeslag1_idx` (`toeslag_id` ASC) VISIBLE,
    INDEX `fk_uur_users1_idx` (`user_id` ASC) VISIBLE,
    CONSTRAINT `fk_uur_toeslag1`
    FOREIGN KEY (`toeslag_id`)
    REFERENCES `mydb`.`toeslag` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_uur_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `mydb`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Maandloonstrook`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Maandloonstrook` (
                                                        `id` INT NOT NULL,
                                                        `bestand` VARCHAR(45) NULL,
    `datum` DATE NULL,
    `bedrag` DECIMAL(9,2) NULL,
    `user_id` INT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_Maandloonstrook_Users1_idx` (`user_id` ASC) VISIBLE,
    CONSTRAINT `fk_Maandloonstrook_Users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `mydb`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
