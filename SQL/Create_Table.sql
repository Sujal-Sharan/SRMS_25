-- Login table --

CREATE TABLE `srms`.`login` (`TID_LOG` INT(7) NOT NULL AUTO_INCREMENT , 
    `userId` VARCHAR(20) NOT NULL , 
    `password` VARCHAR(255) NOT NULL , 
    `role` VARCHAR(11) NOT NULL DEFAULT 'student' ,
    PRIMARY KEY (`TID_LOG`), UNIQUE `UNIQUE_ID` (`userId`)) ENGINE = InnoDB COMMENT = 'Holds login detail of users';

ALTER TABLE `login` ADD `userName` VARCHAR(31) NOT NULL AFTER `userId`;

ALTER TABLE `login` 
    CHANGE `userId` `userName` VARCHAR(25) 
        CHARACTER SET utf8mb4 
        COLLATE utf8mb4_general_ci NOT NULL, 
    CHANGE `userName` `userId` VARCHAR(25) 
        CHARACTER SET utf8mb4 
        COLLATE utf8mb4_general_ci NOT NULL;

-- Student Records --

CREATE TABLE `srms`.`student_records` (`TID_STU_REC` INT(8) NOT NULL AUTO_INCREMENT , 
    `name` VARCHAR(31) NOT NULL , 
    `roll` VARCHAR(15) NOT NULL , 
    `stream` VARCHAR(31) NOT NULL , 
    PRIMARY KEY (`TID_STU_REC`), UNIQUE `UNIQUE_ROLL` (`roll`)) ENGINE = InnoDB COMMENT = 'Holds student records';

-- Student CA Marks --

CREATE TABLE `srms`.`marks_ca` (`TID_CA` INT(11) NOT NULL AUTO_INCREMENT ,
    `name` VARCHAR(31) NOT NULL ,
    `roll` VARCHAR(15) NOT NULL , 
    `subject_code` VARCHAR(15) NOT NULL , 
    `subject_name` VARCHAR(31) NOT NULL , 
    `ca1` VARCHAR(2) NULL , 
    `ca2` VARCHAR(2) NULL , 
    `ca3` VARCHAR(2) NULL , 
    `ca4` VARCHAR(2) NULL , 
    PRIMARY KEY (`TID_CA`)) ENGINE = InnoDB COMMENT = 'Stores student marks';

--  Student PCA Marks-- 

CREATE TABLE `srms`.`marks_pca` (`TID_PCA` INT(11) NOT NULL AUTO_INCREMENT ,
    `name` VARCHAR(31) NOT NULL ,
    `roll` VARCHAR(15) NOT NULL , 
    `subject_code` VARCHAR(15) NOT NULL , 
    `subject_name` VARCHAR(31) NOT NULL , 
    `pca1` VARCHAR(2) NULL , 
    `pca2` VARCHAR(2) NULL , 
    PRIMARY KEY (`TID_PCA`)) ENGINE = InnoDB COMMENT = 'Stores student PCA marks';

-- Attendance --

CREATE TABLE `srms`.'attendance' (`TID_ATDN` BIGINT(15) NOT NULL AUTO_INCREMENT ,
    `userId` VARCHAR(20) NOT NULL , 
    `date` DATE NOT NULL , 
    `status` ENUM('Present','Absent','No Class','') NOT NULL , 
    PRIMARY KEY (`TID_ATDN`)) ENGINE = InnoDB COMMENT = 'Stores attendance status of students';

-- -- 



