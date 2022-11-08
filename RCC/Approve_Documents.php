<!DOCTYPE html>
<html lang="en">

<?php
session_start();
if (isset($_SESSION['StaffId'])&&$_SESSION['StaffType']==='RCC'){
    $_SESSION["Sidebar"]="Approve_Documents";
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
            $stmt->bind_param("sis", $status, $Doc_Id, $Term);
            $stmt->execute();
            echo '<script>alert("Approved Successfully ")</script>';
            break;
        case 'Reject':
            $status = 'Rejected';
            $stmt->bind_param("sis", $status, $Doc_Id, $Term);
            $stmt->execute();
            echo '<script>alert("Rejected Successfully ")</script>';
            break;
        case 'suspend':
            $status = 'suspended';
            $stmt->bind_param("sis", $status, $Doc_Id, $Term);
            $stmt->execute();
            echo '<script>alert("suspended Successfully ")</script>';
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
<script>
    function select_proposals(v = "") {
// alert(v);
        $.ajax({
            type: 'POST',
            url: 'select_documents_tobe_approved_process.php?departments=' + v,
            success: function (html) {
                $('#proposalList').html(html);
            }
        });
    }
    function onChange(v = "") {
        alert(v);
        var data=v.split("&&");
        // alert(data[2]);
        var dep=data[2].split("=");

// alert(dep[1]);
        $.ajax({
            type: 'POST',
            url: 'Approve_Documents.php?document_approval=' + v,
            success: function (html) {
                select_proposals(dep[1]);
            }
        });
    }
</script>
<?php
}else{
    header("Location: http://localhost/system/page-login.php");
}?>