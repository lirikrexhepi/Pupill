<?php

include_once '../../db_connection/config.php';


if (!isset($_SESSION['id'])) {
    header('Location: ../../index.html');
    exit();
}

//Logout function
if (isset($_POST['logout'])) {
    $_SESSION['id'] = NULL;
    header('Location: ../../index.html');
}


$classId = $_GET['class_id'];
$className = $_GET['class_name'];

//School Information
$sql = "SELECT * FROM schools WHERE school_id = ?";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $_SESSION['id']);
$stmt->execute();
$data = $stmt->fetch();

//Class Information
$sql = "SELECT * FROM classes WHERE class_id = ? ";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $classId);
$stmt->execute();
$dataClasses = $stmt->fetch();

//Teacher Information
$sql = "SELECT * FROM teachers WHERE teacher_id = ? ";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $dataClasses['class_headTeacher']);
$stmt->execute();
$dataTeacher = $stmt->fetch();


//Students List
$sql = "SELECT * FROM students WHERE student_class = ?  ";
$stmtS = $con->prepare($sql);
$stmtS->bindParam(1, $classId);
$stmtS->execute();
$dataClassStudents = $stmtS->fetchAll();




$numStudents = $stmtS->rowCount();

//Grades List
$sql = "SELECT * FROM grades WHERE class_id = ?";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $classId);
$stmt->execute();
$grades = $stmt->fetchAll();


//Subjects List
$sql = "SELECT * FROM class_subjects WHERE class_id = ?  ";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $classId);
$stmt->execute();
$dataClassSubjects = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pupill | Empowering Education</title>

    <!--Imports-->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/6ca4c5d806.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="icon" href="../../resources/icons/appLogo.svg" type="image/png">


    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.6/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth'
            });
            calendar.render();
        });
    </script>
</head>

<body class="overflow-y-hidden" style="font-family: 'Inter'">

    <div class="flex flex-row">
        <div class="flex w-1/6 h-screen flex-col">
            <!--The logo-->
            <div class="flex items-center w-full h-14 py-5 border-b  border-gray-200">
                <div class="ml-16">
                    <p onclick="window.location.href='../../index.html'" class="text-3xl font-bold bg-gradient-to-r from-blue-500 to-black text-transparent bg-clip-text cursor-pointer inline-block">pupill</p>
                </div>
            </div>

            <div class="flex justify-between flex-col h-full border-r border-gray-300">
                <!--The Navigation-->
                <div class="flex mt-2">
                    <ul class=" w-full">
                        <li class="flex items-center w-full h-12 border-b border-gray-200 justify-start  ">
                            <span class="ml-16 font-bold text-xl text-gray-700">Navigation</span>
                        </li>
                        <li onclick="window.location.href='schoolDashboard.php'" style="font-family: 'Inter', sans-serif;" class="flex flex-row items-center font-regular pl-16 py-2 my-3 text-md cursor-pointer hover:text-blue-600 transition-colors duration-300">
                            <i class="far fa-calendar-alt mr-3"></i>
                            <span>Dashboard</span>
                        </li>
                        <li onclick="window.location.href='schoolOverview.php'" style="font-family: 'Inter', sans-serif;" class="flex flex-row items-center bg-blue-100 pl-16 py-2 font-regular text-md flex justify-between items-center cursor-pointer text-blue-600">
                            <div>
                                <i class="fas fa-tachometer-alt mr-1.5"></i>
                                <span>Overview</span>
                            </div>
                            <div class="bg-blue-500 h-8 w-1 rounded-l-lg "></div>

                        </li>
                        <li style="font-family: 'Inter', sans-serif;" class="flex flex-row items-center font-regular pl-16 py-2 my-3 text-md cursor-pointer hover:text-blue-600 transition-colors duration-300">
                            <i class="far fa-calendar mr-3"></i>
                            <span class="hover:text-blue-500">Calendar</span>
                        </li>
                    </ul>
                </div>

                <div class="flex flex-col justify-center items-center m-5 ">
                    <i class="fas fa-school mb-3"></i>
                    <p style="font-family: 'Inter', sans-serif;" class="mb-3 text-sm">Signed In as a:</p>
                    <p style="font-family: 'Inter', sans-serif;" class="bg-blue-200 text-blue-700 rounded-lg px-3 py-1">School</p>
                </div>
            </div>
        </div>



        <div class="flex w-5/6 h-screen flex-col ">
            <div class="flex items-center w-full h-14 py-5 border-b border-gray-200 justify-end ">
                <!--Navbar on top-->
                <div class="flex flex-row mr-14 items-center">
                    <img class="w-8 h-8 border mr-3 rounded-md border-blue-400" src="../../resources/icons/school.png" alt="Profile Picture">
                    <div class="relative inline-block">
                        <div class="cursor-pointer" onclick="showDropdown()">
                            <span class="cursor-pointer mr-0.5"><?= $data['school_name'] ?></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline-block ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <div class="absolute z-10 hidden bg-white rounded-md shadow-lg border mt-4">
                            <form method="POST">
                                <button type="submit" name="logout" class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center w-full h-14 py-5 border-b border-gray-200 justify-start  ">
                <!--Navbar on top-->
                <span class="ml-10 font-bold text-xl text-gray-700">Overview</span>
            </div>

            <!--The actual dashboard content-->
            <div class="flex flex-row w-full h-screen">
                <div class="flex flex-col w-3/4 bg-slate-50 h-screen overflow-y-scroll overflow-x-hidden">
                    <!--The "Welcome to [School]" div-->
                    <div class="w-fill h-40 mx-10 mt-10 rounded-md bg-white shadow-md p-7 flex flex-row justify-between">
                        <div class="flex flex-col w-3/6 h-full">
                            <div>
                                <p class="text-xl font-medium">"<?= $data['school_name'] ?>" - Class <?= $className ?></p>
                                <p class="text-sm font-light mt-2">The overview screen in our LMS app provides you with a concise yet comprehensive summary of all the important information about the class <?= $className ?>. </p>
                            </div>

                        </div>

                        <div class="flex justify-end items-center w-4/12">
                            <img class="w-2/4" src="../../resources/vectors/books.png" alt="Books">
                        </div>
                    </div>

                    <!--Class Code and Head Teacher-->
                    <div class="w-fill h-28 m-10 rounded-md flex flex-row justify-between">
                        <div class="flex items-center shadow-md w-3/12 h-full">
                            <div class="flex flex-row items-center">
                                <i class="fas fa-user-graduate mx-5 text-blue-500"></i>
                                <div class="flex flex-col">
                                    <p class="text-md font-light">Class Head Teacher:</p>
                                    <p class="text-md font-medium"> <?= $dataTeacher['teacher_name'] . ' ' . $dataTeacher['teacher_surname']  ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center shadow-md w-3/12 h-full">
                            <div class="flex flex-row items-center">
                                <i class="fas fa-chalkboard-teacher mx-5 text-blue-500"></i>
                                <div class="flex flex-col">
                                    <p class="text-md font-light">Class Code:</p>
                                    <p class="text-md font-medium"> <?= $dataClasses['class_code'] ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center shadow-md w-3/12 h-full">
                            <div class="flex flex-row items-center">
                                <i class="fas fa-user-graduate mx-5 text-blue-500"></i>
                                <div class="flex flex-col">
                                    <p class="text-md font-light">Student Count:</p>
                                    <p class="text-md font-medium"> <?= $numStudents ?></p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!--Show students list-->
                    <?php if ($numStudents > 0) : ?>
                        <div class="container mx-auto">
                            <div class="px-4 py-8">
                                <h2 class="text-2xl font-semibold mb-4">Class Grades</h2>

                                <div class="overflow-x-auto">
                                    <div class="inline-block min-w-full shadow-md rounded-lg overflow-hidden">
                                        <table class="min-w-full leading-normal">
                                            <thead>
                                                <tr>
                                                    <th class="px-5 py-3 border-b-2 border-gray-800 text-left text-xs font-semibold text-gray-700 h-28 uppercase tracking-wider">Students</th> <!-- empty top-left cell -->
                                                    <?php foreach ($dataClassSubjects as $subject) : ?>
                                                        <th class="px-5 py-3 border-b-2 border-gray-800 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"><?php echo $subject['subject']; ?></th>
                                                    <?php endforeach; ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($dataClassStudents as $student) : ?>
                                                    <tr>
                                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-medium"><?php echo $student['student_name'] . ' ' . $student['student_surname']; ?></td>
                                                        <?php foreach ($dataClassSubjects as $subject) : ?>
                                                            <?php
                                                            $grade = '';
                                                            $g = array();
                                                            foreach ($grades as $gr) {
                                                                if ($gr['student_id'] == $student['student_id'] && $gr['subject'] == $subject['subject']) {
                                                                    $grade = $gr['grade_value'];
                                                                    $g = $gr;
                                                                    break;
                                                                }
                                                            }
                                                            ?>
                                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-medium editable" onclick="openGrade('<?= $subject['subject'] ?>', '<?= $student['student_id'] ?>', '<?= $student['student_name'] . ' ' . $student['student_surname'] ?>', '<?= $g['grade_value'] ?? '' ?>', '<?= $g['grade_percentage'] ?? '' ?>', '<?= $g['grade_date'] ?? '' ?>')" name="<?php echo 'grades[' . $student['student_id'] . '][' . $subject['subject'] . ']'; ?>">
                                                                <?php echo $grade != '' ? $grade : 'n/a'; ?>
                                                            </td>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <p class="self-center mt-20">No students in class</p>
                    <?php endif; ?>





                </div>


                <!--The calendar Div-->
                <div class="flex w-1/4 h-full py-5 border-l border-gray-200 justify-center text-sm">
                    <div style="font-family: 'Inter'" class="w-full h-2/6 border-slate-100 px-4 text-sm" id='calendar'></div>
                </div>

            </div>
        </div>
    </div>



    <div id="subject-grades-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg p-6 w-2/5 h-4/6">
            <div class="mb-10 flex flex-row items-center">
                <i class="text-3xl fas fa-chalkboard-teacher mx-5 text-blue-500"></i>
                <div class="flex flex-col mt-2">
                    <h2 id="subjectTitle_Modal" class="text-2xl font-bold ">

                    </h2>
                    <h2 class="mt-1 text-gray-800">
                        Welcome to the grading form! As an administrator, you have a bird's-eye view of students' progress in the subject, with the ability to track grades and overall performance. With this valuable insight, you can provide personalized feedback to each student and adjust your teaching methods accordingly to help them succeed.
                    </h2>
                </div>

            </div>
            <form method="post">
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2" for="class-name">Student</label>
                    <input readonly class="border border-gray-400 p-2 w-full rounded-md" type="text" name="subjectStudent_Modal" id="subjectStudent_Modal">
                </div>
                <div class="flex flex-row">
                    <div class="mb-4 w-1/2 mr-4">
                        <label class="block text-gray-700 font-medium mb-2" for="class-name">Grade</label>
                        <input readonly class="border border-gray-400 p-2 w-full rounded-md" type="text" name="subjectGrade_Modal" id="subjectGrade_Modal">
                    </div>
                    <div class="mb-4 w-1/2  ">
                        <label class="block text-gray-700 font-medium mb-2" for="class-name">Percentage</label>
                        <input readonly class="border border-gray-400 p-2 w-full rounded-md" type="text" name="subjectPercentage_Modal" id="subjectPercentage_Modal">
                    </div>
                </div>
                <div class="mb-4  ">
                    <label class="block text-gray-700 font-medium mb-2" for="class-name">Graded At</label>
                    <input readonly class="border border-gray-400 p-2 w-full rounded-md" type="text" name="subject-gradedAt_Modal" id="subject-gradedAt_Modal">
                </div>
                <!--Hidden inputs that store student id and subject-->
                <input readonly class="border border-gray-400 p-2 w-full rounded-md hidden" type="text" name="student_idModal" id="student_idModal">
                <input readonly class="border border-gray-400 p-2 w-full rounded-md hidden" type="text" name="subject_Modal" id="subject_Modal">

                <div class="flex justify-center mt-14">
                    <button type="button" onclick="closeGrade()" class=" w-1/2 bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-md hover:bg-gray-400 transition duration-300 ease-in-out ml-4">Close</button>
                </div>
            </form>
        </div>
    </div>





    <script>
        //---------------------------------------------------The code to open the popup to asign teachers---------------------------------------------------
        function openGrade(subject, studentId, student, grade, percentage, gradedAt) {
            var modal = document.getElementById('subject-grades-modal');
            modal.style.opacity = "0";
            modal.classList.remove('hidden');
            document.getElementById('subjectTitle_Modal').innerText = subject;
            document.getElementById('subject_Modal').value = subject;
            document.getElementById('subjectStudent_Modal').value = student;
            document.getElementById('student_idModal').value = studentId;

            if (grade == '') {
                document.getElementById('subjectGrade_Modal').value = 'n/a';
            } else {
                document.getElementById('subjectGrade_Modal').value = grade;
            }

            if (percentage == '') {
                document.getElementById('subjectPercentage_Modal').value = 'n/a';
            } else {
                document.getElementById('subjectPercentage_Modal').value = percentage;
            }

            if (gradedAt == '') {
                document.getElementById('subject-gradedAt_Modal').value = 'n/a';
            } else {
                document.getElementById('subject-gradedAt_Modal').value = gradedAt;
            }

            var fadeEffect = setInterval(function() {
                if (!modal.style.opacity) {
                    modal.style.opacity = 0;
                }
                if (modal.style.opacity < 1) {
                    modal.style.opacity = parseFloat(modal.style.opacity) + 0.1;
                } else {
                    clearInterval(fadeEffect);
                }
            }, 20);
        }




        function closeGrade() {
            var modal = document.getElementById('subject-grades-modal');
            modal.style.opacity = "1";
            var fadeEffect = setInterval(function() {
                if (modal.style.opacity > 0) {
                    modal.style.opacity -= 0.1;
                } else {
                    modal.classList.add('hidden');
                    clearInterval(fadeEffect);
                }
            }, 20);
        }


        function showDropdown() {
            var dropdown = document.querySelector(".absolute");
            dropdown.classList.toggle("hidden");
        }
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                defaultDate: moment(),
                editable: false,
                eventLimit: true, // allow "more" link when too many events
            });
        });
    </script>



</body>

</html>