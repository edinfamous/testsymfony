SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `pagomio`.`payment_methods`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pagomio`.`payment_methods` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `pagomio`.`marks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pagomio`.`marks` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pagomio`.`payment_methods_has_marks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pagomio`.`payment_methods_has_marks` (
  `payment_method_id` INT NOT NULL,
  `marks_id` INT NOT NULL,
  `commission` FLOAT NOT NULL,
  PRIMARY KEY (`payment_method_id`, `marks_id`),
  CONSTRAINT `fk_payment_methods_has_marks_payment_methods`
    FOREIGN KEY (`payment_method_id`)
    REFERENCES `pagomio`.`payment_methods` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_payment_methods_has_marks_marks1`
    FOREIGN KEY (`marks_id`)
    REFERENCES `pagomio`.`marks` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE INDEX `fk_payment_methods_has_marks_marks1_idx` ON `pagomio`.`payment_methods_has_marks` (`marks_id` ASC);

CREATE INDEX `fk_payment_methods_has_marks_payment_methods_idx` ON `pagomio`.`payment_methods_has_marks` (`payment_method_id` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `pagomio`.`payment_methods`
-- -----------------------------------------------------
START TRANSACTION;
USE `pagomio`;
INSERT INTO `pagomio`.`payment_methods` (`id`, `name`, `description`) VALUES (1, 'Visa', '');
INSERT INTO `pagomio`.`payment_methods` (`id`, `name`, `description`) VALUES (2, 'MasterCard', NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `pagomio`.`marks`
-- -----------------------------------------------------
START TRANSACTION;
USE `pagomio`;
INSERT INTO `pagomio`.`marks` (`id`, `name`, `description`) VALUES (1, 'Marca1', NULL);
INSERT INTO `pagomio`.`marks` (`id`, `name`, `description`) VALUES (2, 'Marca2', NULL);

COMMIT;

