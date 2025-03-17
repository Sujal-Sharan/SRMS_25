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

