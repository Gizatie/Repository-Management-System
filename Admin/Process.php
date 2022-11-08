<?php
 require_once '../Common/DataBaseConnection.php';


if (isset($_REQUEST["selectDepartment"])) {
    $Faculty = $_REQUEST["Faculty"];
    $stmt = $conn->prepare("Select * from department where Faculty=?");
    $stmt->bind_param("s", $Faculty);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            ?>
            <option value="">Select Department</option>
            <?php
            while ($data = $result->fetch_assoc()) {
                ?>

                <option value="<?php echo $data["Name"] ?>"><?php echo $data["Name"] ?></option>
                <?php
            }

        } else {
            ?>
            <option>No Department Found</option>
            <?php
        }
    }
}
elseif (isset($_REQUEST["selectType"])) {
    $Type = $_REQUEST["Type"];
    if ($Type === 'Academic') {
        ?>
        <div class="form-group">
            <label class="control-label">Select Faculty<small>(for Academic Staffs Only)</small></label>
            <select class="form-control" onchange="selectDepartment(this.value)" name="Faculty">
                <option value="0">Select Faculty</option>
                <?php
                $query = 'Select * from faculty';
                $stmt = $conn->query($query);
                echo 'nnnnnnnnnnnnnnnnnnnnnnnnnnn ' . $stmt->num_rows;
                if ($stmt->num_rows > 0) {
                    while ($data = $stmt->fetch_assoc()) {
                        ?>
                        <option value="<?php echo $data['Name'] ?>"><?php echo $data['Name'] ?></option>
                        <?php
                    }
                }
                ?>

            </select>
        </div>
        <div class="form-group">
            <label class="control-label">Select Department</label>
            <select class="form-control" id="dep" name="Department" required>
                <option value="0">Select Faculty First</option>
            </select>
        </div>
        <?php
    } elseif ($Type === "administrative") {
             ?>
        <div class="form-group">
            <label class="control-label">Select Office</label>
            <select class="form-control" id="dep" name="Department" required>
                <option value="0">Aca</option>
            </select>
        </div>
            <?php
    }
    $stmt = $conn->prepare("Select * from department where Faculty=?");
    $stmt->bind_param("s", $Faculty);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            ?>
            <option value="">Select Department</option>
            <?php
            while ($data = $result->fetch_assoc()) {
                ?>

                <option value="<?php echo $data["Name"] ?>"><?php echo $data["Name"] ?></option>
                <?php
            }

        } else {
            ?>
            <option>No Department Found</option>
            <?php
        }
    }
}
elseif (isset($_REQUEST['Approve'])) {
    $stmt = $conn->prepare("select * from staffs where Department=? and Faculty=? order by Status");
    $Faculty = $_REQUEST["Faculty"];
    $Department = $_REQUEST["Department"];
    echo  $Faculty . ' ' . $Department;
    $stmt->bind_param("ss", $Department, $Faculty);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows) {

        while ($data = $result->fetch_assoc()) {
            ?>
            <tr>
                <td><center><?php echo $data["ID"] ?></center></td>
                <td><center><?php echo $data["First_Name"] ?></center></td>
                <td><center><?php echo $data["Middle_Name"] ?></center></td>
                <td><center><?php echo $data["Last_Name"] ?></center></td>
                <td><center><?php echo $data["sex"] ?></center></td>
                <td><center><?php echo $data["Role"] ?></center></td>
                <td><center><?php echo $data["Status"] ?></center></td>
                <td><center><?php echo $data["Email"] ?></center></td>
                <td><center><?php echo $data["Phone"] ?></center></td>
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

}
elseif (isset($_REQUEST['Approve_Student'])) {
    $stmt = $conn->prepare("select * from staffs where Department=? and Faculty=? and Type='Student' order by Status");
    $Faculty = $_REQUEST["Faculty"];
    $Department = $_REQUEST["Department"];
    echo  $Faculty . ' ' . $Department;
    $stmt->bind_param("ss", $Department, $Faculty);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows) {

        while ($data = $result->fetch_assoc()) {
            ?>
            <tr>
                <td><center><?php echo $data["ID"] ?></center></td>
                <td><center><?php echo $data["First_Name"] ?></center></td>
                <td><center><?php echo $data["Middle_Name"] ?></center></td>
                <td><center><?php echo $data["Last_Name"] ?></center></td>
                <td><center><?php echo $data["sex"] ?></center></td>
                <td><center><?php echo $data["Status"] ?></center></td>
                <td><center>
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle btn-sm" type="button"
                                    data-toggle="dropdown">Action
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="Deactivate_Account.php?Action=Active&&StaffId=<?php echo $data['ID'] ?>">Activate</a>
                                </li>
                                <li>
                                    <a href="Deactivate_Account.php?Action=Deactivate&&StaffId=<?php echo $data['ID'] ?>">Deactivate</a>
                                </li>

                            </ul>
                        </div>
                    </center></td>
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

}
elseif (isset($_REQUEST['Select_Users'])) {
    $stmt = $conn->prepare("select DISTINCT * from staffs as s,users as u where s.ID=StaffId and  s.Department=? and s.Faculty=? and Type=? order by Status");
    $Faculty = $_REQUEST["Faculty"];
    $Department = $_REQUEST["Department"];
    $Type = $_REQUEST["Type"];
    $stmt->bind_param("sss", $Department, $Faculty,$Type);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows) {

        while ($data = $result->fetch_assoc()) {
            ?>
            <tr>
                <td><center><?php echo $data["ID"] ?></center></td>
                <td><center><?php echo $data["First_Name"] ?></center></td>
                <td><center><?php echo $data["Middle_Name"] ?></center></td>
                <td><center><?php echo $data["Last_Name"] ?></center></td>
                <td><center><?php echo $data["user_name"] ?></center></td>
                <td><center><?php echo $data["password"] ?></center></td>
                <td><center><?php echo $data["Status"] ?></center></td>

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

}
?>
