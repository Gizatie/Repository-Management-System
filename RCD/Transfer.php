<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="assets/css/lib/bootstrap.min.css">
<link rel="stylesheet" href="assets/fonts/FontAwesome.otf">
<?php

session_start();
if (isset($_SESSION['StaffId'])&&$_SESSION['StaffType']==='RCD'){
    $_SESSION["Sidebar"]="Transfer";
require_once '../Common/DataBaseConnection.php';
require_once '../Common/Head.php' ?>

<body>
<!--Side bar Starts-->
<?php require_once 'sidebar.php'; ?>
<!--Side bar Ends -->
<?php

$Department = $_SESSION["Department"];
?>
<!-- /# sidebar -->

<?php
require_once '../Common/Header.php'; ?>


<?php
//  to the button action on each proposal is clicked
if(isset($_REQUEST["Transfer"])){
    $oldType=$_REQUEST["Type"];
    $newType=$_REQUEST["Action"];
    $proposalID=$_REQUEST["ProposalId"];
    $conn->query("UPDATE proposal SET Type='" . $newType.   "' WHERE ID='" . $proposalID . "'");
    echo "<script>alert('Successfully Updated') </script>";
}
$input = filter_input_array(INPUT_POST);
if (isset($input['action'] ) && $input['action'] == 'edit') {
    $Rcc_Status='Approved';
    $conn->query("UPDATE proposal SET Cost='" . $input['Budget'] .  "', Rcc_level='" . $Rcc_Status . "' WHERE id='" . $input['ID'] . "'");
} else if (isset($input['action'] ) &&$input['action'] == 'delete') {
    $conn->query("UPDATE proposal SET deleted=1 WHERE id='" . $input['id'] . "'");
} else if (isset($input['action'] ) &&$input['action'] == 'restore') {
    $conn->query("UPDATE proposal SET deleted=0 WHERE id='" . $input['id'] . "'");
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

                            <select class="form-control" id="Faculty"  name="Faculty"
                                    required>
                                <option value="0">Select Faculty </option>
                                <option value="Technology">Technology</option>
                                <option value="Health Science ">Health Science </option>
                                <option value="Natural Computaional Science ">Natural Computaional Science </option>
                                <option value="Business and Economics">Business and Economics  </option>
                                <option value="Agriculture">Agriculture   </option>
                                <option value="Other Social Science ">Other Social Science   </option>
                                <!-- <option value="Community Service"></option> -->
                            </select>
                        </div>
                    </div>
                   <div class="form-group">
                        <div class="col-lg-12">
                            <label class="control-label">Select Department</label>

                            <select class="form-control" id="Department"  name="department"
                                    required>
                                <option value="0">Select Department </option>
                                <option value="Information technology">Information technology</option>
                                <option value="Computer Science">Computer Science</option>
                                <!-- <option value="Community Service"></option> -->
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <label class="control-label">Select Document Type</label>

                            <select class="form-control" id="Type" onchange="SelectDocuments(this.value)" name="Type"
                                    required>
                                <option value="0">Select Department Type</option>
                                <option value="Research">Research</option>
                                <option value="Technology Transfer">Technology Transfer</option>
                                <option value="Community Service">Community Service</option>
                            </select>
                        </div>
                    </div>
                    <table id="tabledit" class="table table-sm table-bordered">
                        <thead class="table-light">
                        <tr>
                            <th class="ID"><center>#</center></th>
                            <th class="Title"><center>Title</center></th>
                            <th class="File"><center>File</center></th>
                            <th class="Rcc_Status"><center>Principal</center></th>
                            <th class="Type"><center>Type</center></th>
                            <th class="Transfer"><center>Transfer To</center></th>
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
<script src="assets/js/jquery.tabledit.js"></script>
<script src="assets/js/jquery.tabledit.min.js"></script>


</body>

</html>
<script type="text/javascript">

    function SelectDocuments(v = '') {
       const dep= document.getElementById("Department").value;
       const fac= document.getElementById("Faculty").value;
        // alert("the value of V is "+dep );

        // alert("Faculty: "+Faculty+" Department : "+Department+" year : "+Year+" V : "+v);
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess?Transfer=Transfer&&v=' + v+"&&Department="+dep+"&&Faculty="+fac,

            success: function (html) {
                $('#tbody').html(html);
                // alert("content is loaded");
                // tableData();
            }
        });
    }

    function tableData() {
        $('#tabledit').Tabledit({
            url: 'Budget_Entry.php',
            eventType: 'dblclick',
            deleteButton: true,
            method: 'POST',
            hideIdentifier: false,
            columns: {
                identifier: [0, 'ID'],
                editable: [
                    [6, 'Transfer']]
            },
            onSuccess: function(data, textStatus, jqXHR) {
                // alert('Successfully Updated');
                // SelectDocuments('');
            },
            onFail: function(jqXHR, textStatus, errorThrown) {
                console.log('onFail(jqXHR, textStatus, errorThrown)');
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            },
            onAjax: function(action, serialize) {
                console.log('onAjax(action, serialize)');
                console.log(action);
                console.log(serialize);
            },
            buttons: {
                edit: {
                    class: 'btn btn-sm btn-default',
                    html: '<span class="ti-pencil"></span>',
                    action: 'edit'
                },
                delete: {
                    class: 'btn btn-sm btn-default',
                    html: '<span class="ti-close"></span>',
                    action: 'delete'
                },
                save: {
                    class: 'btn btn-sm btn-success',
                    html: 'Save'
                },
                restore: {
                    class: 'btn btn-sm btn-warning',
                    html: 'Restore',
                    action: 'restore'
                },
                confirm: {
                    class: 'btn btn-sm btn-danger',
                    html: 'Confirm'
                }
            }

        });
    }
</script>
<?php
}else{
    header("Location: http://localhost/system/page-login.php");
}?>