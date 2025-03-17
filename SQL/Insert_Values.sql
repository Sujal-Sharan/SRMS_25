-- Enter value into Login table --

-- Use T_AddLogin.php

UPDATE `login` SET `userId` = '101'
    WHERE `login`.`TID_LOG` = 2

-- Enter value into Student Records table --

INSERT INTO `student_records` (`TID_STU_REC`, `name`, `roll`, `stream`) 
    VALUES (NULL, 'student', '101', 'CSE');

-- --

