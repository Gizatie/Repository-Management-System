<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="assets/css/lib/bootstrap.min.css">
<link rel="stylesheet" href="assets/fonts/FontAwesome.otf">
<?php

session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Department') {
    $_SESSION["Sidebar"] = "Assign_Committee_Work";
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
            $Committee = $input['Committee'];
            $ID = $input['ID'];
            $stmt = $conn->prepare("UPDATE staffs set Committee = ?   where ID=?");
            $stmt->bind_param("ss", $Committee, $ID);
            $stmt->execute();
        } else if (isset($input['action']) && $input['action'] == 'delete') {
            $Committee = $input['Committee'];
            $ID = $input['ID'];
            $stmt = $conn->prepare("UPDATE staffs set Committee = ?   where ID=?");
            $stmt->bind_param("ss", $Committee, $ID);
            $stmt->execute();
        } else if (isset($input['action']) && $input['action'] == 'restore') {
            $Committee = $input['Committee'];
            $ID = $input['ID'];
            $stmt = $conn->prepare("UPDATE staffs set Committee = ?   where ID=?");
            $stmt->bind_param("ss", $Committee, $ID);
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
                                    <label class="control-label">Select Department </label>

                                    <select class="form-control" onchange="selectProposal(this.value)" id="Department" name="Department" required>
                                        <option value="0">Select Department </option>
                                        <?php
                                        $stmt = $conn->prepare("select * from department as d where d.Name=? ");
                                        $Faculty = $_SESSION["Department"];
                                        $stmt->bind_param("s", $Faculty);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if ($result->num_rows > 0) {
                                            while ($data = $result->fetch_assoc()) {
                                        ?>
                                                <option value="<?php echo $data["Name"] ?>"><?php echo $data["Name"] ?></option>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <option value="0">No Department Found</option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                           
                            <table id="tabledit" class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ID">
                                            <center>ID</center>
                                        </th>
                                        <th class="First_Name">
                                            <center>First Name</center>
                                        </th>
                                        <th class="Middle_Name">
                                            <center>Middle Name </center>
                                        </th>
                                        <th class="Last_Name">
                                            <center>Last Name </center>
                                        </th>
                                        <th class="Committee">
                                            <center>Committee</center>
                                        </th>
                                        <th class="Email">
                                            <center>Email</center>
                                        </th>
                                        <th class="Phone">
                                            <center>Phone</center>
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
            url: 'Assign_Committee_Work_Process.php?Assign_Committee_Work=Assign_Committee_Work&&Department=' + v,
            success: function(html) {
                $('#tbody').html(html);
                tableData();
            }
        });
    }


    function tableData() {
        $('#tabledit').Tabledit({
            url: 'Assign_Committee_Work.php',
            eventType: 'dblclick',
            deleteButton: false,
            method: 'POST',
            hideIdentifier: false,
            columns: {
                identifier: [0, 'ID'],
                editable: [
                    [4, 'Committee', '{"Reviewer":"Reviewer","None":"None"}']
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