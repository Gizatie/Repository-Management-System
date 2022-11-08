<!DOCTYPE html>
<html lang="en">

<?php
session_start();
if (isset($_SESSION['StaffId'])&&$_SESSION['StaffType']==='RCD'){
    $_SESSION["Sidebar"]="ApproveDocument";
require_once '../Common/DataBaseConnection.php';
require_once '../Common/Head.php' ?>

<body>

<?php
require_once 'sidebar.php';

$Department = $_SESSION["Department"];
?>
<!-- /# sidebar -->

<?php  require_once '../Common/Header.php'; ?>?>


<?php
//  to the button action on each proposal is clicked
if (isset($_REQUEST['Action'])) {
    $Action = $_REQUEST["Action"];
    $ProposalId = $_REQUEST["ProposalId"];
    $status = '';
    $stmt = $conn->prepare("UPDATE documents set Rcd_Status=?, Status=? where Proposal_ID=?");

    switch ($Action) {
        case 'Approve':
            $status = 'Approved';
            $Final_status = 'Completed';
            $stmt->bind_param("sss", $status, $Final_status,$ProposalId);
            $stmt->execute();
            echo '<script>alert("Document Approved Successfully ")</script>';
            break;
        case 'Reject':
            $status = 'Reject';
            $Final_status = 'On Progress';
            $stmt->bind_param("sss", $status,$Final_status, $ProposalId);
            $stmt->execute();
            echo '<script>alert("Document Rejected Successfully ")</script>';
            break;
        case 'Pass':
            $status = 'Passed';
            $Final_status = 'On Progress';
            $stmt->bind_param("sss", $status,$Final_status, $ProposalId);
            $stmt->execute();
            echo '<script>alert("Document Passed Successfully ")</script>';
            break;
            case 'suspend':
            $status = 'suspend';
            $Final_status = 'On Progress';
            $stmt->bind_param("sss", $status,$Final_status, $ProposalId);
            $stmt->execute();
            echo '<script>alert("Document Suspended Successfully ")</script>';
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
                            <h1><span>Debretabour University Research,CS, TT and student Projects Repository </span>
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
                    <div class="form-group">
                        <div class="col-lg-12">
                            <label class="control-label">Select Faculty</label>
                            <select class="form-control" onchange="selectDep(this.value)" id="faculty" required>
                                <option value="0">Select Faculty</option>
                                <option value="Technology">Technology</option>
                                <option value="Technology Transfer">Health</option>
                                <option value="Community Service">Humanity</option>
                                <option value="Other">Other</option>
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

                            <select class="form-control" id="Type" onchange="SelectDocuments(this.value)" name="Type"
                                    required>
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
                                <center>Date</center>
                            </th>
                            <th>
                                <center>File</center>
                            </th>
                            <th>
                                <center>Rcc_Status</center>
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

        var Faculty = document.getElementById('faculty').value;
        var Department = document.getElementById('dep').value;
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess.php?Document=document&&Faculty=' + Faculty + '&&Department=' + Department + '&&v=' + v,

            success: function (html) {
                $('#tbody').html(html);
            }
        });
    }

    // function SetType(v='') {
    //     $.ajax({
    //         type:'POST',
    //         url:'ApproveProcess.php?Type=Type&&Faculty='+Faculty+'&&Department='+Department+'&&v='+v,
    //
    //         success:function (html) {
    //             $('#tbody').html(html);
    //         }
    //     });
    // }
    function selectDep(v='') {
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess.php?selectDep=dep&&Faculty='+v,

            success: function (html) {
                $('#dep').html(html);
            }
        });
    }
    function selectType(v='') {
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess.php?selectType=dep&&Department='+v,

            success: function (html) {
                $('#Type').html(html);
            }
        });
    }
</script>
<?php
}else{
    header("Location: http://localhost/system/page-login.php");
}?>