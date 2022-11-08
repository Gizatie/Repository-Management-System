
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
<div class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures">
    <div class="nano">
        <div class="nano-content">
            <div class="logo">
                <a href="index.html">
                    <!-- <img src="assets/images/logo.png" alt="" /> -->
                    <span>DTU</span>
                </a>
            </div>
            <ul>
                <li class="label">Researcher </li>
                <li>
                    <a class="sidebar-sub-toggle">
                        <i class="ti-home"></i> Submit
                        <span class="sidebar-collapse-icon ti-angle-down"></span>
                    </a>
                    <ul>
                        <li>
                            <a href="SubmitProposal.php">Proposal</a>
                        </li>
                        <li>
                            <a href="#">Document</a>
                        </li>



                    </ul>
                </li>
                <li>
                    <a class="sidebar-sub-toggle">
                        <i class="ti-bar-chart-alt"></i> Status
                        <span class="sidebar-collapse-icon ti-angle-down"></span>
                    </a>
                    <ul>
                        <li>
                            <a href="#">Proposal </a>
                        </li>
                        <li>
                            <a href="chart-morris.html">Document</a>
                        </li>

                    </ul>
                </li>
                <li>
                    <a class="sidebar-sub-toggle">
                        <i class="ti-bar-chart-alt"></i> View
                        <span class="sidebar-collapse-icon ti-angle-down"></span>
                    </a>
                    <ul>
                        <li>
                            <a href="chart-flot.html">Technology Transfer  </a>
                        </li>
                        <li>
                            <a href="chart-morris.html">Community Service </a>
                        </li>
                        <li>
                            <a href="chart-morris.html">Research </a>
                        </li>
                        <li>
                            <a href="chart-morris.html">Thesis</a>
                        </li>
                        <li>
                            <a href="chart-morris.html">Project</a>
                        </li>

                    </ul>
                </li>





                <li class="label">Extra</li>

                <li>


                </li>
                <li>
                    <a href="../documentation/index.html">
                        <i class="ti-file"></i> Account Setting</a>
                </li>
                <li>
                    <a>
                        <i class="ti-close"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</div>
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
                                <center>Date</center>
                            </th>
                            <th>
                                <center>File</center>
                            </th>
                            <th>
                                <center>Dep Status</center>
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
                        $stmt = $conn->prepare("SELECT * FROM proposal WHERE EXISTS (SELECT Proposal_ID FROM participant WHERE proposal.ID = participant.Proposal_ID and Staff_ID=? ) and Rcd_level='Approved' and Status='On Progress' order by date ASC");
                        $StaffId = $_SESSION["StaffId"];

                        $stmt->bind_param("s", $StaffId);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows) {

                            while ($data = $result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td><center><?php echo $data["ID"] ?></center></td>
                                    <td><center><?php echo $data["Title"] ?></center></td>
                                    <td><center><?php echo $data["Type"] ?></center></td>
                                    <td><center><?php echo $data["date"] ?></center></td>
                                    <td><center>
                                        <a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a>
                                    </center></td>
                                    <td><center><?php echo $data["Department_level"];?></center></td>
                                    <td><center><?php echo $data["Rcc_level"];?></center></td>
                                    <td><center><?php echo $data["Rcd_level"];?></center></td>
                                    <td><center><?php echo $data["Status"];?></center></td>
                                </tr>
                                <?php
                            }

                        } else {
                            ?>
                            <tr>
                                <td colspan="9">
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