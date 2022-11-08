<?php
session_start();
//if (isset($_SESSION["StaffId"]))
//{
$conn = new mysqli("localhost", "root", "", "repository");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
require_once '../Common/Head.php';

?>
<body>
<?php require_once 'sidebar.php';?>
<!-- /# sidebar -->

<?php
//require_once 'sidebar.php';
require_once '../Common/Header.php';

if (isset($_POST["Submit"])) {
    $proposal=explode('/',$_POST["ProposalId"]);

    $ProposalId = $proposal[0];
    $ProposalType = $proposal[1];
    $name = rand(1000, 10000) . "-" . $_FILES['File']['name'];
    $Comment = $_POST["Comment"];

    $conn->begin_transaction();
    $stmt = $conn->prepare("INSERT INTO documents (Proposal_ID, file, Comment) VALUES ( ? , ? ,?)");
    $stmt->bind_param("sss", $ProposalId, $name, $Comment);


    $file = $_FILES['File'];

    $tname = $_FILES["File"]["tmp_name"];
    $size = $_FILES["File"]["size"];
    $file_type = $_FILES["File"]["type"];
    if ($stmt->execute()) {
        /** @var TYPE_NAME $moved */
        $moved = "";
        $role = "";
        $num = 0;
        switch ($ProposalType) {
            case "Research":
                move_uploaded_file($tname, '../Documents/Research/Final/' . $name);
                $moved = 'Successful';
                break;
            case 'Technology Transfer':
                move_uploaded_file($tname, '../Documents/Technology Transferer/Final/' . $name);
                $moved = 'Successful';
                break;
            case 'Community Service':
                move_uploaded_file($tname, '../Documents/Community Service/Final/' . $name);
                $moved = 'Successful';
                break;
            case 'Thesis':
                move_uploaded_file($tname, '../Documents/Thesis/Final/' . $name);
                $moved = 'Successful';
                break;
            case 'Project':
                move_uploaded_file($tname, '../Documents/Project/Final/' . $name);
                $moved = 'Successful';
                break;
        }
        if ($moved === "Successful") {
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
                                    <form action="SubmitDocument.php" method="post" enctype="multipart/form-data">
                                        <div class="row">

                                            <div class="col-lg-6">
                                                <?php
                                                $stmt = $conn->prepare("SELECT * FROM proposal WHERE EXISTS (SELECT Proposal_ID FROM participant WHERE proposal.ID = participant.Proposal_ID and Staff_ID=? )  and Status='On Progress' order by date ASC");
                                                //                                                $StaffId = $_SESSION["StaffId"];
                                                $StaffId = $_SESSION["StaffId"];
                                                $stmt->bind_param("s", $StaffId);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                ?>
                                                <div class="form-group">
                                                    <label class="control-label">Select The Proposal </label>

                                                    <select class="form-control" name="ProposalId" required>
                                                        <option value="">Select Proposal</option>
                                                        <?php
                                                        while ($data = $result->fetch_assoc()) {


                                                            ?>
                                                            <option value="<?php echo $data['ID'].'/'.$data['Type'] ?>"><?php echo $data["Title"] ?>
                                                                (<?php echo $data["ID"] ?>)
                                                            </option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Upload The Proposal</label>
                                                    <div class="col-sm-10">
                                                        <input type="file" name="File" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Comment</label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control" rows="5" style="height:100%;"
                                                                  name="Comment" rows="3"
                                                                  placeholder="Text input"></textarea>
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
                                            <!-- /# column -->
                                            <div class="col-lg-6">
                                                <!-- we will add the write coloumn content here  -->
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
//}else{
//    header("Location: ../page-login.php");
//}
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
</script>