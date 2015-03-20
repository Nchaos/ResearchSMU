-- MySQL Script generated by MySQL Workbench
-- 03/19/15 19:55:50
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema DBGUI
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema DBGUI
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `DBGUI` ;
USE `DBGUI` ;

-- -----------------------------------------------------
-- Table `DBGUI`.`Institution`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `DBGUI`.`Institution` (
  `inst_ID` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`inst_ID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `DBGUI`.`Users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `DBGUI`.`Users` (
  `user_ID` INT NOT NULL,
  `inst_ID` INT NOT NULL,
  PRIMARY KEY (`user_ID`, `inst_ID`),
  INDEX `fk_Users_Institution1_idx` (`inst_ID` ASC),
  CONSTRAINT `fk_Users_Institution1`
    FOREIGN KEY (`inst_ID`)
    REFERENCES `DBGUI`.`Institution` (`inst_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `DBGUI`.`Admin`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `DBGUI`.`Admin` (
  `admin_ID` INT NOT NULL,
  `user_ID` INT NOT NULL,
  `fName` VARCHAR(45) NOT NULL,
  `lName` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`admin_ID`, `user_ID`),
  INDEX `fk_Admin_Users1_idx` (`user_ID` ASC),
  CONSTRAINT `fk_Admin_Users1`
    FOREIGN KEY (`user_ID`)
    REFERENCES `DBGUI`.`Users` (`user_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `DBGUI`.`Guest`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `DBGUI`.`Guest` (
  `user_ID` INT NOT NULL,
  `Guestcol` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`user_ID`),
  INDEX `fk_Guest_Users1_idx` (`user_ID` ASC),
  CONSTRAINT `fk_Guest_Users1`
    FOREIGN KEY (`user_ID`)
    REFERENCES `DBGUI`.`Users` (`user_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `DBGUI`.`General`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `DBGUI`.`General` (
  `gen_ID` INT NOT NULL,
  `user_ID` INT NOT NULL,
  `fName` VARCHAR(45) NOT NULL,
  `lName` VARCHAR(45) NOT NULL,
  `loginCount` INT NULL,
  `inst_ID` INT NOT NULL,
  `major` VARCHAR(45) NOT NULL,
  `resume` VARCHAR(45) NOT NULL,
  `graduate` TINYINT(1) NOT NULL,
  PRIMARY KEY (`gen_ID`, `user_ID`),
  INDEX `fk_General_Users_idx` (`user_ID` ASC),
  CONSTRAINT `fk_General_Users`
    FOREIGN KEY (`user_ID`)
    REFERENCES `DBGUI`.`Users` (`user_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `DBGUI`.`Department`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `DBGUI`.`Department` (
  `dept_ID` INT NOT NULL,
  `inst_ID` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `rop_ID` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`dept_ID`),
  INDEX `fk_Department_Institution1_idx` (`inst_ID` ASC),
  CONSTRAINT `fk_Department_Institution1`
    FOREIGN KEY (`inst_ID`)
    REFERENCES `DBGUI`.`Institution` (`inst_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `DBGUI`.`Faculty`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `DBGUI`.`Faculty` (
  `faculty_ID` VARCHAR(45) NOT NULL,
  `user_ID` INT NOT NULL,
  `inst_ID` INT NOT NULL,
  `dept_ID` INT NOT NULL,
  `fName` VARCHAR(45) NOT NULL,
  `lName` VARCHAR(45) NOT NULL,
  `loginCount` INT NULL,
  PRIMARY KEY (`faculty_ID`),
  INDEX `fk_Faculty_Users1_idx` (`user_ID` ASC),
  INDEX `fk_Faculty_Institution1_idx` (`inst_ID` ASC),
  INDEX `fk_Faculty_Department1_idx` (`dept_ID` ASC),
  CONSTRAINT `fk_Faculty_Users1`
    FOREIGN KEY (`user_ID`)
    REFERENCES `DBGUI`.`Users` (`user_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Faculty_Institution1`
    FOREIGN KEY (`inst_ID`)
    REFERENCES `DBGUI`.`Institution` (`inst_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Faculty_Department1`
    FOREIGN KEY (`dept_ID`)
    REFERENCES `DBGUI`.`Department` (`dept_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `DBGUI`.`ROP`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `DBGUI`.`ROP` (
  `rop_ID` INT NOT NULL,
  `faculty_ID` VARCHAR(45) NOT NULL,
  `inst_ID` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `dateCreated` VARCHAR(45) NOT NULL,
  `dateFinished` VARCHAR(45) NOT NULL,
  `num_Positions` INT NOT NULL,
  PRIMARY KEY (`rop_ID`),
  INDEX `fk_ROP_Faculty1_idx` (`faculty_ID` ASC, `inst_ID` ASC),
  CONSTRAINT `fk_ROP_Faculty1`
    FOREIGN KEY (`faculty_ID` , `inst_ID`)
    REFERENCES `DBGUI`.`Faculty` (`faculty_ID` , `inst_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `DBGUI`.`Applicants`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `DBGUI`.`Applicants` (
  `rop_ID` INT NOT NULL,
  `gen_ID` INT NOT NULL,
  `count` INT NOT NULL,
  PRIMARY KEY (`rop_ID`, `gen_ID`),
  INDEX `fk_Applicants_ROP1_idx` (`rop_ID` ASC),
  INDEX `fk_Applicants_General1_idx` (`gen_ID` ASC),
  CONSTRAINT `fk_Applicants_ROP1`
    FOREIGN KEY (`rop_ID`)
    REFERENCES `DBGUI`.`ROP` (`rop_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Applicants_General1`
    FOREIGN KEY (`gen_ID`)
    REFERENCES `DBGUI`.`General` (`gen_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `DBGUI`.`Password`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `DBGUI`.`Password` (
  `user_ID` INT NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`user_ID`),
  INDEX `fk_Password_Users1_idx` (`user_ID` ASC),
  CONSTRAINT `fk_Password_Users1`
    FOREIGN KEY (`user_ID`)
    REFERENCES `DBGUI`.`Users` (`user_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
