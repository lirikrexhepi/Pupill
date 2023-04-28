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


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
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

                        <li onclick="window.location.href='studentDashboard.php'" style="font-family: 'Inter', sans-serif;" class="flex flex-row items-center font-regular pl-16 py-2 my-3 text-md cursor-pointer hover:text-blue-600 transition-colors duration-300">
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

            <div class="flex items-center w-full h-14 py-5 border-b border-gray-200 justify-between">
                <!--Dashboard text thing-->
                <span class="ml-10 font-bold text-xl text-gray-700">Class <?= $class['class_name'] ?></span>
                <div class="flex flex-row justify-between w-3/12">
                    <?php if (empty($data['student_class'])) { ?>
                        <form method="Post">
                            <input required class="border text-black border-gray-400 p-2 w-2/4 px-4 rounded-md" type="text" name="class-code" id="classCode" placeholder="Enter your class code here">
                            <button name="join_class" class="text-white bg-blue-500 mr-14 font-medium rounded-md py-1 px-4 border-2 border-solid border-blue-500 hover:border-blue-600 hover:bg-blue-600 transition duration-300 ease-in-out">Join Class +</button>
                        </form>
                    <?php } else { ?>
                        <button onclick="location.href='meeting.php?class=<?= urlencode($class['class_id']) ?>'" class="text-white bg-blue-500 mr-14 font-medium rounded-md py-1 px-4 border-2 border-solid border-blue-500 hover:border-blue-600 hover:bg-blue-600 transition duration-300 ease-in-out">Join Online Meeting +</button>
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






                </div>


                <!--The calendar Div-->
                <div class="flex items-center w-1/4 h-full py-5 border-l border-gray-200 justify-center">
                    <div id="calendar"></div>

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