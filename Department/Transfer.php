<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="assets/css/lib/bootstrap.min.css">
<link rel="stylesheet" href="assets/fonts/FontAwesome.otf">
<?php

session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Department') {
    $_SESSION["Sidebar"] = "Transfer";
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
        if (isset($_REQUEST["Transfer"])) {
            $oldType = $_REQUEST["Type"];
            $newType = $_REQUEST["Action"];
            $proposalID = $_REQUEST["ProposalId"];
            $conn->query("UPDATE proposal SET Type='" . $newType .   "' WHERE ID='" . $proposalID . "'");
            echo "<script>alert('Successfully Updated') </script>";
        }
        $input = filter_input_array(INPUT_POST);
        if (isset($_REQUEST["action"]) && $input['action'] == 'edit') {
            $Rcc_Status = 'Approved';
            $conn->query("UPDATE proposal SET Cost='" . $input['Budget'] .  "', Rcc_level='" . $Rcc_Status . "' WHERE id='" . $input['ID'] . "'");
        } else if (isset($_REQUEST["action"]) && $input['action'] == 'delete') {
            $conn->query("UPDATE proposal SET deleted=1 WHERE id='" . $input['id'] . "'");
        } else if (isset($_REQUEST["action"]) && $input['action'] == 'restore') {
            $conn->query("UPDATE proposal SET deleted=0 WHERE id='" . $input['id'] . "'");
        }
        ?>

        <div class="content-wrap">
            <div class="main">
                <div class="container-fluid">
                    <!-- /# row -->
                    <section id="main-content">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-lg-12">
                                    <label class="control-label">Select Document Type</label>

                                    <select class="form-control" id="Type" onchange="SelectDocuments(this.value)" name="Type" required>
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
                                        <th class="ID">
                                            <center>#</center>
                                        </th>
                                        <th class="Title">
                                            <center>Title</center>
                                        </th>
                                        <th class="File">
                                            <center>File</center>
                                        </th>
                                        <th class="Rcc_Status">
                                            <center>Principal</center>
                                        </th>
                                        <th class="Type">
                                            <center>Type</center>
                                        </th>
                                        <th class="Transfer">
                                            <center>Transfer To</center>
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
        // alert("the value of V is "+v );

        // alert("Faculty: "+Faculty+" Department : "+Department+" year : "+Year+" V : "+v);
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess.php?Transfer=Transfer&&v=' + v,

            success: function(html) {
                $('#tbody').html(html);
                // alert("content is loaded");
                tableData();
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
                    [6, 'Transfer']
                ]
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
} else {
    header("Location: http://localhost/system/page-login.php");
} ?>