<?php
session_start();
if (isset($_SESSION["StaffId"]) && $_SESSION["StaffType"] === "Admin") {
    $_SESSION["Sidebar"] = "AddStaffs";
    require_once '../Common/DataBaseConnection.php';

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    require_once '../Common/Head.php';

?>

    <body>
        <?php
        require_once 'sidebar.php';
        ?>
        <!-- /# sidebar -->

        <?php
        //require_once 'sidebar.php';
        require_once '../Common/Header.php';
        $conn->begin_transaction();
        if (isset($_REQUEST["Submit"])) {
            $uploadfile = $_FILES["File"]["tmp_name"];
            require_once 'PHPExcel_1.8.0_doc/Classes/PHPExcel.php';
            require_once 'PHPExcel_1.8.0_doc/Classes/PHPExcel/IOFactory.php';
            $objectExcel = PHPExcel_IOFactory::load($uploadfile);
            $query = array();
            $data = array();
            foreach ($objectExcel->getWorksheetIterator() as $worksheet) {

                $highestrow = $worksheet->getHighestRow();
                for ($row = 2; $row < ($highestrow + 1); $row++) {
                    $id = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $first_name = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $middle_name = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $last_name = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $sex = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $faculty = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $department = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $Role = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $Type = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $email = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $phone = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $query[] = $id;
                    $query[] = $first_name;
                    $query[] = $middle_name;
                    $query[] = $last_name;
                    $query[] = $sex;
                    $query[] = $faculty;
                    $query[] = $department;
                    $query[] = $Role;
                    $query[] = $Type;
                    $query[] = $email;
                    $query[] = $phone;

                    $data[] = $email;
                    $data[] = generateRandom(8);
                    $data[] = $id;
                }
            }

            $n = count($query);
            $n = $n / 11;
            $typeOne = 'sssssssssss';
            $values = '(?,?,?, ?, ?, ?, ?, ?,?,?,?)';

            $type = 'sss';
            $val = '(?,?,?)';

            for ($i = 1; $i < $n; $i++) {
                $typeOne = $typeOne . 'sssssssssss';
                $values = $values . ',(?,?,?, ?, ?, ?, ?, ?,?,?,?)';

                $val = $val . ',(?,?,?)';
                $type = $type . 'sss';
            }
            $stmt = $conn->prepare("INSERT INTO staffs (ID, First_Name, Middle_Name,Last_Name,sex,Faculty,Department,Role,Type,Email,Phone) VALUES " . $values);
            $stmt->bind_param($typeOne, ...$query);
            echo 'oooooooooooooooooooooo';
            if ($stmt->execute()) {
                $stmt = $conn->prepare("INSERT INTO users (user_name, password, StaffId) VALUES " . $val);
                $stmt->bind_param($type, ...$data);

                if ($stmt->execute()) {
                    echo 'ppppppppppppppppppppppppppppp';
                    $conn->commit();
                    echo "<script>alert('ALL Users  are Successfully Added')</script>";
                } else {
                    $conn->rollback();
                    echo $stmt->error;
                }
            } else {
                $conn->rollback();
                echo 'failed ' . $stmt->error;
            }
        }
        ?>
        <div class="content-wrap">
            <div class="main">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-8 p-r-0 title-margin-right">
                            <div class="page-header">
                                <div class="page-title">
                                    <h1>
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
                                            <a href="#">Researcher</a>
                                        </li>
                                        <li class="breadcrumb-item active">Submit Document</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <!-- /# column -->
                    </div>
                    <!-- /# row -->
                    <section id="main-content">
                        <div class="row">
                            <!-- /# column -->
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-title">
                                        <h4>Horizontal Form Elements</h4>

                                    </div>
                                    <div class="card-body">
                                        <div class="horizontal-form-elements">
                                            <form action="AddStaffs.php" method="post" enctype="multipart/form-data">
                                                <div class="row">

                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Upload The Proposal</label>
                                                            <div class="col-sm-10">
                                                                <input type="file" name="File" class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-sm-offset-2 col-sm-10">
                                                                <button type="submit" name="Submit" value="Submit" class="btn btn-default">Submit
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- /# column -->


                                                </div>
                                            </form>
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

    </script>
    <?php
    function generateRandom($length = 6)
    {
        $characters = '-/.!#@%^<>,:&*_$?_!0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characterslength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $characterslength - 1)];
        }
        return $randomString;
    }

    ?>