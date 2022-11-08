<?php
session_start();
if (isset($_SESSION["StaffId"]))
{
$conn = new mysqli("localhost", "root", "", "repository");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
require_once '../Common/Head.php';
?>
<body>
<?php require_once 'sidebar.php'?>
<!-- /# sidebar -->

<?php
//require_once 'sidebar.php';
require_once '../Common/Header.php';

if (isset($_POST["Submit"])) {
    $Title = $_POST["Title"];
    $Type = $_POST["Type"];
    $name=rand(1000,10000)."-".$_FILES['File']['name'];
    $Abstract = $_POST["Abstract"];
    $Faculty = $_SESSION["Faculty"];
    $Department = $_SESSION["Department"];

//to be get from the session which is added  during login to his home page
    $user_id = $_SESSION["StaffId"];
    $NumberofParticipants = (int)$_POST["participants"];
    $conn->begin_transaction();
    $stmt = $conn->prepare("INSERT INTO proposal (Title, Type, File,Abstract,Faculty,Department) VALUES (?, ?, ?,?,?,?)");
    $stmt->bind_param("ssssss", $Title, $Type, $name, $Abstract,$Faculty,$Department);
    $stmt->execute();
    $query = "SELECT LAST_INSERT_ID() as last";
    $result = $conn->query($query);
    $result = $result->fetch_assoc();
    $last = (int)$result["last"];
    $file=$_FILES['File'];

    $tname=$_FILES["File"]["tmp_name"];
    $size=$_FILES["File"]["size"];
    $file_type=$_FILES["File"]["type"];
    echo 'kkkkkkkkkkkkkkkkkkkkkkkkkkkkk hello';
    if ($last != 0) {
        $status = "";
        $moved = "";
        $role = "";
        $num = 0;
        switch  ($Type){
            case "Research":
                move_uploaded_file($tname,'../Documents/Research/Proposal/'.$name);
                $moved='Successful';
                break;
            case 'Technology Transfer':
                move_uploaded_file($tname,'../Documents/Technology Transferer/Proposal/'.$name);
                $moved='Successful';
                break;
            case 'Community Service':
                move_uploaded_file($tname,'../Documents/Community Service/Proposal/'.$name);
                $moved='Successful';
                break;
            case 'Thesis':
                move_uploaded_file($tname,'../Documents/Thesis/Proposal/'.$name);
                $moved='Successful';
                break;
            case 'Project':
                move_uploaded_file($tname,'../Documents/Project/Proposal/'.$name);
                $moved='Successful';
                break;
        }
        $stmt = $conn->prepare("INSERT INTO participant (	Staff_ID, Proposal_ID, Role) VALUES (?, ?, ?)");

        do {
            if ($num == 0) {
                $role = "PI";
                $stmt->bind_param("sis", $user_id, $last, $role);
            } else {
                $par = "participant_" . $num;
                $user = $_POST[$par];
                $role = "Co";
                $stmt->bind_param("sis", $user, $last, $role);
            }
            if ($stmt->execute()) {
                $status = "Successful";
            } else {
                $status = "Failed";
            }
            $num++;
        } while ($num <= $NumberofParticipants);

        if ($status === "Successful" && $moved==="Successful") {
            $conn->commit();
            echo "<script>alert('Submitted Successfully!!!! ')</script>";
        } else {
            $conn->rollback();
            echo "<script>alert('Submitted Failed!!!! ')</script>";
        }
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
                            <h1>Hello,
                                Home
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
                                    <form action="SubmitProposal.php" method="post" enctype="multipart/form-data">
                                        <div class="row">

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="control-label">Enter Title </label>
                                                    <input type="text" required name="Title" class="form-control">
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Select Proposal Type</label>

                                                    <select class="form-control" name="Type" required>
                                                        <option value="0">Select Proposal Type</option>
                                                        <option value="Thesis">Thesis</option>
                                                        <option value="Project">Project</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Upload The Proposal</label>
                                                    <div class="col-sm-10">
                                                    <input type="file"   name="File" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Abstract</label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control" name="Abstract" rows="3"
                                                                  placeholder="Text input"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /# column -->
                                            <div class="col-lg-6">

                                                <div class="form-group">
                                                    <label class="control-label">Select Number of Participants </label>

                                                    <select class="form-control" onchange="Participants(this.value)"
                                                            name="participants">
                                                        <option value="0">Select Participants</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                        <option value="8">8</option>
                                                    </select>
                                                </div>
                                                <div id="par" name="parent">


                                                </div>
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
}else{
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
                fff.innerHTML += '<div class="form-group"><label class="control-label">Enter Participant ID</label><input type="text" class="form-control" name="participant_' + (1 + i) + '"></div>'
            }
        }
    }
</script>