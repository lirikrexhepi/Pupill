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
$sql = "SELECT * FROM teachers WHERE teacher_id = ? ";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $_SESSION['id']);
$stmt->execute();
$data = $stmt->fetch();



//Select the classes
$sql = "SELECT * FROM classes WHERE class_headTeacher = ?  ";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $_SESSION['id']);
$stmt->execute();
$dataClasses = $stmt->fetchAll();

$numClasses = $stmt->rowCount();


//Select the school where the teacher works
$sql = "SELECT * FROM schools WHERE school_id = ? ";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $data['school_id']);
$stmt->execute();
$dataSchool = $stmt->fetch();

//Php to insert the classes into the table
if (isset($_POST['submit_class'])) {
    $class_name = $_POST['class-name'];
    $class_subjects = isset($_POST['class_subjects']) ? implode(',', $_POST['class_subjects']) : '';
    $class_school = $_POST['class-subject'];
    $class_headTeacher = $_SESSION['id'];
    $class_code = $_POST['class-code']; // Assuming you have the generateRandomString function
    $created_at = date('Y-m-d H:i:s');

    // Prepare and execute SQL query
    $sql = "INSERT INTO classes (class_name, class_subjectCount, school, class_code, class_headTeacher, created_at)
     VALUES (:class_name, :class_subjectCount, :school, :class_code, :class_headTeacher, :created_at)";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':class_name', $class_name);
    $stmt->bindValue(':class_subjectCount', count($_POST['class_subjects']), PDO::PARAM_INT);
    $stmt->bindParam(':school', $class_school);
    $stmt->bindParam(':class_code', $class_code);
    $stmt->bindParam(':class_headTeacher', $class_headTeacher);
    $stmt->bindParam(':created_at', $created_at);
    $stmt->execute();

    // Get the auto-incremented class_id value generated by the above INSERT statement
    $class_id = $con->lastInsertId();

    // Prepare and execute SQL query to insert into class_subjects table for each selected subject
    foreach ($_POST['class_subjects'] as $subject) {
        $sql = "INSERT INTO class_subjects (class_id, subject) VALUES (:class_id, :subject)";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':class_id', $class_id);
        $stmt->bindParam(':subject', $subject);
        $stmt->execute();
    }

    header("Location: teacherClass.php");
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
                        <li onclick="window.location.href='teacherDashboard.php'" style="font-family: 'Inter', sans-serif;" class="flex flex-row items-center font-regular pl-16 py-2 my-2 text-md cursor-pointer hover:text-blue-600 transition-colors duration-300">
                            <i class="fas fa-tachometer-alt mr-1.5"></i>
                            <span class="hover:text-blue-500">Dashboard</span>
                        </li>
                        <li onclick="window.location.href='teacherClass.php'" style="font-family: 'Inter', sans-serif;" class="flex flex-row items-center bg-blue-100 pl-16 py-2 font-regular text-md flex justify-between items-center cursor-pointer text-blue-600">
                            <div>
                                <i class="fas fa-chalkboard-teacher mr-3"></i>
                                <span>Classes</span>
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
                    <i class="fas fa-chalkboard-teacher mb-3"></i>
                    <p style="font-family: 'Inter', sans-serif;" class="mb-3 text-sm">Signed In as a:</p>
                    <p style="font-family: 'Inter', sans-serif;" class="bg-blue-200 text-blue-700 rounded-lg px-3 py-1">Teacher</p>
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

            <div class="flex items-center w-full h-14 py-5 border-b border-gray-200 justify-between  ">
                <!--Navbar on top-->
                <span class="ml-10 font-bold text-xl text-gray-700">Classes</span>
                <button onclick="openModal()" class="text-white bg-blue-500 mr-14 font-medium rounded-md py-1 px-4 border-2 border-solid border-blue-500 hover:border-blue-600 hover:bg-blue-600 transition duration-300 ease-in-out">Create Class +</button>
            </div>

            <!--The actual dashboard content-->
            <div class="flex flex-row w-full h-screen min-h-screen overflow-scroll overflow-x-hidden">
                <div class="flex flex-col w-3/4 h-full bg-slate-50">
                    <!--The "Welcome to [School]" div-->
                    <div class="w-fill h-44 mx-10 mt-10 rounded-md bg-white shadow-md p-7 flex flex-row justify-between">
                        <div class="flex flex-col w-3/6 h-full justify-between">
                            <div>
                                <p class="text-xl font-medium">Hello <?= $data['teacher_name'] ?></p>
                                <p class="text-sm font-light mt-2">Effortlessly manage your class schedule, assign homework and projects, and track their completion status, due dates, and grades, all from here.</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium "> <?php echo date('l, F jS, Y'); ?></p>
                            </div>
                        </div>

                        <div class="flex justify-end items-center w-3/12">
                            <img class="w-2/4" src="../../resources/vectors/books.png" alt="Books">
                        </div>
                    </div>




                    <!--Teacher Class Stats-->
                    <div class="w-fill h-28 m-10 rounded-md flex flex-row justify-between">
                        <div class="flex items-center shadow-md w-5/12 h-28">
                            <div class="flex flex-row items-center">
                                <i class="fas fa-chalkboard-teacher mx-5 text-blue-500"></i>
                                <div class="flex flex-col">
                                    <p class="text-md font-light">Your Teacher Id: </p>
                                    <p class="text-md font-medium"> <?= $data['teacher_id'] ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center shadow-md w-5/12 h-full">
                            <div class="flex flex-row items-center">
                                <i class="fas fa-chalkboard-teacher mx-5 text-blue-500"></i>
                                <div class="flex flex-col">
                                    <p class="text-md font-light">Classes under your supervision:</p>
                                    <p class="text-md font-medium"> <?= $numClasses ?></p>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!--Show teachers class list-->
                    <div class="w-fill h-200 mx-10 mb-14 rounded-md bg-white overflow-y-scroll shadow-md flex flex-col">
                        <div class="border-b-2 w-full h-14 flex pl-5 items-center">
                            <p class="font-medium text-lg">Your Classes</p>
                        </div>
                        <!--The foreach loop to display the classes-->
                        <div class="h-80 overflow-y-auto text-black h-fill">
                            <?php if ($dataClasses) : ?>
                                <?php $counter = 0; ?>
                                <div class="flex flex-row flex-wrap">
                                    <?php foreach ($dataClasses as $class) : ?>
                                        <?php if ($counter % 4 === 0 && $counter !== 0) : ?>
                                </div>
                                <div class="flex flex-row flex-wrap">
                                <?php endif; ?>
                                <li onclick="window.location.href='class.php?class_id=<?= $class['class_id'] ?>'" class="py-4 flex w-1/4 border-2 rounded-md bg-blue-950 text-white shadow-md h-fill items-center cursor-pointer ">
                                    <span class="text-3xl">
                                        <i class="fas fa-chalkboard-teacher text-2xl mx-5 sm:text-md text-white"></i>
                                    </span>
                                    <div class="flex flex-col ml-3 items-center">
                                        <div class="flex flex-col justify-evenly">
                                            <p class="text-lg sm:text-sm font-semibold text-white mr-2"><?= $class['class_name'] ?></p>
                                            <p class="text-sm font-normal text-white"><?= $class['class_code'] ?></p>
                                        </div>
                                    </div>
                                </li>
                                <?php $counter++; ?>
                            <?php endforeach; ?>
                                </div>
                            <?php else : ?>
                                <p class="text-black p-4">No classes found</p>
                            <?php endif; ?>
                        </div>
                    </div>


                    <div class="w-fill h-200 mx-10 mb-14 rounded-md bg-white overflow-y-scroll shadow-md flex flex-col">
                        <div class="border-b-2 w-full h-14 flex pl-5 items-center">
                            <p class="font-medium text-lg">Classes you teach</p>
                        </div>
                        <!--The foreach loop to display the classes-->
                        <div class="h-80 overflow-y-auto text-black h-fill">
                            <?php if ($dataClasses) : ?>
                                <?php $counter = 0; ?>
                                <div class="flex flex-row flex-wrap">
                                    <?php foreach ($dataClasses as $class) : ?>
                                        <?php if ($counter % 4 === 0 && $counter !== 0) : ?>
                                </div>
                                <div class="flex flex-row flex-wrap">
                                <?php endif; ?>
                                <li onclick="window.location.href='class.php?class_id=<?= $class['class_id'] ?>'" class="py-4 flex w-1/4 border-2 rounded-md bg-blue-950 text-white shadow-md h-fill items-center cursor-pointer ">
                                    <span class="text-3xl">
                                        <i class="fas fa-chalkboard-teacher text-2xl mx-5 sm:text-md text-white"></i>
                                    </span>
                                    <div class="flex flex-col ml-3 items-center">
                                        <div class="flex flex-col justify-evenly">
                                            <p class="text-lg sm:text-sm font-semibold text-white mr-2"><?= $class['class_name'] ?></p>
                                            <p class="text-sm font-normal text-white"><?= $class['class_code'] ?></p>
                                        </div>
                                    </div>
                                </li>
                                <?php $counter++; ?>
                            <?php endforeach; ?>
                                </div>
                            <?php else : ?>
                                <p class="text-black p-4">No classes found</p>
                            <?php endif; ?>
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




    <!--The Create a Class Pop up Form-->
    <div id="create-class-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg p-6 w-2/5 h-3/4">
            <div class="mb-10 flex flex-row items-center">
                <i class="text-3xl fas fa-chalkboard-teacher mx-5 text-blue-500"></i>
                <div class="flex flex-col mt-2">
                    <h2 class="text-2xl font-bold ">
                        Create a New Class
                    </h2>
                    <h2 class="mt-1 text-gray-800">
                        Create a new class and make teaching simpler and more efficient
                    </h2>
                </div>

            </div>
            <form method="post">
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2" for="class-name">Class Name*</label>
                    <input required class="border border-gray-400 p-2 w-full rounded-md" type="text" name="class-name" id="class-name">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2 h-2/6" for="class-subject">Class Subjects
                        <i title="To select all the subjects for your class, hold down the Ctrl key and click on each subject you want to include" class="fas fa-question-circle"></i>
                    </label>
                    <select multiple name="class_subjects[]" id="class_subjects" required class="block mt-1 appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2.5 pr-8 rounded leading-tight focus:outline-none focus:border-blue-500 focus:ring-blue-500 focus:ring-1">
                        <option value="" disabled>Select your subject</option>
                        <option value="Literature">Literature</option>
                        <option value="English">English</option>
                        <option value="Biology">Biology</option>
                        <option value="Physics">Physics</option>
                        <option value="Psychology">Psychology</option>
                        <option value="Economics">Economics</option>
                        <option value="Geography">Geography</option>
                        <option value="History">History</option>
                        <option value="Chemistry">Chemistry</option>
                        <option value="Mathematics">Mathematics</option>
                        <option value="Sociology">Sociology</option>
                        <option value="Technology">Technology</option>
                        <option value="Art">Art</option>
                        <option value="Music">Music Studies</option>
                        <option value="PE">Physical Education</option>
                    </select>
                </div>
                <div class="flex flex-row justify-between">
                    <div class="mb-4 w-2/4 mr-2">
                        <label class="block text-gray-700 font-medium mb-2" for="class-subject">Class School*</label>
                        <input required class="border border-gray-400 p-2 w-full rounded-md" value="<?= $dataSchool['school_name'] ?>" type="text" name="class-subject" id="class-subject" readonly>
                    </div>
                    <div class="mb-4 w-2/4 ml-2">
                        <label class="block text-gray-700 font-medium mb-2 " for="class-subject">Class Code
                            <i title="Share this code with your students to allow them acces to your class" class="fas fa-question-circle "></i>
                        </label>
                        <input required class="border text-black border-gray-400 p-2 w-full px-4 rounded-md" type="text" name="class-code" id="classCode" readonly>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2" for="class-subject">Class Head Teacher*</label>
                    <input required class="border border-gray-400 p-2 w-full rounded-md" type="text" value="<?= $data['teacher_name'] . ' ' . $data['teacher_surname']; ?>" name="class-subject" id="class-subject" readonly>
                </div>
                <div class="flex justify-center mt-28">
                    <button type="submit" name="submit_class" class=" w-1/2 bg-blue-500 text-white font-medium py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300 ease-in-out">Create</button>
                    <button type="button" onclick="closeModal()" class=" w-1/2 bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-md hover:bg-gray-400 transition duration-300 ease-in-out ml-4">Cancel</button>
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
            var modal = document.getElementById('create-class-modal');
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
            var modal = document.getElementById('create-class-modal');
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


        //Generate Class Code
        function generateRandomString(length) {
            let result = '';
            let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let charactersLength = characters.length;
            for (let i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        }

        let classCode = generateRandomString(5);
        console.log(classCode);
        document.getElementById("classCode").value = classCode;
    </script>



</body>

</html>