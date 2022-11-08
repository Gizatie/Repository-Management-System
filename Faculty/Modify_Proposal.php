<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="assets/css/lib/bootstrap.min.css">
<link rel="stylesheet" href="assets/fonts/FontAwesome.otf">
<?php

session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Faculty') {
    $_SESSION["Sidebar"] = "Modify_Proposal";
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
            // echo "<script>alert('heeelo');</script>";
                $Title = $input['Title'];
                $Term = $input['Term'];
                $Budget = $input['Budget'];
                $Type = $input['Type'];
            
            
            $ID=$input['ID'];
            $Budget = $input['Budget'];
            $stmt = $conn->prepare("UPDATE proposal set Title = ?, Type=?,Term=?,Cost=?  where ID=?");
            $stmt->bind_param("ssidi", $Title,$Type,$Term,$Budget,$ID);
            $stmt->execute();
        } else if (isset($input['action']) && $input['action'] == 'delete') {
            $ID=$input['ID'];
            $status='Deleted';
            $stmt = $conn->prepare("UPDATE proposal set Status=?   where ID=?");
            $stmt->bind_param("si", $status,$ID);
            $stmt->execute();
        } else if (isset($input['action']) && $input['action'] == 'restore') {
            $ID=$input['ID'];
            $status='Approved';
            $stmt = $conn->prepare("UPDATE proposal set Status=?  where ID=?");
            $stmt->bind_param("si", $status,$ID);
            $stmt->execute();
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
                                    <label class="control-label">Select Year</label>
                                    <select class="form-control" id="Year" required>
                                        <option value="">Select Year</option>
                                        <?php
                                        $year = (int)date('Y');
                                        for ($i = $year; $i > 2001; $i--) {
                                        ?>
                                            <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                        <?php
                                        }
                                        ?>
                                        <option value="<?php echo $year ?>"><?php echo $year ?></option>

                                    </select>
                                </div>
                            </div>
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

                                    <select class="form-control" id="Type" onchange="SelectDocuments(this.value+'/Modify_Proposals')" name="Type" required>
                                        <option value="0">Select Department First</option>
                                    </select>
                                </div>
                            </div>
                            <table id="tabledit" class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ID"> <center>ID</center></th>
                                        <th class="Title"><center>Title</center></th>
                                        <th class="Term"> <center>Term</center></th>
                                        <th class="File"><center>File</center></th>
                                        <th class="Type"><center>Type</center></th>
                                        <th class="Budget"><center>Budget</center></th>
                                        <th class="PI"><center>PI</center></th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">


                                </tbody>

                            </table>
                        </div>
                        <?php require_once '../Common/Footer.php'; ?>
                    </section>
                </div>
            </div>
        </div>
    </body>

</html>
<script type="text/javascript">
    function SelectDocuments(v = '') {
        const data=v.split("/");
            var type=data[0];
            // alert(type);
            var Faculty = document.getElementById('faculty').value;
            var Department = document.getElementById('dep').value;
            var Year = document.getElementById('Year').value;
        // alert("Faculty: "+Faculty+" Department : "+Department+" year : "+Year+" V : "+v);
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess.php?Modify_Proposal=Modify_Proposal&&Faculty=' + Faculty + '&&Department=' + Department + '&&Year=' + Year + '&&v=' + type,

            success: function(html) {
                $('#tbody').html(html);
                tableData();
            }
        });  
    }

    function selectDep(v = '') {

        $.ajax({
            type: 'POST',
            url: 'ApproveProcess.php?selectDep=dep&&Faculty=' + v,

            success: function(html) {
                $('#dep').html(html);
            }
        });
    }

    function selectType(v = '') {
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess.php?selectType=dep&&Department=' + v,

            success: function(html) {
                $('#Type').html(html);
            }
        });
    }

    function tableData() {
        var ss='{"Research":"Research","Technology Transfer":"Technology Transfer","Community Service":"Community Service"}';
        // alert(passedArray.length);
        $('#tabledit').Tabledit({

            url: 'Modify_Proposal.php',
            eventType: 'dblclick',
            method: 'POST',
            hideIdentifier: false,
            deleteButton:false,
            columns: {
                identifier: [0, 'ID'],
                editable: [
                    [5, 'Budget'],
                    [1, 'Title'],
                    [2,'Term','{"1":"One Term","2":"Two Term","3":"Three Term"}'],
                    // [4, 'Type', '{<?php $size = array(1 => 1, 2 => 2, 3 => 3); $value = array(1 => "Research", 2 => "Technology Transfer", 3 => "Community Service");for ($x = 1; $x <= count($size); $x++) { echo '"' . $size[$x] . '":"' . $value[$x] . '",';} ?> "100":"Other"}']
                    [4, 'Type', '{"Research":"Research","Technology Transfer":"Technology Transfer","Community Service":"Community Service"}']
                   
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