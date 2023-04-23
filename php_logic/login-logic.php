<?php
require_once '../db_connection/config.php';

//---------------------------------------------------------------School sign up code--------------------------------------------------------------------------
if (isset($_POST['signIn-button'])) {

    // Get the values from the form
    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check the user role and execute the appropriate query
    if ($role == 'school') {
        $table_name = 'schools';
    } elseif ($role == 'teacher') {
        $table_name = 'teachers';
    } elseif ($role == 'student') {
        $table_name = 'students';
    } elseif ($role == 'parent') {
        $table_name = 'parents';
    } else {
        die('Invalid user role');
    }

    // Prepare the SQL query with placeholders
    $sql = "SELECT * FROM $table_name WHERE {$role}_email = ?";
    $stmt = $con->prepare($sql);


    // Bind the user data to the placeholders
    $stmt->bindParam(1, $email);
    $stmt->execute();


    // Fetch the result of the query
    $data = $stmt->fetch();


    // Check if the user exists and the password is correct
    if (!$data) {
        $_SESSION['error'] = 'User not found';
    } elseif (password_verify($password, $data[$role . '_password'])) {

        if ($role == 'student') {
            $_SESSION['id'] = $data['student_id'];
            header("Location: ../dashboards/student_dashboard/studentDashboard.php");
        } elseif ($role == 'teacher') {
            $_SESSION['id'] = $data['teacher_id'];
            header("Location: ../dashboards/teacher_dashboard/teacherDashboard.php");
        } elseif ($role == 'school') {
            $_SESSION['id'] = $data['school_id'];
            header("Location: ../dashboards/school_dashboard/schoolDashboard.php");
        } elseif ($role == 'parent') {
            $_SESSION['id'] = $data['parent_id'];
            header("Location: ../dashboards/parent_dashboard/parentDashboard.php");
        }
        exit();
    } else {
        $_SESSION['error'] = 'Incorrect credentials';
    }
    header("Location: ../login.php");
    exit();
}
