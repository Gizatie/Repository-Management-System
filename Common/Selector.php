<?php
if ($_SESSION["StaffType"] === "RCD") {
?>
    <div class="col-lg-4">
        <label class="control-label">Select Facult</label>
        <select class="form-control" onchange="selectDepartement(this.value)" id="Faculty" required>
            <option value="">Select Facult</option>
            <?php
            $Faculty = $_SESSION['Faculty'];
            $stmt = $conn->prepare("select * from faculty");
            $stmt->execute();
            $Result = $stmt->get_result();
            while ($data = $Result->fetch_assoc()) {
            ?>
                <option value="<?php echo $data['Name'] ?>"><?php echo $data['Name'] ?></option>
            <?php
            }
            ?>
        </select>
    </div>
    <div class="col-lg-4">
        <label class="control-label">Select Department</label>
        <select class="form-control" onchange="selectType(this.value)" id="Department" required>
            <option value="">Select Faculty First</option>
        </select>
    </div>
    <div class="col-lg-4">
        <label class="control-label">Select Type</label>
        <select class="form-control" id="Type" onchange="selectProposal(this.value)" id="Type" required>
            <option value="">Select Department First</option>

        </select>
    </div>
<?php
}else if ($_SESSION["StaffType"] === "RCC") {
   

    $faculty = $_SESSION["Faculty"];
    $stmt = $conn->prepare("select * from department where Faculty=?");
    $stmt->bind_param("s", $faculty);

               ?>
    <div class="col-lg-4">
        <label class="control-label">Select Department</label>
        <select class="form-control" onchange="selectType(this.value)" id="Department" required>
            <option value="">Select Faculty First</option>
            <?php
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $result = $result->fetch_assoc();
            ?>
                <option value="<?php echo $result["Name"] ?>"><?php echo $result["Name"] ?></option>
            <?php
            }
            ?>
        </select>
    </div>
    <div class="col-lg-4">
        <label class="control-label">Select Type</label>
        <select class="form-control" id="Type" onchange="selectProposal(this.value)" id="Type" required>
            <option value="">Select Department First</option>

        </select>
    </div>
<?php
} else if ($_SESSION["StaffType"] === "Researcher") {
?>
    <div class="col-lg-4">
        <label class="control-label">Select Type</label>
        <select class="form-control" id="Type" onchange="selectProposal(this.value)" id="Type" required>
            <option value="">Select Department Type</option>
            <option value="Research/<?php echo $_SESSION["Department"];?>">Research</option>
            <option value="Technology Transfer/<?php echo $_SESSION["Department"];?>">Technology Transfer</option>
            <option value="Community Service/<?php echo $_SESSION["Department"];?>">Community Service</option>
        </select>
    </div>
<?php
}
?>