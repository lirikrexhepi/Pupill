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

$class_id = $_GET['class_id'];

//Select the class teacher
$sql = "SELECT * FROM teachers WHERE teacher_id = ? ";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $_SESSION['id']);
$stmt->execute();
$data = $stmt->fetch();


//Select the classes
$sql = "SELECT * FROM classes WHERE class_id = ?  ";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $class_id);
$stmt->execute();
$dataClass = $stmt->fetch();



//Select the classes
$sql = "SELECT * FROM students WHERE student_class = ?  ";
$stmtS = $con->prepare($sql);
$stmtS->bindParam(1, $class_id);
$stmtS->execute();
$dataClassStudents = $stmtS->fetchAll();
$numStudents = $stmtS->rowCount();


//Select the grades
$sql = "SELECT * FROM grades WHERE class_id = ?";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $class_id);
$stmt->execute();
$grades = $stmt->fetchAll();

//Count number of students

//Select the subjects
$sql = "SELECT * FROM class_subjects WHERE class_id = ?  ";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $class_id);
$stmt->execute();
$dataClassSubjects = $stmt->fetchAll();



//Assign Homework
//Assign Homework
if (isset($_POST['assign_homework'])) {
    $homework_title = $_POST['homework_title'];
    $homework_description = $_POST['homework_description'];
    $homework_teacher = $_SESSION['id'];
    $homework_subject = $_POST['homework_subject'];
    $homework_ClassID = $class_id;
    $created_at = date('Y-m-d H:i:s');
    $due_at = date('Y-m-d H:i:s', strtotime('+1 week'));
    $homework_file = null;

    if (isset($_FILES['homework_file']) && $_FILES['homework_file']['error'] == 0) {
        $allowed_extensions = array('jpg', 'jpeg', 'png');
        $file_extension = strtolower(pathinfo($_FILES['homework_file']['name'], PATHINFO_EXTENSION));
        if (in_array($file_extension, $allowed_extensions)) {
            $target_dir = "C:/xampp/htdocs/pupill/resources/uploaded_files/";
            $target_file = $target_dir . $homework_title . '.' . $file_extension;

            if (move_uploaded_file($_FILES["homework_file"]["tmp_name"], $target_file)) {
                $homework_file = $target_file;
            } else {
                // Failed to upload file
            }
        } else {
            // Invalid file type
        }
    }

    $stmt = $con->prepare("INSERT INTO homework(homework_title, homework_description, homework_teacher, homework_subject, homework_ClassID, homework_file, created_at, due_at) VALUES (:title, :description, :teacher, :subject, :classID, :file, :created_at, :due_at)");

    $stmt->bindParam(':title', $homework_title);
    $stmt->bindParam(':description', $homework_description);
    $stmt->bindParam(':teacher', $homework_teacher);
    $stmt->bindParam(':subject', $homework_subject);
    $stmt->bindParam(':classID', $homework_ClassID);
    $stmt->bindParam(':file', $homework_file);
    $stmt->bindParam(':created_at', $created_at);
    $stmt->bindParam(':due_at', $due_at);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $homework_id = $con->lastInsertId();
    } else {
        // Failed to insert data into database
    }
}



//Assign teachers to classes
if (isset($_POST['assign_teachers'])) {
    foreach ($dataClassSubjects as $subject) {
        $teacher_id = $_POST[$subject['subject']];
        $query = "UPDATE class_subjects SET teacher_id = :teacher_id WHERE class_id = :class_id AND subject = :subject";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);
        $stmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);
        $stmt->bindParam(':subject', $subject['subject'], PDO::PARAM_STR);
        $stmt->execute();
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
                <span class="ml-10 font-bold text-xl text-gray-700">Class <?= $dataClass['class_name'] ?></span>
                <div>
                    <button onclick="openHomework()" class="text-white bg-blue-500 mr-4 font-medium rounded-md py-1 px-4 border-2 border-solid border-blue-500 hover:border-blue-600 hover:bg-blue-600 transition duration-300 ease-in-out">Assign Homework +</button>
                    <button onclick="openModal()" class="text-white bg-blue-500 mr-14 font-medium rounded-md py-1 px-4 border-2 border-solid border-blue-500 hover:border-blue-600 hover:bg-blue-600 transition duration-300 ease-in-out">Assign Teachers +</button>
                </div>
            </div>


            <!--The actual dashboard content-->
            <div class="flex flex-row w-full h-screen min-h-screen overflow-scroll overflow-x-hidden">
                <div class="flex flex-col w-3/4 h-full bg-slate-50">

                    <!--Teacher Class Stats-->
                    <div class="w-fill h-28 m-10 rounded-md flex flex-row justify-between">
                        <div class="flex items-center shadow-md w-5/12 h-28">
                            <div class="flex flex-row items-center">
                                <i class="fas fa-chalkboard-teacher mx-5 text-blue-500"></i>
                                <div class="flex flex-col">
                                    <p class="text-md font-light">Class Code: </p>
                                    <p class="text-md font-medium"> <?= $dataClass['class_code'] ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center shadow-md w-5/12 h-full">
                            <div class="flex flex-row items-center">
                                <i class="fas fa-chalkboard-teacher mx-5 text-blue-500"></i>
                                <div class="flex flex-col">
                                    <p class="text-md font-light">Class Student Count</p>
                                    <p class="text-md font-medium"> <?= $numStudents ?></p>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!---------------------------------------------------The class table------------------------------------------------->
                    <?php if ($numStudents > 0) : ?>
                        <div class="border rounded-lg overflow-hidden h-80 mx-10">
                            <table>
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th>Grades</th> <!-- empty top-left cell -->
                                        <?php foreach ($dataClassSubjects as $subject) : ?>
                                            <th class="h-36 w-20 transform -rotate-90 origin-bottom-left border-l border-t border-gray-300 p-2 overflow-hidden"><?php echo $subject['subject']; ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dataClassStudents as $student) : ?>
                                        <tr>
                                            <td class="border-l border-t border-gray-300 p-2"><?php echo $student['student_name'] . ' ' . $student['student_surname']; ?></td>
                                            <?php foreach ($dataClassSubjects as $subject) : ?>
                                                <?php
                                                $grade = '';
                                                foreach ($grades as $g) {
                                                    if ($g['student_id'] == $student['student_id'] && $g['subject'] == $subject['subject']) {
                                                        $grade = $g['grade_value'];
                                                        break;
                                                    }
                                                }
                                                ?>
                                                <td class="border-l border-t border-gray-300 p-2" contenteditable="true"><?php echo $grade; ?></td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <p class="self-center mt-20">No students in class</p>
                    <?php endif; ?>





                </div>


                <!-----------------------------------------------The calendar Div------------------------------------------>
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
                        Assign Teachers
                    </h2>
                    <h2 class="mt-1 text-gray-800">
                        Assign all teachers to their respective subjects they teach
                    </h2>
                </div>

            </div>
            <form class="flex flex-col justify-between h-4/5" method="post">
                <div class="h-3/4 overflow-y-scroll overflow-x-hidden">
                    <?php
                    if (empty($dataClassSubjects)) {
                        echo '<div class="bg-red-100 border border-red-400 mb-10 text-red-700 mt-5 px-4 py-3 rounded relative w-3/3" role="alert">';
                        echo '<strong class="font-bold">Error! </strong>';
                        echo '<span class="block sm:inline">No subjects found.</span>';
                        echo '<span class="absolute top-0 bottom-0 right-0 px-4 py-3">';
                        echo '<svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">';
                        echo '<title>Close</title>';
                        echo '<path d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 6.066 4.652a1 1 0 10-1.414 1.414L8.586 10l-3.934 3.934a1 1 0 101.414 1.414L10 11.414l3.934 3.934a1 1 0 001.414-1.414L11.414 10l3.934-3.934a1 1 0 000-1.414z" />';
                        echo '</svg>';
                        echo '</span>';
                        echo '</div>';
                    } else {
                        foreach ($dataClassSubjects as $subject) {
                            echo '<div class="mb-4">';
                            echo '<label class="block text-gray-700 font-medium mb-2" for="' . $subject['subject'] . '">' . $subject['subject'] . '</label>';
                            echo '<input placeholder="Enter teacher id here" required class="border border-gray-400 p-2 w-full rounded-md" type="text" name="' . $subject['subject'] . '" id="' . $subject['subject'] . '">';
                            echo '</div>';
                        }
                    }
                    ?>


                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2" for="class-headTeacher">Class Head Teacher*</label>
                        <input required class="border border-gray-400 p-2 w-full rounded-md" type="text" value="<?= $data['teacher_name'] . ' ' . $data['teacher_surname']; ?>" name="class-headTeacher" id="class-subject" readonly>
                    </div>
                </div>
                <div class="flex justify-center ">
                    <button type="submit" name="assign_teachers" class=" w-1/2 bg-blue-500 text-white font-medium py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300 ease-in-out">Assign</button>
                    <button type="button" onclick="closeModal()" class=" w-1/2 bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-md hover:bg-gray-400 transition duration-300 ease-in-out ml-4">Cancel</button>
                </div>
            </form>
        </div>
    </div>


    <!--The Create a Homework Pop up Form-->
    <div id="create-homework-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg p-6 w-2/5 h-3/4">
            <div class="mb-10 flex flex-row items-center">
                <i class="text-3xl fas fa-chalkboard-teacher mx-5 text-blue-500"></i>
                <div class="flex flex-col mt-2">
                    <h2 class="text-2xl font-bold ">
                        Assign Homework
                    </h2>
                    <h2 class="mt-1 text-gray-800">
                        Assign homework to students in a class and track their progress.
                    </h2>
                </div>

            </div>
            <form class="flex flex-col justify-between" method="post">
                <div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2" for="homework_title">Homework Title*</label>
                        <input required class="border border-gray-400 p-2 w-full rounded-md" type="text" name="homework_title" id="homework_title">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2" for="homework_description">Description</label>
                        <textarea required class="border border-gray-400 p-2 w-full rounded-md h-32 overflow-y-scroll" style="resize:none;" name="homework_description" id="homework_description"></textarea>
                    </div>


                    <div class="flex flex-row justify-between">
                        <div class="mb-4 w-2/4 ">
                            <label class=" block text-gray-700 font-medium mb-2" for="homework_teacher">Teacher*</label>
                            <input required class="border border-gray-400 p-2 w-full rounded-md" type="text" value="<?= $data['teacher_name'] . ' ' . $data['teacher_surname']; ?>" name="class-subject" id="class-subject" readonly>
                        </div>

                        <div class="mb-4 w-2/4 ml-2">
                            <label class="block text-gray-700 font-medium mb-2 " for="homework_subject">Homework Subject</label>
                            <input required class="border text-black border-gray-400 p-2 w-full px-4 rounded-md" value="<?= $data['teacher_subject'] ?>" type="text" name="homework_subject" id="homework_subject" readonly>
                        </div>
                    </div>

                    <div class=" mb-4 ">
                        <label class=" block text-gray-700 font-medium mb-2" for="homework_file">Upload File or Picture</label>
                        <input class="border border-gray-400 p-2 w-full rounded-md" type="file" name="homework_file" id="homework_file" enctype="multipart/form-data">
                    </div>
                </div>

                <div class="flex justify-center mt-14">
                    <button type="submit" name="assign_homework" class=" w-1/2 bg-blue-500 text-white font-medium py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300 ease-in-out">Assign</button>
                    <button type="button" onclick="closeHomework()" class=" w-1/2 bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-md hover:bg-gray-400 transition duration-300 ease-in-out ml-4">Cancel</button>
                </div>
            </form>
        </div>
    </div>





    <script>
        function showDropdown() {
            var dropdown = document.querySelector(".absolute");
            dropdown.classList.toggle("hidden");
        }


        //The code to open the popup to asign teachers
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

        //The code to open the popup to asign teachers
        function openHomework() {
            var modal = document.getElementById('create-homework-modal');
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

        function closeHomework() {
            var modal = document.getElementById('create-homework-modal');
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
    </script>



</body>

</html>