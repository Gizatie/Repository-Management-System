<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="assets/css/lib/bootstrap.min.css">
<link rel="stylesheet" href="assets/fonts/FontAwesome.otf">
<?php

session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'RCC') {
    $_SESSION["Sidebar"] = "Transfer";
    require_once '../Common/DataBaseConnection.php';
    require_once '../Common/Head.php';
    $selectedDepartment = null;
    $selectedType = null;
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
        if (isset($_REQUEST["Transfer"])) {
            $oldType = $_REQUEST["Type"];
            $newType = $_REQUEST["Action"];
            $proposalID = $_REQUEST["ProposalId"];
            $selectedDepartment = $_REQUEST["dep"];
            $selectedType = $_REQUEST["selectType"];
            $conn->query("UPDATE proposal SET Type='" . $newType .   "' WHERE ID='" . $proposalID . "'");
            echo "<script>alert('Successfully Updated') </script>";
        } else if (isset($_REQUEST["PIChange"])) {
            // $oldType=$_REQUEST["Type"];
            // $newType=$_REQUEST["Action"];
            $proposalID = $_REQUEST["ProposalId"];
            $OldPI = $_REQUEST["OldPI"];
            $NewPI = $_REQUEST["NewPI"];
            $Role1 = 'PI';
            $Role2 = 'Co';
            $stmt = $conn->prepare("UPDATE participant set Role = ?  where Proposal_ID=? and Staff_ID=?");
            $stmt->bind_param("sis", $Role2, $proposalID, $OldPI);
            $stmt->execute();
            $stmt = $conn->prepare("UPDATE participant set Role = ?  where Proposal_ID=? and Staff_ID=?");
            $stmt->bind_param("sis", $Role1, $proposalID, $NewPI);
            $stmt->execute();
            $selectedDepartment = $_REQUEST["dep"];
            $selectedType = $_REQUEST["Type"];
            echo "<script>alert('Successfully Updated') </script>";
        }

        if (isset($input['action']) && $input['action'] == 'edit') {
            $Rcc_Status = 'Approved';
            $conn->query("UPDATE proposal SET Cost='" . $input['Budget'] .  "', Rcc_level='" . $Rcc_Status . "' WHERE id='" . $input['ID'] . "'");
        } else if (isset($input['action']) && $input['action'] == 'delete') {
            $conn->query("UPDATE proposal SET deleted=1 WHERE id='" . $input['id'] . "'");
        } else if (isset($input['action']) && $input['action'] == 'restore') {
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
                                    <label class="control-label">Select Department</label>

                                    <select class="form-control" id="Department" name="department" required>
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

                                    <select class="form-control" id="Type" onclick="SelectDocuments(this.value)" name="Type" required>
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
                                    <?php
                                    if ($selectedDepartment) {
                                        $stmt = $conn->prepare("select DISTINCT * from Proposal as p where   p.Status!='On Progress' and  p.Status!='Completed' and p.Department_level='Approved' and p.Rcc_level!='Rejected' and  p.Type=? and p.Faculty=? and p.Department=? order by p.Rcc_level ASC");
                                        $Type = $selectedType;
                                        $Faculty = $_SESSION["Faculty"];
                                        $Department = $selectedDepartment;
                                        // echo $Type . ' ' . $Faculty . ' ' . $Department;
                                        $stmt->bind_param("sss", $Type, $Faculty, $Department);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if ($result->num_rows) {

                                            while ($data = $result->fetch_assoc()) {
                                                $stmt = $conn->prepare("select p.Staff_ID from participant as p where   p.Proposal_ID=? and p.Role='PI'");
                                                $ID = $data["ID"];
                                                $stmt->bind_param("s", $ID);
                                                $stmt->execute();
                                                $res = $stmt->get_result();
                                                $PI = null;
                                                $staffID=null;
                                                if ($res->num_rows) {
                                                    $row = $res->fetch_assoc();
                                                    $staffID = $row["Staff_ID"];
                                                    $stmt = $conn->prepare("select s.First_Name,s.Middle_Name,s.Last_Name from staffs as s where   s.ID=? ");

                                                    $stmt->bind_param("s", $staffID);
                                                    $stmt->execute();
                                                    $res2 = $stmt->get_result();
                                                    if ($res2->num_rows) {
                                                        $row2 = $res2->fetch_assoc();
                                                        $PI = $row2["First_Name"] . " " . $row2["Middle_Name"] . " " . $row2["Last_Name"];
                                                    }
                                                }
                                                            ?>
                                                <tr>
                                                    <td>
                                                        <center><?php echo $data["ID"] ?></center>
                                                    </td>
                                                    <td>
                                                        <center><?php echo $data["Title"] ?></center>
                                                    </td>

                                                    <td>
                                                        <center>
                                                            <a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">

                                                            <button class="btn btn-primary dropdown-toggle btn-md" type="button" data-toggle="dropdown">Action
                                                                <span class="caret"></span></button>
                                                            <ul class="dropdown-menu">
                                                                <?php
                                                                $poposal = $data["ID"];
                                                                $stmt5 = $conn->prepare("SELECT s.ID,s.First_Name,s.Middle_Name,s.Last_Name,pa.Role,pa.Proposal_ID FROM participant as pa INNER JOIN staffs as s ON pa.Proposal_ID=? and s.ID = pa.Staff_ID");

                                                                $stmt5->bind_param("i", $poposal);
                                                                $OldPI = $staffID;
                                                                if ($stmt5->execute()) {
                                                                    
                                                                    $participants = $stmt5->get_result();
                                                                    
                                                                    while ($participants_data = $participants->fetch_assoc()) {
                                                                
                                                                ?>
                                                                        <li>
                                                                            <a <?php ?>href="Transfer.php?PIChange=PIChange&&OldPI=<?php echo $OldPI; ?>&&ProposalId=<?php echo $poposal;
                                                                                             $role = '';
                                                                               $participants_data['Role'] === 'PI' ? $role = '(PI)' : $role = ''; ?>&&NewPI=<?php echo $participants_data['ID'] ?>&&dep=<?php echo $data['Department'] ?>&&Type=<?php echo $data['Type'] ?>"><?php echo $participants_data['First_Name'] . ' ' . $participants_data['Middle_Name'] . $role ?></a>
                                                                        </li>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>

                                                            </ul>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <center><?php echo $data["Type"] ?></center>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">Action
                                                                <span class="caret"></span></button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a href="Transfer.php?Transfer=Transfer&&Action=Research&&ProposalId=<?php echo $data['ID'] ?>&&Type=<?php echo $data['Type'] ?>&&selectType=<?php echo $Type ?>&&dep=<?php echo $Department ?>">Research</a>
                                                                </li>
                                                                <li>
                                                                    <a href="Transfer.php?Transfer=Transfer&&Action=Technology Transfer&&ProposalId=<?php echo $data['ID'] ?>&&Type=<?php echo $data['Type'] ?>&&selectType=<?php echo $Type ?>&&dep=<?php echo $Department ?>">Technology Transfer</a>
                                                                </li>
                                                                <li>
                                                                    <a href="Transsfer.php?Transfer=Transfer&&Action=Comunity Service&&ProposalId=<?php echo $data['ID'] ?>&&Type=<?php echo $data['Type'] ?>&&selectType=<?php echo $Type ?>&&dep=<?php echo $Department ?>">Community Service</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="6">
                                                    <center>No Record Found</center>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php require_once '../Common/Footer.php'; ?>
                    </section>
                </div>
            </div>
        </div>


        <!-- Common -->
       


    </body>

</html>
<script type="text/javascript">
    function SelectDocuments(v = '') {
        const dep = document.getElementById("Department").value;
        // alert("the value of V is "+dep );

        // alert("Faculty: "+Faculty+" Department : "+Department+" year : "+Year+" V : "+v);
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess.php?Transfer=Transfer&&v=' + v + "&&Department=" + dep,

            success: function(html) {
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