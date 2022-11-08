<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="assets/css/lib/bootstrap.min.css">
<link rel="stylesheet" href="assets/fonts/FontAwesome.otf">
<?php

session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Department' || $_SESSION['StaffType'] === 'RCC'|| $_SESSION['StaffType'] === 'RCD' || $_SESSION['StaffType'] === 'Researcher'  || $_SESSION['StaffType'] === 'Faculty' ) {
    $_SESSION["Sidebar"] = "Assigned_Proposals";
    require_once '../Common/DataBaseConnection.php';
    require_once '../Common/Head.php' ?>
   <body>
        <!--Side bar Starts-->
        <?php
        $currentUserType = $_SESSION['StaffType'];
        switch ($currentUserType) {
            case 'Researcher':
                require_once '../Researcher/sidebar.php';
                break;
            case 'Department':
                require_once '../Department/sidebar.php';
                break;
            case 'RCC':
                require_once '../RCC/sidebar.php';
                break;
            case 'RCD':
                require_once '../RCD/sidebar.php';
                break;
            case 'Faculty':
                require_once '../Faculty/sidebar.php';
                break;
        }
        ?>
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
            $ProposalID = $input['ID'];
            $Comment = $input['Comment'];
            $Decession = $input['Decession'];
            $stmt = $conn->prepare("UPDATE proposal set Committee_Decision=?, Comment=? where ID=?");
            $stmt->bind_param("ssi", $Decession,$Comment,$ProposalID);
            $stmt->execute();
        } else if (isset($input['action']) && $input['action'] == 'delete') {
            $ProposalID = $input['ID'];
            $Comment = $input['Comment'];
            $Decession = $input['Decession'];
            $stmt = $conn->prepare("UPDATE proposal set Committee_Decision=?, Comment=? where ID=?");
            $stmt->bind_param("ssi", $Decession,$Comment,$ProposalID);
            $stmt->execute();
        } else if (isset($input['action']) && $input['action'] == 'restore') {
            $ProposalID = $input['ID'];
            $Comment = $input['Comment'];
            $Decession = $input['Decession'];
            $stmt = $conn->prepare("UPDATE proposal set Committee_Decision=?, Comment=? where ID=?");
            $stmt->bind_param("ssi", $Decession,$Comment,$ProposalID);
            $stmt->execute();
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
                                    <label class="control-label">Select Type </label>

                                    <select class="form-control" onchange="selectProposals(this.value)" id="Type" name="Type">
                                        <option value="0">Select Type </option>
                                        <option value="Research">Research</option>
                                        <option value="Community Service">Community Service</option>
                                        <option value="Technology Transfer">Technology Transfer</option>
                                        <option value="Thesis">Thesis</option>
                                        <option value="Project">Project</option>
                                    </select>
                                </div>
                            </div>
                            
                            <table id="tabledit" class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                    <th class="ID">
                                            <center>ID</center>
                                        </th>
                                        <th class="Title">
                                            <center>Title</center>
                                        </th>
                                        <th class="File">
                                            <center>File</center>
                                        </th>
                                        <th class="Decession">
                                            <center>Decession</center>
                                        </th>
                                        <th class="Comment"  style="width: 50%">
                                            <center>Comment</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                 <tr><td colspan="5"><center>Select Type First</center></td></tr>

                                </tbody>

                            </table>
                        </div>
                        <?php require_once '../Common/Footer.php';?>
                    </section>
                </div>
            </div>
        </div>


        <!-- Common -->
        


    </body>

</html>
<script type="text/javascript">
    function selectProposals(v='') {
        // alert(v);
        $.ajax({
            type: 'POST',
            url: 'Process.php?ViewProposal=ViewProposal&&Type=' + v,
            success: function(html) {
                $('#tbody').html(html);
                tableData();
            }
        });
    }


    function tableData() {
        $('#tabledit').Tabledit({
            url: 'Assigned_Proposals.php',
            eventType: 'dblclick',
            deleteButton: false,
            method: 'POST',
            hideIdentifier: false,
            columns: {
                identifier: [0, 'ID'],
                editable: [
                    [3, 'Decession','{"Approve":"Approve","Reject":"Reject","Suspend":"Suspend"}'],
                    [4,'Comment']
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
                // alert(textStatus.log);
            },
            onAjax: function(action, serialize) {
                console.log('onAjax(action, serialize)');
                console.log(action);
                console.log(serialize);
                // alert(serialize);
                // alert(action);
            },
            buttons: {
                edit: {
                    class: 'btn btn-sm btn-default',
                    html: '<span class="ti-pencil"></span>',
                    action: 'edit'
                },

                save: {
                    class: 'btn btn-sm btn-success',
                    html: 'Save'
                }
                
            }

        });
    }
</script>
<?php
} else {
    header("Location: http://localhost/system/page-login.php");
} ?>