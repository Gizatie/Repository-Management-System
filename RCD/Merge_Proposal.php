<!DOCTYPE html>
<html lang="en">

<?php
session_start();
$selected_Type = null;
$selected_Department = null;
$selected_Proposal = null;
// if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'RCD') {
$_SESSION["Sidebar"] = 'Merge_Proposal';
require_once '../Common/DataBaseConnection.php';
require_once '../Common/Head.php';
require_once '../Common/Functions.php';
?>

<body>

    <!--Sidebar starts-->
    <?php require_once "sidebar.php"; ?>
    <!--Sidebar Ends-->
    <!-- /# sidebar -->

    <?php  require_once '../Common/Header.php'; ?> ?>


    <?php
    //  to the button action on each proposal is clicked
    if (isset($_GET['Action'])) {
        $Action = $_GET["Action"];
        $ProposalId = $_GET["ProposalId"];
        $status = '';
        $Final_Status = '';
        $stmt = $conn->prepare("UPDATE proposal set Department_level=? where ID=?");

        switch ($Action) {
            case 'Approve':
                $status = 'Approved';
                $stmt->bind_param("ss", $status, $ProposalId);
                break;
            case 'Reject':
                $status = 'Reject';
                $stmt->bind_param("ss", $status, $ProposalId);
                break;
            case 'suspend':
                $status = 'suspend';
                $stmt->bind_param("ss", $status, $ProposalId);
                break;
        }
        if ($stmt->execute()) {
            echo '<script>alert("Action taken")</script>';
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
                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1><span></span>
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
                        <?php require_once '../Common/Selector.php';?>
                        
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        <center>#</center>
                                    </th>
                                    <th>
                                        <center>Title</center>
                                    </th>
                                    <th>
                                        <center>Type</center>
                                    </th>
                                    <th>
                                        <center>Date</center>
                                    </th>
                                    <th>
                                        <center>Req.Terms</center>
                                    </th>
                                    <th>
                                        <center>File</center>
                                    </th>
                                    <th>
                                        <center>Status</center>
                                    </th>
                                    <th>
                                        <center>Merged With</center>
                                    </th>
                                    <th>
                                        <center>Action</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="body">
                                <?php
                                if ($GLOBALS["selected_Department"]) {

                                    $Year = date('Y');
                                    $stmt = $conn->prepare("select * from proposal where  Status!='On Progress' and Status!='Completed'  and Department_level='Approved' and Rcc_level='Approved' and  Department=?  and Type=? and date like '" . $Year . "%' order by date DESC");
                                    $Department = $GLOBALS["selected_Department"];
                                    $Type = $GLOBALS["selected_Type"];
                                    $stmt->bind_param("ss", $Department, $Type);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result->num_rows>0) {

                                        while ($data = $result->fetch_assoc()) {
                                ?>
                                            <tr>
                                                <td><?php echo $data["ID"] ?></td>
                                                <td><?php echo $data["Title"] ?></td>
                                                <td><?php echo $data["Type"] ?></td>
                                                <td><?php echo $data["date"] ?></td>
                                                <td><?php echo $data["Term"]  ?></td>
                                                <td>
                                                    <a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a>
                                                </td>
                                                <td><?php echo $data["Status"]  ?></td>
                                                <td><?php echo $data["Merge_With"]; ?></td>
                                                <td>
                                                    <!-- Trigger the modal with a button -->
                                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="<?php echo '#myModal' . $data['ID']; ?>">Merge
                                                    </button>

                                                    <!-- Modal -->
                                                    <div id="<?php echo 'myModal' . $data['ID']; ?>" class="modal fade " role="dialog">
                                                        <div class="modal-dialog">

                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <form id="role-form" method="POST" action="Merge_Proposal.php">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                                        <h4 class="modal-title">Select The Proposal tobe merged</h4>
                                                                    </div>
                                                                    <div class="modal-body">

                                                                        <div class="form-group">
                                                                            <div class="col-lg-12">
                                                                                <center>
                                                                                    <?php
                                                                                    $year = date("Y");
                                                                                    //                                                    $Department_level = 'Not Approved';
                                                                                    //                                                    $Department_level = 'Not Approved';
                                                                                    $ID = $data['ID'];
                                                                                    $proposal_ID = $GLOBALS["selected_Proposal"];
                                                                                    $stmt = $conn->prepare("select * from proposal where  Status!='On Progress' and Status!='Completed' and Status!='Merged' and Department_level='Approved' and Rcc_level='Approved' and ID!=? and  Department=?  and Type=? and date like '" . $Year . "%' order by date DESC");

                                                                                    $stmt->bind_param("iss", $proposal_ID, $Department, $Type);
                                                                                    $stmt->execute();
                                                                                    $merging_Proposals = $stmt->get_result();
                                                                                    ?>
                                                                                    <label class="control-label">Select Proposals (<small>press Control For
                                                                                            multiple selection </small>)</label>
                                                                                    <select multiple="multiple" id="proposals" size="50" style="height: 100px; overflow-y: scroll;" data-live-search="true" data-size="10" class="form-control" name="ary[]">
                                                                                        <?php
                                                                                        if ($merging_Proposals->num_rows>0) {
                                                                                            while ($row = $merging_Proposals->fetch_assoc()) {
                                                                                        ?>
                                                                                                <option value="<?php echo $row['ID'] . "/" . $$proposal_ID . "/" . $Type . "/" . $Department ?>"><?php echo $row['Title']  ?>(<?php echo $row['ID']  ?>)</option>
                                                                                        <?php
                                                                                            }
                                                                                        }
                                                                                        ?>
                                                                                    </select>
                                                                                </center>
                                                                            </div>
                                                                            <div class="clearfix"></div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="submit" name="Submit" class="btn btn-success">Merg</button>
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                                        </div>
                                                                </form>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="8">
                                                <center>No Record</center>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="7">
                                            <center>No Record Found</center>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
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
<script>
    function selectProposal(v = '') {
        const myArray = v.split("/");
        // alert(myArray.length);
        // var Department = document.getElementById('Department').value;
        // alert(v);
        if (myArray.length === 4) {
            $.ajax({
                type: 'GET',
                url: 'ApproveProcess.php?Merge=Merge&&data=' + v,

                success: function(html) {
                    $('#body').html(html);
                }
            });
        } else if (myArray.length === 5) {
            $.ajax({
                type: 'GET',
                url: 'ApproveProcess.php?Merge=Merge&&data=' + v,

                success: function(html) {
                    $('#proposals').html(html);
                }
            });
        }

    }
   
    document.getElementById('proposal').onchange = function() {
        var selected = [];
        for (var option of document.getElementById('pets').options) {
            if (option.selected) {
                selected.push(option.value);
            }
        }
        alert('event started ');
        alert(selected);
    }

    function Merge() {
        alert("hello");
        let selectElement = document.getElementById('proposals')
        let selectedValues = Array.from(selectElement.selectedOptions)
            .map(option => option.value)
        alert(selectedValues);
        alert(options[0]);
        let select = document.getElementsByName('ary[]');
        // let $Proposal_ID="";
        let selected_Proposal = select.options[select.selectedIndex].value;

        alert("length " + selected_Proposal);
        // alert("0th  "+selected_Proposal+" 1st  "+selected_Proposal[1]);

        //         for(let $i=0;$i<selected_Proposal.length;$i++){
        //             let temp=selected_Proposal[$i].value;
        //             $Proposal_ID=$Proposal_ID+""+temp +"&"
        //         }
        // alert("finally sent "+$Proposal_ID);
        $.ajax({
            type: 'POST',
            url: 'Merge_Proposal.php?Proposal_ID=' + selected_Proposal,

            success: function(html) {
                $('#body').html(html);
            }
        });




    }
</script>
<?php
// } else {
//     header("Location: http://localhost/system/page-login.php");
// } 
?>