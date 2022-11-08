<!DOCTYPE html>
<html lang="en">

<?php
session_start();
require_once 'Common/DataBaseConnection.php';
require_once 'Common/Head.php' ?>

<body>

<?php
//require_once 'sidebar.php';

//$Department = $_SESSION["Department"];
?>
<!-- /# sidebar -->

<?php //require_once 'header.php'?>
<?php
//  to the button action on each proposal is clicked
//if (isset($_REQUEST['Action'])) {
//    $Action = $_REQUEST["Action"];
//    $ProposalId = $_REQUEST["ProposalId"];
//    $status = '';
//    $stmt = $conn->prepare("UPDATE documents set Rcd_Status=?, Status=? where Proposal_ID=?");
//
//    switch ($Action) {
//        case 'Approve':
//            $status = 'Approved';
//            $Final_status = 'Completed';
//            $stmt->bind_param("sss", $status, $Final_status,$ProposalId);
//            $stmt->execute();
//            echo '<script>alert("Document Approved Successfully ")</script>';
//            break;
//        case 'Reject':
//            $status = 'Reject';
//            $Final_status = 'On Progress';
//            $stmt->bind_param("sss", $status,$Final_status, $ProposalId);
//            $stmt->execute();
//            echo '<script>alert("Document Rejected Successfully ")</script>';
//            break;
//        case 'Pass':
//            $status = 'Passed';
//            $Final_status = 'On Progress';
//            $stmt->bind_param("sss", $status,$Final_status, $ProposalId);
//            $stmt->execute();
//            echo '<script>alert("Document Passed Successfully ")</script>';
//            break;
//            case 'suspend':
//            $status = 'suspend';
//            $Final_status = 'On Progress';
//            $stmt->bind_param("sss", $status,$Final_status, $ProposalId);
//            $stmt->execute();
//            echo '<script>alert("Document Suspended Successfully ")</script>';
//            break;
//    }
//
//}
//?>

<div class="content-wrap">
    <div class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 p-r-0 title-margin-right">
                    <div class="page-header">
                        <div class="page-title">

                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                </li>
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
                            <select class="form-control"  onchange="SelectYear(this.value)" id="faculty" required>
                                <option value="">Select Faculty</option>
                                <option value="Technology">Technology</option>
                                <option value="Medicine and Health Science">Medicine and Health Science</option>
                                <option value="Natural and Computational Science">Natural and Computational Science</option>
                                <option value="Social Science  and Humanities">Social Science  and Humanities </option>
                                <option value="Business  and Economics">Business  and Economics </option>
                                <option value="Agriculture   and Environmental Science">Agriculture   and Environmental Science </option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <label class="control-label">Select Year </label>

                            <select class="form-control" onchange="SelectDocuments(this.value)"  id="Year"  required>
                                <option value="">Select Faculty First</option>

                            </select>
                        </div>
                    </div>

                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                        <tr>
                            <th colspan="2">
                                <center>Work List</center>
                            </th>
                            <th rowspan="2">
                                <center>Count</center>
                            </th>
                            <th rowspan="2" >
                                <center>Title</center>
                            </th>
                            <th rowspan="2">
                                <center>Budget</center>
                            </th>
                            <th colspan="2">
                                <center>No of Participant</center>
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <center>Type</center>
                            </th>
                            <th>
                                <center>Category</center>
                            </th>
                            <th>
                                <center>Female</center>
                            </th>
                            <th>
                                <center>Male</center>
                            </th>

                        </tr>
                        </thead>
                        <tbody id="tbody">
                           <tr>
                               <td colspan="7"><center>Specify The Faculty then Year</center></td>
                           </tr>

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
        // alert(v);
        var Faculty = document.getElementById('faculty').value;
        $.ajax({
            type: 'POST',
            url: 'Process?View=View&&Faculty=' + Faculty  + '&&v=' + v,

            success: function (html) {
                $('#tbody').html(html);
            }
        });
    }
function SelectYear(v='') {
    $.ajax({
        type: 'POST',
        url: 'Process?Year=Year&&v=' + v,

        success: function (html) {
            $('#Year').html(html);
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
    // function selectDep(v='') {
    //     $.ajax({
    //         type: 'POST',
    //         url: 'ApproveProcess?selectDep=dep&&Faculty='+v,
    //
    //         success: function (html) {
    //             $('#dep').html(html);
    //         }
    //     });
    // }
    // function selectType(v='') {
    //     $.ajax({
    //         type: 'POST',
    //         url: 'ApproveProcess?selectType=dep&&Department='+v,
    //
    //         success: function (html) {
    //             $('#Type').html(html);
    //         }
    //     });
    // }
</script>