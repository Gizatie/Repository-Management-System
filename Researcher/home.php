<?php
session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Researcher'  || $_SESSION['StaffType'] === 'RCC' || $_SESSION['StaffType'] === 'RCD' || $_SESSION['StaffType'] === 'Faculty' || $_SESSION['StaffType'] === 'Department') {
    $_SESSION["Sidebar"] = "home";
    require_once('../Common/DataBaseConnection.php');
    require_once '../Common/Head.php'; ?>

    <body>

        <?php
        $currentUserType = $_SESSION['StaffType'];
        switch ($currentUserType) {
            case 'Researcher':
                require_once '../Researcher/sidebar.php';
                break;
            case 'Department':
                require_once '../Department/sidebar.php';
                break;
            case 'RCC':
                require_once '../RCC/sidebar.php';
                break;
            case 'RCD':
                require_once '../RCD/sidebar.php';
                break;
            case 'Faculty':
                require_once '../Faculty/sidebar.php';
                break;
        }


        require_once '../Common/Header.php';


        ?>



        <div class="content-wrap">
            <div class="main">
                <div class="container-fluid">
                    <!-- /# row -->
                    <section id="main-content">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-title">
                                        <h4>Proposal Search</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class='row'>

                                            <div class='col-lg-4'>
                                                <label class='control-label'>Select Type</label>

                                                <select class='form-control' name='Type' onchange="Select_Start_Year(this.value)">
                                                    <option value='0'>Select Proposal Type</option>
                                                    <option value='Research'>Research</option>
                                                    <option value='Technology Transfer'>Technology Transfer</option>
                                                    <option value='Community Service'>Community Service</option>
                                                    <option value='Thesis'>Thesis</option>
                                                    <option value='Project'>Project</option>
                                                    <option value='Other'>Other</option>
                                                </select>
                                            </div>
                                            <div class='col-lg-4'>
                                                <label class='control-label'>From</label>

                                                <select class='form-control' id='Start_Year' onchange="Select_End_Year(this.value)">
                                                    <option value=''>Select Type First</option>
                                                </select>
                                            </div>
                                            <div class='col-lg-4'>
                                                <label class='control-label'>To</label>
                                                <select class='form-control' onchange='Select_Proposal(this.value)' id=End_Year>
                                                    <option value='0'>Select Start Year First</option>

                                                </select>
                                            </div>
                                            <div class='row col-lg-12' id='par'>
                                                <hr class="mt-5">
                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">ID</th>
                                                                <th scope="col">Title</th>
                                                                <th scope="col">
                                                                    <center>Proposal</center>
                                                                </th>
                                                                <th scope="col">Document</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tbody">
                                                            <tr>
                                                                <td colspan="4">
                                                                    <center>No Record Found</center>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /# column -->
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-title">
                                        <h4 class="text-danger">Notification page </h4><h2 class=" blink_me text-danger">Urgent</h2>
                                    </div>
                                    <div class="card-body">
                                        <?php
                                        $Faculty = $_SESSION['Faculty'];
                                        $date = explode("-", Date("y-m-d"));
                                        $month = $date[1] - 2;
                                        $daylimit = Date("Y") . '-0' . $month . '-30';
                                        $Department = $_SESSION['Department'];
                                        $stmt = $conn->prepare("select * from notification as n where (n.Faculty='All'  OR n.Department=?  OR  ( n.Faculty=? and n.Department='All')) and n.PostDate >=$daylimit ");
                                        $stmt->bind_param("ss", $Department, $Faculty);
                                        if ($stmt->execute()) {
                                            $result = $stmt->get_result();
                                            if ($result->num_rows) {
                                                while ($data = $result->fetch_assoc()) {
                                                    $stmt = $conn->prepare("select * from staffs as s where s.ID=?");
                                                    $PostedBy=$data['PostedBy'];
                                                    $stmt->bind_param("s", $PostedBy);
                                                    $stmt->execute();
                                                    $result1 = $stmt->get_result();
                                                    $StaffDetail = $result1->fetch_assoc()
                                        ?>
                                                    <div class="card" style="width: 18rem;">
                                                        <div class="card-body">
                                                            <h5  style="background-color: #00AA9E;" class="card-title"> <?php echo '<b>From-' .$StaffDetail['Role'].'</b>'; ?></h5>
                                                            <h6 class="card-subtitle mb-2 text-muted"><?php echo '<b>From-' .$StaffDetail['First_Name'].' '.$StaffDetail['Middle_Name'].' '.$StaffDetail['Last_Name'].'</b>'; ?></h6>
                                                            <p class="card-text"><?php echo $data["Message"] ?></p>
                                                            <?php if ($data["File"] !== "NA") {
                                                            ?>
                                                                <a href="../Documents/Other/Notifications/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a>
                                                            <?php
                                                            } ?>
                                                        </div>
                                                    </div>
                                        <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <!-- /# column -->
                        </div>
                        <?php require_once '../Common/Footer.php'; ?>
                    </section>
                </div>
            </div>
        </div>


        <?php require_once '../Common/Footer.php'; ?>

    </body>

    </html>
    <script>
        function Select_Start_Year(v = '') {
            // alert(v);
            $.ajax({
                type: 'POST',
                url: 'Process.php?SelectYear=' + v,

                success: function(html) {
                    $('#Start_Year').html(html);
                }
            });
        }

        function Select_Proposal(v = '') {
            // alert('hello'+v);
            $.ajax({
                type: 'POST',
                url: 'Process.php?Select_Proposal=' + v,

                success: function(html) {
                    $('#tbody').html(html);
                    // alert("done");
                }
            });
        }

        function Select_End_Year(v = '') {
            const data = v.split("/");
            // alert(v);
            $.ajax({
                type: 'POST',
                url: 'Process.php?EndYear=' + v,

                success: function(html) {
                    $('#End_Year').html(html);
                }
            });
            $.ajax({
                type: 'POST',
                url: 'Process.php?Select_Proposal=' + v,

                success: function(html) {
                    $('#tbody').html(html);
                }
            });

        }
    </script>
    <style>
        .blink_me {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
    </style>
<?php

} else {
    header("Location: http://localhost/system/page-login.php");
} ?>