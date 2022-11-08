<!DOCTYPE html>
<html lang="en">

<?php
session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Faculty') {
    $_SESSION["Sidebar"] = "ApproveDocument";
    require_once '../Common/DataBaseConnection.php';
    require_once '../Common/Head.php' ?>

    <body>
        <!--Side bar Starts-->
        <?php require_once "sidebar.php" ?>
        <!--Side bar Ends -->
        <?php

        $Department = $_SESSION["Department"];
        ?>
        <!-- /# sidebar -->

        <?php
        require_once '../Common/Header.php'; ?>


        <?php
        $departments = null;
        //  to the button action on each proposal is clicked
        if (isset($_GET['document_approval'])) {
            $Action = $_GET["document_approval"];
            $ProposalId = $_GET["ProposalId"];
            $departments = $_GET["departments"];
            $Doc_Id = $_GET["Doc_ID"];
            $Term = $_GET["Term"];
            $status = '';
            $stmt = $conn->prepare("UPDATE documents set Faculty_Level=? where ID=? and Term=?");

            switch ($Action) {
                case 'Approve':
                    $status = 'Approved';
                    $stmt->bind_param("sis", $status, $Doc_Id,$Term);
                    $stmt->execute();
                    echo '<script>alert("Approved Successfully ")</script>';
                    break;
                case 'Reject':
                    $status = 'Rejected';
                    $stmt->bind_param("sis", $status, $Doc_Id,$Term);
                    $stmt->execute();
                    echo '<script>alert("Rejected Successfully ")</script>';
                    break;
                case 'suspend':
                    $status = 'suspended';
                    $stmt->bind_param("sis", $status, $Doc_Id,$Term);
                    $stmt->execute();
                    echo '<script>alert("Suspended Successfully ")</script>';
                    break;
            }
        }
        ?>

        <div class="content-wrap">
            <div class="main">
                <div class="container-fluid">
                    <!-- /# row -->
                    <section id="main-content">

                        <?php
                        /*
                 * filling the department selection
                 */
                        $stmt = $conn->prepare("select  * from department as d  where  d.Faculty=? order by Name ASC");
                        $Faculty = $_SESSION["Faculty"];
                        $stmt->bind_param("s",  $Faculty);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        ?>
                        <div class="form-group">
                            <div class="col-lg-12">
                                <label class="control-label">Select Department </label>

                                <select class="form-control" onchange="select_proposals(this.value)" name="Department" required>
                                    <option selected>Select Department</option>
                                    <?php
                                    if ($result->num_rows) {
                                        while ($data = $result->fetch_assoc()) {

                                    ?>
                                            <option value="<?php echo $data["Name"] ?>"><?php echo $data["Name"] ?></option>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <option value="0">No Department Found</option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <table class="table table-sm table-bordered" id="Proposals">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <center>ID</center>
                                        </th>
                                        <th>
                                            <center>Title</center>
                                        </th>
                                        <th>
                                            <center>Term</center>
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
                                            <center>Faculty Status</center>
                                        </th>
                                        <th>
                                            <center>Action</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="proposalList">
                                    <?php
                                    if ($departments != NULL) {
                                        $Year = date('Y');
                                        $stmt = $conn->prepare("select d.ID,p.Department,d.Proposal_ID,d.Term,d.file,p.Title,p.Type,p.date,d.Rcc_Status from proposal as p, documents as d  where p.ID=d.Proposal_ID and    p.Department=?  and d.Status='On Progress' and p.Status!='Compeleted' and p.date like '" . $Year . "%' order by p.ID ASC");

                                        $stmt->bind_param("s",  $departments);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if ($result->num_rows) {

                                            while ($row = $result->fetch_assoc()) {

                                    ?>

                                                <tr>
                                                    <td><?php echo $row["ID"] ?></td>
                                                    <td><?php echo $row["Title"] ?></td>
                                                    <td><?php echo $row["Term"] ?></td>
                                                    <td><?php echo $row["Type"] ?></td>
                                                    <td><?php echo $row["date"] ?></td>
                                                    <td>
                                                        <a href="../Documents/<?php echo $row["Type"] ?>/Proposal/<?php echo $row["File"] ?>"><?php echo $row["File"] ?></a>
                                                    </td>
                                                    <td><?php echo $row["Rcc_level"] ?></td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">Action
                                                                <span class="caret"></span></button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a href=Approve_Documents.php?document_approval=Approve&&ProposalId=<?php echo $row['ID'] ?>&&departments=<?php echo $row['Department'] ?>&&Proposal_ID=<?php echo $row['Proposal_ID'] ?>&&Term=<?php echo $row['Term'] ?>">Approve</a>
                                                                </li>
                                                                <li>
                                                                    <a href="Approve_Documents.php?document_approval=Reject&&ProposalId=<?php echo $row['ID'] ?>&&departments=<?php echo $row['Department'] ?>&&Proposal_ID=<?php echo $row['Proposal_ID'] ?>&&Term=<?php echo $row['Term'] ?>">Reject</a>
                                                                </li>
                                                                <li>
                                                                    <a href="Approve_Documents.php?document_approval=suspend&&ProposalId=<?php echo $row['ID'] ?>&&departments=<?php echo $row['Department'] ?>&&Proposal_ID=<?php echo $row['Proposal_ID'] ?>&&Term=<?php echo $row['Term'] ?>">Suspend</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="7">
                                                <center>No Record Found</center>
                                            </td>
                                        </tr>

                                    <?php
                                    }
                                    ?>
                                </tbody>

                            </table>
                        </div>
                        <?php require_once '../Common/Footer.php'; ?>
                    </section>
                </div>
            </div>
        </div>
    </body>

</html>
<script type="text/javascript">
    function select_proposals(v = "") {
        // alert(v);
        $.ajax({
            type: 'POST',
            url: 'select_documents_tobe_approved_process.php?departments=' + v,
            success: function(html) {
                $('#proposalList').html(html);
            }
        });
    }
    function onChange(v = "") {
        alert(v);
        var data=v.split("&&");
        var dep=data[2].split("=");
        // alert(dep[1]);
        $.ajax({
            type: 'POST',
            url: 'ApproveDocument.php?document_approval=' + v,
            success: function(html) {
                select_proposals(dep[1]); 
            }
        });
    }
    function selectDep(v = '') {
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess.php?selectDep=dep&&Faculty=' + v,

            success: function(html) {
                $('#dep').html(html);
            }
        });
    }

    function selectType(v = '') {
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess.php?selectType=dep&&Department=' + v,

            success: function(html) {
                $('#Type').html(html);
            }
        });
    }
</script>
<?php
} else {
    header("Location: http://localhost/system/page-login.php");
} ?>