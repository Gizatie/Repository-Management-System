<!DOCTYPE html>
<html lang="en">

<?php
session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Faculty') {
    $_SESSION["Sidebar"] = "Approve";
    require_once '../Common/DataBaseConnection.php';
    require_once '../Common/Head.php' ?>

    <body>

        <?php require_once "sidebar.php" ?>
        <!-- /# sidebar starts -->
        <!-- /# sidebar ends  -->
        <?php

        require_once '../Common/Header.php'; ?>


        <?php
        $departments = null;
        //  to the button action on each proposal is clicked
        if (isset($_GET['Action'])) {
            $Action = $_GET["Action"];
            $ProposalId = $_GET["ProposalId"];
            $departments = $_GET["departments"];
            $status = '';
            $stmt = $conn->prepare("UPDATE proposal set Faculty_Level=? where ID=?");

            switch ($Action) {
                case 'Approve':
                    $status = 'Approved';
                    $stmt->bind_param("ss", $status, $ProposalId);
                    $stmt->execute();
                    echo '<script> alert("Successfully Approved")</script>';
                    break;
                case 'Reject':
                    $status = 'Rejected';
                    $stmt->bind_param("ss", $status, $ProposalId);
                    $stmt->execute();
                    echo '<script> alert("Successfully Approved")</script>';
                    break;
                case 'suspend':
                    $status = 'suspended';
                    $stmt->bind_param("ss", $status, $ProposalId);
                    $stmt->execute();
                    echo '<script> alert("Successfully Approved")</script>';
                    break;
            }
        }
        /*
 * Approval for document performed here
 */

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
                                            <center>RCC Status</center>
                                        </th>
                                        </th>
                                        <th>
                                            <center>Faculty Status</center>
                                        </th>
                                        </th>
                                        <th>
                                            <center>Final Status</center>
                                        </th>
                                        <th>
                                            <center>Action</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="proposalList">

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
            url: 'select_proposals_tobe_approved_process.php?departments=' + v,
            success: function(html) {
                $('#proposalList').html(html);
            }
        });
    }

    function ChangeType(v = '') {
        // alert(v);
        $.ajax({
            type: 'POST',
            url: 'Approve.php?Action=' + v,
            success: function(html) {
                select_proposals(v);
            }
        });
    }
</script>
<?php
} else {
    header("Location: http://localhost/system/page-login.php");
}
?>