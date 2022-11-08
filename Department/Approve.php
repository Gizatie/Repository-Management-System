<!DOCTYPE html>
<html lang="en">

<?php
session_start();
if (isset($_SESSION['StaffId'])&&$_SESSION['StaffType']==='Department'){
    $_SESSION["Sidebar"]='Approve';
require_once '../Common/DataBaseConnection.php';
require_once '../Common/Head.php' ?>

<body>

<?php require_once "sidebar.php"?>
<!-- /# sidebar -->

<?php require_once '../Common/Header.php';  ?>


<?php
//  to the button action on each proposal is clicked
if (isset($_GET['Action'])) {
    $Action = $_GET["Action"];
    $ProposalId = $_GET["ProposalId"];
    $status = '';
    $Final_Status='';
    $stmt = $conn->prepare("UPDATE proposal set Department_level=? where ID=?");

    switch ($Action) {
        case 'Approve':
            $status = 'Approved';
            $stmt->bind_param("ss", $status, $ProposalId);
            $stmt->execute();
            echo '<script>alert("Proposal Approved Successfully")</script>';
            break;
        case 'Reject':
            $status = 'Rejected';
            $stmt->bind_param("ss", $status,$ProposalId);
            $stmt->execute();
            echo '<script>alert("Proposal Rejected Successfully")</script>';
            break;
        case 'suspend':
            $status = 'suspended';
            $stmt->bind_param("ss", $status, $ProposalId);
            $stmt->execute();
            echo '<script>alert("Proposal suspended Successfully")</script>';
            break;
    }

}
?>
<div class="content-wrap">
    <div class="main">
        <div class="container-fluid">
            <!-- /# row -->
            <section id="main-content">
                <div class="row">
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
                                <center>Terms</center>
                            </th>
                            <th>
                                <center>File</center>
                            </th>
                            <th>
                                <center>Dep Status</center>
                            </th>
                            <th>
                                <center>Action</center>
                            </th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $Year=date('Y');
                        $stmt = $conn->prepare("select * from proposal where Status!='On Progress' and (Merge_With='None' or Merge_With='Merged') and Status!='Completed' and Merge_With !='Merged With'  and Faculty=? and Department=? and date like '".$Year."%'  order by Status DESC");
                        $status = $_SESSION["Faculty"];
                        $Department = $_SESSION["Department"];

                        $stmt->bind_param("ss", $status,$Department);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows) {

                            while ($data = $result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td><?php echo $data["ID"] ?></td>
                                    <td><?php echo $data["Title"] ?></td>
                                    <td><?php echo $data["Type"] ?></td>
                                    <td><?php echo $data["date"] ?></td>
                                    <td><?php echo $data["Term"] .' Terms'?></td>
                                    <td>
                                        <a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a>
                                    </td>

                                    <td><?php echo $data["Department_level"]?></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle btn-sm" type="button"
                                                    data-toggle="dropdown">Action
                                                <span class="caret"></span></button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="Approve.php?Action=Approve&&ProposalId=<?php echo $data['ID'] ?>">Approve</a>
                                                </li>
                                                <li>
                                                    <a href="Approve.php?Action=Reject&&ProposalId=<?php echo $data['ID'] ?>">Reject</a>
                                                </li>
                                                <li>
                                                    <a href="Approve.php?Action=suspend&&ProposalId=<?php echo $data['ID'] ?>">Suspend</a>
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
                                    <center>No Record</center>
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
<?php
}else{
    header("Location: http://localhost/system/page-login.php");
}?>