<?php
session_start();
if (isset($_SESSION["StaffId"])&&$_SESSION['StaffType']==='RCC')
{
$conn = new mysqli("localhost", "root", "", "repository");
$_SESSION["Sidebar"]="Home";
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

if (isset($_GET["Action"])) {
    $conn->begin_transaction();
    $StaffID = $_GET["StaffId"];
    $Action = $_GET["Action"];
    $stmt = $conn->prepare("UPDATE staffs  set Status=? where ID=?");
    $stmt->bind_param("ss", $Action, $StaffID);
    if ($stmt->execute()) {
        $Password = generateRandom(6);
        $stmt = $conn->prepare("UPDATE  users set  password=? where StaffId=?");
        $stmt->bind_param("ss", $Password, $StaffID);
        if ($stmt->execute()) {
            $conn->commit();
            if ($Action === 'Activated') {
                echo '<script>alert("User Account SuccessFully Activated")</script>';
            } else {
                echo '<script>alert("User Account SuccessFully Deactivated")</script>';
            }

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
                                <h4>Account Manage</h4>

                            </div>
                            <div class="card-body">
                                <div class="horizontal-form-elements">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <label class="control-label">Select Type</label>
                                                <select class="form-control" id="Type" name="Type" required>
                                                    <option value="0">Select Type</option>
                                                    <option value="Academic">Academic</option>
                                                    <option value="Student">Student</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <label class="control-label">Select Faculty</label>
                                                <select class="form-control" onchange="selectDepartment(this.value)"
                                                        name="Faculty" id="faculty">
                                                    <option value="0">Select Faculty</option>
                                                    <?php
                                                    $query = 'Select * from faculty';
                                                    $stmt = $conn->query($query);
                                                    if ($stmt->num_rows > 0) {
                                                        while ($data = $stmt->fetch_assoc()) {
                                                            ?>
                                                            <option value="<?php echo $data['Name'] ?>"><?php echo $data['Name'] ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <div class="col-lg-12">
                                                <label class="control-label">Select Department</label>
                                                <select class="form-control" onchange="SelectDocuments(this.value)"
                                                        id="dep"
                                                        name="Department" required>
                                                    <option value="0">Select Faculty First</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-light">
                                            <tr>
                                                <th>
                                                    <center>ID</center>
                                                </th>
                                                <th>
                                                    <center>First Name</center>
                                                </th>
                                                <th>
                                                    <center>Middle Name</center>
                                                </th>
                                                <th>
                                                    <center>Last Name</center>
                                                </th>
                                                <th>
                                                    <center>User_Name</center>
                                                </th>
                                                <th>
                                                    <center>Password</center>
                                                </th>
                                                <th>
                                                    <center>Staffs</center>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody id="tbody">


                                            </tbody>

                                        </table>
                                    </div>
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
    function SelectDocuments(v = '') {

        var Faculty = document.getElementById('faculty').value;
        var Department = document.getElementById('dep').value;
        var Type = document.getElementById('Type').value;
        // alert(Faculty+' '+Department);
        $.ajax({
            type: 'POST',
            url: 'Process?Select_Users=Select_Users&&Faculty=' + Faculty + '&&Department=' + Department + '&&Type=' + Type + '&&v=' + v,

            success: function (html) {
                $('#tbody').html(html);
            }
        });
    }

    function selectDepartment(v = '') {
        // alert(v);
        $.ajax({
            type: 'POST',
            url: 'Process?selectDepartment=dep&&Faculty=' + v,

            success: function (html) {
                $('#dep').html(html);
            }
        });
    }

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
