<?php

include_once '../../db_connection/config.php';

//See if person is logged in
if (!isset($_SESSION['id'])) {
    header('Location: ../../index.html');
    exit();
}

//Logout function
if (isset($_POST['logout'])) {
    $_SESSION['id'] = NULL;
    header('Location: ../../index.html');
    unset($_SESSION['error']);
}

//Remove error function
if (isset($_SESSION['error'])) {
    unset($_SESSION['error']);
}

// Check if the student is in a class
if (isset($_SESSION['id'])) {
    $student_id = $_SESSION['id'];
    $sql = "SELECT * FROM students WHERE student_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(1, $student_id);
    $stmt->execute();
    $student = $stmt->fetch();
    if ($student && $student['student_class']) {
        $class_id = $student['student_class'];
        $sql = "SELECT * FROM classes WHERE class_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $class_id);
        $stmt->execute();
        $class = $stmt->fetch();
    }
}

//Select the student
$sql = "SELECT * FROM students WHERE student_id = ? ";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $_SESSION['id']);
$stmt->execute();
$data = $stmt->fetch();


$sql = "SELECT * FROM class_subjects WHERE class_id = ? ";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $class['class_id']);
$stmt->execute();
$dataSubjects = $stmt->fetchAll();


//Select Grades
$sql = "SELECT * FROM grades WHERE student_id = ?";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $_SESSION['id']);
$stmt->execute();
$grades = $stmt->fetchAll();

// Join class function
if (isset($_POST['join_class'])) {
    $class_code = $_POST['class-code'];
    $sql = "SELECT * FROM classes WHERE class_code = ?";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(1, $class_code);
    $stmt->execute();
    $class = $stmt->fetch();
    if (!$class) {
        $_SESSION['error'] = "No class found with that class code.";
    } else {
        $class_id = $class['class_id'];
        $student_id = $_SESSION['id'];
        $sql = "UPDATE students SET student_class = ? WHERE student_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $class_id);
        $stmt->bindParam(2, $student_id);
        $stmt->execute();
        unset($_SESSION['error']);
        header("Location: class.php");
        exit();
    }
}


// Link parent function
if (isset($_POST['link_parent'])) {
    $parent_identifier = $_POST['parent-identifier'];
    $sql = "SELECT * FROM parents WHERE parent_identifier = ?";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(1, $parent_identifier);
    $stmt->execute();
    $parent = $stmt->fetch();
    if ($parent) {
        $student_id = $_SESSION['id'];
        $sql = "UPDATE students SET student_parentCode = ? WHERE student_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $parent_identifier);
        $stmt->bindParam(2, $student_id);
        $stmt->execute();
        header("Location: class.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid parent identifier code.";
    }
}



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

<body style="font-family: 'Inter'">

    <div class=" flex flex-row">
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

                        <li onclick="window.location.href='studentDashboard.php'" style="font-family: 'Inter', sans-serif;" class="flex flex-row items-center font-regular pl-16 py-2 mt-2 mb-2 text-md cursor-pointer hover:text-blue-600 transition-colors duration-300">
                            <i class="fas fa-tachometer-alt mr-2.5"></i>
                            <span>Dashboard</span>
                        </li>
                        <li onclick="window.location.href='class.php'" style="font-family: 'Inter', sans-serif;" class="flex flex-row items-center bg-blue-100 pl-16 py-2 font-regular text-md flex justify-between items-center cursor-pointer text-blue-600">
                            <div>
                                <i class="fas fa-chalkboard-teacher mr-3"></i>
                                <span>Class</span>
                            </div>
                            <div class="bg-blue-500 h-8 w-1 rounded-l-lg "></div>
                        </li>
                        <li onclick="window.location.href='assignments.php'" style="font-family: 'Inter', sans-serif;" class="flex flex-row items-center font-regular pl-16 py-2 my-3 text-md cursor-pointer hover:text-blue-600 transition-colors duration-300">
                            <i class="far fa-bookmark mr-4"></i>
                            <span class="hover:text-blue-500">Assignments</span>
                        </li>
                        <li style="font-family: 'Inter', sans-serif;" class="flex flex-row items-center font-regular pl-16 py-2 my-3 text-md cursor-pointer hover:text-blue-600 transition-colors duration-300">
                            <i class="far fa-calendar mr-4"></i>
                            <span class="hover:text-blue-500">Calendar</span>
                        </li>
                    </ul>
                </div>

                <div class="flex flex-col justify-center items-center m-5 ">
                    <i class="fas fa-chalkboard-teacher mb-3"></i>
                    <p style="font-family: 'Inter', sans-serif;" class="mb-3 text-sm">Signed In as a:</p>
                    <p style="font-family: 'Inter', sans-serif;" class="bg-blue-200 text-blue-700 rounded-lg px-3 py-1">Student</p>
                </div>
            </div>
        </div>



        <div class="flex w-5/6 h-screen flex-col">
            <div class="flex items-center w-full h-14 py-5 border-b border-gray-200 justify-end ">
                <!--Navbar on top-->
                <div class="flex flex-row mr-14 items-center">
                    <img class="w-8 h-8 border mr-3 rounded-md border-blue-400" src="../../resources/icons/school.png" alt="Profile Picture">
                    <div class="relative inline-block">
                        <div class="cursor-pointer" onclick="showDropdown()">
                            <span class="cursor-pointer mr-0.5"><?= $data['student_name'] ?></span>
                            <span class="cursor-pointer"><?= $data['student_surname'] ?></span>
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

            <div class="flex items-center w-full h-14 py-5 border-b border-gray-200 justify-evenly">
                <!--Dashboard text thing-->
                <div class="w-8/12">
                    <span class="ml-10 font-bold text-xl text-gray-700">Class <?= !empty($class['class_name']) ? $class['class_name'] : '' ?></span>

                </div>
                <div class="flex flex-row w-5/12">
                    <?php if (empty($data['student_parentCode'])) { ?>
                        <button onclick="openModal()" name="join_class" class="text-white bg-blue-500 mr-2 font-medium rounded-md py-1 px-4  hover:bg-blue-600 transition duration-300 ease-in-out">Link Parent +</button>
                    <?php } ?>
                    <?php if (empty($data['student_class'])) { ?>
                        <form method="Post">
                            <input required class="border text-black border-gray-400 p-2 w-2/4 px-4 rounded-md" type="text" name="class-code" id="classCode" placeholder="Enter your class code here">
                            <button name="join_class" class="text-white bg-blue-500 mr-14 font-medium rounded-md py-1 px-4 border-2 border-solid border-blue-500 hover:border-blue-600 hover:bg-blue-600 transition duration-300 ease-in-out">Join Class +</button>
                        </form>
                    <?php } else { ?>
                        <button onclick="location.href='meeting.php?class=<?= urlencode($class['class_id']) ?>'" class="text-white bg-blue-500  font-medium rounded-md py-1 px-4 border-2 border-solid border-blue-500 hover:border-blue-600 hover:bg-blue-600 transition duration-300 ease-in-out">Join Online Meeting +</button>
                    <?php } ?>
                </div>
            </div>

            <!--The actual dashboard content-->
            <div class="flex flex-row w-full h-screen overflow-scroll overflow-x-hidden">
                <div class="flex flex-col w-3/4 bg-slate-50">
                    <!--The "Welcome to [School]" div-->
                    <!--Show the error here-->
                    <?php if (isset($_SESSION['error'])) { ?>
                        <div class="bg-red-100 mx-10 border border-red-400 text-red-700 mt-5 px-4 py-3 rounded relative w-1/1" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline"><?php echo $_SESSION['error']; ?></span>
                        </div>
                    <?php } ?>



                    <div class="w-fill h-60 mx-10 mt-10 rounded-md bg-white shadow-md p-7 flex flex-row">
                        <div class="flex flex-col w-3/6 h-full justify-between">
                            <div>
                                <p class="text-xl font-medium">Hello <?= $data['student_name'] ?></p>
                                <p class="text-sm font-light mt-2">View all your school activity in one place. This includes your grades for each subject, upcoming assignments, completed homework, and attendance records. Everything you need is conveniently organized on this single page</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium "> <?php echo date('l, F jS, Y'); ?></p>
                            </div>
                        </div>

                        <div class="flex justify-end items-center w-3/6">
                            <img class="w-2/4" src="../../resources/vectors/books.png" alt="Books">
                        </div>
                    </div>


                    <div class="w-fill h-200 mx-10 my-14 rounded-md bg-white overflow-y-scroll shadow-md flex flex-col">
                        <div class="border-b-2 w-full h-14 flex pl-5 items-center">
                            <p class="font-medium text-lg">Subjects</p>
                        </div>
                        <!--The foreach loop to display the classes-->
                        <div class="h-80 overflow-y-auto text-black h-fill">
                            <?php if ($dataSubjects) : ?>
                                <?php $counter = 0; ?>
                                <div class="flex flex-row flex-wrap">
                                    <?php foreach ($dataSubjects as $subject) : ?>
                                        <?php if ($counter % 4 === 0 && $counter !== 0) : ?>
                                </div>
                                <div class="flex flex-row flex-wrap">
                                <?php endif; ?>
                                <li onclick="openSubjectModal('<?= $subject['subject'] ?>', '<?= $subject['teacher_id'] ?>', '<?= $class['class_id'] ?>')" class="py-4 flex w-1/4 border-2 rounded-md bg-blue-950 text-white shadow-md h-fill items-center cursor-pointer">
                                    <span class="text-3xl">
                                        <i class="fas fa-chalkboard-teacher text-2xl mx-5 sm:text-md text-white"></i>
                                    </span>
                                    <div class="flex flex-col ml-3 items-center">
                                        <div class="flex flex-col justify-evenly">
                                            <p class="text-lg sm:text-sm font-semibold text-white mr-2"><?= $subject['subject'] ?></p>
                                            <p class="text-sm font-normal text-white">Teacher Id: <?= $subject['teacher_id'] ?></p>
                                        </div>
                                    </div>
                                </li>
                                <?php $counter++; ?>
                            <?php endforeach; ?>
                                </div>
                            <?php else : ?>
                                <p class="text-black p-4">No subjects found</p>
                            <?php endif; ?>
                        </div>
                    </div>






                </div>


                <!--The calendar Div-->
                <div class="flex w-1/4 h-full py-5 border-l border-gray-200 justify-center">
                    <div style="font-family: 'Inter'" class="w-full h-4/6 border-slate-100 px-4" id='calendar'></div>
                </div>
            </div>

        </div>
    </div>



    <div id="link-parent-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg p-6 w-2/5 h-2/5">
            <div class="mb-10 flex flex-row items-center">
                <i class="text-3xl fas fa-chalkboard-teacher mx-5 text-blue-500"></i>
                <div class="flex flex-col mt-2">
                    <h2 class="text-2xl font-bold ">
                        Link Parent
                    </h2>
                    <h2 class="mt-1 text-gray-800">
                        Connect your parent to your account so they can stay informed and support you on your learning journey.
                    </h2>
                </div>

            </div>
            <form method="post">
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2" for="class-name">Parent Identifier
                        <i title="Please enter the unique identifier code found in your parent's account to connect your parent to your account" class="fas fa-question-circle"></i>
                    </label>
                    <input required class="border border-gray-400 p-2 w-full rounded-md" type="text" name="parent-identifier" id="parent-identifier">
                </div>
                <div class="flex justify-center mt-20">
                    <button type="submit" name="link_parent" class=" w-1/2 bg-blue-500 text-white font-medium py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300 ease-in-out">Link</button>
                    <button type="button" onclick="closeModal()" class=" w-1/2 bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-md hover:bg-gray-400 transition duration-300 ease-in-out ml-4">Cancel</button>
                </div>
            </form>
        </div>
    </div>


    <div id="subject-grades-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg p-6 w-2/5 h-4/6">
            <div class="mb-10 flex flex-row items-center">
                <i class="text-3xl fas fa-chalkboard-teacher mx-5 text-blue-500"></i>
                <div class="flex flex-col mt-2">
                    <h2 id="subjectTitle_Modal" class="text-2xl font-bold"></h2>
                    <h2 class="mt-1 text-gray-800">
                        Congratulations on completing this subject! Regardless of your grade, you should be proud of the hard work and effort you put into it. Remember, success is not just about the grade you receive, but about the knowledge and skills you gain along the way. Keep up the good work!
                    </h2>
                </div>
            </div>
            <form method="post">
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2" for="class-name">Teacher</label>
                    <input required class="border border-gray-400 p-2 w-full rounded-md" type="text" name="teacher-identifier" id="subjectTeacher_Modal" readonly>
                </div>
                <div class="flex flex-row">
                    <div class="mb-4 w-1/2 mr-4">
                        <label class="block text-gray-700 font-medium mb-2" for="class-name">Grade</label>
                        <?php if (!empty($grades)) : ?>
                            <input required class="border border-gray-400 p-2 w-full rounded-md" type="text" name="teacher-identifier" id="subjectGrade_Modal" value="<?= $grades[0]['grade_value'] ?>" readonly>
                        <?php else : ?>
                            <input required class="border border-gray-400 p-2 w-full rounded-md" type="text" name="teacher-identifier" id="subjectGrade_Modal" value="N/A" readonly>
                        <?php endif; ?>

                    </div>
                    <div class="mb-4 w-1/2">
                        <label class="block text-gray-700 font-medium mb-2" for="class-name">Percentage</label>
                        <input required class="border border-gray-400 p-2 w-full rounded-md" type="text" name="teacher-identifier" id="subjectPercentage_Modal" readonly>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2" for="class-name">Graded At</label>
                    <input required class="border border-gray-400 p-2 w-full rounded-md" type="text" name="teacher-identifier" id="subject-gradedAt_Modal" readonly>
                </div>
                <div class="flex justify-center mt-20">
                    <button type="button" onclick="closeSubjectModal()" class="w-full bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-md hover:bg-gray-400 transition duration-300 ease-in-out">Close</button>
                </div>
            </form>
        </div>
    </div>



    <script>
        function showDropdown() {
            var dropdown = document.querySelector(".absolute");
            dropdown.classList.toggle("hidden");
        }


        function openModal() {
            var modal = document.getElementById('link-parent-modal');
            modal.style.opacity = "0";
            modal.classList.remove('hidden');
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

        function closeModal() {
            var modal = document.getElementById('link-parent-modal');
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


        //------------------------------------------Open Subject---------------------------------------
        function openSubjectModal(subject, teacher_id, class_id) {
            var modal = document.getElementById('subject-grades-modal');
            modal.style.opacity = "0";
            modal.classList.remove('hidden');
            document.getElementById('subjectTitle_Modal').innerText = subject;
            document.getElementById('subjectTeacher_Modal').value = teacher_id;

            // Fetch grades and teacher's name for the subject and student
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText);
                        var grades = data.grades;
                        var teacherName = data.teacher_name;
                        var teacherSurname = data.teacher_surname;

                        document.getElementById('subjectGrade_Modal').value = grades ? grades.grade_value : "N/A";
                        document.getElementById('subjectPercentage_Modal').value = grades ? grades.grade_percentage + "%" : "N/A";
                        document.getElementById('subject-gradedAt_Modal').value = grades ? grades.grade_date : "N/A";
                        document.getElementById('subjectTeacher_Modal').value = teacherName + " " + teacherSurname;
                    } else {
                        // Handle the error case
                        console.error(xhr.status);
                    }
                }
            };
            xhr.open('GET', 'get_grades.php?subject=' + subject + '&student_id=' + <?php echo $_SESSION['id']; ?> + '&teacher_id=' + teacher_id, true);
            xhr.send();


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




        function closeSubjectModal() {
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
    </script>



</body>

</html>