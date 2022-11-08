<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="assets/css/lib/bootstrap.min.css">
<link rel="stylesheet" href="assets/fonts/FontAwesome.otf">
<?php

session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'RCD') {
    $_SESSION["Sidebar"] = "Approve_Agreement";
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

        $input = filter_input_array(INPUT_POST);
        if (isset($input['action']) && $input['action'] == 'edit') {
                $stmt = $conn->prepare("UPDATE proposal set RCD_Agreement_Status=?,Status=? where ID =?");
                $status = $input['RCC_Status'];
                $Final_status ='';
                $ProposalID = $input['ID'];
                switch($input['RCC_Status']){
                    case $status==="Approved":
                        $Final_status="On Progress";
                        break;
                    case $status==="Suspended":
                        $Final_status="Suspended for Agreement Approval";
                        break;
                    case $status==="Rejected":
                        $Final_status="Rejected for Agreement Approval";
                        break;
                    default :
                        $Final_status=$status;
                        break;        
                }
                $stmt->bind_param("ssi", $status,$Final_status, $ProposalID);
                $stmt->execute();
                
        } else if (isset($input['action']) && $input['action'] == 'delete') {
                // $stmt = $conn->prepare("UPDATE participant set RCC_Agreement_Status=? where Proposal_ID =?");
                // $status = 'Approved';
                // $ProposalID = $input['ID'];
                // $stmt->bind_param("si", $status, $ProposalID);
                // $stmt->execute();
        } else if (isset($input['action']) && $input['action'] == 'restore') {
          
            //     $stmt = $conn->prepare("UPDATE participant set RCC_Agreement_Status=? where Proposal_ID =?");
            //     $status = 'Approved';
            //     $ProposalID = $input['ID'];
            //     $stmt->bind_param("si", $status, $ProposalID);
            //     $stmt->execute();
        
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
                        <div class="col-lg-4">
                                <label class="control-label">Select Faculty</label>
                                <select class="form-control" onchange="selectdep(this.value)" required>
                                    <option value="">Select Faculty</option>
                                    <?php
                                    $stmt = $conn->prepare("SELECT * from faculty");
                                    if ($stmt->execute()) {
                                        $result = $stmt->get_result();
                                        while ($data = $result->fetch_assoc()) {
                                    ?>
                                            <option value="<?php echo $data["Name"]; ?>"><?php echo $data["Name"]; ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        <div class="col-lg-4">
                                <label class="control-label">Select Department  </label>
                                <select class="form-control" onchange="selectType(this.value)" id="Department" required>
                                    <option value="">Select Faculty First</option>

                                </select>
                            </div>
                            
                            <div class="col-lg-4">
                                <label class="control-label">Select Type </label>
                                <select class="form-control" onchange="SelectDocuments(this.value)" id="Type" required>
                                    <option value="">Select Department First</option>

                                </select>
                            </div>
                            <table id="tabledit" class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ID">
                                            <center>#</center>
                                        </th>
                                        <th class="Title">
                                            <center>Title</center>
                                        </th>
                                        <th class="File">
                                            <center>File</center>
                                        </th>
                                        <th class="Budget">
                                            <center>Cost</center>
                                        </th>
                                        <th class="Budget">
                                            <center>Agreement</center>
                                        </th>
                                        <th class="RCC_Status">
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

        // alert(v);
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess.php?Approve_Agreement=Approve_Agreement&&Department=' + v,

            success: function(html) {
                $('#tbody').html(html);
                // alert('cccccccccc');
                tableData();

            }
        });
    }

    function selectType(v) {
        // alert(v);
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess.php?selectTypeRCD=selectTypeRCD&&Department=' + v,

            success: function(html) {
                $('#Type').html(html);
            }
        });
    }
    function selectdep(v) {
        // alert(v);
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess.php?selectDepRCD=selectDepRCD&&Faculty=' + v,

            success: function(html) {
                $('#Department').html(html);
            }
        });
    }
    function tableData() {
        $('#tabledit').Tabledit({
            url: 'Approve_Agreement.php',
            eventType: 'dblclick',
            deleteButton: true,
            method: 'POST',
            hideIdentifier: false,
            columns: {
                identifier: [0, 'ID'],
                editable: [
                    [5, 'RCC_Status', '{"Approved":"Approve","Rejected":"Reject","Suspended":"Suspend"}']
                ]
            },
            onSuccess: function(data, textStatus, jqXHR) {
                alert('Successfully Updated');
                SelectDocuments('');
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
                // alert(serialize);
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
} else {
    header("Location: http://localhost/system/page-login.php");
} ?>