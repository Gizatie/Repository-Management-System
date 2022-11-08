<?php
session_start();
if (isset($_SESSION['StaffId'])&&$_SESSION['StaffType']==='RCC'){
require_once '../Common/DataBaseConnection.php';
if (isset($_REQUEST["departments"])) {


    $Year = date('Y');
    $stmt = $conn->prepare("select * from proposal where Department_level=? and Faculty=? and Department=?  and Status!='Compeleted' and date like '" . $Year . "%' order by Status ASC");
    $DepStatus = 'Approved';
    $RCCStatus = 'Not Approved';
    $Faculty = $_SESSION["Faculty"];
    $department = $_REQUEST["departments"];
    echo 'fac '.$Faculty.' dep '.$department;
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
                <td><?php echo $data["Status"] ?></td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle btn-sm" type="button"
                                data-toggle="dropdown">Action
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="Approve.php?Action=Approve&&ProposalId=<?php echo $data['ID'] ?>&&departments=<?php echo $department ?>">Approve</a>
                            </li>
                            <li>
                                <a href="Approve.php?Action=Reject&&ProposalId=<?php echo $data['ID'] ?>&&departments=<?php echo$department?>">Reject</a>
                            </li>
                            <li>
                                <a href="Approve.php?Action=suspend&&ProposalId=<?php echo $data['ID'] ?>&&departments=<?php echo $department ?>">Suspend</a>
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
            <td colspan="7">
                <center>No Record</center>
            </td>
        </tr>

        <?php
    }


}

}else{
    header("Location: http://localhost/system/page-login.php");
}?>
