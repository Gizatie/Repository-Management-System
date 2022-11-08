<?php
session_start();
if (isset($_SESSION["StaffId"]) && $_SESSION["StaffType"] === "Admin") {
    $_SESSION["Sidebar"] = "Deactivate_Account";
    require_once '../Common/DataBaseConnection.php';


    require_once '../Common/Head.php';
?>

    <body>
        <?php
        require_once 'sidebar.php';
        ?>
        <!-- /# sidebar -->

        <?php
        //require_once 'sidebar.php';
        require_once '../Common/Header.php';

        if (isset($_GET["Action"])) {
            $conn->begin_transaction();
            $StaffID = $_GET["StaffId"];
            $Action = $_GET["Action"];
            $stmt = $conn->prepare("UPDATE staffs  set Status=? where ID=?");
            $stmt->bind_param("ss", $Action, $StaffID);
            if ($stmt->execute()) {
                $Password = generateRandom(6);
                $stmt = $conn->prepare("UPDATE  users set  password=? where StaffId=?");
                $stmt->bind_param("ss", $Password, $StaffID);
                if ($stmt->execute()) {
                    $conn->commit();
                    if ($Action === 'Activated') {
                        echo '<script>alert("User Account SuccessFully Activated")</script>';
                    } else {
                        echo '<script>alert("User Account SuccessFully Deactivated")</script>';
                    }
                } else {
                    $Status = 'Failed Account Creation';
                }
            } else {
                $Status = 'Failed Account Creation';
            }
        }
        $input = filter_input_array(INPUT_POST);
        if (isset($input['action']) && $input['action'] == 'edit') {
            $ID = $input['ID'];
            $First_Name=$input['First-Name'];
            $Middle_Name=$input['Middle-Name'];
            $Last_Name=$input['Last-Name'];
            $Status=$input['Status'];
            $Role=$input['Role'];
            $Sex=$input['Sex'];
            $Email=$input['Email'];
            $Phone=$input['Phone'];
            $stmt = $conn->prepare("UPDATE staffs  set First_Name=?,Middle_Name=?,Last_Name=?,Sex=?,Role=?, Status=?,Email=?,Phone=? where ID=?");
            $stmt->bind_param("sssssssss", $First_Name, $Middle_Name, $Last_Name, $Sex, $Role, $Status, $Email, $Phone, $ID);
            
            if ($stmt->execute()) {
                echo '<script>alert("Successfully Update");</script>';
            }
        } else if (isset($input['action']) && $input['action'] == 'delete') {
        } else if (isset($input['action']) && $input['action'] == 'restore') {
        }
        ?>


        <div class="content-wrap">
            <div class="main">
                <div class="container-fluid">
                    <!-- /# row -->
                    <section id="main-content">
                        <div class="row">
                            <!-- /# column -->
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-title">
                                        <h4>Account Manage</h4>

                                    </div>
                                    <div class="card-body">
                                        <div class="horizontal-form-elements">
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-lg-12">
                                                        <label class="control-label">Select Faculty</label>
                                                        <select class="form-control" onchange="selectDepartment(this.value)" name="Faculty" id="faculty">
                                                            <option value="0">Select Faculty</option>
                                                            <?php
                                                            $query = 'Select * from faculty';
                                                            $stmt = $conn->query($query);
                                                            if ($stmt->num_rows > 0) {
                                                                while ($data = $stmt->fetch_assoc()) {
                                                            ?>
                                                                    <option value="<?php echo $data['Name'] ?>"><?php echo $data['Name'] ?></option>
                                                            <?php
                                                                }
                                                            }
                                                            ?>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-lg-12 col-lg-3">
                                                        <label class="control-label">Select Department</label>
                                                        <select class="form-control" onchange="SelectDocuments(this.value)" id="dep" name="Department" required>
                                                            <option value="0">Select Faculty First</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <table class="table table-sm table-bordered" id="tabledit">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th class="ID">
                                                                <center>ID</center>
                                                            </th>
                                                            <th class="First-Name">
                                                                <center>First Name</center>
                                                            </th>
                                                            <th class="Middle-Name">
                                                                <center>Middle Name</center>
                                                            </th>
                                                            <th class="Last-Name">
                                                                <center>Last Name</center>
                                                            </th>
                                                            <th class="Sex">
                                                                <center>Sex</center>
                                                            </th>
                                                            <th class="Role">
                                                                <center>Role</center>
                                                            </th>
                                                            <th class="Status">
                                                                <center>Status</center>
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
                                            <!-- /# column -->
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- /# card -->
                        </div>

                    </section>
                    <!-- /# column -->
                </div>
                <?php require_once '../Common/Footer.php'; ?>
                </section>
            </div>
        </div>


    <?php
} else {
    header("Location: ../page-login.php");
}

    ?>
    </body>

    </html>

    <script>
        function SelectDocuments(v = '') {

            var Faculty = document.getElementById('faculty').value;
            var Department = document.getElementById('dep').value;
            // alert(Faculty+' '+Department);
            $.ajax({
                type: 'POST',
                url: 'Process.php?Approve=Approve&&Faculty=' + Faculty + '&&Department=' + Department + '&&v=' + v,

                success: function(html) {
                    $('#tbody').html(html);
                    tableData();
                }
            });
        }

        function tableData() {
            // alert('hhhhhh');
            $('#tabledit').Tabledit({
                url: 'Deactivate_Account.php',
                eventType: 'dblclick',
                deleteButton: false,
                method: 'POST',
                hideIdentifier: true,
                columns: {
                    identifier: [0, 'ID'],
                    editable: [
                        [1, 'First-Name'],
                        [2, 'Middle-Name'],
                        [3, 'Last-Name'],
                        [4, 'Sex', '{"Male":"Male","Female":"Female ","Other":"Other"}'],
                        [5, 'Role', '{"Department":"Department","Faculty":"Faculty ","RCC":"RCC","RCD":"RCD","Instructor":"Instructor","VPO":"VPO","PO":"PO","Other":"Other"}'],
                        [6, 'Status', '{"Activated":"Activate","Deactivated":"Deactivate ","Suspended":"Suspend"}'],
                        [7, 'Email'],
                        [8, 'Phone'],

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
                    alert(serialize);
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

        function selectDepartment(v = '') {
            // alert(v);
            $.ajax({
                type: 'POST',
                url: 'Process.php?selectDepartment=dep&&Faculty=' + v,

                success: function(html) {
                    $('#dep').html(html);
                }
            });
        }
    </script>

    <?php
    function generateRandom($length = 6)
    {
        $characters = '-/.!#@%^<>,:&*_$?_!0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characterslength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $characterslength - 1)];
        }
        return $randomString;
    }

    ?>