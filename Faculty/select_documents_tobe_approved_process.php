<?php
session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Faculty') {
    require_once '../Common/DataBaseConnection.php';
    if (isset($_REQUEST["departments"])) {


        $Year = date('Y');
        $stmt = $conn->prepare("select d.ID,p.Department,d.Proposal_ID,d.Term,d.file,d.Faculty_Level,p.Title,p.Type,p.date,d.Rcd_Status from proposal as p, documents as d  where p.ID=d.Proposal_ID and d.Rcc_Status='Approved' and    p.Department=?  and d.Status='On Progress' and p.Status!='Compeleted' and p.date like '" . $Year . "%' order by p.ID ASC");

        $department = $_REQUEST["departments"];
        $stmt->bind_param("s",  $department);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {

            while ($data = $result->fetch_assoc()) {
?>

                <tr>
                    <td><?php echo $data["ID"] ?></td>
                    <td><?php echo $data["Title"] ?></td>
                    <td><?php echo $data["Term"] ?></td>
                    <td><?php echo $data["Type"] ?></td>
                    <td><?php echo $data["date"] ?></td>
                    <td>
                        <a href="../Documents/<?php echo $data["Type"] ?>/Final/<?php echo $data["file"] ?>"><?php echo $data["file"] ?></a>
                    </td>
                    <td><?php echo $data["Faculty_Level"] ?></td>
                    <td>
                        <div class="col-lg-12">
                            <select class="form-select form-select-sm" aria-label=".form-select-sm example" onchange="onChange(this.value)">
                                <option selected>Select Type</option>
                                <option value="Approve&&ProposalId=<?php echo $data['Proposal_ID'] ?>&&departments=<?php echo $department ?>&&Doc_ID=<?php echo $data['ID'] ?>&&Term=<?php echo $data['Term'] ?>">Approve</option>
                                <option value="Reject&&ProposalId=<?php echo $data['Proposal_ID'] ?>&&departments=<?php echo $department ?>&&Doc_ID=<?php echo $data['ID'] ?>&&Term=<?php echo $data['Term'] ?>">Reject</option>
                                <option value="suspend&&ProposalId=<?php echo $data['Proposal_ID'] ?>&&departments=<?php echo $department ?>&&Doc_ID=<?php echo $data['ID'] ?>&&Term=<?php echo $data['Term'] ?>">Suspend</option>
                            </select>
                        </div>

                    </td>
                </tr>
            <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="7">
                    <center>No Record</center>
                </td>
            </tr>

    <?php
        }
    }
    ?>
<?php
} else {
    header("Location: http://localhost/system/page-login.php");
} ?>