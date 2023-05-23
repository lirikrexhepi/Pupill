<?php

include_once '../../db_connection/config.php';

if (!isset($_SESSION['id'])) {
    header('Location: ../../index.html');
    exit();
}

$_SESSION['id'];
$class_id = $_GET['classId'];

//Logout function
if (isset($_POST['logout'])) {
    $_SESSION['id'] = NULL;
    header('Location: ../../index.html');
}

//Select the school teachers
$sql = "SELECT * FROM teachers WHERE teacher_id = ? ";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $_SESSION['id']);
$stmt->execute();
$data = $stmt->fetch();

$sql = "SELECT * FROM completed_homework WHERE homework_subject = ?  AND homework_ClassID = ?";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $data['teacher_subject']);
$stmt->bindParam(2, $class_id);
$stmt->execute();
$dataHomework = $stmt->fetchAll();
$numAssignments = $stmt->rowCount();


$sql = "SELECT class_name FROM classes WHERE class_id = ? ";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $class_id);
$stmt->execute();
$className = $stmt->fetch();



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
                        <li onclick="window.location.href='teacherDashboard.php'" style="font-family: 'Inter', sans-serif;" class="flex flex-row items-center font-regular pl-16 py-2 my-2 text-md cursor-pointer hover:text-blue-600 transition-colors duration-300">
                            <i class="far fa-calendar mr-3"></i>
                            <span class="hover:text-blue-500">Dashboard</span>
                        </li>
                        <li onclick="window.location.href='teacherClass.php'" style="font-family: 'Inter', sans-serif;" class="flex flex-row items-center bg-blue-100 pl-16 py-2 font-regular text-md flex justify-between items-center cursor-pointer text-blue-600">
                            <div>
                                <i class="fas fa-tachometer-alt mr-1.5"></i>
                                <span>Classes</span>
                            </div>
                            <div class="bg-blue-500 h-8 w-1 rounded-l-lg "></div>

                        </li>
                        <li style="font-family: 'Inter', sans-serif;" class="flex flex-row items-center font-regular pl-16 py-2 my-3 text-md cursor-pointer hover:text-blue-600 transition-colors duration-300">
                            <i class="far fa-calendar mr-3"></i>
                            <span class="hover:text-blue-500">Calendar</span>
                        </li>
                        <div class="w-fill flex items-center justify-center">
                            <li onclick="window.location.href='assignments.php?classId=<?= urlencode($dataClass['class_id']) ?>'" style="font-family: 'Inter', sans-serif;" class="mt-10 w-3/5 flex flx-row items-center justify-center text-white bg-blue-500 font-medium rounded-md py-1 px-4 border-2 border-solid border-blue-500 hover:border-blue-600 hover:bg-blue-600 transition duration-300 ease-in-out cursor-pointer">
                                <i class="far fa-bookmark mr-4"></i>
                                <span>Assignments</span>
                            </li>
                        </div>
                    </ul>
                </div>

                <div class="flex flex-col justify-center items-center m-5 ">
                    <i class="fas fa-chalkboard-teacher mb-3"></i>
                    <p style="font-family: 'Inter', sans-serif;" class="mb-3 text-sm">Signed In as a:</p>
                    <p style="font-family: 'Inter', sans-serif;" class="bg-blue-200 text-blue-700 rounded-lg px-3 py-1">Teacher</p>
                </div>
            </div>
        </div>



        <div class="flex w-5/6 h-screen flex-col">
            <div class="flex items-center w-full h-14 py-5 border-b border-gray-200 justify-end">
                <!-- Navbar on top -->
                <div class="flex flex-row mr-14 items-center">
                    <img class="w-8 h-8 border mr-3 rounded-md border-blue-400" src="../../resources/icons/school.png" alt="Profile Picture">
                    <div class="relative inline-block">
                        <div class="cursor-pointer" onclick="showDropdown()">
                            <span class="cursor-pointer mr-0.5"><?= $data['teacher_name'] ?></span>
                            <span class="cursor-pointer"><?= $data['teacher_surname'] ?></span>
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
                <span class="ml-10 font-bold text-xl text-gray-700">Assignments</span>
            </div>

            <!--The actual dashboard content-->
            <div class="flex flex-row w-full h-200 overflow-scroll overflow-x-hidden">
                <div class="flex flex-col w-4/4 bg-slate-50">
                    <!--The "Welcome to [School]" div-->
                    <div class="w-fill h-48 mx-10 mt-10 rounded-md bg-white shadow-md p-7 flex flex-row justify-between">
                        <div class="flex flex-col w-3/6 h-full justify-between">
                            <div>
                                <p class="text-xl font-medium">Assignments</p>
                                <p class="text-sm font-light mt-2">Access a comprehensive list of homework assignments submitted by your students in this class, allowing you to effortlessly review and grade their work, all within a single page.</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium "> <?php echo date('l, F jS, Y'); ?></p>
                            </div>
                        </div>

                        <div class="flex justify-end items-center w-2/5">
                            <img class="w-2/4" src="../../resources/vectors/books.png" alt="Books">
                        </div>
                    </div>


                    <!--Teacher Class Stats-->
                    <div class="w-fill h-28 m-10 rounded-md flex flex-row justify-between">
                        <div class="flex items-center shadow-md w-5/12 h-28">
                            <div class="flex flex-row items-center">
                                <i class="fas fa-chalkboard-teacher mx-5 text-blue-500"></i>
                                <div class="flex flex-col">
                                    <p class="text-md font-light">Class Name: </p>
                                    <p class="text-md font-medium"> <?= $className[0] ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center shadow-md w-5/12 h-28">
                            <div class="flex flex-row items-center">
                                <i class="fas fa-user-graduate mx-5 text-blue-500"></i>
                                <div class="flex flex-col">
                                    <p class="text-md font-light">Total assignments submited:</p>
                                    <p class="text-md font-medium"> <?= $numAssignments ?></p>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!--Show Assignments list-->
                    <div class="w-fill h-94 mx-10 my-14 rounded-md bg-white shadow-md flex flex-col">
                        <div class="border-b-2 w-full h-14 flex pl-5 items-center">
                            <p class="font-medium text-lg">Assignments Overview</p>
                        </div>
                        <!--The foreach loop to display the classes-->
                        <div class="h-80 overflow-y-auto text-black h-fill">
                            <?php if ($dataHomework) : ?>
                                <ul class="divide-y divide-gray-200 h-full flex flex-wrap">
                                    <?php $count = 0; ?>
                                    <?php foreach ($dataHomework as $homework) : ?>
                                        <li onclick="openHomeworkModal('<?= $homework['homework_title'] ?>','<?= $homework['homework_description'] ?>','<?= $homework['homework_subject'] ?>','<?= $homework['homework_answer'] ?>','<?= $homework['studentFullname'] ?>')" class="py-4 flex w-1/4 border-2 h-1/4 rounded-md items-center">
                                            <span class="text-3xl">
                                                <i class="fas fa-chalkboard-teacher mx-5 text-blue-500"></i>
                                            </span>
                                            <div class="flex flex-col ml-3 items-center">
                                                <div class=" flex flex-row justify-evenly">
                                                    <p class="text-lg font-semibold text-black mr-2"><?= $homework['homework_title'] ?></p>
                                                </div>
                                                <p class="text-sm font-medium text-black"> <?= $homework['studentFullname'] ?></p>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else : ?>
                                <p class="text-black p-4">No homework has been submited</p>
                            <?php endif; ?>
                        </div>
                    </div>



                </div>


                <!--The calendar Div-->
                <div class="flex w-1/4 h-screen py-5 border-l border-gray-200 justify-center text-sm">
                    <div style="font-family: 'Inter'" class="w-full h-94 border-slate-100 px-4 text-sm" id='calendar'></div>
                </div>

            </div>
        </div>
    </div>



    <div id="assignment-modal" class="absolute z-12 inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg p-6 w-4/5 h-4/4">
            <div class="mb-10 flex flex-row items-center">
                <i class="text-3xl fas fa-chalkboard-teacher mx-5 text-blue-500"></i>
                <div class="flex flex-col mt-2">
                    <h2 class="text-2xl font-bold ">
                        Assignment
                    </h2>
                    <h2 class="mt-1 text-gray-800">
                        Here is where u can view and submit your answer to this assignment
                    </h2>
                </div>

            </div>
            <div class="flex flex-row justify-between">
                <!---------------------------------------------------------------The Homework----------------------------------------------------------------->
                <div class="w-1/2 mr-10">
                    <form method="post">
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-2" for="class-name">Homework Title*</label>
                            <input readonly class="border text-black border-gray-400 p-2 w-full rounded-md" type="text" name="homework_title" id="homework_title">
                        </div>
                        <div class="mb-4 w-fill ">
                            <label class="block text-gray-700 font-medium mb-2" for="class-subject">Homework Description*</label>
                            <textarea readonly class="border border-gray-400 p-2 w-full rounded-md h-40" value="" type="text" name="homework_description" id="homework_description"></textarea>
                        </div>

                        <div class="mb-4 w-1/1 mr-2">
                            <label class="block text-gray-700 font-medium mb-2 " for="class-subject">Homework Subject*</label>
                            <input readonly class="border text-black border-gray-400 p-3 w-full px-4 rounded-md" type="text" name="homework_subject" id="homework_subject">
                        </div>


                </div>

                <div class="h-full w-2 border-2-black"></div>
                <!-----------------------------------------------------------The Homeworks Answer----------------------------------------------------------------->
                <div class="w-1/2">

                    <div class="flex flex-col">
                        <div class="mb-4 w-fill h-40">
                            <label class="block text-gray-700 font-medium mb-2" for="class-subject">Homework Answer*</label>
                            <textarea readonly class="border border-gray-400 p-2 w-full rounded-md h-64" type="text" name="homework_answer" id="homework_answer"></textarea>
                        </div>
                        <div class="mt-32">
                            <label class="block text-gray-700 font-medium mt mb-2" for="class-name">Student*</label>
                            <input readonly class="border text-black border-gray-400 p-2 w-full rounded-md" type="text" name="homework_student" id="homework_student">
                        </div>

                    </div>

                </div>
            </div>
            <div class="flex flex-col items-center justify-center mt-20">
                <div class="flex flex-row mb-4">
                    <button type="submit" name="homework_correct" class=" w-1/2 bg-blue-500 text-white font-medium py-4 px-8 rounded-md hover:bg-blue-600 transition duration-300 ease-in-out mr-4">Correct</button>
                    <button type="submit" name="homework_incorrect" class="w-1/2 bg-red-500 text-white font-medium py-4 px-8 rounded-md hover:bg-red-600 transition duration-300 ease-in-out mr-4">Incorrect</button>
                </div>
                <button type="button" onclick="closeModal()" class=" w-1/5 bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-md hover:bg-gray-400 transition duration-300 ease-in-out">Cancel</button>
                </form>
            </div>
        </div>
    </div>




    <script>
        function openHomeworkModal(title, description, subject, answer, student) {
            var modal = document.getElementById('assignment-modal');
            modal.style.opacity = "0";
            modal.classList.remove('hidden');
            document.getElementById('homework_title').value = title;
            document.getElementById('homework_description').innerText = description;
            document.getElementById('homework_subject').value = subject;
            document.getElementById('homework_answer').value = answer;
            document.getElementById('homework_student').value = student;
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
            var modal = document.getElementById('assignment-modal');
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
    </script>



</body>

</html>