<?php
session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Faculty') {
    require_once '../Common/DataBaseConnection.php';
    if (isset($_REQUEST["departments"])) {


        $Year = date('Y');
        $stmt = $conn->prepare("select * from proposal where Department_level=? and Rcc_level='Approved' and Faculty=? and Department=?  and Rcc_level='Approved' and Status!='Compeleted' and Status!='Merged' and date like '" . $Year . "%' order by Status ASC");
        $DepStatus = 'Approved';
        $RCCStatus = 'Approved';
        $Faculty = $_SESSION["Faculty"];
        $department = $_REQUEST["departments"];
        echo 'fac ' . $Faculty . ' dep ' . $department;
        $stmt->bind_param("sss", $DepStatus, $Faculty, $department);
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
                    <td>
                        <a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a>
                    </td>
                    <td><?php echo $data["Rcc_level"] ?></td>
                    <td><?php echo $data["Faculty_Level"] ?></td>
                    <td><?php echo $data["Status"] ?></td>
                    <td>
                        <div class="form-group">
                            <div class="col-lg-12">
                                <select class="form-select form-select-sm" aria-label=".form-select-sm example" onchange="ChangeType(this.value)">
                                    <option selected>Select Type</option>
                                    <option value="Approve&&ProposalId=<?php echo $data['ID'] ?>&&departments=<?php echo $department ?>">Approve</option>
                                    <option value="Reject&&ProposalId=<?php echo $data['ID'] ?>&&departments=<?php echo $department ?>">Reject</option>
                                    <option value="suspend&&ProposalId=<?php echo $data['ID'] ?>&&departments=<?php echo $department ?>">Suspend</option>
                                </select>
                            </div>
                        </div>
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
    }
} else {
    header("Location: http://localhost/system/page-login.php");
} ?>