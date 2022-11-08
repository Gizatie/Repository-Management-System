<!DOCTYPE html>
<html lang="en">

<?php
session_start();
if (isset($_SESSION['StaffId'])&&$_SESSION['StaffType']==='RCC'){
require_once '../Common/DataBaseConnection.php';
require_once '../Common/Head.php' ?>

<body>

<?php
require_once 'sidebar.php';

//$Department = $_SESSION["Department"];
?>
<!-- /# sidebar -->

<?php
require_once '../Common/Header.php'; ?>


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
            <!-- /# row -->
            <section id="main-content">
                <div class="row">

                    <div class="form-group">
                        <div class="col-lg-12">
                            <label class="control-label">Select Faculty</label>
                            <select class="form-control"  onchange="SelectYear(this.value)" id="faculty" required>
                                <option value="">Select Faculty</option>
                                <option value="Technology">Technology</option>
                                <option value="Technology Transfer">Medicine and Health Science</option>
                                <option value="Community Service">Natural and Computational Science</option>
                                <option value="Community Service">Social Science  and Humanities </option>
                                <option value="Community Service">Business  and Economics </option>
                                <option value="Community Service">Agriculture   and Environmental Science </option>
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
                <?php require_once '../Common/Footer.php';?>
            </section>
        </div>
    </div>
</div>



</body>

</html>
<script type="text/javascript">

    function SelectDocuments(v = '') {
        // alert(v);
        var Faculty = document.getElementById('faculty').value;
        $.ajax({
            type: 'POST',
            url: '../Process?View=View&&Faculty=' + Faculty  + '&&v=' + v,

            success: function (html) {
                $('#tbody').html(html);
            }
        });
    }
function SelectYear(v='') {
    $.ajax({
        type: 'POST',
        url: '../Process?Year=Year&&v=' + v,

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
<?php
}else{
    header("Location: http://localhost/system/page-login.php");
}?>