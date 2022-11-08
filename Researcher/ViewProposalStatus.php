<?php
session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Department' || $_SESSION['StaffType'] === 'RCC' || $_SESSION['StaffType'] === 'RCD' || $_SESSION['StaffType'] === 'Researcher'  || $_SESSION['StaffType'] === 'Faculty') {
    $_SESSION["Sidebar"] = "ViewProposalStatus";
    require_once('../Common/DataBaseConnection.php');
?>
    <!DOCTYPE html>
    <html lang="en">

    <?php
    require_once '../Common/Head.php' ?>

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
        <?php require_once '../Common/Header.php' ?>

        <div class="content-wrap">
            <div class="main">
                <div class="container-fluid">
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
                                                    <?php
                                                    if ($data["Merge_With"] == 'None') {
                                                        echo 'Not Merged';
                                                    } else {


                                                    ?>
                                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="<?php echo '#myModal' . $data['ID']; ?>"><?php echo $data["Merge_With"]; ?>
                                                        </button>

                                                        <!-- Modal -->
                                                        <div id="<?php echo 'myModal' . $data['ID']; ?>" class="modal fade" role="dialog">
                                                            <div class="modal-dialog">

                                                                <!-- Modal content-->
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Merged Proposals</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
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
                                                                                            <th>
                                                                                                <center>File</center>
                                                                                            </th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <?php
                                                                                        $proposalStatus = $data["Merge_With"];
                                                                                        if ($proposalStatus === 'None') {
                                                                                        ?>
                                                                                            <tr>
                                                                                                <td colspan="4">
                                                                                                    <center>Not Merged</center< /td>
                                                                                            </tr>
                                                                                            <?php
                                                                                        } else if ($proposalStatus === 'Merged') {
                                                                                            $parentProposalID = $data["ID"];
                                                                                            $StaffId = $_SESSION["StaffId"];
                                                                                            $stmt1 = $conn->prepare("SELECT DISTINCT s.First_Name,s.Middle_Name,s.Last_Name,s.Department,p.ID,p.Title,p.Department,p.Type,p.File, pa.Proposal_ID,pa.Role FROM proposal as p 
                                                                                            INNER JOIN participant as pa ON p.ID=pa.Proposal_ID and p.Merge_With=? and pa.Role='PI' INNER JOIN staffs as s ON s.ID=pa.Staff_ID");
                                                                                            $stmt1->bind_param("s", $parentProposalID);
                                                                                            if ($stmt1->execute()) {
                                                                                                $ourData = $stmt1->get_result();
                                                                                                if ($ourData->num_rows > 0) {
                                                                                                    while ($childProposal = $ourData->fetch_assoc()) {
                                                                                            ?>
                                                                                                        <tr>
                                                                                                            <td><?php echo $childProposal["ID"]; ?></td>
                                                                                                            <td><?php echo $childProposal["Title"]; ?></td>
                                                                                                            <td><?php echo $childProposal["First_Name"] . "  " . $childProposal["Middle_Name"]; ?></td>
                                                                                                            <td><?php echo $childProposal["Department"]; ?></td>
                                                                                                            <td>
                                                                                                                <center>
                                                                                                                    <a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a>
                                                                                                                </center>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    <?php
                                                                                                    }
                                                                                                } else {
                                                                                                    ?>
                                                                                                    <tr>
                                                                                                        <td colspan="4">
                                                                                                            <center>No Records Found</center>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <?php
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            $proposalStatus = $data["Merge_With"];
                                                                                            $stmt3 = $conn->prepare("SELECT DISTINCT s.First_Name,s.Middle_Name,s.Last_Name,s.Department, p.ID,p.Title,p.Department,p.Type,p.File, pa.Proposal_ID,pa.Role FROM proposal as p INNER JOIN participant as pa ON p.ID=pa.Proposal_ID and p.ID=? and pa.Role='PI' INNER JOIN staffs as s ON s.ID=pa.Staff_ID");
                                                                                            $stmt3->bind_param("i", $proposalStatus);
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
                                                                                                            <td>
                                                                                                                <center>
                                                                                                                    <a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a>
                                                                                                                </center>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    <?php
                                                                                                    }
                                                                                                } else {
                                                                                                    ?>
                                                                                                    <tr>
                                                                                                        <td colspan="4">
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
                                                    <?php
                                                    }
                                                    ?>
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
                        <?php require_once '../Common/Footer.php';?>
                    </section>
                </div>
            </div>
        </div>
    </body>

    </html>
<?php

} else {
    header("Location: ../page-login.php");
} ?>

<style>
    .modal-header {

        background-color: #337AB7;

        padding: 16px 16px;

        color: #FFF;

        border-bottom: 2px dashed #337AB7;

    }
</style>