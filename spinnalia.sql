-- MySQL Script generated by MySQL Workbench
-- 03/13/18 09:46:06
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema spinnalia
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema spinnalia
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `spinnalia` DEFAULT CHARACTER SET utf8 ;
USE `spinnalia` ;

-- -----------------------------------------------------
-- Table `spinnalia`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spinnalia`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `firstname` VARCHAR(45) NULL,
  `lastname` VARCHAR(45) NULL,
  `nickname` VARCHAR(45) NULL,
  `mail` VARCHAR(255) NOT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  `validite` DATETIME NULL,
  `userscol` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `spinnalia`.`Category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spinnalia`.`Category` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `description` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `spinnalia`.`websites`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spinnalia`.`websites` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `url` VARCHAR(255) NOT NULL,
  `name` VARCHAR(100) NULL,
  `description` TEXT NULL,
  `users_id` INT NOT NULL,
  `Category_id` INT NOT NULL,
  PRIMARY KEY (`id`, `users_id`),
  UNIQUE INDEX `url_UNIQUE` (`url` ASC),
  INDEX `fk_websites_users_idx` (`users_id` ASC),
  INDEX `fk_websites_Category1_idx` (`Category_id` ASC),
  CONSTRAINT `fk_websites_users`
    FOREIGN KEY (`users_id`)
    REFERENCES `spinnalia`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_websites_Category1`
    FOREIGN KEY (`Category_id`)
    REFERENCES `spinnalia`.`Category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `spinnalia`.`report`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spinnalia`.`report` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `value` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `spinnalia`.`report_done`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spinnalia`.`report_done` (
  `rapport_id` INT NOT NULL,
  `websites_id` INT NOT NULL,
  `websites_users_id` INT NOT NULL,
  `date` DATETIME NULL,
  PRIMARY KEY (`rapport_id`, `websites_id`, `websites_users_id`),
  INDEX `fk_rapport_has_websites_websites1_idx` (`websites_id` ASC, `websites_users_id` ASC),
  INDEX `fk_rapport_has_websites_rapport1_idx` (`rapport_id` ASC),
  CONSTRAINT `fk_rapport_has_websites_rapport1`
    FOREIGN KEY (`rapport_id`)
    REFERENCES `spinnalia`.`report` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_rapport_has_websites_websites1`
    FOREIGN KEY (`websites_id` , `websites_users_id`)
    REFERENCES `spinnalia`.`websites` (`id` , `users_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;