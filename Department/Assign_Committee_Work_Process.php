<?php

session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Department') {
    if (isset($_REQUEST['Assign_Committee_Work'])) {
        require_once '../Common/DataBaseConnection.php';

        $stmt = $conn->prepare("select s.ID,s.First_Name,s.Middle_Name,s.Last_Name,s.Committee, s.Email,s.Phone from staffs as s where s.Status='Active' and s.Department=?");
        $Department = $_REQUEST["Department"];
        $stmt->bind_param("s", $Department);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($data = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $data["ID"] ?></td>
                    <td><?php echo $data["First_Name"] ?></td>
                    <td><?php echo $data["Middle_Name"] ?></td>
                    <td><?php echo $data["Last_Name"] ?></td>
                    <td><?php echo $data["Committee"] ?></td>
                    <td><?php echo $data["Email"] ?></td>
                    <td><?php echo $data["Phone"] ?></td>

                </tr>
            <?php
            }
            ?>
        <?php
        } else {
        ?>
            <tr>
                <td colspan="7">
                    <center>No Record</center>
                </td>
            </tr>
            <?php
        }
    } else if (isset($_REQUEST['Assign_Proposal'])) {
        require_once '../Common/DataBaseConnection.php';

        $stmt = $conn->prepare("SELECT DISTINCT p.Reviewer,s.First_Name,s.Middle_Name,s.Last_Name,s.Department, p.ID,p.Title,p.Department,p.Type,p.File, pa.Proposal_ID,pa.Role FROM proposal as p INNER JOIN participant as pa ON p.ID=pa.Proposal_ID and p.Department=? and p.Type=? and pa.Role='PI' and p.date like ?  INNER JOIN staffs as s ON s.ID=pa.Staff_ID");
        $Type = $_REQUEST["Type"];
        $Department = $_SESSION["Department"];
        $date = Date('Y') . '%';
        echo $date . 'dep ' . $Department . 'Type ' . $Type;
        $stmt->bind_param("sss", $Department, $Type, $date);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($data = $result->fetch_assoc()) {
            ?>
                    <tr>
                        <td><?php echo $data["ID"] ?></td>
                        <td><?php echo $data["Title"] ?></td>
                        <td><?php echo $data["Type"] ?></td>
                        <td>
                            <a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a>
                        </td>
                        <td><?php echo $data["First_Name"] . " " . $data["Middle_Name"] . " " . $data["Last_Name"] ?></td>
                        <td>
                            <?php
                            $staffID = $data["Reviewer"];
                            $stmt2 = $conn->prepare("SELECT * FROM staffs as s where ID=?");
                            $stmt2->bind_param("s", $staffID);
                            if ($stmt2->execute()) {
                                $reviewer = $stmt2->get_result();
                                if($reviewer->num_rows>0){
                                    $reviewerData = $reviewer->fetch_assoc();
                                echo $reviewerData["First_Name"] . " " . $reviewerData["Middle_Name"] . " " . $reviewerData["Last_Name"];
                                }else {
                                    echo 'Reviewer Not Found';
                                }
                            } else {
                                echo 'Reviewer Not Found';
                            }
                            ?>
                        </td>



                    </tr>
                <?php
                }
                ?>
            <?php
            } else {
            ?>
                <tr>
                    <td colspan="6">
                        <center>No Record Found</center>
                    </td>
                </tr>
            <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="6">
                    <center>Something is wrong Contact Admin</center>
                </td>
            </tr>
<?php
        }
    }
}
