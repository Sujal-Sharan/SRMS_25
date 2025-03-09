CREATE TABLE `srms`.`login` (`TID_LOG` INT(7) NOT NULL AUTO_INCREMENT , 
    `userId` VARCHAR(20) NOT NULL , 
    `password` VARCHAR(255) NOT NULL , 
    `role` VARCHAR(11) NOT NULL DEFAULT 'student' ,
    PRIMARY KEY (`TID_LOG`), UNIQUE `UNIQUE_ID` (`userId`)) ENGINE = InnoDB COMMENT = 'Holds login detail of users';