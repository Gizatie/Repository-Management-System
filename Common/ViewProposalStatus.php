<?php
session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Researcher') {
    $_SESSION["Sidebar"] = "ViewProposalStatus";
?>
    <!DOCTYPE html>
    <html lang="en">

    <?php

    require_once 'DataBaseConnection.php';
    require_once 'Head.php' ?>

    <body>
        <?php 
        $currentUserType=$_SESSION['StaffType'];
        switch ($currentUserType){
            case 'Researcher':
                require_once '../Researcher/sidebar.php' ;
                break;
            case 'ApproveDocument':
                $sidebar_links[2]="#";
                break;
            case 'Merge_Proposal':
                $sidebar_links[3]="#";
                break;
            case 'Budget_Entry':
                $sidebar_links[4]="#";
                break;
            case 'SubmitProposal':
                $sidebar_links[5]="#";
                break;
            case 'SubmitDocument':
                $sidebar_links[6]="#";
                break;
            case 'ViewProposalStatus':
                $sidebar_links[7]="#";
                break;
            case 'ViewDocumentStatus':
                $sidebar_links[8]="#";
                break;
            case 'Completed_Researches':
                $sidebar_links[9]="#";
                break;
            case 'Progressing_Researches':
                $sidebar_links[10]="#";
                break;
            case 'Completed_Researches_Proposals':
                $sidebar_links[11]="#";
                break;
            case 'Completed_TT':
                $sidebar_links[12]="#";
                break;
            case 'Progressing_TT':
                $sidebar_links[13]="#";
                break;  
        }
        ?>

        <!-- /# sidebar -->
        <?php require_once '../Common/Header.php' ?>

        <div class="content-wrap">
            <div class="main">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-8 p-r-0 title-margin-right">
                            <div class="page-header">
                                <div class="page-title">
                                    <h1><span></span>
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
                                            <a href="#">DTU</a>
                                        </li>
                                        <li class="breadcrumb-item active">Department/home/Approve Proposal</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <!-- /# column -->
                    </div>
                    <!-- /# row -->
                    <section id="main-content">
                        <div class="row">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <center>#</center>
                                        </th>
                                        <th>
                                            <center>Title</center>
                                        </th>
                                        <th>
                                            <center>Type</center>
                                        </th>
                                        <th>
                                            <center>Date</center>
                                        </th>
                                        <th>
                                            <center>File</center>
                                        </th>
                                        <th>
                                            <center>Dep Status</center>
                                        </th>
                                        <th>
                                            <center>RCC Status</center>
                                        </th>
                                        <th>
                                            <center>RCD Status</center>
                                        </th>
                                        <th>
                                            <center>Final Status</center>
                                        </th>
                                        <th>
                                            <center> Merge Status</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $conn->prepare("SELECT * FROM proposal WHERE EXISTS (SELECT Proposal_ID FROM participant WHERE proposal.ID = participant.Proposal_ID and Staff_ID=? ) and Status!='Completed' order by date ASC");
                                    $StaffId = $_SESSION["StaffId"];

                                    $stmt->bind_param("s", $StaffId);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result->num_rows) {

                                        while ($data = $result->fetch_assoc()) {
                                    ?>
                                            <tr>
                                                <td>
                                                    <center><?php echo $data["ID"] ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data["Title"] ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data["Type"] ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data["date"] ?></center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data["Department_level"]; ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data["Rcc_level"]; ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data["Rcd_level"]; ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data["Status"]; ?></center>
                                                </td>
                                                <td>
                                                    <!-- Trigger the modal with a button -->
                                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="<?php echo '#myModal' . $data['ID']; ?>"><?php echo $data["Merge_With"]; ?>
                                                    </button>

                                                    <!-- Modal -->
                                                    <div id="<?php echo 'myModal' . $data['ID']; ?>" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">

                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <div class="modal-header mo">
                                                                    <button type="button" class="close" data-dismiss="modal"></button>
                                                                    <h4 class="modal-title">proposal list</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <div class="col-lg-12">

                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="col-lg-12" multiple="">

                                                                            <table class="table table-sm table-bordered">
                                                                                <thead class="table-light">
                                                                                    <tr>
                                                                                        <th>
                                                                                            <center>ID</center>
                                                                                        </th>
                                                                                        <th>
                                                                                            <center>Title</center>
                                                                                        </th>
                                                                                        <th>
                                                                                            <center>PI</center>
                                                                                        </th>
                                                                                        <th>
                                                                                            <center>Department</center>
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    $proposalStatus = $data["Merge_With"];
                                                                                    if ($proposalStatus === 'None') {
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td colspan="3">
                                                                                                <center>No Record Found</center< /td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    } else if ($proposalStatus === 'Merged') {
                                                                                        $parentProposalID = $data["ID"];
                                                                                        $stmt3 = $conn->prepare("SELECT s.First_Name,pa.Role,p.Title,p.ID,s.Middle_Name,s.Last_Name,s.Department FROM proposal as p,participant as pa, staffs as s WHERE s.ID=pa.Staff_ID and p.ID=pa.Proposal_ID and pa.Role='PI' and p.Merge_With=?");
                                                                                        $stmt3->bind_param("s", $parentProposalID);
                                                                                        if ($stmt3->execute()) {
                                                                                            $ourData = $stmt3->get_result();
                                                                                            if ($ourData->num_rows > 0) {
                                                                                                while ($childProposal = $ourData->fetch_assoc()) {
                                                                                        ?>
                                                                                                    <tr>
                                                                                                        <td><?php echo $childProposal["ID"]; ?></td>
                                                                                                        <td><?php echo $childProposal["Title"]; ?></td>
                                                                                                        <td><?php echo $childProposal["First_Name"] . "  " . $childProposal["Middle_Name"]; ?></td>
                                                                                                        <td><?php echo $childProposal["Department"]; ?></td>
                                                                                                    </tr>
                                                                                                <?php
                                                                                                }
                                                                                            } else {
                                                                                                ?>
                                                                                                <tr>
                                                                                                    <td colspan="3">
                                                                                                        <center>No Records Found</center>
                                                                                                    </td>
                                                                                                </tr>
                                                                                      <?php
                                                                                            }
                                                                                        }
                                                                                    }else {
                                                                                        
                                                                                        $stmt3 = $conn->prepare("SELECT s.First_Name,pa.Role,p.Title,p.ID,s.Middle_Name,s.Last_Name,s.Department FROM proposal as p,participant as pa, staffs as s WHERE s.ID=pa.Staff_ID and p.ID=pa.Proposal_ID and pa.Role='PI' and p.Merge_With='Merged' and p.ID=?");
                                                                                        $stmt3->bind_param("s", $proposalStatus);
                                                                                        if ($stmt3->execute()) {
                                                                                            $ourData = $stmt3->get_result();
                                                                                            if ($ourData->num_rows > 0) {
                                                                                                while ($childProposal = $ourData->fetch_assoc()) {
                                                                                        ?>
                                                                                                    <tr>
                                                                                                        <td><?php echo $childProposal["ID"]; ?></td>
                                                                                                        <td><?php echo $childProposal["Title"]; ?></td>
                                                                                                        <td><?php echo $childProposal["First_Name"] . "  " . $childProposal["Middle_Name"]; ?></td>
                                                                                                        <td><?php echo $childProposal["Department"]; ?></td>
                                                                                                    </tr>
                                                                                                <?php
                                                                                                }
                                                                                            } else {
                                                                                                ?>
                                                                                                <tr>
                                                                                                    <td colspan="3">
                                                                                                        <center>No Records Found</center>
                                                                                                    </td>
                                                                                                </tr>
                                                                                        <?php
                                                                                            }
                                                                                        }
                                                                                    }

                                                                                    ?>
                                                                                </tbody>
                                                                            </table>

                                                                        </div>
                                                                    </div>
                                                                    <button class="btn btn-danger"><a href="">Cancel</a>
                                                                    </button>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="9">
                                                <center>No Record</center>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>

                                </tbody>

                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="extra-area-chart"></div>
                                <div id="morris-line-chart"></div>
                                <div class="footer">
                                    <p>2020 © DTU. -
                                        <a href="#">dtu.gov.et</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <!-- Common -->
        <script src="assets/js/lib/jquery.min.js"></script>
        <script src="assets/js/lib/jquery.nanoscroller.min.js"></script>
        <script src="assets/js/lib/menubar/sidebar.js"></script>
        <script src="assets/js/lib/preloader/pace.min.js"></script>
        <script src="assets/js/lib/bootstrap.min.js"></script>
        <script src="assets/js/scripts.js"></script>


    </body>

    </html>
<?php

} else {
    header("Location: ../page-login.php");
} ?>