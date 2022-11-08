<?php
session_start();
if (isset($_SESSION['StaffId'])&&$_SESSION['StaffType']==='Department'){
$conn = new mysqli("localhost", "root", "yaya@1984", "repository");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_REQUEST["SelectTerm"])) {

    $stmt = $conn->prepare("select  * from proposal  where proposal.ID=?  ");
    $ProposalID = $_REQUEST["ProposalId"];
    $ProposalID=explode(",",$ProposalID);
    $ProposalID=(int)$ProposalID[0];
    $stmt->bind_param("i", $ProposalID);
    $stmt->execute();
    $Result = $stmt->get_result();
    if ($Result->num_rows > 0) {
        $Result = $Result->fetch_assoc();
        $Term = (int)$Result["Term"];
        ?>
        <select class="form-control"  name="Terms" >
        <option value="">Select Term</option>
        <?php
        for ($i=1;$i<=($Term);$i++) {
            ?>
            <option value="<?php echo $i;?>"><?php  echo $i; ?></option>
            <?php
        }
    }
    ?>
    </select>
            <?php
}
}else{
    header("Location: http://localhost/system/page-login.php");
}?>
