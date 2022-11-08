<!DOCTYPE html>
<html lang="en">

<?php
session_start();
$Selected_Type = null;
$Selected_Department = null;
// echo "ddddddddddddddddddd " . $_SESSION['StaffType'];
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Department') {
    $_SESSION["Sidebar"] = 'Merge_Proposal';
    require_once '../Common/DataBaseConnection.php';
    require_once '../Common/Head.php';
    require_once '../Common/Functions.php';
?>

    <body>

        <!--Sidebar starts-->
        <?php require_once "sidebar.php"; ?>
        <!--Sidebar Ends-->
        <!-- /# sidebar -->

        <?php require_once '../Common/Header.php';  ?>


        <?php
        //  to the button action on each proposal is clicked
        if (isset($_GET['Action'])) {
            $Action = $_GET["Action"];
            $ProposalId = $_GET["ProposalId"];
            $status = '';
            $Final_Status = '';
            $stmt = $conn->prepare("UPDATE proposal set Department_level=? where ID=?");

            switch ($Action) {
                case 'Approve':
                    $status = 'Approved';
                    $stmt->bind_param("ss", $status, $ProposalId);
                    break;
                case 'Reject':
                    $status = 'Reject';
                    $stmt->bind_param("ss", $status, $ProposalId);
                    break;
                case 'suspend':
                    $status = 'suspend';
                    $stmt->bind_param("ss", $status, $ProposalId);
                    break;
            }
            if ($stmt->execute()) {
                echo '<script>alert("Action taken")</script>';
            }
        } elseif (isset($_POST['Submit'])) {

            $conn->begin_transaction();
            $values = $_POST['ary'];
            $new_data = explode("/", $values[0]);

            $GLOBALS["Selected_Type"] = $new_data[2];
            $GLOBALS["Selected_Department"] = $new_data[3];
            Merg($values);
        }
        ?>
        <div class="content-wrap">
            <div class="main">
                <div class="container-fluid">
                    <!-- /# row -->
                    <section id="main-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="control-label">Select Type</label>
                                <select class="form-control" onchange="selectProposal(this.value)" id="Type" required>
                                    <option value="">Select Type</option>
                                    <option value="Research">Research</option>
                                    <option value="Technology Transfer">Technology Transfer</option>
                                    <option value="Community Service">Community Service</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
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
                                            <center>Terms</center>
                                        </th>
                                        <th>
                                            <center>File</center>
                                        </th>
                                        <th>
                                            <center>Merged With</center>
                                        </th>
                                        <th>
                                            <center>Action</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="body">
                                    <?php
                                    if ($GLOBALS["Selected_Department"]) {

                                        $Year = date('Y');
                                        $stmt = $conn->prepare("select * from proposal where Status!='Completed' and Status!='On Progress' and Merge_With='None' and Merge_With!='Merged' and Department=?  and Type=? and date like '" . $Year . "%' order by date DESC");
                                        $Department = $GLOBALS["Selected_Department"];
                                        $Type = $GLOBALS["Selected_Type"];
                                        $stmt->bind_param("ss", $Department, $Type);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if ($result->num_rows) {

                                            while ($data = $result->fetch_assoc()) {
                                    ?>
                                                <tr>
                                                    <td><?php echo $data["ID"] ?></td>
                                                    <td><?php echo $data["Title"] ?></td>
                                                    <td><?php echo $data["Type"] ?></td>
                                                    <td><?php echo $data["date"] ?></td>
                                                    <td><?php echo $data["Term"] . ' Terms' ?></td>
                                                    <td>
                                                        <a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a>
                                                    </td>
                                                    <td><?php echo $data["Merge_With"]; ?></td>
                                                    <td>
                                                        <!-- Trigger the modal with a button -->
                                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="<?php echo '#myModal' . $data['ID']; ?>">Merge
                                                        </button>

                                                        <!-- Modal -->
                                                        <div id="<?php echo 'myModal' . $data['ID']; ?>" class="modal fade " role="dialog">
                                                            <div class="modal-dialog">

                                                                <!-- Modal content-->
                                                                <div class="modal-content">
                                                                    <form id="role-form" method="get" action="Mserge_Proposal.php">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                            <h4 class="modal-title">Select The Proposal tobe merged</h4>
                                                                        </div>
                                                                        <div class="modal-body">

                                                                            <div class="form-group">
                                                                                <div class="col-lg-12">
                                                                                    <center>
                                                                                        <?php
                                                                                        $year = date("Y");
                                                                                        //                                                    $Department_level = 'Not Approved';
                                                                                        //                                                    $Department_level = 'Not Approved';
                                                                                        $ID = $data['ID'];
                                                                                        $stmt = $conn->prepare("select * from proposal where  Department=? and Type=? and ID!=? and Status!='On Progress' and Status!='Completed' and Status!='Merged' and date like '" . $Year . "%' order by date DESC");

                                                                                        $stmt->bind_param("sss",  $Department, $Type, $ID);
                                                                                        $stmt->execute();
                                                                                        $merging_Proposals = $stmt->get_result();
                                                                                        ?>
                                                                                        <label class="control-label">Select Proposals (<small>press Control For
                                                                                                multiple selection </small>)</label>
                                                                                        <select multiple="multiple" id="proposals" size="50" style="height: 100px; overflow-y: scroll;" data-live-search="true" data-size="10" class="form-control" name="ary[]">
                                                                                            <?php
                                                                                            if ($merging_Proposals->num_rows) {
                                                                                                while ($row = $merging_Proposals->fetch_assoc()) {
                                                                                            ?>
                                                                                                    <option value="<?php echo $row['ID'] . "/" . $data["ID"] ?>"><?php echo $row['Title']  ?>(<?php echo $row['ID']  ?>)</option>
                                                                                            <?php
                                                                                                }
                                                                                            }
                                                                                            ?>
                                                                                        </select>
                                                                                    </center>
                                                                                </div>
                                                                                <div class="clearfix"></div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="submit" name="Submit" class="btn btn-success">Merg</button>
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>

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
                                                <td colspan="8">
                                                    <center>No Record</center>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="8">
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
<script>
    function selectProposal(v = '') {

        $.ajax({
            type: 'POST',
            url: 'ApproveProcess.php?Proposals=Proposals&&v=' + v,

            success: function(html) {
                $('#body').html(html);
            }
        });
    }
    document.getElementById('proposal').onchange = function() {
        var selected = [];
        for (var option of document.getElementById('pets').options) {
            if (option.selected) {
                selected.push(option.value);
            }
        }
        alert('event started ');
        alert(selected);
    }
</script>
<?php
} else {
    header("Location: http://localhost/system/page-login.php");
} ?>