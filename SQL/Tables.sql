-- Store student records
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    university_roll VARCHAR(30) UNIQUE NOT NULL,
    college_roll VARCHAR(30) UNIQUE NOT NULL,     
    name VARCHAR(50) NOT NULL,
    current_semester INT NOT NULL DEFAULT '1',
    dob DATE NOT NULL,
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(15),
    address TEXT,
    gender VARCHAR(10),
    department VARCHAR(50),
    batch_year YEAR,
    photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Login table --
CREATE TABLE login (
    user_id VARCHAR(30) PRIMARY KEY,  
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin', 'faculty') DEFAULT 'student',  -- using ENUM for roles
    is_active ENUM('active', 'inactive') DEFAULT 'active',  -- using ENUM for status
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Stores different subject data -- 
CREATE TABLE subjects (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,  -- Unique ID for each subject
    subject_code VARCHAR(20) UNIQUE NOT NULL,   -- Unique subject code (e.g., MATH101, PHY102)
    subject_name VARCHAR(50) NOT NULL,          -- Name of the subject (e.g., "Mathematics", "Physics")
    subject_credit INT NOT NULL
);

-- Courses -- 
CREATE TABLE courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,   -- Unique ID for each course
    course_code VARCHAR(20) UNIQUE NOT NULL,     -- Unique course code (e.g., BSC-MATHS, BTECH-CS)
    course_name VARCHAR(50) NOT NULL            -- Name of the course (e.g., "B.Sc. Mathematics", "B.Tech Computer Science")
);

-- Joining course_subject_semester
CREATE TABLE course_subject_semester (
    course_id INT,                             -- Foreign key from Courses table
    subject_id INT,                            -- Foreign key from Subjects table
    semester INT,
    PRIMARY KEY (course_id, subject_id, semester),  -- Composite primary key
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE
);

-- Stores internal and external marks --
CREATE TABLE marks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(30) NOT NULL,
    subject_id INT NOT NULL,
    semester INT NOT NULL,
    test_type ENUM('CA1', 'CA2', 'CA3', 'CA4', 'PCA1', 'PCA2', 'FINAL') NOT NULL,
    marks_obtained DECIMAL(5,2),
    max_marks DECIMAL(5,2),
    exam_date DATE,
    is_absent BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES student(college_roll) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE,
    UNIQUE (student_id, subject_id, semester, test_type)
);

-- Stores attendance records --  
CREATE TABLE attendance (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(30) NOT NULL,             -- Reference to student
    subject_id INT NOT NULL,             -- Reference to subject
    semester INT NOT NULL,               -- For which semester
    attendance_date DATE NOT NULL,       -- Date of the class
    status ENUM('PRESENT', 'ABSENT', 'LATE', 'EXCUSED') NOT NULL,  -- Attendance status
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (student_id) REFERENCES students(college_roll) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE,
    
    UNIQUE (student_id, subject_id, attendance_date)  --  Prevent duplicate entries for same student, subject, date
);

-- Stores faculty details --
CREATE TABLE faculty (
    faculty_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(30) NOT NULL,
    name VARCHAR(50) NOT NULL,
    department VARCHAR(50) NOT NULL,
    designation VARCHAR(50),
    phone VARCHAR(15),
    email VARCHAR(100),
    joined_at DATE NOT NULL
);

-- Joining faculty_subject --
CREATE TABLE faculty_subject (
    faculty_id INT NOT NULL,
    subject_id INT NOT NULL,
    PRIMARY KEY (faculty_id, subject_id),
    FOREIGN KEY (faculty_id) REFERENCES faculty(faculty_id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE
);


--Students Documents Stroing Table--
CREATE TABLE student_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100),
    roll_no VARCHAR(20),
    reg_no VARCHAR(20),
    document_name VARCHAR(100),
    stream VARCHAR(10),
    batch VARCHAR(20),
    document_url VARCHAR(255),
    semester INT,
    status ENUM('Verified', 'Unverified') DEFAULT 'Unverified'
);

--Inserting Value in the students_documents Table--
INSERT INTO student_documents 
(student_name, roll_no, reg_no, document_name, document_url, stream, batch, semester, status) 
VALUES
-- CSE
('Ananya Sen', 'CSE2001', 'REG2001', 'Semester 1 Marksheet', 'uploads/sem1_cse2001.pdf', 'CSE', '2020-24', 1, 'Unverified'),
('Raj Gupta', 'CSE2002', 'REG2002', 'Semester 2 Marksheet', 'uploads/sem2_cse2002.pdf', 'CSE', '2020-24', 2, 'Verified'),
('Priya Das', 'CSE2101', 'REG2101', 'Semester 3 Marksheet', 'uploads/sem3_cse2101.pdf', 'CSE', '2021-25', 3, 'Unverified'),
('Soham Roy', 'CSE2201', 'REG2201', 'Semester 4 Marksheet', 'uploads/sem4_cse2201.pdf', 'CSE', '2022-26', 4, 'Verified'),

-- IT
('Ritika Paul', 'IT2001', 'REG3001', 'Semester 1 Marksheet', 'uploads/sem1_it2001.pdf', 'IT', '2020-24', 1, 'Unverified'),
('Abhishek Das', 'IT2002', 'REG3002', 'Semester 5 Marksheet', 'uploads/sem5_it2002.pdf', 'IT', '2020-24', 5, 'Verified'),
('Manish Sharma', 'IT2101', 'REG3101', 'Semester 6 Marksheet', 'uploads/sem6_it2101.pdf', 'IT', '2021-25', 6, 'Unverified'),
('Tania Dey', 'IT2201', 'REG3201', 'Semester 7 Marksheet', 'uploads/sem7_it2201.pdf', 'IT', '2022-26', 7, 'Verified'),

-- ECE
('Ankita Ghosh', 'ECE2001', 'REG4001', 'Semester 2 Marksheet', 'uploads/sem2_ece2001.pdf', 'ECE', '2020-24', 2, 'Unverified'),
('Sayan Das', 'ECE2002', 'REG4002', 'Semester 3 Marksheet', 'uploads/sem3_ece2002.pdf', 'ECE', '2020-24', 3, 'Verified'),
('Sneha Roy', 'ECE2101', 'REG4101', 'Semester 4 Marksheet', 'uploads/sem4_ece2101.pdf', 'ECE', '2021-25', 4, 'Unverified'),
('Arnab Bose', 'ECE2201', 'REG4201', 'Semester 8 Marksheet', 'uploads/sem8_ece2201.pdf', 'ECE', '2022-26', 8, 'Verified'),

-- EE
('Debanjan Pal', 'EE2001', 'REG5001', 'Semester 1 Marksheet', 'uploads/sem1_ee2001.pdf', 'EE', '2020-24', 1, 'Verified'),
('Poulami Sen', 'EE2002', 'REG5002', 'Semester 2 Marksheet', 'uploads/sem2_ee2002.pdf', 'EE', '2020-24', 2, 'Unverified'),
('Kunal Kar', 'EE2101', 'REG5101', 'Semester 5 Marksheet', 'uploads/sem5_ee2101.pdf', 'EE', '2021-25', 5, 'Verified'),
('Ishita Paul', 'EE2201', 'REG5201', 'Semester 6 Marksheet', 'uploads/sem6_ee2201.pdf', 'EE', '2022-26', 6, 'Unverified'),

-- More entries for diversity
('Aritra Das', 'CSE2202', 'REG2202', 'Project Report', 'uploads/project_cse2202.pdf', 'CSE', '2022-26', 6, 'Unverified'),
('Moumita Roy', 'IT2102', 'REG3102', 'Internship Certificate', 'uploads/intern_it2102.pdf', 'IT', '2021-25', 5, 'Verified'),
('Sumit Kumar', 'ECE2202', 'REG4202', 'Lab Report', 'uploads/lab_ece2202.pdf', 'ECE', '2022-26', 7, 'Unverified'),
('Priyanka Dey', 'EE2102', 'REG5102', 'Workshop Certificate', 'uploads/workshop_ee2102.pdf', 'EE', '2021-25', 4, 'Verified'),
('Arunava Mitra', 'IT2203', 'REG3203', 'Seminar Report', 'uploads/seminar_it2203.pdf', 'IT', '2022-26', 8, 'Unverified'),
('Tushar Sinha', 'CSE2103', 'REG2103', 'Semester 4 Marksheet', 'uploads/sem4_cse2103.pdf', 'CSE', '2021-25', 4, 'Verified'),
('Payel Nandi', 'ECE2102', 'REG4102', 'Semester 6 Marksheet', 'uploads/sem6_ece2102.pdf', 'ECE', '2021-25', 6, 'Unverified');


--Stores faculty details--
CREATE TABLE faculty (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    department VARCHAR(50),
    email VARCHAR(100),
    phone VARCHAR(20),
    category VARCHAR(50),
    subjects TEXT
);

INSERT INTO faculty (id, name, department, email, phone, category, subjects) VALUES
(6, 'Prof R. Mehta', 'CSE', 'r.mehta.cse@tint.edu.in', '9876543210', 'Assistant Professor', 'Data Science, Python Programming'),
(7, 'N. Banerjee', 'IT', 'n.banerjee.it@tint.edu.in', '9638527410', 'Lab instructor', 'Web Technologies, JavaScript'),
(8, 'Prof S. Roy', 'ECE', 's.roy.ece@tint.edu.in', '9547612380', 'Senior Professor', 'Microprocessors, VLSI'),
(9, 'K. Sharma', 'CSE', 'k.sharma.cse@tint.edu.in', '9812345678', 'Lab instructor', 'Database Management Systems, SQL'),
(10, 'Prof A. Das', 'IT', 'a.das.it@tint.edu.in', '9001234567', 'Assistant Professor', 'Object-Oriented Programming, Software Engineering'),
(11, 'Prof M. Bose', 'ECE', 'm.bose.ece@tint.edu.in', '9988776655', 'Senior Professor', 'Signal Processing, Analog Circuits'),
(12, 'T. Mukherjee', 'CSE', 't.mukherjee.cse@tint.edu.in', '9123456780', 'Lab instructor', 'Java Programming, Operating Systems'),
(13, 'Prof R. Sen', 'IT', 'r.sen.it@tint.edu.in', '9112233445', 'Senior Professor', 'Cloud Computing, Distributed Systems'),
(14, 'D. Ghosh', 'ECE', 'd.ghosh.ece@tint.edu.in', '9234567810', 'Assistant Professor', 'Control Systems, Communication Engineering'),
(15, 'Prof P. Nandi', 'CSE', 'p.nandi.cse@tint.edu.in', '9988225566', 'Assistant Professor', 'Machine Learning, Natural Language Processing');

-- Stores Documents for Admin View --
CREATE TABLE IF NOT EXISTS admin_view_docs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100),
    roll_no VARCHAR(20),
    reg_no VARCHAR(20),
    stream VARCHAR(10),
    batch VARCHAR(20),
    semester INT,
    document_name TEXT,
    document_path TEXT,
    upload_date DATETIME

);

-- Please Create uploads folder in your system.... Example file path: C:\xampp\htdocs\SRMS_25\uploads\zips --
-- this will create dummy zip file for every student where documents can be stored --
-- *** Another main thing in your xampp control panel open Apache then click config then click php.ini file then find ;extension:zip please remove ; from that line and save it open apache again then it run properly--
-- Example Insrtion Data in the table -- 
INSERT INTO admin_view_docs 
(student_name, roll_no, reg_no, stream, batch, semester, document_name, document_path, upload_date) 
VALUES
-- CSE Students
('Rohit Sharma', 'CSE202101', 'REG202101', 'CSE', '2020-24', 6, 'Admission Docs, Grade Card, Internship Cert', 'uploads/zips/CSE202101.zip', '2025-04-30 10:45:00'),
('Ankit Das', 'CSE202103', 'REG202103', 'CSE', '2020-24', 6, 'Admission Docs, Scholarship Form, Fee Receipt', 'uploads/zips/CSE202103.zip', '2025-04-28 11:15:00'),

-- ECE
('Priya Sen', 'ECE202102', 'REG202102', 'ECE', '2020-24', 6, 'Grade Card, MOOC Certificate', 'uploads/zips/ECE202102.zip', '2025-04-29 14:30:00'),

-- EE
('Megha Roy', 'EE202104', 'REG202104', 'EE', '2020-24', 6, 'Grade Card, Internship Cert', 'uploads/zips/EE202104.zip', '2025-04-27 12:00:00'),

-- IT, Sem 4
('Amit Paul', 'IT202204', 'REG202204', 'IT', '2022-26', 4, 'Admission Docs, Grade Card', 'uploads/zips/IT202204.zip', '2025-04-25 09:15:00'),

-- AIML, Sem 2
('Sneha Das', 'AIML202301', 'REG202301', 'AIML', '2023-27', 2, 'Fee Receipt, MOOC Cert', 'uploads/zips/AIML202301.zip', '2025-03-20 15:00:00'),

-- CIVIL, Sem 8
('Raju Mondal', 'CIVIL201905', 'REG201905', 'CIVIL', '2019-23', 8, 'Final Semester Report, Internship Cert', 'uploads/zips/CIVIL201905.zip', '2023-12-10 16:00:00'),

-- ME, Sem 7
('Sourav Ghosh', 'ME202105', 'REG202105', 'ME', '2021-25', 7, 'Project Report, Grade Card', 'uploads/zips/ME202105.zip', '2025-04-26 09:50:00'),

-- Mixed others
('Neha Kumari', 'CSE202304', 'REG202304', 'CSE', '2023-27', 2, 'Admission Docs', 'uploads/zips/CSE202304.zip', '2025-04-12 11:30:00'),
('Rajib Dey', 'ECE201907', 'REG201907', 'ECE', '2019-23', 8, 'Internship Cert, Final Grade Card', 'uploads/zips/ECE201907.zip', '2023-12-01 10:00:00');
