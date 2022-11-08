<!DOCTYPE html>
<html lang="en">

<?php
session_start();
$selected_Type = null;
$selected_Department = null;
$selected_Proposal = null;
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'RCC' || $_SESSION['StaffType'] === 'RCD'  || $_SESSION['StaffType'] === 'Researcher' || $_SESSION['StaffType'] === 'Department' || $_SESSION['StaffType'] === 'Faculty') {
    $_SESSION["Sidebar"] = 'Update_Budget_Researcher';
    $_SESSION["as"] = 'Researcher';
    require_once '../Common/DataBaseConnection.php';
    require_once '../Common/Head.php';
    require_once('../Common/Functions.php');
    // require_once '../Common/Functions.php';

?>

    <body>

        <!--Sidebar starts-->

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
        require_once '../Common/Header.php';  ?>
        <?php
        //  to the button action on each proposal is clicked
        $input = filter_input_array(INPUT_POST);
        if (isset($input['action']) && $input['action'] == 'edit') {
            echo "<script>alert('Successfully updated');</script>";
            $data = explode("/", $input['ID']);
            $proposalID = $data[0];
            $updateColumn = $data[1];
            $bugetData = $input['Total_Birr'];

            $bugetdetailData = '';
            if (!isset($input['numbers'])) {
                $bugetdetailData = "industry standard ";
            } else {
                $bugetdetailData = $input['numbers'];
            }
            if ($data[2] === 'Community Service') {
                $stmt = $conn->prepare('UPDATE community_service_budget set ' . $updateColumn . ' = ? where Proposal_ID = ?  ');
                $stmt->bind_param('di', $bugetData, $proposalID);
            } else {
                $stmt = $conn->prepare('UPDATE budget set ' . $updateColumn . ' = ? where Proposal_ID = ?  ');
                $stmt->bind_param('di', $bugetData, $proposalID);
            }
            if ($stmt->execute()) {
                if ($data[2] === 'Community Service') {
                    $stmt = $conn->prepare('UPDATE community_service_budget_details set ' . $updateColumn . ' = ? where Proposal_ID = ?  ');
                    $stmt->bind_param('si', $bugetdetailData, $proposalID);
                } else {
                    $stmt = $conn->prepare('UPDATE budget_detail set ' . $updateColumn . ' = ? where Proposal_ID = ?  ');
                    $stmt->bind_param('si', $bugetdetailData, $proposalID);
                }
                if ($stmt->execute()) {
                    echo "<script>alert('Successfully updated');</script>";
                } else {
                    echo "<script>alert('Failed to updated');</script>";
                }
            }
        } else if (isset($_POST['Submit'])) {
            $Title = $_POST['Title'];
            $Typedata = explode("/", $_POST['Type']);
            $Type = $Typedata[0];
            $proID = $Typedata[1];
            $Term = $_POST['Term'];
            $name = 'None';
            if ($_FILES['File']['name']) {
                $name = rand(1000, 10000) . '-' . $_FILES['File']['name'];
            }

            $Abstract = $_POST['Abstract'];
            $Faculty = $_SESSION['Faculty'];
            $Department = $_SESSION['Department'];
            $date = $_POST['Year'] . '-01' . '-01';
            $user_id = $_SESSION['StaffId'];
            $ParticipantsData = explode("/", $_POST['participants']);
            $OldNumberofParticipants = $ParticipantsData[0];
            $NumberofNewParticipants = $ParticipantsData[1];
            echo 'number of Old Participants= ' . $OldNumberofParticipants;
            echo 'number of new Participants= ' . $NumberofNewParticipants;
            if ($OldNumberofParticipants > 0) {
                $stmt = $conn->prepare('DELETE from  participant where Role!="PI" and Proposal_ID= ?');
                $stmt->bind_param('i', $proID);
                $stmt->execute();

                while ($OldNumberofParticipants > 0) {
                    $IDData = 'par_' . trim($OldNumberofParticipants) . '';
                    $OldStaffIDData = explode("-", $_POST[$IDData]);
                    $OldstaffID = $OldStaffIDData[0];
                    if (isset($OldStaffIDData[1])) {
                        $OldstaffID = $OldStaffIDData[1];
                    }
                    // echo 'olds participant new  ID= '.$OldstaffID;
                    $role = 'CO';
                    // echo 'dddddddddddddddddddd' . $OldstaffID . $proID;
                    $stmt1 = $conn->prepare("INSERT into participant (Staff_ID,Proposal_ID,Role) VALUES (?,?,?)");
                    $stmt1->bind_param('sis', $OldstaffID, $proID, $role);
                    if ($stmt1->execute()) {
                        // echo 'Deeeleted' . $OldstaffID . $proID . 'Role' . $role;
                    } else {
                        echo $stmt1->error;
                    }
                    $OldNumberofParticipants--;
                }
            }
            // echo $Title . ' ' . $Type . ' ' . $name . ' ' . $date . ' ' . $Abstract . ' ' . $Term . ' ' . $proID;
            $stmt = $conn->prepare('UPDATE  proposal set Title=?, Type=?, File=?,date=?,Abstract=?,Term=? where ID = ? ');
            $stmt->bind_param('sssssii', $Title, $Type, $name, $date, $Abstract, $Term, $proID);
            $stmt->execute();
            $last = $proID;
            $file = $_FILES['File'];
            $tname = $_FILES['File']['tmp_name'];
            $size = $_FILES['File']['size'];
            $file_type = $_FILES['File']['type'];
            if ($NumberofNewParticipants > 0) {
                while ($NumberofNewParticipants > 0) {
                    $role = 'Co';
                    $staffId_String = 'participant_' . $NumberofNewParticipants;
                    $user = $_POST[$staffId_String];
                    echo $user;
                    $stmt = $conn->prepare('INSERT INTO participant (	Staff_ID, Proposal_ID, Role) VALUES (?, ?, ?)');
                    $stmt->bind_param('sis', $user, $last, $role);
                    if (validateUser($user, $user_id)) {
                        if ($stmt->execute()) {
                            $status = 'Successful';
                        } else {
                            $status = 'Failed';
                        }
                    }
                    $NumberofNewParticipants--;
                }
            }

            if ($_FILES['File']['type']) {

                $status = '';
                $moved = '';
                $role = '';
                $num = 0;
                switch ($Type) {
                    case 'Research':
                        move_uploaded_file($tname, '../Documents/Research/Proposal/' . $name);
                        $moved = 'Successful';
                        break;
                    case 'Technology Transfer':
                        move_uploaded_file($tname, '../Documents/Technology Transferer/Proposal/' . $name);
                        $moved = 'Successful';
                        break;
                    case 'Community Service':
                        move_uploaded_file($tname, '../Documents/Community Service/Proposal/' . $name);
                        $moved = 'Successful';
                        break;
                    case 'Thesis':
                        move_uploaded_file($tname, '../Documents/Thesis/Proposal/' . $name);
                        $moved = 'Successful';
                        break;
                    case 'Project':
                        move_uploaded_file($tname, '../Documents/Project/Proposal/' . $name);
                        $moved = 'Successful';
                        break;
                }

                if ($status === 'Successful' && $moved === 'Successful') {

                    $conn->Commit();
                    echo "<script>alert('Successfully Submitted')</script>";
                } else {
                    $conn->rollback();
                    echo "<script>alert('Submitted Failed!!!! ')</script>";
                }
            } else {
                echo $stmt->error;
            }
        } elseif (isset($_REQUEST["Proposal_ID"])) {

            $proposal_Id = $_REQUEST["Proposal_ID"];
            //    echo "ddddddddddddddddddddddddgggggddddd".$proposal_Id;
            echo "<script>alert('going to merge $proposal_Id')</script>";
        } elseif (isset($_POST['Submit'])) {

            $conn->begin_transaction();
            $values = $_POST['ary'];
            $new_data = explode('/', $values[0]);
            $GLOBALS["selected_Department"] = $new_data[3];
            $GLOBALS["selected_Type"] = $new_data[2];
            $GLOBALS["selected_Proposal"] = $new_data[1];
            Merg($values);
        }
        ?>
        <div class="content-wrap">
            <div class="main">
                <div class="container-fluid">
                    <section id="main-content">
                        <div class="row">

                            <div class="col-lg-4">
                                <label class="control-label">Select Type</label>
                                <select class="form-control" id="Type" onchange="selectProposal(this.value)" id="Type" required>
                                    <option value="">Select Department Type</option>
                                    <option value="Research/<?php echo $_SESSION["Department"]; ?>">Research</option>
                                    <option value="Technology Transfer/<?php echo $_SESSION["Department"]; ?>">Technology Transfer</option>
                                    <option value="Community Service/<?php echo $_SESSION["Department"]; ?>">Community Service</option>
                                </select>
                            </div>

                            <div class="col-lg-12" id="tbody">

                            </div>
                        </div>
                        <?php require_once '../Common/Footer.php'; ?>
                    </section>
                </div>
            </div>
        </div>
        <!-- Common -->


        <script src="../Common/Scripts.js"></script>
    </body>

</html>
<script>
    function validation(serialize) {
        const totalbirr = serialize.split("&")[2].split("=");

        if (isNaN(totalbirr[1])) {

            alert("Total can't be a string");
            return false;
        } else if (totalbirr[1] < 0) {
            alert("Total can't be Negative");
            return false;
        }
    }

    function tableData() {
        $('.tabledit').Tabledit({
            url: 'Update_Budget.php',
            eventType: 'dblclick',
            deleteButton: false,
            method: 'POST',
            hideIdentifier: true,
            columns: {
                identifier: [0, 'ID'],
                editable: [
                    [2, 'numbers'],
                    [3, 'Total_Birr']
                ]
            },
            onSuccess: function(data, textStatus, jqXHR) {
                alert('Successfully Updated');
                selectProposal();
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
                validation(serialize);
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

    function Participants(v = '') {

        // let par = '';
        var fff = document.getElementById('par');
        fff.innerHTML = '';

        var num = v.split("/");
        v = num[1];
        alert(v);
        if (v > 0) {
            // var parent = document.getElementById('par');
            var html = '';
            for (let i = 0; i < v; i++) {

                html += '<div class="col-lg-3"><label class="control-label">Enter Participant ID</label><input type="text" min="1"  class="form-control" name="participant_' + (1 + i) + '"></div>';
                // parent.after(html);

            }
            fff.innerHTML += html;
        }
    }

    function selectProposal(v = '') {
        // alert(v);
        const myArray = v.split("/");
        var data = '';
        if (myArray[2]) {
            data = 'Department=' + myArray[1] + '&&v=' + myArray[0] + '&&Faculty=' + myArray[2];
        } else {
            data = 'Department=' + myArray[1] + '&&v=' + myArray[0];
        }
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess.php?Budget_Researcher=Budget&&' + data,

            success: function(html) {
                $('#tbody').html(html);
                tableData();
            }
        });

    }
</script>
<?php
} else {
    header("Location: http://localhost/system/page-login.php");
}
?>