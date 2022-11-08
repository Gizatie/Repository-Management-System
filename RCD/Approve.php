<!DOCTYPE html>
<html lang="en">

<?php
session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'RCD') {
    $_SESSION["Sidebar"] = "Approve";
    require_once '../Common/DataBaseConnection.php';
    require_once '../Common/Head.php' ?>

    <body>

        <?php
        require_once 'sidebar.php';

        $Department = $_SESSION["Department"];
        ?>
        <!-- /# sidebar -->
    
        <?php
        require_once '../Common/Header.php'; ?>


        <?php
        $Type = NULL;
        /*
 * to the button action on each proposal is clicked
 */
        if (isset($_REQUEST['Action'])) {
            $Action = $_REQUEST["Action"];
            $data = explode('/',$_REQUEST["data"]);
            $ProposalId = (int)$data[3];
            $status = '';
            $Final_status = '';
            $Type = $data[0];
            $stmt = $conn->prepare("UPDATE proposal set Rcd_level=?, Status=?  where ID=?");

            switch ($Action) {
                case 'Approve':
                    $status = 'Approved';
                    $finalStatus = "Waiting For Agrrement";
                    $stmt->bind_param("ssi", $status, $finalStatus, $ProposalId);
                    $stmt->execute();
                    echo '<script>alert("Approved Successfully ")</script>';
                    break;
                case 'Reject':
                    $status = 'Rejected';
                    $finalStatus = "Rejected";
                    $stmt->bind_param("ssi", $status, $finalStatus, $ProposalId);
                    $stmt->execute();
                    echo '<script>alert("Rejected Successfully ")</script>';
                    break;
                case 'suspend':
                    $status = 'suspended';
                    $finalStatus = "suspended";
                    $stmt->bind_param("ssi", $status, $finalStatus, $ProposalId);
                    $stmt->execute();
                    echo '<script>alert("Suspended Successfully ")</script>';
                    break;
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
                                        <li class="breadcrumb-item active">RCD/home/Approve Proposal</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <!-- /# column -->
                    </div>
                    <!-- /# row -->
                    <section id="main-content">
                        <div class="row">

                            <?php
                            /*
                     * filling the department selection
                     */
                            $stmt = $conn->prepare("select  * from faculty    order by Name ASC");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            ?>
                            <div class="form-group">
                                <div class="col-lg-12">
                                    <label class="control-label">Select Faculty </label>

                                    <select class="form-control" onchange="selectDep(this.value)" id="faculty" required>
                                        <option selected>Select Faculty</option>
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
                            <div class="form-group">
                                <div class="col-lg-12">
                                    <label class="control-label">Select Department </label>

                                    <select class="form-control" onchange="selectType(this.value)" id="dep" name="Type" required>
                                        <option value="0">Select Faculty First</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-12">
                                    <label class="control-label">Select Document Type</label>

                                    <select class="form-control" id="Type" onchange="SelectDocuments(this.value)" name="Type" required>
                                        <option value="0">Select Department First</option>
                                    </select>
                                </div>
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
                                            <center>Buget</center>
                                        </th>
                                        <th>
                                            <center>Date</center>
                                        </th>
                                        <th>
                                            <center>File</center>
                                        </th>
                                        <th>
                                            <center>Rcd_Status</center>
                                        </th>
                                        <th>
                                            <center>Final Status</center>
                                        </th>
                                        <th>
                                            <center>Action</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                           

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
<script type="text/javascript">
    function SelectDocuments(v = '') {
        
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess.php?Approve=Approve&&data=' + v,

            success: function(html) {
                $('#tbody').html(html);
            }
        });
    }

    function ChangeType(v = '') {

        // alert(v);
        $.ajax({
            type: 'POST',
            url: 'Approve.php?Action='+v,

            success: function(html) {
                // alert("Successfully updated");
                SelectDocuments(v);
            }
        });
    }

    // function SetType(v='') {
    //     $.ajax({
    //         type:'POST',
    //         url:'ApproveProcess?Type=Type&&Faculty='+Faculty+'&&Department='+Department+'&&v='+v,
    //
    //         success:function (html) {
    //             $('#tbody').html(html);
    //         }
    //     });
    // }
    function selectDep(v = '') {
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess.php?selectDepRCD=dep&&Faculty=' + v,

            success: function(html) {
                $('#dep').html(html);
            }
        });
    }

    function selectType(v = '') {
        
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess.php?selectTypeRCD=dep&&Department=' + v,

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