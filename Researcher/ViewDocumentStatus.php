
<?php
session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Department' || $_SESSION['StaffType'] === 'RCC' || $_SESSION['StaffType'] === 'RCD' || $_SESSION['StaffType'] === 'Researcher'  || $_SESSION['StaffType'] === 'Faculty')  {
    $_SESSION["Sidebar"]="ViewDocumentStatus";
    require_once('../Common/DataBaseConnection.php');
?>
<!DOCTYPE html>
<html lang="en">

<?php

require_once '../Common/Head.php' ?>

<body>
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
<!-- /# sidebar -->
<?php require_once '../Common/Header.php' ?>

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
                                <center>Proposal</center>
                            </th>
                            <th>
                                <center>Document</center>
                            </th>
                            <th>
                                <center>RCC Status</center>
                            </th>
                            <th>
                                <center> RCD Status</center>
                            </th>
                            <th>
                                <center> Final Status</center>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $stmt = $conn->prepare("SELECT * FROM proposal WHERE EXISTS (SELECT Proposal_ID FROM participant WHERE proposal.ID = participant.Proposal_ID and Staff_ID=? )  and Status='On Progress' order by date ASC");
                        $StaffId = $_SESSION["StaffId"];

                        $stmt->bind_param("s", $StaffId);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows) {

                            while ($data = $result->fetch_assoc()) {
                                $stmt = $conn->prepare("SELECT * FROM Documents  WHERE Proposal_ID=?  and Status='On Progress' ");
                                $ProposalId=(int)$data["ID"];
                                $stmt->bind_param("i", $ProposalId);
                                $stmt->execute();
                                $temp=$stmt->get_result();
                                $row=$temp->fetch_assoc();

                                ?>
                                <tr>
                                    <td><center><?php echo $row['ID']; ?></center></td>
                                    <td><center><?php echo $data["Title"] ?></center></td>
                                    <td><center><?php echo $data["Type"] ?></center></td>
                                    <td><center>
                                        <a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a>
                                    </center></td>
                                    <td><center>
                                        <a href="../Documents/<?php echo $data["Type"] ?>/Final/<?php echo $row["file"] ?>"><?php echo $row["file"] ?></a>
                                    </center></td>
                                    <td><center><?php echo $row["Rcc_Status"];?></center></td>
                                    <td><center><?php echo $row["Rcd_Status"];?></center></td>
                                    <td><center><?php echo $row["Status"];?></center></td>
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
                        ?>

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
<?php

}else{
    header("Location: ../page-login.php");
}?>