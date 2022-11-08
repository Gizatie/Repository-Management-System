<?php
session_start();
if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Department' || $_SESSION['StaffType'] === 'RCC' || $_SESSION['StaffType'] === 'RCD' || $_SESSION['StaffType'] === 'Researcher'  || $_SESSION['StaffType'] === 'Faculty')  {
    $_SESSION["Sidebar"]="ViewPreviewsWork";
    require_once('../Common/DataBaseConnection.php');
?>
    <!DOCTYPE html>
    <html lang="en">

    <?php

    require_once '../Common/Head.php' ?>

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
        ?>
        <!-- /# sidebar -->
        <?php require_once '../Common/Header.php' ?>

        <div class="content-wrap">
            <div class="main">
                <div class="container-fluid">
                    <!-- /# row -->
                    <section id="main-content">
                        <div class="row">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <center>ID</center>
                                        </th>
                                        <th>
                                            <center>Title</center>
                                        </th>
                                        <th>
                                            <center>Type</center>
                                        </th>
                                        <th>
                                            <center>File</center>
                                        </th>
                                        <th>
                                            <center>Cost</center>
                                        </th>
                                        <th>
                                            <center>Role</center>
                                        </th>
                                        <th>
                                            <center>Year</center>
                                        </th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $conn->prepare("SELECT pa.Role,p.ID,p.Title,d.Finish_year,P.Type,d.file,p.Cost,p.Status FROM proposal as p,participant as pa, documents as d where d.Proposal_ID=p.ID and d.Proposal_ID=pa.Proposal_ID and p.ID=pa.Proposal_ID and d.Final_Status='Completed' and pa.Staff_ID=?  order by Finish_year DESC");
                                    $StaffId = $_SESSION["StaffId"];

                                    $stmt->bind_param("s", $StaffId);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result->num_rows) {

                                        while ($data = $result->fetch_assoc()) {
                                    ?>
                                            <tr>
                                                <td>
                                                    <center><?php echo $data["ID"] ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data["Title"] ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data["Type"] ?></center>
                                                </td>
                                                
                                                <td>
                                                    <center>
                                                        <a href="../Documents/<?php echo $data["Type"] ?>/Final/<?php echo $data["file"] ?>"><?php echo $data["file"] ?></a>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data["Cost"]; ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data["Role"]; ?></center>
                                                </td>
                                                <td>
                                                    <center><?php echo $data["Finish_year"]; ?></center>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="9">
                                                <center>No Record</center>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>

                                </tbody>

                            </table>
                        </div>
                        <?php require_once '../Common/Footer.php';?>
                    </section>
                </div>
            </div>
        </div>
    </body>

    </html>
<?php

} else {
    header("Location: ../page-login.php");
} ?>