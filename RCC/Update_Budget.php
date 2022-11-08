<!DOCTYPE html>
<html lang="en">

<?php
session_start();
$selected_Type = null;
$selected_Department = null;
$selected_Proposal = null;
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'RCC' || $_SESSION['StaffType'] === 'RCD'  || $_SESSION['StaffType'] === 'Researcher' || $_SESSION['StaffType'] === 'Department') {
    $_SESSION["Sidebar"] = 'Update_Budget';
    $_SESSION["as"] = 'Researcher';
    require_once '../Common/DataBaseConnection.php';
    require_once '../Common/Head.php';
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
        require_once '../Common/Header.php'; ?> ?>
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
                            <?php require_once '../Common/Selector.php'; ?>
                            <div class="col-lg-12" id="tbody">

                                <div id="accordion class="">
                                    <div class="card">
                                        <div class="card-header p-3 mb-2 " style="background-color: <?php $color % 2 === 0 ? $colorvalue = '#e1ebfc' : $colorvalue = '#ffeee8';
                                                                                                    echo $colorvalue ?>;" id="heading">
                                            <h1>Select Faculty -> Department</h1>
                                            </h5>
                                        </div>
                                    </div>
                                </div>

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

    function selectProposal(v = '') {
        // alert(v);
        const myArray = v.split("/");
        var data = '';
        if (myArray[2]) {
            data = 'Department=' + myArray[1] + '&&v=' + myArray[0] + '&&Faculty=' + myArray[2];
        } else {
            data = 'Department=' + myArray[1] + '&&v=' + myArray[0];
        }

        // alert(myArray.length);
        // var Department = document.getElementById('Department').value;
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess.php?Budget=Budget&&' + data,

            success: function(html) {
                $('#tbody').html(html);
                // alert('ssssssssssss' + myArray[0] + myArray[1]);
                tableData();
            }
        });

    }

    function selectType(v = '') {

        $.ajax({
            type: 'GET',
            url: '../Common/Common_Calls.php?Type=Type&&Faculty=' + v,

            success: function(html) {
                $('#Type').html(html);
                $('#Type_modal').html(html);
            }
        });
    }
</script>
<!-- <style>
    tr.hide-table-padding td {
        padding: 0;
    }

    .expand-button {
        position: relative;
    }

    .accordion-toggle .expand-button:after {
        position: absolute;
        left: .75rem;
        top: 50%;
        transform: translate(0, -50%);
        content: '-';
    }

    .accordion-toggle.collapsed .expand-button:after {
        content: '+';
    }
</style> -->
<?php
} else {
    header("Location: http://localhost/system/page-login.php");
}
?>