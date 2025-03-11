-- Login table --

CREATE TABLE `srms`.`login` (`TID_LOG` INT(7) NOT NULL AUTO_INCREMENT , 
    `userId` VARCHAR(20) NOT NULL , 
    `password` VARCHAR(255) NOT NULL , 
    `role` VARCHAR(11) NOT NULL DEFAULT 'student' ,
    PRIMARY KEY (`TID_LOG`), UNIQUE `UNIQUE_ID` (`userId`)) ENGINE = InnoDB COMMENT = 'Holds login detail of users';


-- Student Records --

CREATE TABLE `srms`.`student_records` (`TID_STU_REC` INT(8) NOT NULL AUTO_INCREMENT , 
    `name` VARCHAR(31) NOT NULL , 
    `roll` VARCHAR(15) NOT NULL , 
    `stream` VARCHAR(31) NOT NULL , 
    PRIMARY KEY (`TID_STU_REC`), UNIQUE `UNIQUE_ROLL` (`roll`)) ENGINE = InnoDB COMMENT = 'Holds student records';

