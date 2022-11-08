<!DOCTYPE html>
<html lang="en">
<?php
session_start();
// echo "ddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd";
if (true) {
    require_once '../Common/Head.php' ?>

    <body>

        <?php require_once 'sidebar.php';
        require_once '../Common/Header.php';
        ?>
        <div class="content-wrap">
            <div class="main">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-8 p-r-0 title-margin-right">
                            <div class="page-header">
                                <div class="page-title">
                                    <h1>Hello,
                                        <span>Welcome Here</span>
                                    </h1>
                                </div>
                            </div>
                        </div>
                        <!-- /# column -->
                        <div class="col-lg-4 p-l-0 title-margin-left">
                            <div class="page-header">
                                <div class="page-title">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="#">Dashboard</a>
                                        </li>
                                        <li class="breadcrumb-item active">UI-Blank</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <!-- /# column -->
                    </div>
                    <!-- /# row -->
                    <section id="main-content">
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
        </div>






    <?php require_once '../Common/Footer.php';
} else {
    header("Location: ../page-login.php");
}
    ?>





























    <!--  Dashboard 1 -->
    <script src="assets/js/dashboard1.js"></script>
    <script src="assets/js/dashboard2.js"></script>

    </body>

</html>