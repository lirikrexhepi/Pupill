<?php
// get_grades.php

include_once '../../db_connection/config.php';

$subject = $_GET['subject'];
$studentId = $_GET['student_id'];
$teacherId = $_GET['teacher_id'];

// Retrieve grades
$sqlGrades = "SELECT * FROM grades WHERE student_id = ? AND subject = ?";
$stmtGrades = $con->prepare($sqlGrades);
$stmtGrades->bindParam(1, $studentId, PDO::PARAM_INT);
$stmtGrades->bindParam(2, $subject, PDO::PARAM_STR);
$stmtGrades->execute();
$grades = $stmtGrades->fetch(PDO::FETCH_ASSOC);

// Retrieve teacher's name and surname
$sqlTeacher = "SELECT teacher_name, teacher_surname FROM teachers WHERE teacher_Id = ?";
$stmtTeacher = $con->prepare($sqlTeacher);
$stmtTeacher->bindParam(1, $teacherId, PDO::PARAM_INT);
$stmtTeacher->execute();
$teacher = $stmtTeacher->fetch(PDO::FETCH_ASSOC);

// Array that holds both grades and the teacher's name and surname
$result = array(
    'grades' => $grades,
    'teacher_name' => $teacher['teacher_name'],
    'teacher_surname' => $teacher['teacher_surname']
);

echo json_encode($result);
