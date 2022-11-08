<?php
use PHPMailer\PHPMailer\PHPMailer;
session_start();
if (isset($_SESSION["StaffId"]) && $_SESSION["StaffType"] === "Admin")
{
    $_SESSION["Sidebar"] = "CreateAccount";
    require_once '../Common/DataBaseConnection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
require_once '../Common/Head.php';
?>
<body>
<?php
//require_once 'sidebar.php';
?>
<!-- /# sidebar -->

<?php

require_once 'sidebar.php';
require_once '../Common/Header.php';

if (isset($_POST["Submit"])) {

    $ID = $_POST["ID"];
    $First_Name = $_POST["First_Name"];
    $Middle_Name = $_POST["Middle_Name"];
    $Last_Name = $_POST["Last_Name"];
    $Sex = $_POST["Sex"];
    $Faculty = $_POST["Faculty"];
    $Department = $_POST["Department"];
    $Role = $_POST["Role"];
    $Type = $_POST["Type"];
    $Email = $_POST["Email"];
    $Phone = $_POST["Phone"];
    $Status = '';
    $conn->begin_transaction();
    $stmt = $conn->prepare("INSERT INTO staffs (ID, First_Name, Middle_Name,Last_Name,sex,Faculty,Department,Role,Type,Email,Phone) VALUES (?, ?, ?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssssssss", $ID, $First_Name, $Middle_Name, $Last_Name, $Sex, $Faculty, $Department, $Role, $Type, $Email, $Phone);
    if ($stmt->execute()) {
        $Password = generateRandom(6);
        $stmt = $conn->prepare("INSERT INTO users (user_name, password,StaffId) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $Email, $Password, $ID);
        if ($stmt->execute()) {
            require_once "PHPMailer/PHPMailer.php";
            require_once "PHPMailer/SMTP.php";
            require_once "PHPMailer/Exception.php";

            $Mail=new PHPMailer();
//            SMTP Setting
            $Mail->isSMTP();
            $Mail->Host="smtp.gmail.com";
            $Mail->SMTPAuth=true;
            $Mail->Username="yayasoles@gmail.com";
            $Mail->Password="yayasoles@1984";
            $Mail->Port=465;
            $Mail->SMTPSecure="ssl";
//            email setting
            $Mail->isHTML(true);
            $Mail->setFrom($Email,$First_Name);
            $Mail->addAddress("yayasoles@gmail.com");
            $Mail->Subject=("$Email(Subject)");
            $Mail->Body="User Name is : ".$Email." and your Password is ".$Password;
            if ($Mail->send()){
                $status="success";
                $response="email is sent";
                $conn->commit();
//                echo '<script>alert("User Account SuccessFully Created")</script>';
            }else{
                $status="failed";
                $response="email is wrong".$Mail->ErrorInfo.$Mail->ErrorInfo;
                echo '<script>alert("User Account Failed Because Email Sending")</script>';
            }
exit(json_encode(array("status"=>$status,"response" => $response)));
        } else {
            $Status = 'Failed Account Creation';
        }
    } else {
        $Status = 'Failed Account Creation';
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
                                <li class="breadcrumb-item active">Submit_Proposal</li>
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
                                    <form action="CreateAccount.php" id="Acount" method="post" enctype="multipart/form-data">
                                        <div class="row">

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="control-label">Enter Employee ID</label>
                                                    <input type="text" required name="ID" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Enter First Name</label>
                                                    <input type="text" required name="First_Name" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Enter Middle Name</label>
                                                    <input type="text" required name="Middle_Name" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Enter Last Name</label>
                                                    <input type="text" required name="Last_Name" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Select Sex</label>
                                                    <select class="form-control" name="Sex" required>
                                                        <option value="0">Select Sex</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Enter User Type</label>
                                                    <select class="form-control" onchange="SelectType(this.value)" name="Type" required>
                                                        <option value="0">Select User Type</option>
                                                        <option value="Academic">Academic</option>
                                                        <option value="administrative">Administrative</option>
                                                        <option value="Student">Student</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Select Faculty<small>(for Academic Staffs Only)</small></label>
                                                    <select class="form-control" onchange="selectDepartment(this.value)" name="Faculty">
                                                        <option value="0">Select Faculty</option>
                                                <?php
                                                $query = 'Select * from faculty';
                                                $stmt = $conn->query($query);
                                                echo 'nnnnnnnnnnnnnnnnnnnnnnnnnnn '. $stmt->num_rows;
                                                if ($stmt->num_rows>0){
                                                    while ($data=$stmt->fetch_assoc()){
                                                        ?>
                                                        <option value="<?php echo $data['Name']?>"><?php echo $data['Name']?></option>
                                                <?php
                                                    }
                                                }
                                                ?>

                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Select Department</label>
                                                    <select class="form-control" id="dep" name="Department" required>
                                                        <option value="0">Select Faculty First</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Select Role</label>
                                                    <select class="form-control" name="Role" required>
                                                        <option value="Researcher">Select Role</option>
                                                        <option value="Researcher">Instructor</option>
                                                        <option value="Department">HOD</option>
                                                        <option value="RCC">RCC</option>
                                                        <option value="RCD">RCD</option>
                                                        <option value="Admin">Admin</option>
                                                        <option value="Student">Student</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Enter Email</label>
                                                    <input type="email" required name="Email" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Enter Phone Number</label>
                                                    <input type="number" required name="Phone" class="form-control">
                                                </div>
                                            </div>
                                            <!-- /# column -->
                                            <div class="col-lg-6">
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" name="Submit" value="Submit"
                                                            class="btn btn-default">Submit
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"> </script>
                                    <script type="text/javascript" > </script>
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
    }function selectDepartment(v='') {
        // alert(v);
        $.ajax({
            type: 'POST',
            url: 'Process.php?selectDepartment=dep&&Faculty='+v,

            success: function (html) {
                $('#dep').html(html);
            }
        });
    }
    function SelectType(v='') {
        $.ajax({
            type: 'POST',
            url: 'Process.php?selectType=dep&&Type='+v,

            success: function (html) {
                $('#dep').html(html);
            }
        });
    }
</script>

<?php
function generateRandom($length=6){
    $characters='-/.!#@%^?_!0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characterslength=strlen($characters);
    $randomString='';
    for ($i=0;$i<$length;$i++){
        $randomString.=$characters[rand(0,$characterslength-1)];
    }
    return$randomString;
}

?>
