<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="assets/css/lib/bootstrap.min.css">
<link rel="stylesheet" href="assets/fonts/FontAwesome.otf">
<?php

session_start();
if (isset($_SESSION['StaffId'])&&$_SESSION['StaffType']==='RCC'){
    $_SESSION["Sidebar"]="Budget_Entry";
require_once '../Common/DataBaseConnection.php';
require_once '../Common/Head.php' ?>

<body>
<!--Side bar Starts-->
<?php require_once 'sidebar.php'; ?>
<!--Side bar Ends -->
<?php

$Department = $_SESSION["Department"];
?>
<!-- /# sidebar -->

<?php
require_once '../Common/Header.php'; ?>


<?php
//  to the button action on each proposal is clicked

$input = filter_input_array(INPUT_POST);
if ( isset($input['action'])&&$input['action'] == 'edit') {
    
    $Rcc_Status='Approved';
    $conn->query("UPDATE proposal SET Cost='" . $input['Budget'] .  "', Rcc_level='" . $Rcc_Status . "' WHERE id='" . $input['ID'] . "'");
} else if ( isset($input['action'])&&$input['action'] == 'delete') {
    $conn->query("UPDATE proposal SET deleted=1 WHERE id='" . $input['id'] . "'");
} else if ( isset($input['action'])&&$input['action'] == 'restore') {
    $conn->query("UPDATE proposal SET deleted=0 WHERE id='" . $input['id'] . "'");
}
?>

<div class="content-wrap">
    <div class="main">
        <div class="container-fluid">
            <!-- /# row -->
            <section id="main-content">
                <div class="row">
                    <div class="form-group">
                        <div class="col-lg-12">
                            <label class="control-label">Select Year</label>
                            <select class="form-control"  id="Year" required>
                                <option value="">Select Year</option>
                                <?php
                                $year=(int)date('Y');
                                for ($i=$year;$i>2001;$i--){
                                    ?>
                                    <option value="<?php echo $i?>"><?php echo $i?></option>
                                <?php
                                }
                                ?>
                                <option value="<?php echo $year?>"><?php echo $year?></option>

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <label class="control-label">Select Faculty</label>
                            <select class="form-control" onchange="selectDep(this.value)" id="faculty" required>
                                <option value="0">Select Faculty</option>
                                <option value="Technology">Technology</option>
                                <option value="Technology Transfer">Health</option>
                                <option value="Community Service">Humanity</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <label class="control-label">Select Department </label>

                            <select class="form-control" onchange="selectType(this.value)" id="dep" name="Type" required>
                                <option value="0">Select Faculty First</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <label class="control-label">Select Document Type</label>

                            <select class="form-control" id="Type" onchange="SelectDocuments(this.value)" name="Type"
                                    required>
                                <option value="0">Select Department First</option>
                            </select>
                        </div>
                    </div>
                    <table id="tabledit" class="table table-sm table-bordered">
                        <thead class="table-light">
                        <tr>
                            <th class="ID"><center>#</center></th>
                            <th class="Title"><center>Title</center></th>
                            <th class="File"><center>File</center></th>
                            <th class="Rcc_Status"><center>Rcc_Status</center></th>
                            <th class="Budget"><center>Budget</center></th>
                            <th class="Act"><center>Action</center></th>

                        </tr>
                        </thead>
                        <tbody id="tbody">


                        </tbody>

                    </table>
                </div>
                <?php require_once '../Common/Footer.php';?>
            </section>
        </div>
    </div>
</div>


<!-- Common -->



</body>

</html>
<script type="text/javascript">

    function SelectDocuments(v = '') {

        var Faculty = document.getElementById('faculty').value;
        var Department = document.getElementById('dep').value;
        var Year = document.getElementById('Year').value;
        // alert("Faculty: "+Faculty+" Department : "+Department+" year : "+Year+" V : "+v);
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess.php?Budget=Budget&&Faculty=' + Faculty + '&&Department=' + Department + '&&Year='+Year+'&&v=' + v,

            success: function (html) {
                $('#tbody').html(html);
                tableData();
            }
        });
    }

    function selectDep(v='') {
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess.php?selectDep=dep&&Faculty='+v,

            success: function (html) {
                $('#dep').html(html);
            }
        });
    }
    function selectType(v='') {
        $.ajax({
            type: 'POST',
            url: 'ApproveProcess.php?selectType=dep&&Department='+v,

            success: function (html) {
                $('#Type').html(html);
            }
        });
    }
    function tableData() {
        $('#tabledit').Tabledit({
            url: 'Budget_Entry.php',
            eventType: 'dblclick',
            deleteButton: true,
            method: 'POST',
            hideIdentifier: false,
            columns: {
                identifier: [0, 'ID'],
                editable: [
                    [4, 'Budget']]
            },
            onSuccess: function(data, textStatus, jqXHR) {
                alert('Successfully Updated');
                SelectDocuments('');
            },
            onFail: function(jqXHR, textStatus, errorThrown) {
                console.log('onFail(jqXHR, textStatus, errorThrown)');
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            },
            onAjax: function(action, serialize) {
                console.log('onAjax(action, serialize)');
                console.log(action);
                console.log(serialize);
            },
            buttons: {
                edit: {
                    class: 'btn btn-sm btn-default',
                    html: '<span class="ti-pencil"></span>',
                    action: 'edit'
                },
                delete: {
                    class: 'btn btn-sm btn-default',
                    html: '<span class="ti-close"></span>',
                    action: 'delete'
                },
                save: {
                    class: 'btn btn-sm btn-success',
                    html: 'Save'
                },
                restore: {
                    class: 'btn btn-sm btn-warning',
                    html: 'Restore',
                    action: 'restore'
                },
                confirm: {
                    class: 'btn btn-sm btn-danger',
                    html: 'Confirm'
                }
            }

        });
    }
</script>
<?php
}else{
    header("Location: http://localhost/system/page-login.php");
}?>