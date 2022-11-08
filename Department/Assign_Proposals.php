<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="assets/css/lib/bootstrap.min.css">
<link rel="stylesheet" href="assets/fonts/FontAwesome.otf">
<?php

session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Department') {
    $_SESSION["Sidebar"] = "Assign_Proposals";
    require_once '../Common/DataBaseConnection.php';
    require_once '../Common/Head.php' ?>
    <?php

    $Department = $_SESSION['Department'];
    $stmt = $conn->prepare("select * from staffs as s where s.Committee='Reviewer' and s.Department=?");
    $stmt->bind_param("s", $Department);
    $stmt->execute();
    $result = $stmt->get_result();
    $reviwers = array();
    if ($result->num_rows > 0) {
        while ($data = $result->fetch_assoc()) {
            $reviwers[] = $data["ID"] . ' ' . $data["First_Name"] . ' ' . $data["Middle_Name"] . ' ' . $data["Last_Name"];
        }
    }
    $size = array();
    $value = array();
    for ($i = 0; $i < count($reviwers); $i++) {
        $data = explode(" ", $reviwers[$i]);
        $size[$i] = $data[0];
        $value[$i] = $reviwers[$i];
    }

    ?>

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
            $Reviewer = $input['Reviewer'];
            $ID = $input['ID'];
            $stmt = $conn->prepare("SELECT Staff_ID  from  participant   where Proposal_ID=?");
            $stmt->bind_param("i", $ID);
            $stmt->execute();
            $participant = $stmt->get_result();
            $participants = array();
            while ($participantData = $participant->fetch_assoc()) {
                $participants[] = $participantData["Staff_ID"];
            }

            if (!in_array($Reviewer, $participants)) {
                $stmt = $conn->prepare("UPDATE proposal set Reviewer = ?   where ID=?");
                $stmt->bind_param("si", $Reviewer, $ID);
                $stmt->execute();
            }
            
        } else if (isset($input['action']) && $input['action'] == 'delete') {
            // $Reviewer = $input['Reviewer'];
            // $ID = $input['ID'];
            // $stmt = $conn->prepare("UPDATE proposal set Reviewer = ?   where ID=?");
            // $stmt->bind_param("si", $Reviewer, $ID);
            // $stmt->execute();
        } else if (isset($input['action']) && $input['action'] == 'restore') {
            // $Reviewer = $input['Reviewer'];
            // $ID = $input['ID'];
            // $stmt = $conn->prepare("UPDATE proposal set Reviewer = ?   where ID=?");
            // $stmt->bind_param("si", $Reviewer, $ID);
            // $stmt->execute();
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

                                    <select class="form-control" onchange="selectProposal(this.value)" id="Department" name="Department" required>
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
                                        <th class="Type">
                                            <center>Type</center>
                                        </th>
                                        <th class="File">
                                            <center>File</center>
                                        </th>
                                        <th class="PI">
                                            <center>PI</center>
                                        </th>
                                        <th class="Reviewer">
                                            <center>Reviewer</center>
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
    function selectProposal(v = '') {
        // alert(v);
        $.ajax({
            type: 'POST',
            url: 'Assign_Committee_Work_Process.php?Assign_Proposal=Assign_Proposal&&Type=' + v,
            success: function(html) {
                $('#tbody').html(html);
                tableData();
            }
        });
    }


    function tableData() {
        $('#tabledit').Tabledit({
            url: 'Assign_Proposals.php',
            eventType: 'dblclick',
            deleteButton: false,
            method: 'POST',
            hideIdentifier: false,
            columns: {
                identifier: [0, 'ID'],
                editable: [
                    [5, 'Reviewer', '{<?php for ($x = 0; $x < count($size); $x++) {if ($x === count($size) - 1) {echo '"' . $size[$x] . '":"' . $value[$x] . '"';} else {echo '"' . $size[$x] . '":"' . $value[$x] . '",';}} ?> }']]
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