<?php

include_once '../../db_connection/config.php';

if (!isset($_SESSION['id'])) {
    header('Location: ../../index.html');
    exit();
}

$_SESSION['id'];

//Logout function
if (isset($_POST['logout'])) {
    $_SESSION['id'] = NULL;
    header('Location: ../../index.html');
}

//Select the school teachers
$sql = "SELECT * FROM parents WHERE parent_id = ? ";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $_SESSION['id']);
$stmt->execute();
$data = $stmt->fetch();

//Select the children
$sql = "SELECT * FROM students WHERE student_parentCode = ? ";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $data['parent_identifier']);
$stmt->execute();
$dataChildren = $stmt->fetch();

$classId = $dataChildren['student_class'];

//Select the classes
$sql = "SELECT * FROM classes WHERE class_id = ? ";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $classId);
$stmt->execute();
$dataClass = $stmt->fetch();

//Select the subjects
$sql = "SELECT * FROM class_subjects WHERE class_id = ? ";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $classId);
$stmt->execute();
$dataSubjects = $stmt->fetchAll();

//Select the teacher
$sql = "SELECT * FROM teachers WHERE teacher_id = ? ";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $dataClass['class_headTeacher']);
$stmt->execute();
$dataTeacher = $stmt->fetch();

//Select the school
$sql = "SELECT school_name FROM schools WHERE school_id = ? ";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $dataChildren['school_id']);
$stmt->execute();
$dataSchool = $stmt->fetch();



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




        <div class="flex w-screen h-screen flex-col">
            <div class="flex items-center w-full h-14 py-5 border-b border-gray-200 justfy-between">
                <!-- Navbar on top -->
                <div class="flex items-center w-full h-14 py-5 border-b  border-gray-200">
                    <div class="ml-16">
                        <p onclick="window.location.href='../../index.html'" class="text-3xl font-bold bg-gradient-to-r from-blue-500 to-black text-transparent bg-clip-text cursor-pointer inline-block">pupill</p>
                    </div>
                </div>

                <div class="w-2/12 flex display-row items-center">
                    <img class="w-8 h-8 border mr-3 rounded-md border-blue-400" src="../../resources/icons/school.png" alt="Profile Picture">
                    <div class="relative inline-block">
                        <div class="cursor-pointer" onclick="showDropdown()">
                            <span class="cursor-pointer mr-0.5"><?= $data['parent_name'] ?></span>
                            <span class="cursor-pointer"><?= $data['parent_surname'] ?></span>
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
                <span class="ml-16 font-bold text-xl text-gray-700">Overview</span>
            </div>

            <!--The actual dashboard content-->
            <div class="flex flex-row w-full overflow-scroll overflow-x-hidden">
                <div class="flex flex-col w-5/5 h-screen bg-slate-50">
                    <!--The "Welcome to [School]" div-->
                    <div class="flex flex-row w-fill items-center mt-10">
                        <div class="w-9/12 h-60 ml-10 mr-4 rounded-md bg-white shadow-md p-7 flex flex-row">
                            <div class="flex flex-col w-3/6 h-full justify-between">
                                <div>
                                    <p class="text-xl font-medium">Hello <?= $data['parent_name'] ?></p>
                                    <p class="text-sm font-light mt-2">Our comprehensive platform allows you to seamlessly manage all aspects of your teaching responsibilities, from lesson planning to grading, all in one centralized location.</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium "> <?php echo date('l, F jS, Y'); ?></p>
                                </div>
                            </div>

                            <div class="flex justify-end items-center w-3/6">
                                <img class="w-1/2" src="../../resources/vectors/books.png" alt="Books">
                            </div>
                        </div>
                        <div class="flex w-3/12">
                            <div class="flex items-center shadow-md w-full h-60 mr-10">
                                <div class="flex flex-row items-center">
                                    <i class="fas fa-chalkboard-teacher mx-5 text-blue-500"></i>
                                    <div class="flex flex-col">
                                        <p class="text-md font-light">Your Parent Identifier: </p>
                                        <p class="text-md font-medium"> <?= $data['parent_identifier'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!--Teacher Class Stats-->
                    <div class="w-fill h-28 m-10 rounded-md flex flex-row justify-between">
                        <div class="flex items-center shadow-md w-3/12 h-28 mr-4">
                            <div class="flex flex-row items-center">
                                <i class="fas fas fa-user-graduate mx-5 text-blue-500"></i>
                                <div class="flex flex-col">
                                    <p class="text-md font-light">Your Children: </p>
                                    <p class="text-md font-medium"><?= $dataChildren['student_name'] . ' ' . $dataChildren['student_surname'] ?></p>

                                </div>
                            </div>
                        </div>

                        <div class="flex items-center shadow-md w-3/12 h-28 mr-4">
                            <div class="flex flex-row items-center">
                                <i class="fas fa-chalkboard-teacher mx-5 text-blue-500"></i>
                                <div class="flex flex-col">
                                    <p class="text-md font-light">Your Children Class</p>
                                    <p class="text-md font-medium"> <?= $dataClass['class_name'] ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center shadow-md w-3/12 h-28 mr-4">
                            <div class="flex flex-row items-center">
                                <i class="fas fa-user-tie mx-5 text-blue-500"></i>
                                <div class="flex flex-col">
                                    <p class="text-md font-light">Your Children's Head Teacher</p>
                                    <p class="text-md font-medium"> <?= $dataTeacher['teacher_name'] . ' ' . $dataTeacher['teacher_surname'] ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center shadow-md w-3/12 h-28">
                            <div class="flex flex-row items-center">
                                <i class="fas fa-university mx-5 text-blue-500"></i>
                                <div class="flex flex-col">
                                    <p class="text-md font-light">Your Children's School</p>
                                    <p class="text-md font-medium"><?= $dataSchool['school_name'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!--Show classes list-->
                    <div class="w-fill h-screen mx-10 mb-20 rounded-md bg-white shadow-md flex flex-col">
                        <div class="border-b-2 w-full h-14 flex pl-5 items-center">
                            <p class="font-medium text-lg">Children Grades</p>
                        </div>
                        <div class=" overflow-y-auto text-black h-fill">
                            <?php if ($dataSubjects) : ?>
                                <ul class="divide-y divide-gray-200 h-full">
                                    <?php $count = 0; ?>
                                    <?php foreach ($dataSubjects as $subject) :
                                        $teacher_id = $subject['teacher_id'];
                                        $stmt = $con->prepare("SELECT teacher_name, teacher_surname FROM teachers WHERE teacher_id = ?");
                                        $stmt->bindParam(1, $teacher_id);
                                        $stmt->execute();
                                        $teacher = $stmt->fetch();
                                        if ($teacher) {
                                            $teacher_nameNsurname = $teacher['teacher_name'] . ' ' . $teacher['teacher_surname'];
                                        } else {
                                            $teacher_name = "No teacher found";
                                        }

                                        if ($count % 4 == 0) { // create new column after every 4 rows
                                            if ($count > 0) { // close previous column
                                                echo '</div>';
                                            }
                                            echo '<div class="flex flex-wrap">';
                                        }
                                    ?>
                                        <li onclick="openSubjectModal('<?= $subject['subject'] ?>', '<?= $teacher_id ?>',  '<?= $dataChildren['student_id'] ?>')" class="py-4 flex w-1/4 border-2 rounded-md h-20 items-center">
                                            <span class="text-3xl">
                                                <i class="fas fa-chalkboard-teacher mx-5 text-blue-500"></i>
                                            </span>
                                            <div class="flex flex-col ml-3 items-center">
                                                <div class=" flex flex-col justify-evenly">
                                                    <p class="text-lg font-semibold text-black mr-2"><?= $subject['subject'] ?></p>
                                                    <p class="text-sm font-light text-black">Class teacher: <?= $teacher_nameNsurname ?></p>
                                                </div>
                                            </div>
                                        </li>
                                        <?php $count++; ?>
                                    <?php endforeach; ?>
                                </ul>
                                <?php if ($count > 0) { // close last column
                                    echo '</div>';
                                } ?>
                            <?php else : ?>
                                <p class="text-black p-4">No subjects found</p>
                            <?php endif; ?>
                        </div>
                    </div>


                </div>



            </div>

            <div class="flex w-1/5 h-[50vh] py-5 border-l border-gray-200 justify-center text-sm">
                <div style="font-family: 'Inter'" class="w-full h-94 border-slate-100 px-4 text-sm" id='calendar'></div>
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
                        Welcome to your children's class overview! Here, you can find all of the important information about your child's progress, assignments, and participation in class. From grades and attendance to upcoming events and teacher feedback, this is the go-to place for all the details about your child's activity in this class.
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
                        <input required class="border border-gray-400 p-2 w-full rounded-md" type="text" name="teacher-identifier" id="subjectGrade_Modal" readonly>
                    </div>
                    <div class="mb-4 w-1/2  ">
                        <label class="block text-gray-700 font-medium mb-2" for="class-name">Percentage</label>
                        <input required class="border border-gray-400 p-2 w-full rounded-md" type="text" name="teacher-identifier" id="subjectPercentage_Modal" readonly>
                    </div>
                </div>
                <div class="mb-4  ">
                    <label class="block text-gray-700 font-medium mb-2" for="class-name">Graded At</label>
                    <input required class="border border-gray-400 p-2 w-full rounded-md" type="text" name="teacher-identifier" id="subject-gradedAt_Modal" readonly>
                </div>
                <div class="flex justify-center mt-20">
                    <button type="button" onclick="closeSubjectModal()" class=" w-full bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-md hover:bg-gray-400 transition duration-300 ease-in-out">Close</button>
                </div>
            </form>
        </div>
    </div>



    <script>
        function showDropdown() {
            var dropdown = document.querySelector(".absolute");
            dropdown.classList.toggle("hidden");
        }

        //------------------------------------------Open Subject---------------------------------------
        function openSubjectModal(subject, teacher_id, student_id) {
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
            xhr.open('GET', 'get_grades.php?subject=' + subject + '&student_id=' + student_id + '&teacher_id=' + teacher_id, true);
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