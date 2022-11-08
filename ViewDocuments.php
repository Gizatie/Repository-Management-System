<!DOCTYPE html>
<html lang="en">

<?php
session_start();
require_once '../Common/DataBaseConnection.php';
require_once '../Common/Head.php' ?>

<body>

<?php
require_once 'sidebar.php';

$Department = $_SESSION["Department"];
?>
<!-- /# sidebar -->

<?php require_once 'header.php' ?>


<?php
//  to the button action on each proposal is clicked
if (isset($_REQUEST['Action'])) {
    $Action = $_REQUEST["Action"];
    $ProposalId = $_REQUEST["ProposalId"];
    $status = '';
    $stmt = $conn->prepare("UPDATE documents set Rcc_Status=? where Proposal_ID=?");

    switch ($Action) {
        case 'Approve':
            $status = 'Approved';
            $stmt->bind_param("ss", $status, $ProposalId);
            $stmt->execute();
            echo '<script>alert("Document Approved Successfully ")</script>';
            break;
        case 'Reject':
            $status = 'Reject';
            $stmt->bind_param("ss", $status, $ProposalId);
            $stmt->execute();
            echo '<script>alert("Document Rejected Successfully ")</script>';
            break;
        case 'suspend':
            $status = 'suspend';
            $stmt->bind_param("ss", $status, $ProposalId);
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
<script type="text/javascript">

    function SelectDocuments(v = '') {

        var Faculty = document.getElementById('faculty').value;
        var Department = document.getElementById('dep').value;
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess?Document=document&&Faculty=' + Faculty + '&&Department=' + Department + '&&v=' + v,

            success: function (html) {
                $('#tbody').html(html);
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
    function selectDep(v='') {
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess?selectDep=dep&&Faculty='+v,

            success: function (html) {
                $('#dep').html(html);
            }
        });
    }
    function selectType(v='') {
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess?selectType=dep&&Department='+v,

            success: function (html) {
                $('#Type').html(html);
            }
        });
    }
</script>