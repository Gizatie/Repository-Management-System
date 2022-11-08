
<?php
session_start();
if (isset($_SESSION["StaffId"]))
{
?>
<!DOCTYPE html>
<html lang="en">

<?php

require_once '../Common/DataBaseConnection.php';
require_once '../Common/Head.php' ?>

<body>
<?php require_once 'sidebar.php'?>
<!-- /# sidebar -->
<?php require_once '../Common/Header.php' ?>

<div class="content-wrap">
    <div class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 p-r-0 title-margin-right">
                    <div class="page-header">
                        <div class="page-title">
                            <h1><span>Debretabour University Research,CS, TT and student Projects Repository </span>
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
                        $stmt = $conn->prepare("SELECT * FROM Documents  WHERE Proposal_ID=?  and Status='On Progress' ");

                        if ($result->num_rows) {

                            while ($data = $result->fetch_assoc()) {
                                $ProposalId=(int)$data["ID"];
                                $stmt->bind_param("i", $ProposalId);
                                $stmt->execute();
                                $row=$stmt->get_result();
                                $row=$row->fetch_assoc();

                                ?>
                                <tr>
                                    <td><center><?php echo $row["ID"] ?></center></td>
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
                <div class="row">
                    <div class="col-lg-12">
                        <div id="extra-area-chart"></div>
                        <div id="morris-line-chart"></div>
                        <div class="footer">
                            <p>2020 © DTU. -
                                <a href="#">dtu.gov.et</a>
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- Common -->
<script src="assets/js/lib/jquery.min.js"></script>
<script src="assets/js/lib/jquery.nanoscroller.min.js"></script>
<script src="assets/js/lib/menubar/sidebar.js"></script>
<script src="assets/js/lib/preloader/pace.min.js"></script>
<script src="assets/js/lib/bootstrap.min.js"></script>
<script src="assets/js/scripts.js"></script>


</body>

</html>
<?php

}else{
    header("Location: ../page-login.php");
}?>