<?php
session_start();

if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Department' || $_SESSION['StaffType'] === 'RCC'|| $_SESSION['StaffType'] === 'RCD' || $_SESSION['StaffType'] === 'Researcher'  || $_SESSION['StaffType'] === 'Faculty') {
    $_SESSION["Sidebar"] = "SubmitDocument";
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
            $proposal = explode('/', $_POST["ProposalId"]);

            $ProposalId = $proposal[0];
            $ProposalType = $proposal[1];
            $name = rand(1000, 10000) . "-" . $_FILES['File']['name'];
            $Comment = $_POST["Comment"];
            $Term = (int)$_POST["Terms"];

            $conn->begin_transaction();
            $stmt = $conn->prepare("INSERT INTO documents (Proposal_ID, file, Comment,Term) VALUES ( ? , ? ,?,?)");
            $stmt->bind_param("sssi", $ProposalId, $name, $Comment, $Term);


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
                                            <form action="SubmitDocument.php" method="post" enctype="multipart/form-data">
                                                <div class="row">

                                                    <div class="col-lg-6">
                                                        <?php
                                                        $stmt = $conn->prepare("SELECT * FROM proposal WHERE EXISTS (SELECT Proposal_ID FROM participant WHERE proposal.ID = participant.Proposal_ID and Staff_ID=? ) and Rcd_level='Approved' and Status='On Progress' order by date ASC");
                                                        $StaffId = $_SESSION["StaffId"];
                                                        //                                                $StaffId = 1;
                                                        $stmt->bind_param("s", $StaffId);
                                                        $stmt->execute();
                                                        $result = $stmt->get_result();
                                                        ?>
                                                        <div class="form-group">
                                                            <label class="control-label">Select The Proposal </label>

                                                            <select class="form-control" on onchange="SelectTerm(this.value)" name="ProposalId" required>
                                                                <option value="">Select Proposal</option>
                                                                <?php
                                                                while ($data = $result->fetch_assoc()) {


                                                                ?>
                                                                    <option value="<?php echo $data['ID'] . '/' . $data['Type'] ?>"><?php echo $data["Title"] ?>
                                                                        (<?php echo $data["ID"] ?>)
                                                                    </option>
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div id="terms">

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
                                                                <textarea class="form-control" rows="5" style="height:100%;" name="Comment" rows="3" placeholder="Text input"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-lg-6">
                                                                <div class="col-sm-offset-2 col-sm-10">
                                                                    <button type="submit" name="Submit" value="Submit" class="btn btn-default">Submit
                                                                    </button>
                                                                </div>
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

        function SelectTerm(v = '') {
            let ProposalId = v.split("/");
            $.ajax({
                type: 'POST',
                url: 'ResearcherProcess.php?SelectTerm=term&&ProposalId=' + ProposalId,

                success: function(html) {
                    $('#terms').html(html);
                }
            });
        }
    </script>