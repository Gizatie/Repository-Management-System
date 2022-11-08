<!DOCTYPE html>
<html lang="en">

<?php
session_start();
if (isset($_SESSION['StaffId'])&&$_SESSION['StaffType']==='Faculty'){
    $_SESSION["Sidebar"]="ApproveDocument";
require_once '../Common/DataBaseConnection.php';
require_once '../Common/Head.php' ?>

<body>


<!-- /# sidebar starts -->
<?php require_once "sidebar.php"?>
<!-- /# sidebar ends  -->
<?php

require_once '../Common/Header.php'; ?>


<?php
$departments = null;
/*
 * Approval for document performed here
 */
if (isset($_GET['document_approval'])) {
    $Action = $_GET["document_approval"];
    $ProposalId = $_GET["ProposalId"];
    $Doc_Id = $_GET["Doc_ID"];
    $departments = $_GET["departments"];
    $Term = $_GET["Term"];
    $status = '';
    $stmt = $conn->prepare("UPDATE documents set Rcc_Status=? where ID=? and Term=?");

    switch ($Action) {
        case 'Approve':
            $status = 'Approved';
            $stmt->bind_param("sss", $status, $Doc_Id, $Term);
            $stmt->execute();
            $stmt2 = $conn->prepare("UPDATE proposal set Rcc_level=? where ID=? ");
            $status = 'Approved Term-' . $Term;
            $stmt2->bind_param("ss", $status, $ProposalId);
            $stmt2->execute();
            break;
        case 'Reject':
            $status = 'Rejected';
            $stmt->bind_param("sss", $status, $Doc_Id, $Term);
            $stmt->execute();
            $stmt2 = $conn->prepare("UPDATE proposal set Rcc_level=? where ID=? ");
            $status = 'Rejected Term-' . $Term;
            $stmt2->bind_param("ss", $status, $ProposalId);
            $stmt2->execute();
            break;
        case 'suspend':
            $status = 'suspended';
            $stmt->bind_param("sss", $status, $Doc_Id, $Term);
            $stmt->execute();
            $stmt2 = $conn->prepare("UPDATE proposal set Rcc_level=? where ID=? ");
            $status = 'suspended Term-' . $Term;
            $stmt2->bind_param("ss", $status, $ProposalId);
            $stmt2->execute();
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
                $stmt->bind_param("s", $Faculty);
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
                            </th><th>
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
                                <center>RCC Status</center>
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


                            $stmt->bind_param("s",$departments );
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
                                            <a href="../Documents/<?php echo $row["Type"] ?>/Final/<?php echo $row["file"] ?>"><?php echo $row["file"] ?></a>
                                        </td>
                                        <td> <?php echo $row["Rcc_Status"] ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle btn-sm" type="button"
                                                        data-toggle="dropdown">Action
                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="Approve_Documents.php?document_approval=Approve&&ProposalId=<?php echo $row['ID'] ?>&&departments=<?php echo $row['Department'] ?>&&Doc_ID=<?php echo $row['ID'] ?>&&Term=<?php echo $row['Term'] ?>">Approve</a>
                                                    </li>
                                                    <li>
                                                        <a href="Approve_Documents.php?document_approval=Reject&&ProposalId=<?php echo $row['ID'] ?>&&departments=<?php echo $row['Department'] ?>&&Doc_ID=<?php echo $row['ID'] ?>&&Term=<?php echo $row['Term'] ?>">Reject</a>
                                                    </li>
                                                    <li>
                                                        <a href="Approve_Documents.php?document_approval=suspend&&ProposalId=<?php echo $row['ID'] ?>&&departments=<?php echo $row['Department'] ?>&&Doc_ID=<?php echo $row['ID'] ?>&&Term=<?php echo $row['Term'] ?>">Suspend</a>
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
<script>
    function select_proposals(v = "") {
// alert(v);
        $.ajax({
            type: 'POST',
            url: 'select_documents_tobe_approved_process?departments=' + v,
            success: function (html) {
                $('#proposalList').html(html);
            }
        });
    }
</script>
<?php
}else{
    header("Location: http://localhost/system/page-login.php");
}?>