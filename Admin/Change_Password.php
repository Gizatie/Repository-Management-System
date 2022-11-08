<?php

use PHPMailer\PHPMailer\PHPMailer;

session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Department' || $_SESSION['StaffType'] === 'RCC' || $_SESSION['StaffType'] === 'RCD' || $_SESSION['StaffType'] === 'Researcher'  || $_SESSION['StaffType'] === 'Faculty'|| $_SESSION['StaffType'] === 'Admin') {
    $_SESSION["Sidebar"] = "Change_Password";
    require_once '../Common/DataBaseConnection.php';

    require_once '../Common/Head.php';
    $currentUserType = $_SESSION['StaffType'];
    // switch ($currentUserType) {
    //     case 'Researcher':
    //         require_once '../Researcher/sidebar.php';
    //         break;
    //     case 'Department':
    //         require_once '../Department/sidebar.php';
    //         break;
    //     case 'RCC':
    //         require_once '../RCC/sidebar.php';
    //         break;
    //     case 'RCD':
    //         require_once '../RCD/sidebar.php';
    //         break;
    //     case 'Faculty':
    //         require_once '../Faculty/sidebar.php';
    //         break;
    //     case 'Admin':
    //         require_once '../Admin/sidebar.php';
    //         break;
    // }
    // require_once '../Common/Header.php';
    if (isset($_POST["Submit"])) {

        $OldPassword = $_POST["OldPassword"];
        $New_Password = $_POST["New_Password"];
        $Repeat_New_Password = $_POST["Repeat_New_Password"];
        $userName = $_SESSION["UserName"];
        $staffID = $_SESSION["StaffId"];
        $accessTime = "Regular";
        if ($New_Password === $Repeat_New_Password) {
            $password = md5($Repeat_New_Password);
            $stmt = $conn->prepare("UPDATE users set password=?,Access_Time=? where user_name=? and StaffId=?");
            $stmt->bind_param("ssss", $password, $accessTime, $userName, $staffID);
            if ($stmt->execute()) {
                echo '<script type="text/javascript">';
                echo 'alert("Successfully Updated Click OK to Login again");';
                echo 'window.location.href = "http://localhost/system/page-login.php";';
                echo '</script>';
            } else {
                echo "<script>alert('Failed to  Updated')</script>";
            }
        }
    }
?>
<div class=" ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="float-left">
                    <div class="hamburger sidebar-toggle">
                        
                    </div>
                </div>
                <div class="float-right">
                    <div class="dropdown dib">
                        <div class="header-icon" data-toggle="dropdown">
                                <span class="user-avatar">ssss<?php echo  $_SESSION["UserName"];?>
                                    <i class="ti-angle-down f-s-10"></i>
                                </span>
                            <div class="drop-down dropdown-profile dropdown-menu dropdown-menu-right">

                                <div class="dropdown-content-body">
                                    <ul>
                                        <li>
                                            <a href="../page-login.php?action=logout"">
                                                <i class="ti-user"></i>
                                                <span>Profile</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="../page-login.php?action=logout">
                                                Logout
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <body>
        <div class="content-wrap">
            <div class="main">
                <div class="container-fluid">
                    <!-- /# row -->
                    <section id="main-content">
                        
                        <div class="row">
                            <!-- /# column -->
                            <div class="col-lg-3">

                            </div>
                            <div class="col-lg-7 ">
                                <div class="card">
                                    <div class="card-title">
                                        <h4> Change your Password to access the futures</h4>

                                    </div>
                                    <div class="card-body">
                                        <div class="horizontal-form-elements">
                                            <form action="Change_Password.php" id="Acount" method="post" enctype="multipart/form-data">
                                                <div class="row">

                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Enter Old Password</label>
                                                            <input type="text" name="OldPassword" id="OldPassword" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Enter New Password</label>
                                                            <input type="password" required name="New_Password" id="New_Password" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label">Repeat New Password</label>
                                                            <input type="password" required name="Repeat_New_Password" id="Repeat_New_Password" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-sm-offset-2 col-sm-10">
                                                                <button type="submit" name="Submit" value="Submit" class="btn btn-default">Submit
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </form>
                                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"> </script>
                                            <script type="text/javascript"> </script>
                                            <!-- /# column -->
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- /# card -->
                        </div>

                    </section>
                    <!-- /# column -->
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div id="extra-area-chart"></div>
                        <div id="morris-line-chart"></div>
                        <div class="footer">
                            <p>2018 © Admin Board. -
                                <a href="#">example.com</a>
                            </p>
                        </div>
                    </div>
                </div>
                </section>
            </div>
        </div>


    <?php require_once '../Common/Footer.php';
} else {
    header("Location: ../page-login.php");
}

    ?>
    </body>

    </html>

    <script>
        function Participants(v = '') {

            let par = '';
            var fff = document.getElementById("par");

            if (v > 0) {
                fff.innerHTML = '';
                for (let i = 0; i < v; i++) {
                    fff.innerHTML += '<div class="form-group"><label class="control-label">Text Input</label><input type="text" class="form-control" name="participant_' + (1 + i) + '"></div>'
                }
            }
        }

        function selectDepartment(v = '') {
            // alert(v);
            $.ajax({
                type: 'POST',
                url: 'Process.php?selectDepartment=dep&&Faculty=' + v,

                success: function(html) {
                    $('#dep').html(html);
                }
            });
        }
    </script>

    <?php
    function generateRandom($length = 6)
    {
        $characters = '-/.!#@%^?_!0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characterslength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $characterslength - 1)];
        }
        return $randomString;
    }

    ?>