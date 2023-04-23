    <?php
    require_once '../db_connection/config.php';

    //---------------------------------------------------------------School sign up code--------------------------------------------------------------------------
    if (isset($_POST['school-submit'])) {

        $school_name = $_POST['school-name'];
        $school_email = $_POST['school-email'];
        $school_password = password_hash($_POST['school-password'], PASSWORD_DEFAULT);
        $school_teacher_code = $_POST['school-teacherCode'];
        $school_student_code = $_POST['school-studentCode'];
        $created_at = date('Y-m-d H:i:s');


        $query = "INSERT INTO schools (school_name, school_email, school_password, school_studentCode, school_teacherCode, created_at) VALUES (:name, :email, :password, :teacher_code, :student_code, :created_at)";

        $stmt = $con->prepare($query);
        $stmt->bindParam(':name', $school_name);
        $stmt->bindParam(':email', $school_email);
        $stmt->bindParam(':password', $school_password);
        $stmt->bindParam(':teacher_code', $school_teacher_code);
        $stmt->bindParam(':student_code', $school_student_code);
        $stmt->bindParam(':created_at', $created_at);

        if ($stmt->execute()) {
            $_SESSION['success'] = "School created successfully.";
            header('Location: ../index.php');
        } else {
            $_SESSION['error'] = "Error creating school. Please try again.";
            header('Location: ../signup.php');
        }
    }


    //---------------------------------------------------------------Teacher sign up code--------------------------------------------------------------------------
    if (isset($_POST['teacher-submit'])) {
        $teacher_name = $_POST['teacher-name'];
        $teacher_surname = $_POST['teacher-surname'];
        $teacher_email = $_POST['teacher-email'];
        $teacher_password = password_hash($_POST['teacher-password'], PASSWORD_DEFAULT);
        $teacher_subject = $_POST['teacher-subject'];
        $teacher_schoolCode = $_POST['teacher-schoolCode'];
        $created_at = $date = date('Y-m-d H:i:s.u');

        // Check if the school code entered by the user exists in the database
        $query = "SELECT school_id FROM schools WHERE school_teacherCode = :teacherCode";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':teacherCode', $teacher_schoolCode);
        $stmt->execute();
        $school = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($school) {
            // If the school exists, create the teacher account and link it to the school
            $query = "INSERT INTO teachers (teacher_name, teacher_surname, teacher_email, teacher_password, teacher_subject, school_id, created_at) VALUES (:name, :surname, :email, :password, :subject, :school_id, :created_at)";
            $stmt = $con->prepare($query);
            $stmt->bindParam(':name', $teacher_name);
            $stmt->bindParam(':surname', $teacher_surname);
            $stmt->bindParam(':email', $teacher_email);
            $stmt->bindParam(':password', $teacher_password);
            $stmt->bindParam(':subject', $teacher_subject);
            $stmt->bindParam(':school_id', $school['school_id']);
            $stmt->bindParam(':created_at', $created_at);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Account created successfully.";
                header('Location: ../index.php');
            } else {
                $_SESSION['error'] = "Error creating account. Please try again.";
                header('Location: ../signup.php');
            }
        } else {
            // If the school does not exist, show an error message
            $_SESSION['error'] = "Incorrect school code.";
            header('Location: ../signup.php');
        }
    }



    //---------------------------------------------------------------Student sign up code--------------------------------------------------------------------------
    if (isset($_POST['student-submit'])) {
        $student_name = $_POST['student-name'];
        $student_surname = $_POST['student-surname'];
        $student_email = $_POST['student-email'];
        $student_password = password_hash($_POST['student-password'], PASSWORD_DEFAULT);
        $student_birthday = $_POST['student-birthday'];
        $student_schoolCode = $_POST['student-schoolCode'];
        $created_at = $date = date('Y-m-d H:i:s.u');

        // Check if the school code entered by the user exists in the database
        $query = "SELECT school_id FROM schools WHERE school_studentCode = :studentCode";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':studentCode', $student_schoolCode);
        $stmt->execute();
        $school = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($school) {
            // If the school exists, create the teacher account and link it to the school
            $query = "INSERT INTO students (student_name, student_surname, student_email, student_password, student_birthday, school_id, created_at) VALUES (:name, :surname, :email, :password, :birthday, :school_id, :created_at)";
            $stmt = $con->prepare($query);
            $stmt->bindParam(':name', $student_name);
            $stmt->bindParam(':surname', $student_surname);
            $stmt->bindParam(':email', $student_email);
            $stmt->bindParam(':password', $student_password);
            $stmt->bindParam(':birthday', $student_birthday);
            $stmt->bindParam(':school_id', $school['school_id']);
            $stmt->bindParam(':created_at', $created_at);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Account created successfully.";
                header('Location: ../index.php');
            } else {
                $_SESSION['error'] = "Error creating account. Please try again.";
                header('Location: ../signupar.php');
            }
        } else {
            // If the school does not exist, show an error message
            $_SESSION['error'] = "Incorrect school code.";
            header('Location: ../signup.php');
        }
    }



    //---------------------------------------------------------------Parent sign up code--------------------------------------------------------------------------
    if (isset($_POST['parent-submit'])) {
        $parent_name = $_POST['parent-name'];
        $parent_surname = $_POST['parent-surname'];
        $parent_email = $_POST['parent-email'];
        $parent_password = password_hash($_POST['parent-password'], PASSWORD_DEFAULT);
        $parent_identifier = $_POST['parent-identifier'];
        $created_at = $date = date('Y-m-d H:i:s.u');


        $query = "INSERT INTO parents (parent_name, parent_surname, parent_email, parent_password, parent_identifier, created_at) VALUES (:name, :surname, :email, :password, :identifier, :created_at)";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':name', $parent_name);
        $stmt->bindParam(':surname', $parent_surname);
        $stmt->bindParam(':email', $parent_email);
        $stmt->bindParam(':password', $parent_password);
        $stmt->bindParam(':identifier', $parent_identifier);
        $stmt->bindParam(':created_at', $created_at);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Account created successfully.";
            header('Location: ../index.php');
        } else {
            $_SESSION['error'] = "Error creating account. Please try again.";
            header('Location: ../signupar.php');
        }
    }
