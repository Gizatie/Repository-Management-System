<?php
// Create connection
$conn = new mysqli("localhost", "root", "", "repository");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_REQUEST['Document'])) {
    $stmt = $conn->prepare("select DISTINCT * from documents as d, Proposal as p where d.Proposal_ID=p.ID and d.Rcc_Status='Not Approved'   and p.Status='On Progress'  and  p.Type=? and p.Faculty=? and p.Department=? order by date ASC");
    $Type = $_REQUEST["v"];
    $Faculty = $_REQUEST["Faculty"];
    $Department = $_REQUEST["Department"];
    echo $Type . ' ' . $Faculty . ' ' . $Department;
    $stmt->bind_param("sss", $Type, $Faculty, $Department);
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
                <td>
                    <center><a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a></center>
                </td>
                <td><center><?php echo $data["Rcc_Status"] ?></center></td>
                <td><center>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle btn-sm" type="button"
                                data-toggle="dropdown">Action
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="ApproveDocument.php?Action=Approve&&ProposalId=<?php echo $data['ID'] ?>">Approve</a>
                            </li>
                            <li>
                                <a href="ApproveDocument.php?Action=Reject&&ProposalId=<?php echo $data['ID'] ?>">Reject</a>
                            </li>
                            <li>
                                <a href="ApproveDocument.php?Action=suspend&&ProposalId=<?php echo $data['ID'] ?>">Suspend</a>
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
echo 'hello  ';
if (isset($_REQUEST["selectDep"])){
    $stmt = $conn->prepare("select  * from department where Faculty=?");
    $Faculty = $_REQUEST["Faculty"];

    $stmt->bind_param("s" ,$Faculty);
    $stmt->execute();
    $result=$stmt->get_result();
    $num=$result->num_rows;
    echo 'Faculty is '.$Faculty.'  '.$num;
    if ($num>0){

        while ($data=$result->fetch_assoc()){
            ?>
            <option value="">Select Department</option>
            <option value="<?php echo  $data["Name"];?>"><?php echo $data["Name"];?></option>
<?php
        }
    }else{
        ?>
<option value="">No Department Found<?php echo $result->num_rows?></option>
<?php
    }

}
if (isset($_REQUEST["selectType"])){
//    $stmt = $conn->prepare("select  * from department where Faculty=?");
//    $Faculty = $_REQUEST["Faculty"];
//
//    $stmt->bind_param("s" ,$Faculty);
//    $stmt->execute();
//    $result=$stmt->get_result();
//    $num=$result->num_rows;
//    echo 'Faculty is '.$Faculty.'  '.$num;
//    if ($num>0){
//
//        while ($data=$result->fetch_assoc()){
//            ?>
<!--            <option value="">Select Department</option>-->
<!--            <option value="--><?php //echo $data["Name"];?><!--">--><?php //echo $data["Name"];?><!--</option>-->
<!--            --><?php
//        }
//    }else{
//        ?>
<!--        <option value="">No Department Found--><?php //echo $result->num_rows?><!--</option>-->
<!--        --><?php
//    }
?>
    <option>Select Document Type</option>
    <option value="">Select Document Type</option>
    <option value="Research">Research</option>
    <option value="Technology Transfer">Technology Transfer</option>
    <option value="Community Service">Community Service</option>
    <option value="Other">Other</option>
<?php
}
?>