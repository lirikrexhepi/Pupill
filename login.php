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

    <link rel="icon" href="resources/icons/appLogo.svg" type="image/png">
</head>

<body>


    <div class="flex md:container md:mx-auto mt-14">
        <div class="w-2/3">
            <p onclick="window.location.href='index.html'" class="text-3xl font-bold bg-gradient-to-r from-blue-500 to-black text-transparent bg-clip-text cursor-pointer inline-block">pupill</p>
        </div>
    </div>


    <div class="flex w-screen">
        <div class="flex flex-col container mx-auto max-w-screen-md px-8 mt-40 w-full justify-evenly ">
            <p style=" font-family: 'Inter' , sans-serif;" class="font-semibold text-3xl">Welocme back</p>
            <p style="font-family: 'Inter', sans-serif;" class="font-normal text-md text-slate-400 mt-2">Welcome back! Please enter your details.</p>


            <!--The code for the Alerts-->
            <?php
            session_start();
            if (isset($_SESSION['error'])) {
            ?>
                <div class="bg-red-100 border border-red-400 text-red-700 mt-5 px-4 py-3 rounded relative w-2/3" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline"><?php echo $_SESSION['error']; ?></span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Close</title>
                            <path d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 6.066 4.652a1 1 0 10-1.414 1.414L8.586 10l-3.934 3.934a1 1 0 101.414 1.414L10 11.414l3.934 3.934a1 1 0 001.414-1.414L11.414 10l3.934-3.934a1 1 0 000-1.414z" />
                        </svg>
                    </span>
                </div>
            <?php
                unset($_SESSION['error']);
            }
            ?>




            <div class="mt-10">
                <form action="php_logic/login-logic.php" method="Post">
                    <div class="w-2/3 mb-5">
                        <p style="font-family: 'Inter', sans-serif;" class="font-medium text-md">Select your role</p>
                        <div class="flex flex-row justify-evenly">
                            <div tabindex="0" onclick="document.querySelector('#school').checked = true; showForm('school');" class="mt-1 mr-3 px-3 py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-blue-500 block w-1/2 rounded-md sm:text-sm focus:ring-1">
                                <p>School</p>
                                <input value="school" id="school" name="role" type="radio" checked>
                                <i class="fas fa-school"></i>
                            </div>

                            <div tabindex="0" onclick="document.querySelector('#teacher').checked = true; showForm('teacher');" class="mt-1 mr-3  px-3 py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-blue-500 block w-1/2 rounded-md sm:text-sm focus:ring-1">
                                <p>Teacher</p>
                                <input value="teacher" id="teacher" name="role" type="radio">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>

                            <div tabindex="0" onclick="document.querySelector('#student').checked = true; showForm('student');" class="mt-1 px-3 mr-3 py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-blue-500 block w-1/2 rounded-md sm:text-sm focus:ring-1">
                                <p>Student</p>
                                <input value="student" id="student" name="role" type="radio">
                                <i class="fas fa-graduation-cap"></i>
                            </div>

                            <div tabindex="0" onclick="document.querySelector('#parent').checked = true; showForm('parent');" class="mt-1 px-3  py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-blue-500 block w-1/2 rounded-md sm:text-sm focus:ring-1">
                                <p>Parent</p>
                                <input value="parent" id="parent" name="role" type="radio">
                                <i class="fa fa-user-friends"></i>
                            </div>
                        </div>
                    </div>


                    <div>
                        <p style="font-family: 'Inter', sans-serif;" class="font-medium text-md">Email</p>
                        <input name="email" class=" mt-1 px-3 py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-2/3 rounded-md sm:text-sm focus:ring-1" type="email" placeholder="Enter your email" required>
                    </div>

                    <div class="mt-5">
                        <p style="font-family: 'Inter', sans-serif;" class="font-medium text-md">Password</p>
                        <input name="password" class="mt-1 px-3 py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-2/3 rounded-md sm:text-sm focus:ring-1" type="password" placeholder="Enter your password" required>
                    </div>

                    <input name="signIn-button" type="submit" value="Sign In" class="mt-5 text-white font-medium rounded-md py-1 px-4 border-2 border-solid cursor-pointer w-2/3 border-blue-500 bg-blue-500">
                </form>
                <div class="w-2/3 flex justify-center mt-5">
                    <p style="font-family: 'Inter', sans-serif;">Don't have an account yet? <a href="signup.php" class="text-blue-600 font-semibold">Sign Up</a></p>
                </div>
            </div>
        </div>

        <div class="flex w-1/3 transform: translate(-50%, -50%); z-index: -1; mt-40">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 550 550" width="50%" id="blobSvg" style="opacity: 1;">
                <defs>
                    <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" style="stop-color: rgb(59, 129, 254);"></stop>
                        <stop offset="100%" style="stop-color: rgb(114, 97, 239);"></stop>
                    </linearGradient>
                </defs>
                <path id="blob" fill="url(#gradient)" style="opacity: 1;">
                    <animate attributeName="d" dur="25000ms" repeatCount="indefinite" values="M409.06419,322.5266Q395.0532,395.0532,322.5266,445.11739Q250,495.18159,163.51944,459.07135Q77.03888,422.96112,82.39949,336.48056Q87.7601,250,115.64271,196.76266Q143.52532,143.52532,196.76266,76.83657Q250,10.14783,323.24578,56.82813Q396.49156,103.50844,409.78338,176.75422Q423.07519,250,409.06419,322.5266Z;M446.86448,329.36764Q408.73529,408.73529,329.36764,419.76576Q250,430.79624,166.60504,423.79308Q83.21008,416.78992,69.36975,333.39496Q55.52942,250,96.13341,193.3687Q136.7374,136.7374,193.3687,119.10083Q250,101.46426,313.50105,112.23108Q377.00211,122.99789,430.99789,186.49895Q484.99368,250,446.86448,329.36764Z;M423.42552,332.41134Q414.82268,414.82268,332.41134,424.30554Q250,433.78841,170.96572,420.92848Q91.93144,408.06856,46.07152,329.03428Q0.21159,250,66.88003,191.77423Q133.54846,133.54846,191.77423,102.82861Q250,72.10876,305.00592,106.04846Q360.01185,139.98815,396.0201,194.99408Q432.02836,250,423.42552,332.41134Z;M421.63508,307.39005Q364.7801,364.7801,307.39005,427.43403Q250,490.08796,191.6822,428.36178Q133.3644,366.6356,70.9089,308.3178Q8.4534,250,54.21728,174.99058Q99.98115,99.98115,174.99058,81.49686Q250,63.01257,330.66021,75.84607Q411.32042,88.67958,444.90524,169.33979Q478.49006,250,421.63508,307.39005Z;M395.5,320Q390,390,320,400Q250,410,172,408Q94,406,59,328Q24,250,70.5,183.5Q117,117,183.5,108Q250,99,335,89.5Q420,80,410.5,165Q401,250,395.5,320Z;M449.66467,329.57458Q409.14917,409.14917,329.57458,407.97733Q250,406.80549,191.3735,387.02924Q132.74701,367.25299,77.06026,308.6265Q21.3735,250,49.05191,163.36516Q76.73032,76.73032,163.36516,85.537Q250,94.34367,322.00775,100.16408Q394.01551,105.98449,442.09784,177.99225Q490.18018,250,449.66467,329.57458Z;M409.06419,322.5266Q395.0532,395.0532,322.5266,445.11739Q250,495.18159,163.51944,459.07135Q77.03888,422.96112,82.39949,336.48056Q87.7601,250,115.64271,196.76266Q143.52532,143.52532,196.76266,76.83657Q250,10.14783,323.24578,56.82813Q396.49156,103.50844,409.78338,176.75422Q423.07519,250,409.06419,322.5266Z">
                    </animate>
                </path>
            </svg>
        </div>
    </div>


    <p style="font-family: 'Inter', sans-serif;" class="font-normal text-md text-slate-400 mt-2 absolute bottom-10 left-20">© Pupill 2023</p>





</body>

</html>