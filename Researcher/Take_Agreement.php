<!DOCTYPE html>
<html lang="en">

<?php
session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Department' || $_SESSION['StaffType'] === 'RCC' || $_SESSION['StaffType'] === 'RCD' || $_SESSION['StaffType'] === 'Researcher'  || $_SESSION['StaffType'] === 'Faculty') {
    $_SESSION["Sidebar"] = "Take_Agreement";
    require_once('../Common/DataBaseConnection.php');
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

        <?php  require_once '../Common/Header.php'; ?>


        <?php
        //   the code below is trigger when then button Agreement on the take agreement page was clicked

        if (isset($_REQUEST['Action'])) {
            $State = $_REQUEST['Action'];
            $ProposalID = (int)$_REQUEST['ProposalID'];
            $StaffId = $_SESSION['StaffId'];
            $stmt = $conn->prepare("select * from participant where Proposal_ID=?");
            $stmt->bind_param('i', $ProposalID);
            if ($stmt->execute()) {
                $Result = $stmt->get_result();
                $Number_Of_Participants_Agreed = 0;
                $Number_Of_Participants_Expected_To_Agreed = $Result->num_rows;
                if ($Result->num_rows > 0) {
                    while ($data = $Result->fetch_assoc()) {
                        if ($data['Agreement'] === 'Agreed') {
                            $Number_Of_Participants_Agreed += 1;
                        }
                    }
                }
                if ($Number_Of_Participants_Agreed < ($Number_Of_Participants_Expected_To_Agreed - 1)) {

                    $stmt = $conn->prepare("select * from participant where Proposal_ID=? and Staff_ID=? and Agreement='Agreed'");
                    $stmt->bind_param('is', $ProposalID, $StaffId);
                    if ($stmt->execute()) {
                        $temp = $stmt->get_result();
                        if ($temp->num_rows > 0) {
                            echo "<script>alert('You Have Already Agreed')</script>";
                        } else {
                            $stmt = $conn->prepare("update participant set Agreement=? where Staff_ID=? and Proposal_ID=? ");
                            $stmt->bind_param('ssi', $State, $StaffId, $ProposalID);
                            if ($stmt->execute()) {
                                echo "<script>alert('Successfully Agreed')</script>";
                            } else {
                                echo "<script>alert(' Agreed Taking Failed')</script>";
                            }
                        }
                    }
                } elseif ($Number_Of_Participants_Agreed >= $Number_Of_Participants_Expected_To_Agreed) {

                    echo "<script>alert(' Agreed Already Taken')</script>";
                } elseif ($Number_Of_Participants_Agreed === ($Number_Of_Participants_Expected_To_Agreed - 1)) {
                    $stmt = $conn->prepare("select * from participant where Proposal_ID=? and Staff_ID=? and Agreement='Agreed'");
                    $stmt->bind_param('is', $ProposalID, $StaffId);
                    if ($stmt->execute()) {
                        $temp = $stmt->get_result();
                        if ($temp->num_rows > 0) {
                            echo "<script>alert('You Have Already Agreed')</script>";
                        } else {
                            $stmt = $conn->prepare("update participant set Agreement=? where Staff_ID=? and Proposal_ID=? ");
                            $stmt->bind_param('ssi', $State, $StaffId, $ProposalID);
                            if ($stmt->execute()) {
                                $Final_Status = 'Waiting for Agreement Approval';
                                $stmt = $conn->prepare("update proposal set Status=? where ID=? ");
                                $stmt->bind_param('si', $Final_Status, $ProposalID);
                                if ($stmt->execute()) {
                                    echo "<script>alert(' All Participants Agreed Successfully Proposal is on Progress')</script>";
                                } else {
                                    echo "<script>alert(' Agreed Taking Failed')</script>";
                                }
                            } else {
                                echo "<script>alert(' Agreed Taking Failed')</script>";
                            }
                        }
                    }
                }
            }
        }
        ?>
        <div class="content-wrap">
            <div class="main">
                <div class="container-fluid">
                    <!-- /# row -->
                    <section id="main-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="control-label">Select Type</label>
                                <select class="form-control" onchange="selectProposal(this.value)" id="Type" required>
                                    <option value="">Select Type</option>
                                    <option value="Research">Research</option>
                                    <option value="Technology Transfer">Technology Transfer</option>
                                    <option value="Community Service">Community Service</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
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
                                            <center> Status</center>
                                        </th>
                                        <th>
                                            <center>Cost</center>
                                        </th>
                                        <th>
                                            <center>Action</center>
                                        </th>
                                        <th>
                                            <center>View Status</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="body">

                                </tbody>

                            </table>
                        </div>
                        <?php require_once '../Common/Footer.php'; ?>
                    </section>
                </div>
            </div>
        </div>

    </body>

</html>
<script>
    function selectProposal(v = '') {
        // alert(v);
        $.ajax({
            type: 'POST',
            url: 'Process.php?Proposals=Proposals&&v=' + v,

            success: function(html) {
                $('#body').html(html);
            }
        });
    }
    $(document).ready(function() {
        $('.hover').tooltip({
            title: 'fetchData',
            html: true,

        });

        function fetchData() {
            var fetch_data = 'helllllooooo every body';

            return fetch_data;
        }
    });
</script>
<?php
} else {
    header("Location: http://localhost/system/page-login.php");
} ?>