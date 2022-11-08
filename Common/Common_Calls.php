     <?php
        require_once '../Common/DataBaseConnection.php';
        //selects the departments in the specified Faculty
    if (isset($_REQUEST["selectDepartment"])) {
        $data=explode("/",$_REQUEST["Faculty"])
        ?>
         <option value="">Select Department</option>

         <?php
            $Faculty =$data[0] ;
            $proposalID =$data[1] ;
            $stmt = $conn->prepare("select * from department where Faculty=?");
            $stmt->bind_param("s", $Faculty);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                while ($data = $result->fetch_assoc()) {
            ?>
                 <option value="<?php echo $data['Name']."/".$Faculty.'/'.$proposalID ?>"><?php echo $data['Name'] ?></option>
     <?php
                }
            }
}else if(isset($_REQUEST["Type"])){
    $data = explode("/",$_REQUEST["Faculty"]);
    $department=$data[0];
    $faculty=$data[1];
    $proposalID =$data[2] ;
    //         $stmt = $conn->prepare("select * from department where Faculty=?");
    //         $stmt->bind_param("s", $Faculty);
?>
 <option value="">Select Type</option>
 <option value="Research/<?php echo $department."/".$faculty.'/'.$proposalID  ?>">Research</option>
 <option value="Technology Transfer/<?php echo $department."/".$faculty.'/'.$proposalID ?>">Technology Transfer</option>
 <option value="Community Service/<?php echo $department."/".$faculty.'/'.$proposalID  ?>">Community Service</option>

 <?php

}
