-- Enter value into Login table --

-- Use T_AddLogin.php

UPDATE `login` SET `userId` = '101'
    WHERE `login`.`TID_LOG` = 2

-- Enter value into Student_Records table --

INSERT INTO `student_records` (`TID_STU_REC`, `name`, `roll`, `stream`) 
    VALUES (NULL, 'student', '101', 'CSE');

-- Insert value into CA_Marks table --

INSERT INTO `marks_ca` (`TID_CA`, `name`, `roll`, `subject_code`, `subject_name`, `ca1`, `ca2`, `ca3`, `ca4`) 
    VALUES (NULL, 'student', '101', 'S-1', 'Subject_1', '19', '25', '20', '11');

-- Insert value into PCA_Marks table -- 

INSERT INTO `marks_pca` (`TID_PCA`, `name`, `roll`, `subject_code`, `subject_name`, `pca1`, `pca2`) 
    VALUES (NULL, 'student', '101', 'S-1', 'Subject_1', '20', '17');

-- Attendance -- 

INSERT INTO `attendance` (`TID_ATDN`, `userId`, `date`, `status`) 
    VALUES (NULL, '101', '2025-03-17', 'Present')

-- --





