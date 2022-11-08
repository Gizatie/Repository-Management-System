<?php
session_start();
if (isset($_SESSION['StaffId']) || $_SESSION['StaffType'] === 'Department' || $_SESSION['StaffType'] === 'RCC'|| $_SESSION['StaffType'] === 'RCD' || $_SESSION['StaffType'] === 'Researcher'  || $_SESSION['StaffType'] === 'Faculty' ) {
    require_once('../Common/DataBaseConnection.php');
require_once '../Common/Head.php';

?>
<body>
<?php
                $currentUserType = $_SESSION['StaffType'];
                switch ($currentUserType) {
                    case 'Researcher':
                        require_once '../Researcher/sidebar.php';
                        break;
                    case 'Department':
                        require_once '../Department/sidebar.php';
                        break;
                    case 'RCC':
                        require_once '../RCC/sidebar.php';
                        break;
                    case 'RCD':
                        require_once '../RCD/sidebar.php';
                        break;
                    case 'Faculty':
                        require_once '../Faculty/sidebar.php';
                        break;
                }
                ?>
<!-- /# sidebar -->

<?php
//require_once 'sidebar.php';
require_once '../Common/Header.php';

if (isset($_POST["Submit"])) {
    $ProposalId = $_REQUEST['ProposalId'];
    $No_of_Investigators=$_REQUEST['investigators'];
    $Male_Direct_Investigators=$_REQUEST['Male_Direct_beneficiaries'];
    $Female_Direct_Investigators=$_REQUEST['Female_Direct_beneficiaries'];
    $Male_Indirect_Investigators=$_REQUEST['Male_Indirect_beneficiaries'];
    $Female_Indirect_Investigators=$_REQUEST['Female_Indirect_beneficiaries'];
    $Other_beneficiaries=$_REQUEST['Other_beneficiaries'];

    $name = rand(1000, 10000) . "-" . $_FILES['File']['name'];
    $Comment = $_POST["Comment"];

    $conn->begin_transaction();
    $stmt = $conn->prepare("INSERT INTO community_service_report (Number_of_investigators, Male_Direct_Beneficiaries, Female_Direct_beneficiaries,Male_Indirect_Beneficiaries,Female_Indirect_beneficiaries,Other,Proposal_Id) VALUES ( ? , ? ,?,?,?,?,?)");
    $stmt->bind_param("ssssssi", $No_of_Investigators,$Male_Direct_Investigators,$Female_Direct_Investigators,$Male_Indirect_Investigators,$Female_Indirect_Investigators,$Other_beneficiaries,$ProposalId);
    $file = $_FILES['File'];
    $tname = $_FILES["File"]["tmp_name"];
    $size = $_FILES["File"]["size"];
    $file_type = $_FILES["File"]["type"];

    if ($stmt->execute()) {
        /** @var TYPE_NAME $moved */
        move_uploaded_file($tname, '../Documents/Community Service/Final/' . $name);
        $conn->commit();
        echo "<script>alert('Community Service Report Successfully Submitted')</script>";
    }
}
?>


<div class="content-wrap">
    <div class="main">
        <div class="container-fluid">
            <!-- /# row -->
            <section id="main-content">
                <div class="row">
                    <!-- /# column -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-title">
                                <h4></h4>

                            </div>
                            <div class="card-body">
                                <div class="horizontal-form-elements">
                                    <form action="Submit_Community_Service_Report.php" method="post" enctype="multipart/form-data">
                                        <div class="row">

                                            <div class="col-lg-6">
                                                <?php
                                                $stmt = $conn->prepare("SELECT * FROM proposal WHERE EXISTS (SELECT Proposal_ID FROM participant WHERE proposal.ID = participant.Proposal_ID and Staff_ID=? ) and Rcd_level='Approved' and Type='Community Service' and Status='On Progress' order by date ASC");
                                                                                                $StaffId = $_SESSION["StaffId"];
//                                                $StaffId = 1;
                                                $stmt->bind_param("s", $StaffId);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                ?>
                                                <div class="form-group">
                                                    <label class="control-label">Select The Proposal </label>

                                                    <select class="form-control"  name="ProposalId" required>
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
                                                    <label class="control-label">Enter No of investigators </label>
                                                    <input type="number" class="form-control" name="investigators" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Enter Male Direct beneficiaries </label>
                                                    <input type="number" class="form-control" name="Male_Direct_beneficiaries" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Enter Female Direct beneficiaries </label>
                                                    <input type="number" class="form-control" name="Female_Direct_beneficiaries" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Enter Male Indirect beneficiaries </label>
                                                    <input type="number" class="form-control" name="Male_Indirect_beneficiaries" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Enter Female Indirect beneficiaries </label>
                                                    <input type="number" class="form-control" name="Female_Indirect_beneficiaries" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Enter Other Type  beneficiaries </label>
                                                    <input type="text" class="form-control" name="Other_beneficiaries" required>
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
        <?php require_once '../Common/Footer.php'; ?>
        </section>
    </div>
</div>


<?php 
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
                fff.innerHTML += '<div class="form-group"><label class="control-label">Text Input</label><input type="text" class="form-control" name="participant_' + (1 + i) + '"></div>'
            }
        }
    }
    function SelectTerm(v='') {
         let ProposalId=v.split("/");
        $.ajax({
            type: 'POST',
            url: 'ResearcherProcess?SelectTerm=term&&ProposalId='+ProposalId,

            success: function (html) {
                $('#terms').html(html);
            }
        });
    }
</script>