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


//School Information

$sql = "SELECT * FROM schools WHERE school_id = ?";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $_SESSION['id']);
$stmt->execute();
$data = $stmt->fetch();


//Select the school teachers
$sql = "SELECT * FROM teachers WHERE school_id = ? ORDER BY created_at DESC LIMIT 4";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $_SESSION['id']);
$stmt->execute();
$dataTeachers = $stmt->fetchAll();

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

<body class="overflow-hidden" style="font-family: 'Inter'">

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
                        <li onclick="window.location.href='schoolDashboard.php'" style="font-family: 'Inter', sans-serif;" class="flex flex-row items-center bg-blue-100 pl-16 py-2 font-regular text-md flex justify-between items-center cursor-pointer text-blue-600">
                            <div>
                                <i class="fas fa-tachometer-alt mr-1.5"></i>
                                <span>Dashboard</span>
                            </div>
                            <div class="bg-blue-500 h-8 w-1 rounded-l-lg "></div>

                        </li>
                        <li onclick="window.location.href='schoolOverview.php'" style="font-family: 'Inter', sans-serif;" class="flex flex-row items-center font-regular pl-16 py-2 my-3 text-md cursor-pointer hover:text-blue-600 transition-colors duration-300">
                            <i class="far fa-calendar-alt mr-3"></i>
                            <span>Overview</span>
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



        <div class="flex w-5/6 h-max flex-col ">
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
                <span class="ml-10 font-bold text-xl text-gray-700">Dashboard</span>
            </div>

            <!--The actual dashboard content-->
            <div class="flex flex-row w-full h-fill">
                <div style=" height:1200px;" class="flex flex-col w-3/4 bg-slate-50 overflow-scroll overflow-x-hidden">
                    <!--The "Welcome to [School]" div-->
                    <div class="w-fill h-60 mx-10 mt-10 rounded-md bg-white shadow-md p-7 flex flex-row">
                        <div class="flex flex-col w-3/6 h-full justify-between">
                            <div>
                                <p class="text-xl font-medium">Welcome to "<?= $data['school_name'] ?>"</p>
                                <p class="text-sm font-light mt-2">Effortlessly manage your school's day-to-day activities from one centralized platform.</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium "> <?php echo date('l, F jS, Y'); ?></p>
                            </div>
                        </div>

                        <div class="flex justify-end items-center w-3/6">
                            <img class="w-2/4" src="../../resources/vectors/books.png" alt="Books">
                        </div>
                    </div>

                    <!--The School Codes Div-->
                    <div class="w-fill h-28 m-10 rounded-md flex flex-row justify-between">
                        <div class="flex items-center shadow-md w-5/12 h-full">
                            <div class="flex flex-row items-center">
                                <i class="fas fa-chalkboard-teacher mx-5 text-blue-500"></i>
                                <div class="flex flex-col">
                                    <p class="text-md font-light">Teacher Code:</p>
                                    <p class="text-md font-medium"> <?= $data['school_teacherCode'] ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center shadow-md w-5/12 h-full">
                            <div class="flex flex-row items-center">
                                <i class="fas fa-user-graduate mx-5 text-blue-500"></i>
                                <div class="flex flex-col">
                                    <p class="text-md font-light">Student Code:</p>
                                    <p class="text-md font-medium"> <?= $data['school_teacherCode'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!--The Class Navigation-->
                    <div class="w-fill h-48 mx-10 mb-10 rounded-md bg-blue-950 text-white shadow-md p-7 flex flex-row">
                        <div class="flex flex-col w-3/6 h-full justify-between">
                            <div>
                                <p class="text-xl font-medium">Navigate to your classes</p>
                                <p class="text-sm font-light mt-2"> Access your classes and stay on top of assignments, materials, and important information. With our platform, managing your classes is a breeze.</p>
                            </div>
                            <div>
                                <button onclick="window.location.href='schoolClasses.php'" class="text-white font-medium rounded-md py-1 px-4 bg-blue-900 transition duration-300 ease-in-out">Explore Classes ➜</button>
                            </div>
                        </div>

                        <div class="flex justify-end items-center w-3/6">
                            <img class="w-3/5" src="../../resources/vectors/classes.png" alt="Books">
                        </div>
                    </div>



                    <!--Show teachers list-->
                    <div class="w-fill h-40 mx-10 mb-10 rounded-md bg-white shadow-md flex flex-col">
                        <div class="border-b-2 w-full h-14 flex pl-5 items-center">
                            <p class="font-medium text-lg">Recent Teachers</p>
                        </div>
                        <!--The foreach loop to display the teachers-->
                        <div class="h-80 overflow-y-auto text-black h-fill">
                            <?php if ($dataTeachers) : ?>
                                <ul class="divide-y divide-gray-200 h-full flex flex-row">
                                    <?php foreach ($dataTeachers as $teacher) : ?>

                                        <li class="py-4 flex w-1/4 border-2 rounded-md h-fill items-center">
                                            <span class="text-3xl">
                                                <i class="fas fa-chalkboard-teacher mx-5 text-blue-500"></i>
                                            </span>
                                            <div class="flex flex-col ml-3 items-center">
                                                <div class=" flex flex-row justify-evenly">
                                                    <p class="text-lg font-semibold text-black mr-2"><?= $teacher['teacher_name'] ?></p>
                                                    <p class="text-lg font-semibold text-black"><?= $teacher['teacher_surname'] ?></p>
                                                </div>
                                                <p class="text-sm font-medium text-black">Subject: <?= $teacher['teacher_subject'] ?></p>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else : ?>
                                <p class="text-black p-4">No teachers found</p>
                            <?php endif; ?>
                        </div>

                    </div>



                </div>


                <!--The calendar Div-->
                <div class="flex w-1/4 h-full py-5 border-l border-gray-200 justify-center text-sm">
                    <div style="font-family: 'Inter'" class="w-full h-6/6 border-slate-100 px-4 text-sm" id='calendar'></div>
                </div>

            </div>
        </div>

    </div>
    </div>





    <script>
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