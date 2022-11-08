   <?php
    session_start();
    if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'Faculty') {
        // Create connection
        require_once '../Common/DataBaseConnection.php';
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
                           <center><?php echo $data["date"] ?></center>
                       </td>
                       <td>
                           <center><a href="../Documents/<?php echo $data["Type"] ?>/Final/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a></center>
                       </td>
                       <td>
                           <center><?php echo $data["Rcc_Status"] ?></center>
                       </td>
                       <td>
                           <center>
                               <div class="dropdown">
                                   <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">Action
                                       <span class="caret"></span></button>
                                   <ul class="dropdown-menu">
                                       <li>
                                           <a href="ApproveDocument.php?Action=Approve&&ProposalId=<?php echo $data['ID'] ?>">Approve</a>
                                       </li>
                                       <li>
                                           <a href="ApproveDocument.php?Action=Reject&&ProposalId=<?php echo $data['ID'] ?>">Reject</a>
                                       </li>
                                       <li>
                                           <a href="ApproveDocument.php?Action=Pass&&ProposalId=<?php echo $data['ID'] ?>">Pass</a>
                                       </li>
                                       <li>
                                           <a href="ApproveDocument.php?Action=suspend&&ProposalId=<?php echo $data['ID'] ?>">Suspend</a>
                                       </li>
                                   </ul>
                               </div>
                           </center>
                       </td>
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
        } else if (isset($_REQUEST['Approve_Agreement'])) {
            $data = explode('/', $_REQUEST["Department"]);
            $stmt = $conn->prepare("SELECT DISTINCT s.ID,s.First_Name,s.Middle_Name,s.Last_Name,p.ID,p.Title,p.File,pa.Role,pa.Agreement,p.Faculty_Agreement_Status,pa.Staff_ID,p.Cost,p.RCC_Agreement_Status FROM participant as pa INNER JOIN proposal as p ON pa.Proposal_ID = p.ID INNER JOIN staffs as s  ON s.ID=pa.Staff_ID and p.RCC_Agreement_Status='Approved' and p.Type=? and p.Department=? and date like ? and p.Status='Waiting for Agreement Approval' ORDER BY pa.Role ASC");
            $year = Date("Y") . '%';
            $Department = $data[0];
            $Type = $data[1];
            // echo $Type . ' ' . $Faculty . ' ' . $Department;
            $stmt->bind_param("sss", $Type, $Department, $year);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows) {

                while ($data = $result->fetch_assoc()) {
                    if ($data["Role"] === "PI") {
                ?>
                       <tr>
                           <td>
                               <center><?php echo $data["ID"] ?></center>
                           </td>
                           <td>
                               <center><?php echo $data["Title"] ?></center>
                           </td>
                           <td>
                               <center><a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a></center>
                           </td>
                           <td>
                               <center><?php echo $data["Cost"] ?></center>
                           </td>
                           <td>
                               <!-- Trigger the modal with a button -->
                               <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="<?php echo '#myModal' . $data['ID']; ?>">View Agreement
                               </button>

                               <!-- Modal -->
                               <div id="<?php echo 'myModal' . $data['ID']; ?>" class="modal fade" role="dialog">
                                   <div class="modal-dialog">

                                       <!-- Modal content-->
                                       <div class="modal-content">
                                           <div class="modal-header mo">
                                               <button type="button" class="close" data-dismiss="modal"></button>
                                               <h4 class="modal-title">Select The Proposal tobe merged with</h4>
                                           </div>
                                           <div class="modal-body">
                                               <form action="Take_Agreement.php">
                                                   <div class="form-group">
                                                       <div class="col-lg-12">
                                                           <label id="label">Dear <?php echo $data['ID'] ?> </label>
                                                           <input type="email" name="Staff ID" class="form-control" placeholder="">
                                                       </div>
                                                   </div>
                                                   <div class="form-group">
                                                       <div class="col-lg-12" multiple="">
                                                           <center>

                                                               THIS AGREEMENT is entered into and effective as of the ____ day
                                                               of
                                                               __________ 20__
                                                               ("Effective Date") by and between _______________________, a
                                                               _________corporation with
                                                               offices at ___________________________(“SPONSOR”); and The
                                                               University of Tennessee, a
                                                               public higher education institution and instrumentality of the
                                                               State
                                                               of Tennessee, on behalf of its
                                                               Health Science Center, with offices at 62 S. Dunlap, Suite 300,
                                                               Memphis, Tennessee 38163 ("the
                                                               UNIVERSITY").
                                                               RECITALS
                                                               WHEREAS, the research program contemplated by this Agreement is
                                                               of
                                                               mutual interest and benefit
                                                               to the parties and will further the instructional and research
                                                               objectives of the UNIVERSITY and the
                                                               research objectives of SPONSOR in a manner consistent with the
                                                               UNIVERSITY's status as an
                                                               educational corporate agency of the State of Tennessee.
                                                               NOW, THEREFORE, the parties agree as follows:
                                                               1. STATEMENT OF WORK AND PRINCIPAL INVESTIGATOR.
                                                               1.1 The UNIVERSITY agrees to use its reasonable efforts to
                                                               perform
                                                               the research project
                                                               ("Project") as set out in the Statement of Work titled,
                                                               ________________________________________, attached hereto and
                                                               incorporated herein by
                                                               reference as Appendix A.
                                                               1.2 The UNIVERSITY's obligations as stated in Article 1.1 above
                                                               shall be carried out under the
                                                               supervision of _____________________, Principal Investigator.
                                                               The
                                                               failure of _______________
                                                               for any reason to continue to serve as Principal Investigator
                                                               until
                                                               the normal conclusion of the Project
                                                               shall not be considered a breach of this Agreement and shall not
                                                               subject the UNIVERSITY to any
                                                               liability. In such case, the UNIVERSITY and SPONSOR shall
                                                               endeavor
                                                               to agree upon a successor,
                                                               and if they fail to agree, either of them may terminate this
                                                               Agreement without cause upon written
                                                               notice to the other party.
                                                               2. RESEARCH SUPPORT BY SPONSOR.
                                                               2.1 The total research support to be provided by SPONSOR is
                                                               $___________. Payments shall be
                                                               made to the UNIVERSITY by SPONSOR according to the following
                                                               schedule:
                                                               Insert schedule of payments (include non-refundable start-up
                                                               costs
                                                               if applicable, applicable
                                                               overhead, as much as possible up front, and not more than 10%
                                                               withheld as final payment)
                                                               NOTE: Budget should cover FULL cost of research
                                                               2
                                                               2.2 Checks shall be made payable to The University of Tennessee
                                                               and
                                                               shall be mailed to the
                                                               following address:
                                                               Anthony A. Ferrara, Vice Chancellor for Finance and Operations
                                                               UTHSC
                                                               62 S. Dunlap, Suite 300
                                                               Memphis, TN 38163
                                                               901 448-5523
                                                               spa@uthsc.edu
                                                               2.3 All funds provided by SPONSOR under this Agreement may be
                                                               used
                                                               at the discretion of the
                                                               UNIVERSITY.
                                                               3. TERM AND TERMINATION.
                                                               3.1 The term of this Agreement shall be from ___________________
                                                               (Effective Date) through
                                                               _____________________ , 20 ____, unless sooner terminated in
                                                               accordance with the provisions set
                                                               out herein. This Agreement may be extended, renewed, or
                                                               otherwise
                                                               amended at any time by the
                                                               mutual written consent of the parties.
                                                               3.2 In the event that either the UNIVERSITY or SPONSOR defaults
                                                               in
                                                               the due performance of its
                                                               obligations hereunder or in the event that any representation by
                                                               either of them proves to be false or
                                                               incorrect, and such default or breach is not cured within thirty
                                                               (30) days of written notice thereof,
                                                               then the party giving such notice may elect to terminate this
                                                               Agreement by final written notice to the
                                                               defaulting party. The parties recognize that the results of any
                                                               particular research project cannot be
                                                               guaranteed even through the use of the UNIVERSITY's reasonable
                                                               efforts; therefore, it is specifically
                                                               agreed that the failure of the UNIVERSITY to achieve specific
                                                               research results shall not constitute a
                                                               default or breach of this Agreement.
                                                               3.3 Either UNIVERSITY or SPONSOR may terminate this Agreement
                                                               without cause upon
                                                               written notification to the other at least thirty (30) days
                                                               prior to
                                                               the effective date of termination.
                                                               3.4 If the total funds paid by SPONSOR by the date of
                                                               termination
                                                               are insufficient to cover the
                                                               amounts earned in accordance with the budget and commitments
                                                               incurred by the UNIVERSITY in
                                                               the performance of the research, SPONSOR shall reimburse the
                                                               UNIVERSITY for same within thirty
                                                               (30) days of termination, provided that in no event shall
                                                               SPONSOR be
                                                               responsible for any amount in
                                                               excess of that stated in Article 2.1.
                                                               3.5 In the event of termination of this Agreement by the
                                                               UNIVERSITY
                                                               for default on the part of
                                                               SPONSOR, or termination of this Agreement by SPONSOR without
                                                               cause,
                                                               the Option granted under
                                                               Article 7 below shall thereupon terminate automatically.
                                                               3
                                                               4. EQUIPMENT.
                                                               4.1 Title to any equipment purchased, manufactured, or otherwise
                                                               acquired in the course of the
                                                               work under this Agreement shall vest in the UNIVERSITY,
                                                               notwithstanding any contribution
                                                               directly or indirectly from SPONSOR.
                                                               5. PUBLISHING.
                                                               5.1 UNIVERSITY reserves to itself and its employees the sole
                                                               right
                                                               to publish the results of the
                                                               Project in whole or in part as they deem appropriate. In order
                                                               that
                                                               premature public disclosure of
                                                               such information does not adversely affect the interests of the
                                                               parties, the UNIVERSITY shall
                                                               provide SPONSOR with a copy of each manuscript pertaining to the
                                                               Project that is intended for
                                                               publication. SPONSOR may request delay in publication for a
                                                               period
                                                               not to exceed ninety (90) days
                                                               from the date on which SPONSOR receives the manuscript. If
                                                               SPONSOR
                                                               does not make a written
                                                               request for delay in publication within thirty (30) days after
                                                               receipt of a manuscript, UNIVERSITY
                                                               shall be free to publish the manuscript at any time after the
                                                               end of
                                                               the thirty (30) days. SPONSOR’s
                                                               right to request a delay in publication shall not apply to any
                                                               thesis or dissertation.
                                                               6. CONFIDENTIALITY.
                                                               6.1 The UNIVERSITY and SPONSOR recognize that the conduct of a
                                                               research program may
                                                               require the transfer of proprietary information between the
                                                               parties.
                                                               Accordingly, it is agreed that the
                                                               acceptance by either of them of the other's proprietary
                                                               information
                                                               shall be subject to the following:
                                                               A. The term "Confidential Information" as used herein, in the
                                                               case
                                                               of documentary information,
                                                               shall include only that documentary information which is clearly
                                                               marked as proprietary (or
                                                               confidential) at the time when it is given to the receiving
                                                               party.
                                                               "Confidential Information" which is
                                                               originally orally disclosed shall include only that information
                                                               which is identified as being proprietary
                                                               or confidential at the time of disclosure and confirmed as
                                                               confidential by written communication sent
                                                               within a reasonably prompt period of time after it is disclosed
                                                               to
                                                               the receiving party.
                                                               B. The subject matter of the Confidential Information is to be
                                                               limited to that which is relative to
                                                               the research outlined in the Statement of Work under Article 1
                                                               above.
                                                               C. The receiving party will not publish or otherwise reveal to
                                                               any
                                                               third party the Confidential
                                                               Information (properly designated) of the disclosing party
                                                               without
                                                               the disclosing party's written
                                                               permission, unless the information:
                                                               (1) is already lawfully in the receiving party's possession at
                                                               the
                                                               time of receipt from the
                                                               disclosing party as evidenced by appropriate documentation;
                                                               (2) is or later becomes public through no fault of the receiving
                                                               party;
                                                               (3) is published by the UNIVERSITY and or its employee(s) in
                                                               accordance with the
                                                               provisions of Article 5 above;
                                                               4
                                                               (4) is lawfully received from a third party as evidenced by
                                                               appropriate documentation;
                                                               (5) has been in the possession of the receiving party for five
                                                               (5)
                                                               years or longer;
                                                               (6) is independently developed by the receiving party as
                                                               evidenced
                                                               by appropriate
                                                               documentation; or
                                                               (7) is required by law, including the Tennessee Public Records
                                                               Act,
                                                               T.C.A. 10-7-503 et
                                                               seq., to be disclosed.
                                                               7. INTELLECTUAL PROPERTY.
                                                               7.1 Pre-Existing Intellectual Property Rights of the Parties. No
                                                               party claims by virtue of this
                                                               Agreement any right, title, or interest in (a) any issued or
                                                               pending
                                                               patents or any copyrights owned or
                                                               controlled by another party or (b) any previous invention,
                                                               process,
                                                               or product of another party,
                                                               whether or not patented or patentable.
                                                               7.2 Definition. The term "Intellectual Property" shall mean all
                                                               inventions and developments
                                                               (whether or not patentable) and other creative works (excluding
                                                               theses, dissertations and scholarly
                                                               publications) developed in the course of the performance of the
                                                               work
                                                               under this Agreement,
                                                               including without limitation any patent, trademark, copyright,
                                                               mask
                                                               work right, or other property
                                                               right pertaining to same.
                                                               7.3 Allocation of rights.
                                                               A. Both UNIVERSITY and SPONSOR agree to promptly disclose to the
                                                               other all Intellectual
                                                               Property developed in the course of the work under this
                                                               Agreement.
                                                               B. The Intellectual Property developed solely by the UNIVERSITY
                                                               or
                                                               jointly by the
                                                               UNIVERSITY and SPONSOR in the performance of work under this
                                                               Agreement shall be
                                                               owned by the UNIVERSITY.
                                                               C. UNIVERSITY hereby grants to SPONSOR an exclusive option
                                                               ("Option") to acquire a
                                                               worldwide (to the extent possible) royalty-bearing license to
                                                               use
                                                               the Intellectual Property
                                                               developed in the course of the work under this Agreement in the
                                                               field of
                                                               __________________ (the “Optioned IP”).
                                                               D. The "Option Period" shall commence on the date of disclosure
                                                               to
                                                               SPONSOR of the Optioned
                                                               IP and shall terminate on the earlier of the following: (a) six
                                                               months from the date of
                                                               disclosure; or (b) termination of the Option pursuant to Article
                                                               3.5; or (c) proper exercise of
                                                               the Option by SPONSOR.
                                                               E. SPONSOR may exercise the Option during the Option Period by
                                                               giving written notice of
                                                               same to UNIVERSITY provided that SPONSOR is not then in default
                                                               or
                                                               breach of any of its
                                                               obligations under this Agreement.
                                                               F. Upon proper exercise of the Option by SPONSOR, UNIVERSITY and
                                                               SPONSOR will
                                                               negotiate in good faith in an effort to reach a
                                                               commercialization
                                                               agreement satisfactory to
                                                               5
                                                               both parties, the negotiation period not to exceed six (6)
                                                               months.
                                                               Upon the first to occur of
                                                               (a) termination of the Option by operation of the provisions of
                                                               Article 3.5 above; or (b)
                                                               expiration of the Option Period with the Option unexercised; or
                                                               (c)
                                                               expiration of the sixmonth negotiation period without the
                                                               execution
                                                               of a commercialization agreement,
                                                               UNIVERSITY shall have no further obligation to SPONSOR under
                                                               this
                                                               Agreement with
                                                               regard to the Optioned IP. In the absence of a further agreement
                                                               between UNIVERSITY and
                                                               SPONSOR, SPONSOR agrees that it will not use the Optioned IP for
                                                               any
                                                               commercial or noncommercial purpose.
                                                               G. During the Option Period, UNIVERSITY and SPONSOR will confer
                                                               concerning the proper
                                                               protection of the Optioned IP. Within thirty (30) days after
                                                               receipt
                                                               of an invoice from
                                                               UNIVERSITY, SPONSOR shall reimburse UNIVERSITY for all
                                                               out-of-pocket
                                                               expenses
                                                               incurred by UNIVERSITY during the Option Period in the filing,
                                                               prosecution, and
                                                               maintenance of United States and foreign patent applications,
                                                               issued
                                                               patents, and other forms
                                                               of intellectual property protection for the Optioned IP, all of
                                                               which shall be owned by
                                                               UNIVERSITY.
                                                               H. It is understood and agreed that any rights granted by or to
                                                               any
                                                               party by the terms of this
                                                               Agreement shall in all respects be subject to any rights claimed
                                                               or
                                                               restrictions and obligations
                                                               imposed by the United States government or any agency thereof,
                                                               whether such rights or
                                                               restrictions and obligations arise out of federal funding of the
                                                               underlying research or
                                                               otherwise.
                                                               8. BINDING AGREEMENT.
                                                               8.1 This Agreement shall be binding upon and inure to the
                                                               benefit of
                                                               each of the parties hereto,
                                                               their successors and assigns; provided, however, that this
                                                               Agreement
                                                               is not assignable or transferable,
                                                               in whole or in part, by any party without the prior written
                                                               consent
                                                               of the other parties.
                                                               Notwithstanding the foregoing, UNIVERSITY may assign its rights
                                                               and
                                                               interest to the University of
                                                               Tennessee Research Foundation (“UTRF”) or a successor in
                                                               interest to
                                                               UTRF or the UNIVERSITY
                                                               without the prior written consent of SPONSOR.
                                                               9. LIABILITY/INDEMNIFICATION.
                                                               9.1 The UNIVERSITY makes no warranties, either expressed or
                                                               implied,
                                                               as to the work to be
                                                               performed under this Agreement or the Optioned IP. THE
                                                               UNIVERSITY
                                                               SPECIFICALLY
                                                               DISCLAIMs ANY WARRANTIES OF MERCHANTABILITY OR FITNESS FOR A
                                                               PARTICULAR PURPOSE. The UNIVERSITY shall not be liable for any
                                                               direct, consequential, or
                                                               other damages suffered by SPONSOR or others resulting from the
                                                               work
                                                               performed under this
                                                               Agreement or the Optioned IP.
                                                               9.2 LIMITATION OF LIABILITY ON BEHALF OF THE UNIVERSITY. The
                                                               UNIVERSITY
                                                               is self-insured under the provisions of the Tennessee Claims
                                                               Commission Act (T.C.A. 9-8-301 et
                                                               seq.), and its liability to SPONSOR and to third parties for the
                                                               negligence of the UNIVERSITY and
                                                               its employees is subject to the tort provisions of that Act.
                                                               Accordingly, any liability of the
                                                               UNIVERSITY for any damages, losses, or costs arising out of or
                                                               related to acts performed by the
                                                               6
                                                               UNIVERSITY or its employees under this agreement is governed by
                                                               the
                                                               provisions of said Act.
                                                               Notwithstanding anything in this agreement to the contrary, any
                                                               provisions or provisions of this
                                                               agreement will not apply to the extent that it is (they are)
                                                               finally
                                                               determined to violate the laws or
                                                               Constitution of the State of Tennessee.
                                                               9.3 SPONSOR will indemnify UNIVERSITY and their respective
                                                               trustees,
                                                               directors, officers,
                                                               employees and agents and hold them harmless from every loss,
                                                               cost or
                                                               damage for judgments, awards
                                                               or the compromise of any claim arising out of the use of the
                                                               Optioned IP or the advertisement,
                                                               manufacture, use or sale of any product or process by SPONSOR,
                                                               its
                                                               sublicensees, dealers or
                                                               customers.
                                                               10. EXPORT C
                                                               <label id="label">The Proposal Id is . <?php ?> </label>
                                                               <div class="well well-lg text-danger">Remember if you click the
                                                                   agree button it will be considered as you agree the terms
                                                                   listed
                                                                   above including the Buget
                                                               </div>
                                                       </div>
                                                   </div>
                                                   <button type="submit" name="Submit" class="btn btn-primary"><a href="Take_Agreement.php?Action=Agreed&&ProposalID=<?php echo $data['ID'] ?>">Agree</a>
                                                   </button>
                                                   <button type="submit" name="Submit" class="btn btn-primary"><a href="Take_Agreement.php?Action=Disagreed&&ProposalID=<?php echo $data['ID'] ?>">Disagree</a>
                                                   </button>
                                               </form>
                                           </div>

                                       </div>

                                   </div>
                               </div>
                           </td>
                           <td>
                               <center><?php echo $data["Faculty_Agreement_Status"] ?></center>
                           </td>

                       </tr>
               <?php
                    }
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
        } else if (isset($_REQUEST['Transfer'])) {
            // echo "<script>alert('Transfer')</script>";
            $stmt = $conn->prepare("select DISTINCT * from Proposal as p where   p.Status!='On Progress' and  p.Status!='Completed' and p.Department_level='Approved' and p.Rcc_level!='Rejected' and  p.Type=? and p.Faculty=? and p.Department=? order by p.Rcc_level ASC");
            $Type = $_REQUEST["v"];
            $Faculty = $_SESSION["Faculty"];
            $Department = $_REQUEST["Department"];
            // echo $Type . ' ' . $Faculty . ' ' . $Department;
            $stmt->bind_param("sss", $Type, $Faculty, $Department);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows) {

                while ($data = $result->fetch_assoc()) {
                    $stmt = $conn->prepare("select p.Staff_ID from participant as p where   p.Proposal_ID=? and p.Role='PI'");
                    $ID = $data["ID"];
                    $stmt->bind_param("s", $ID);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    $PI = null;
                    $staffID = null;
                    if ($res->num_rows) {
                        $row = $res->fetch_assoc();
                        $staffID = $row["Staff_ID"];
                        $stmt = $conn->prepare("select s.First_Name,s.Middle_Name,s.Last_Name from staffs as s where   s.ID=? ");

                        $stmt->bind_param("s", $staffID);
                        $stmt->execute();
                        $res2 = $stmt->get_result();
                        if ($res2->num_rows) {
                            $row2 = $res2->fetch_assoc();
                            $PI = $row2["First_Name"] . " " . $row2["Middle_Name"] . " " . $row2["Last_Name"];
                        }
                    }
                ?>
                   <tr>
                       <td>
                           <center><?php echo $data["ID"] ?></center>
                       </td>
                       <td>
                           <center><?php echo $data["Title"] ?></center>
                       </td>

                       <td>
                           <center>
                               <a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a>
                           </center>
                       </td>
                       <td>
                           <div class="dropdown">

                               <button class="btn btn-primary dropdown-toggle btn-md" type="button" data-toggle="dropdown">Action
                                   <span class="caret"></span></button>
                               <ul class="dropdown-menu">
                                   <?php
                                    $poposal = $data["ID"];
                                    $stmt = $conn->prepare("SELECT s.ID,s.First_Name,s.Middle_Name,s.Last_Name,pa.Role,pa.Proposal_ID FROM participant as pa INNER JOIN staffs as s ON pa.Proposal_ID=? and s.ID = pa.Staff_ID ");

                                    $stmt->bind_param("i", $poposal);
                                    $stmt->execute();
                                    $participants = $stmt->get_result();
                                    $OldPI = $staffID;

                                    while ($participants_data = $participants->fetch_assoc()) {

                                    ?>
                                       <li>
                                           <a <?php ?>href="Transfer.php?PIChange=PIChange&&OldPI=<?php echo $OldPI; ?>&&ProposalId=<?php echo $poposal;
                                                                                                                                    $role = '';
                                                                                                                                    $participants_data['Role'] === 'PI' ? $role = '(PI)' : $role = ''; ?>&&NewPI=<?php echo $participants_data['ID'] ?>&&dep=<?php echo $data['Department'] ?>&&Type=<?php echo $data['Type'] ?>"><?php echo $participants_data['First_Name'] . ' ' . $participants_data['Middle_Name'] . $role ?></a>
                                       </li>
                                   <?php
                                    }
                                    ?>
                               </ul>
                           </div>
                       </td>
                       <td>
                           <center><?php echo $data["Type"] ?></center>
                       </td>
                       <td>
                           <div class="dropdown">
                               <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">Action
                                   <span class="caret"></span></button>
                               <ul class="dropdown-menu">
                                   <li>
                                       <a href="Transfer.php?Transfer=Transfer&&Action=Research&&ProposalId=<?php echo $data['ID'] ?>&&Type=<?php echo $data['Type'] ?>&&selectType=<?php echo $Type ?>&&dep=<?php echo $Department ?>">Research</a>
                                   </li>
                                   <li>
                                       <a href="Transfer.php?Transfer=Transfer&&Action=Technology Transfer&&ProposalId=<?php echo $data['ID'] ?>&&Type=<?php echo $data['Type'] ?>&&selectType=<?php echo $Type ?>&&dep=<?php echo $Department ?>">Technology Transfer</a>
                                   </li>
                                   <li>
                                       <a href="Transsfer.php?Transfer=Transfer&&Action=Comunity Service&&ProposalId=<?php echo $data['ID'] ?>&&Type=<?php echo $data['Type'] ?>&&selectType=<?php echo $Type ?>&&dep=<?php echo $Department ?>">Community Service</a>
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
                   <td colspan="6">
                       <center>No Record Found</center>
                   </td>
               </tr>
               <?php
            }
        } else if (isset($_REQUEST['Agreement'])) {
            $StaffID = $_SESSION['StaffId'];
            $stmt = $conn->prepare("select * from proposal as p , participant as pa  where p.ID=pa.Proposal_ID  and pa.Staff_ID=? and Type=? and p.Rcd_level='Approved'  and p.date like '2022%' order by p.date DESC");
            $status = 'Not Approved';
            $Department = $_SESSION["Department"];
            $Type = $_REQUEST['v'];
            $stmt->bind_param("ss", $StaffID, $Type);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows) {
                $ToolTipModalId = 0;
                while ($data = $result->fetch_assoc()) {
                    $ToolTipModalId++;
                ?>
                   <tr>
                       <td><?php echo $data["ID"] ?></td>
                       <td><?php echo $data["Title"] ?></td>
                       <td><?php echo $data["Type"] ?></td>
                       <td><?php echo $data["date"] ?></td>
                       <td>
                           <a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a>
                       </td>
                       <td><?php echo $data["Agreement"]; ?></td>
                       <td><?php echo $data["Cost"]; ?></td>
                       <td>
                           <!-- Trigger the modal with a button -->
                           <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="<?php echo '#myModal' . $data['ID']; ?>">More
                           </button>

                           <!-- Modal -->
                           <div id="<?php echo 'myModal' . $data['ID']; ?>" class="modal fade" role="dialog">
                               <div class="modal-dialog">

                                   <!-- Modal content-->
                                   <div class="modal-content">
                                       <div class="modal-header mo">
                                           <button type="button" class="close" data-dismiss="modal"></button>
                                           <h4 class="modal-title">Select The Proposal tobe merged with</h4>
                                       </div>
                                       <div class="modal-body">
                                           <form action="Take_Agreement.php">
                                               <div class="form-group">
                                                   <div class="col-lg-12">
                                                       <label id="label">Dear <?php echo $data['ID'] ?> </label>
                                                       <input type="email" name="Staff ID" class="form-control" placeholder="">
                                                   </div>
                                               </div>
                                               <div class="form-group">
                                                   <div class="col-lg-12" multiple="">
                                                       <center>

                                                           THIS AGREEMENT is entered into and effective as of the ____ day
                                                           of
                                                           __________ 20__
                                                           ("Effective Date") by and between _______________________, a
                                                           _________corporation with
                                                           offices at ___________________________(“SPONSOR”); and The
                                                           University of Tennessee, a
                                                           public higher education institution and instrumentality of the
                                                           State
                                                           of Tennessee, on behalf of its
                                                           Health Science Center, with offices at 62 S. Dunlap, Suite 300,
                                                           Memphis, Tennessee 38163 ("the
                                                           UNIVERSITY").
                                                           RECITALS
                                                           WHEREAS, the research program contemplated by this Agreement is
                                                           of
                                                           mutual interest and benefit
                                                           to the parties and will further the instructional and research
                                                           objectives of the UNIVERSITY and the
                                                           research objectives of SPONSOR in a manner consistent with the
                                                           UNIVERSITY's status as an
                                                           educational corporate agency of the State of Tennessee.
                                                           NOW, THEREFORE, the parties agree as follows:
                                                           1. STATEMENT OF WORK AND PRINCIPAL INVESTIGATOR.
                                                           1.1 The UNIVERSITY agrees to use its reasonable efforts to
                                                           perform
                                                           the research project
                                                           ("Project") as set out in the Statement of Work titled,
                                                           ________________________________________, attached hereto and
                                                           incorporated herein by
                                                           reference as Appendix A.
                                                           1.2 The UNIVERSITY's obligations as stated in Article 1.1 above
                                                           shall be carried out under the
                                                           supervision of _____________________, Principal Investigator.
                                                           The
                                                           failure of _______________
                                                           for any reason to continue to serve as Principal Investigator
                                                           until
                                                           the normal conclusion of the Project
                                                           shall not be considered a breach of this Agreement and shall not
                                                           subject the UNIVERSITY to any
                                                           liability. In such case, the UNIVERSITY and SPONSOR shall
                                                           endeavor
                                                           to agree upon a successor,
                                                           and if they fail to agree, either of them may terminate this
                                                           Agreement without cause upon written
                                                           notice to the other party.
                                                           2. RESEARCH SUPPORT BY SPONSOR.
                                                           2.1 The total research support to be provided by SPONSOR is
                                                           $___________. Payments shall be
                                                           made to the UNIVERSITY by SPONSOR according to the following
                                                           schedule:
                                                           Insert schedule of payments (include non-refundable start-up
                                                           costs
                                                           if applicable, applicable
                                                           overhead, as much as possible up front, and not more than 10%
                                                           withheld as final payment)
                                                           NOTE: Budget should cover FULL cost of research
                                                           2
                                                           2.2 Checks shall be made payable to The University of Tennessee
                                                           and
                                                           shall be mailed to the
                                                           following address:
                                                           Anthony A. Ferrara, Vice Chancellor for Finance and Operations
                                                           UTHSC
                                                           62 S. Dunlap, Suite 300
                                                           Memphis, TN 38163
                                                           901 448-5523
                                                           spa@uthsc.edu
                                                           2.3 All funds provided by SPONSOR under this Agreement may be
                                                           used
                                                           at the discretion of the
                                                           UNIVERSITY.
                                                           3. TERM AND TERMINATION.
                                                           3.1 The term of this Agreement shall be from ___________________
                                                           (Effective Date) through
                                                           _____________________ , 20 ____, unless sooner terminated in
                                                           accordance with the provisions set
                                                           out herein. This Agreement may be extended, renewed, or
                                                           otherwise
                                                           amended at any time by the
                                                           mutual written consent of the parties.
                                                           3.2 In the event that either the UNIVERSITY or SPONSOR defaults
                                                           in
                                                           the due performance of its
                                                           obligations hereunder or in the event that any representation by
                                                           either of them proves to be false or
                                                           incorrect, and such default or breach is not cured within thirty
                                                           (30) days of written notice thereof,
                                                           then the party giving such notice may elect to terminate this
                                                           Agreement by final written notice to the
                                                           defaulting party. The parties recognize that the results of any
                                                           particular research project cannot be
                                                           guaranteed even through the use of the UNIVERSITY's reasonable
                                                           efforts; therefore, it is specifically
                                                           agreed that the failure of the UNIVERSITY to achieve specific
                                                           research results shall not constitute a
                                                           default or breach of this Agreement.
                                                           3.3 Either UNIVERSITY or SPONSOR may terminate this Agreement
                                                           without cause upon
                                                           written notification to the other at least thirty (30) days
                                                           prior to
                                                           the effective date of termination.
                                                           3.4 If the total funds paid by SPONSOR by the date of
                                                           termination
                                                           are insufficient to cover the
                                                           amounts earned in accordance with the budget and commitments
                                                           incurred by the UNIVERSITY in
                                                           the performance of the research, SPONSOR shall reimburse the
                                                           UNIVERSITY for same within thirty
                                                           (30) days of termination, provided that in no event shall
                                                           SPONSOR be
                                                           responsible for any amount in
                                                           excess of that stated in Article 2.1.
                                                           3.5 In the event of termination of this Agreement by the
                                                           UNIVERSITY
                                                           for default on the part of
                                                           SPONSOR, or termination of this Agreement by SPONSOR without
                                                           cause,
                                                           the Option granted under
                                                           Article 7 below shall thereupon terminate automatically.
                                                           3
                                                           4. EQUIPMENT.
                                                           4.1 Title to any equipment purchased, manufactured, or otherwise
                                                           acquired in the course of the
                                                           work under this Agreement shall vest in the UNIVERSITY,
                                                           notwithstanding any contribution
                                                           directly or indirectly from SPONSOR.
                                                           5. PUBLISHING.
                                                           5.1 UNIVERSITY reserves to itself and its employees the sole
                                                           right
                                                           to publish the results of the
                                                           Project in whole or in part as they deem appropriate. In order
                                                           that
                                                           premature public disclosure of
                                                           such information does not adversely affect the interests of the
                                                           parties, the UNIVERSITY shall
                                                           provide SPONSOR with a copy of each manuscript pertaining to the
                                                           Project that is intended for
                                                           publication. SPONSOR may request delay in publication for a
                                                           period
                                                           not to exceed ninety (90) days
                                                           from the date on which SPONSOR receives the manuscript. If
                                                           SPONSOR
                                                           does not make a written
                                                           request for delay in publication within thirty (30) days after
                                                           receipt of a manuscript, UNIVERSITY
                                                           shall be free to publish the manuscript at any time after the
                                                           end of
                                                           the thirty (30) days. SPONSOR’s
                                                           right to request a delay in publication shall not apply to any
                                                           thesis or dissertation.
                                                           6. CONFIDENTIALITY.
                                                           6.1 The UNIVERSITY and SPONSOR recognize that the conduct of a
                                                           research program may
                                                           require the transfer of proprietary information between the
                                                           parties.
                                                           Accordingly, it is agreed that the
                                                           acceptance by either of them of the other's proprietary
                                                           information
                                                           shall be subject to the following:
                                                           A. The term "Confidential Information" as used herein, in the
                                                           case
                                                           of documentary information,
                                                           shall include only that documentary information which is clearly
                                                           marked as proprietary (or
                                                           confidential) at the time when it is given to the receiving
                                                           party.
                                                           "Confidential Information" which is
                                                           originally orally disclosed shall include only that information
                                                           which is identified as being proprietary
                                                           or confidential at the time of disclosure and confirmed as
                                                           confidential by written communication sent
                                                           within a reasonably prompt period of time after it is disclosed
                                                           to
                                                           the receiving party.
                                                           B. The subject matter of the Confidential Information is to be
                                                           limited to that which is relative to
                                                           the research outlined in the Statement of Work under Article 1
                                                           above.
                                                           C. The receiving party will not publish or otherwise reveal to
                                                           any
                                                           third party the Confidential
                                                           Information (properly designated) of the disclosing party
                                                           without
                                                           the disclosing party's written
                                                           permission, unless the information:
                                                           (1) is already lawfully in the receiving party's possession at
                                                           the
                                                           time of receipt from the
                                                           disclosing party as evidenced by appropriate documentation;
                                                           (2) is or later becomes public through no fault of the receiving
                                                           party;
                                                           (3) is published by the UNIVERSITY and or its employee(s) in
                                                           accordance with the
                                                           provisions of Article 5 above;
                                                           4
                                                           (4) is lawfully received from a third party as evidenced by
                                                           appropriate documentation;
                                                           (5) has been in the possession of the receiving party for five
                                                           (5)
                                                           years or longer;
                                                           (6) is independently developed by the receiving party as
                                                           evidenced
                                                           by appropriate
                                                           documentation; or
                                                           (7) is required by law, including the Tennessee Public Records
                                                           Act,
                                                           T.C.A. 10-7-503 et
                                                           seq., to be disclosed.
                                                           7. INTELLECTUAL PROPERTY.
                                                           7.1 Pre-Existing Intellectual Property Rights of the Parties. No
                                                           party claims by virtue of this
                                                           Agreement any right, title, or interest in (a) any issued or
                                                           pending
                                                           patents or any copyrights owned or
                                                           controlled by another party or (b) any previous invention,
                                                           process,
                                                           or product of another party,
                                                           whether or not patented or patentable.
                                                           7.2 Definition. The term "Intellectual Property" shall mean all
                                                           inventions and developments
                                                           (whether or not patentable) and other creative works (excluding
                                                           theses, dissertations and scholarly
                                                           publications) developed in the course of the performance of the
                                                           work
                                                           under this Agreement,
                                                           including without limitation any patent, trademark, copyright,
                                                           mask
                                                           work right, or other property
                                                           right pertaining to same.
                                                           7.3 Allocation of rights.
                                                           A. Both UNIVERSITY and SPONSOR agree to promptly disclose to the
                                                           other all Intellectual
                                                           Property developed in the course of the work under this
                                                           Agreement.
                                                           B. The Intellectual Property developed solely by the UNIVERSITY
                                                           or
                                                           jointly by the
                                                           UNIVERSITY and SPONSOR in the performance of work under this
                                                           Agreement shall be
                                                           owned by the UNIVERSITY.
                                                           C. UNIVERSITY hereby grants to SPONSOR an exclusive option
                                                           ("Option") to acquire a
                                                           worldwide (to the extent possible) royalty-bearing license to
                                                           use
                                                           the Intellectual Property
                                                           developed in the course of the work under this Agreement in the
                                                           field of
                                                           __________________ (the “Optioned IP”).
                                                           D. The "Option Period" shall commence on the date of disclosure
                                                           to
                                                           SPONSOR of the Optioned
                                                           IP and shall terminate on the earlier of the following: (a) six
                                                           months from the date of
                                                           disclosure; or (b) termination of the Option pursuant to Article
                                                           3.5; or (c) proper exercise of
                                                           the Option by SPONSOR.
                                                           E. SPONSOR may exercise the Option during the Option Period by
                                                           giving written notice of
                                                           same to UNIVERSITY provided that SPONSOR is not then in default
                                                           or
                                                           breach of any of its
                                                           obligations under this Agreement.
                                                           F. Upon proper exercise of the Option by SPONSOR, UNIVERSITY and
                                                           SPONSOR will
                                                           negotiate in good faith in an effort to reach a
                                                           commercialization
                                                           agreement satisfactory to
                                                           5
                                                           both parties, the negotiation period not to exceed six (6)
                                                           months.
                                                           Upon the first to occur of
                                                           (a) termination of the Option by operation of the provisions of
                                                           Article 3.5 above; or (b)
                                                           expiration of the Option Period with the Option unexercised; or
                                                           (c)
                                                           expiration of the sixmonth negotiation period without the
                                                           execution
                                                           of a commercialization agreement,
                                                           UNIVERSITY shall have no further obligation to SPONSOR under
                                                           this
                                                           Agreement with
                                                           regard to the Optioned IP. In the absence of a further agreement
                                                           between UNIVERSITY and
                                                           SPONSOR, SPONSOR agrees that it will not use the Optioned IP for
                                                           any
                                                           commercial or noncommercial purpose.
                                                           G. During the Option Period, UNIVERSITY and SPONSOR will confer
                                                           concerning the proper
                                                           protection of the Optioned IP. Within thirty (30) days after
                                                           receipt
                                                           of an invoice from
                                                           UNIVERSITY, SPONSOR shall reimburse UNIVERSITY for all
                                                           out-of-pocket
                                                           expenses
                                                           incurred by UNIVERSITY during the Option Period in the filing,
                                                           prosecution, and
                                                           maintenance of United States and foreign patent applications,
                                                           issued
                                                           patents, and other forms
                                                           of intellectual property protection for the Optioned IP, all of
                                                           which shall be owned by
                                                           UNIVERSITY.
                                                           H. It is understood and agreed that any rights granted by or to
                                                           any
                                                           party by the terms of this
                                                           Agreement shall in all respects be subject to any rights claimed
                                                           or
                                                           restrictions and obligations
                                                           imposed by the United States government or any agency thereof,
                                                           whether such rights or
                                                           restrictions and obligations arise out of federal funding of the
                                                           underlying research or
                                                           otherwise.
                                                           8. BINDING AGREEMENT.
                                                           8.1 This Agreement shall be binding upon and inure to the
                                                           benefit of
                                                           each of the parties hereto,
                                                           their successors and assigns; provided, however, that this
                                                           Agreement
                                                           is not assignable or transferable,
                                                           in whole or in part, by any party without the prior written
                                                           consent
                                                           of the other parties.
                                                           Notwithstanding the foregoing, UNIVERSITY may assign its rights
                                                           and
                                                           interest to the University of
                                                           Tennessee Research Foundation (“UTRF”) or a successor in
                                                           interest to
                                                           UTRF or the UNIVERSITY
                                                           without the prior written consent of SPONSOR.
                                                           9. LIABILITY/INDEMNIFICATION.
                                                           9.1 The UNIVERSITY makes no warranties, either expressed or
                                                           implied,
                                                           as to the work to be
                                                           performed under this Agreement or the Optioned IP. THE
                                                           UNIVERSITY
                                                           SPECIFICALLY
                                                           DISCLAIMs ANY WARRANTIES OF MERCHANTABILITY OR FITNESS FOR A
                                                           PARTICULAR PURPOSE. The UNIVERSITY shall not be liable for any
                                                           direct, consequential, or
                                                           other damages suffered by SPONSOR or others resulting from the
                                                           work
                                                           performed under this
                                                           Agreement or the Optioned IP.
                                                           9.2 LIMITATION OF LIABILITY ON BEHALF OF THE UNIVERSITY. The
                                                           UNIVERSITY
                                                           is self-insured under the provisions of the Tennessee Claims
                                                           Commission Act (T.C.A. 9-8-301 et
                                                           seq.), and its liability to SPONSOR and to third parties for the
                                                           negligence of the UNIVERSITY and
                                                           its employees is subject to the tort provisions of that Act.
                                                           Accordingly, any liability of the
                                                           UNIVERSITY for any damages, losses, or costs arising out of or
                                                           related to acts performed by the
                                                           6
                                                           UNIVERSITY or its employees under this agreement is governed by
                                                           the
                                                           provisions of said Act.
                                                           Notwithstanding anything in this agreement to the contrary, any
                                                           provisions or provisions of this
                                                           agreement will not apply to the extent that it is (they are)
                                                           finally
                                                           determined to violate the laws or
                                                           Constitution of the State of Tennessee.
                                                           9.3 SPONSOR will indemnify UNIVERSITY and their respective
                                                           trustees,
                                                           directors, officers,
                                                           employees and agents and hold them harmless from every loss,
                                                           cost or
                                                           damage for judgments, awards
                                                           or the compromise of any claim arising out of the use of the
                                                           Optioned IP or the advertisement,
                                                           manufacture, use or sale of any product or process by SPONSOR,
                                                           its
                                                           sublicensees, dealers or
                                                           customers.
                                                           10. EXPORT C
                                                           <label id="label">The Proposal Id is . <?php ?> </label>
                                                           <div class="well well-lg text-danger">Remember if you click the
                                                               agree button it will be considered as you agree the terms
                                                               listed
                                                               above including the Buget
                                                           </div>
                                                   </div>
                                               </div>
                                               <button type="submit" name="Submit" class="btn btn-primary"><a href="Take_Agreement.php?Action=Agreed&&ProposalID=<?php echo $data['ID'] ?>">Agree</a>
                                               </button>
                                               <button type="submit" name="Submit" class="btn btn-primary"><a href="Take_Agreement.php?Action=Disagreed&&ProposalID=<?php echo $data['ID'] ?>">Disagree</a>
                                               </button>
                                           </form>
                                       </div>

                                   </div>

                               </div>
                           </div>
                       </td>
                       <td>
                           <!-- Trigger the modal with a button -->
                           <button type="button" class="btn btn-info btn-small" data-toggle="modal" data-target="<?php echo '#ToolTipModal' . $data['ID']; ?>">See
                           </button>

                           <!-- Modal -->
                           <div id="<?php echo 'ToolTipModal' . $data['ID']; ?>" class="modal fade" role="dialog">
                               <div class="modal-dialog">

                                   <!-- Modal content-->
                                   <div class="modal-content">
                                       <div class="modal-header mo">
                                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                               <span aria-hidden="true">&times;</span>
                                           </button>
                                       </div>
                                       <div class="modal-body">
                                           <table class="table table-sm table-bordered">
                                               <thead class="table-light">
                                                   <tr>
                                                       <th>
                                                           <center>First Name </center>
                                                       </th>
                                                       <th>
                                                           <center>Middle Name</center>
                                                       </th>
                                                       <th>
                                                           <center>Last Name</center>
                                                       </th>
                                                       <th>
                                                           <center>Status</center>
                                                       </th </tr>
                                               </thead>
                                               <tbody>
                                                   <?php
                                                    $stmt = $conn->prepare("select * from staffs as s, participant as pa where  pa.Proposal_ID =?  and  s.ID=pa.Staff_ID  ");
                                                    $Proposal_Id = $data['ID'];
                                                    $stmt->bind_param("s", $Proposal_Id);
                                                    $stmt->execute();
                                                    $output = $stmt->get_result();
                                                    if ($output->num_rows > 0) {
                                                        while ($row = $output->fetch_assoc()) {
                                                    ?>
                                                           <tr>
                                                               <td><?php echo $row["First_Name"] ?></td>
                                                               <td><?php echo $row["Middle_Name"] ?></td>
                                                               <td><?php echo $row["Last_Name"] ?></td>
                                                               <td><?php echo $row["Agreement"] ?></td>
                                                       <?php
                                                        }
                                                    }
                                                        ?>
                                               </tbody>
                                           </table>
                                       </div>
                                       <div class="modal-footer">
                                           <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                           </button>
                                       </div>

                                   </div>

                               </div>
                           </div>
                       </td>
                   </tr>
               <?php
                }
            } else {
                ?>
               <tr>
                   <td colspan="8">
                       <center>No Record </center>
                   </td>
               </tr>
               <?php
            }
        } else if (isset($_REQUEST['Budget'])) {
            $year = date('Y');
            $stmt = $conn->prepare("select DISTINCT * from  Proposal as p where  p.Rcc_level='Approved' and p.status!='On Progress' and p.status!='Completed'  and  p.Type=? and p.Faculty=? and p.Department=? and date  like '2022%' order by date ASC");
            $Type = $_REQUEST["v"];
            $year = $_REQUEST["Year"];
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
                       <td>
                           <center><?php echo $data["ID"] ?></center>
                       </td>
                       <td>
                           <center><?php echo $data["Title"] ?></center>
                       </td>
                       <td>
                           <center><a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a></center>
                       </td>
                       <td>
                           <center><?php echo $data["Rcc_level"] ?></center>
                       </td>

                       <td>
                           <center><?php echo $data["Cost"] ?></center>
                       </td>
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
        } else if (isset($_REQUEST['Modify_Proposal'])) {
            $year = date('Y');
            $stmt = $conn->prepare("select DISTINCT * from  Proposal as p where  p.Rcc_level='Approved' and p.Department_level='Approved'  and p.status!='Completed'  and  p.Type=? and p.Faculty=? and p.Department=? and date  like '" . $year . "%' order by date ASC");
            $Type = $_REQUEST["v"];
            $year = $_REQUEST["Year"];
            $Faculty = $_REQUEST["Faculty"];
            $Department = $_REQUEST["Department"];
            echo $Type . ' ' . $Faculty . ' ' . $Department;
            $stmt->bind_param("sss", $Type, $Faculty, $Department);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows) {
                $participants = array();
                while ($data = $result->fetch_assoc()) {
                    $ID = $data["ID"];
                    $stmt = $conn->prepare("SELECT * FROM participant where Proposal_ID=?");
                    $stmt->bind_param("i", $ID,);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $rows = array();
                    while ($r = $result->fetch_array()) {
                        $rows[] = $r;
                    }
                    $participants[] = $rows;
                ?>
                   <tr>
                       <td>
                           <center><?php echo $data["ID"] ?></center>
                       </td>
                       <td>
                           <center><?php echo $data["Title"] ?></center>
                       </td>
                       <td>
                           <center><?php echo $data["Term"] ?></center>
                       </td>
                       <td>
                           <center><a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a></center>
                       </td>
                       <td>
                           <center><?php echo $data["Type"] ?></center>
                       </td>

                       <td>
                           <center><?php echo $data["Cost"] ?></center>
                       </td>
                       <td>
                           <center><?php
                                    $proposal = $data["ID"];
                                    $stmt = $conn->prepare("SELECT s.ID,s.First_Name,s.Middle_Name,s.Last_Name, p.Proposal_ID,p.Role FROM participant as p INNER JOIN staffs as s ON s.ID = p.Staff_ID and p.Role='PI' and p.Proposal_ID=? ");
                                    $stmt->bind_param("i", $proposal,);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $d = $result->fetch_array();
                                    echo $d["First_Name"] . " " . $d["Middle_Name"] . " " . $d["Last_Name"];
                                    ?></center>
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
        } else if (isset($_REQUEST["select_year"])) {
            $type = $_REQUEST["data"];
            $year = (int)date("Y");
            for ($i = 2001; $i < $year; $i++) {
            ?>
               <option value="<?php echo $i . "," . $type; ?>"><?php echo $i ?></option>
               <?php
            }
        } elseif (isset($_REQUEST["selectDep"])) {
            $stmt = $conn->prepare("select  * from department where Faculty=?");
            $Faculty = $_REQUEST["Faculty"];

            $stmt->bind_param("s", $Faculty);
            $stmt->execute();
            $result = $stmt->get_result();
            $num = $result->num_rows;
            echo 'Faculty is ' . $Faculty . '  ' . $num;
            if ($num > 0) {

                while ($data = $result->fetch_assoc()) {
                ?>
                   <option value="">Select Department</option>
                   <option value="<?php echo  $data["Name"]; ?>"><?php echo $data["Name"]; ?></option>
               <?php
                }
            } else {
                ?>
               <option value="">No Department Found<?php echo $result->num_rows ?></option>
           <?php
            }
        } elseif (isset($_REQUEST["selectType"])) {
            $data = $_REQUEST["data"];
            ?>
           <option value="">Select Document Type</option>
           <option value="<?php echo $data . "Research" ?>">Research</option>
           <option value="<?php echo $data . "Technology Transfer" ?>">Technology Transfer</option>
           <option value="<?php echo $data . "Community Service" ?>">Community Service</option>
           <option value="<?php echo $data . "Other" ?>">Other</option>
           <?php
        } elseif (isset($_GET['Merge'])) {
            $Year = date('Y');
            $stmt = $conn->prepare("select * from proposal where Department_level='Approved'  and Status!='On Progress' and Status!='Merged' and Status!='Completed' and Department=?  and Type=? and date like '" . $Year . "%' order by date DESC");
            $Department = $_GET["Department"];
            $Type = $_GET['v'];
            $stmt->bind_param("ss", $Department, $Type);
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
                       <td><?php echo $data["Term"] . ' Terms' ?></td>
                       <td>
                           <a href="../Documents/<?php echo $data["Type"] ?>/Proposal/<?php echo $data["File"] ?>"><?php echo $data["File"] ?></a>
                       </td>
                       <td><?php echo $data["Merge_With"]; ?></td>
                       <td>
                           <!-- Trigger the modal with a button -->
                           <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="<?php echo '#myModal' . $data['ID']; ?>">Merge
                           </button>

                           <!-- Modal -->
                           <div id="<?php echo 'myModal' . $data['ID']; ?>" class="modal fade " role="dialog">
                               <div class="modal-dialog">

                                   <!-- Modal content-->
                                   <div class="modal-content">
                                       <form id="role-form" method="POST" action="Merge_Proposal.php">
                                           <div class="modal-header">
                                               <h4 class="modal-title">Select Proposal</h4>
                                               <button type="button" class="close" data-dismiss="modal">&times;</button>
                                           </div>
                                           <div class="modal-body">
                                               <div class="form-group">
                                                   <div class="col-lg-12">
                                                       <center>
                                                           <div class="col-lg-6">
                                                               <label class="control-label">Select Department</label>
                                                               <select class="form-control" id="Department_modal" required>
                                                                   <option value="">Select Department</option>
                                                                   <?php
                                                                    $ID = $data['ID'];
                                                                    $Faculty = $_SESSION['Faculty'];
                                                                    $stmt = $conn->prepare("select * from department where Faculty=?");
                                                                    $stmt->bind_param("s", $Faculty);
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
                                                           <div class="col-lg-6">
                                                               <label class="control-label">Select Type</label>
                                                               <select class="form-control" onchange="selectProposal_modal(this.value)" id="Type_modal" required>
                                                                   <option value="">Select Type</option>
                                                                   <option value="Research/<?php echo $ID; ?>">Research</option>
                                                                   <option value="Technology Transfer/<?php echo $ID; ?>">Technology Transfer</option>
                                                                   <option value="Community Service/<?php echo $ID; ?>">Community Service</option>
                                                                   <option value="Other/<?php echo $ID; ?>">Other</option>
                                                               </select>
                                                           </div>
                                                           <?php
                                                            $year = date("Y");
                                                            //                                                    $Department_level = 'Not Approved';
                                                            //                                                    $Department_level = 'Not Approved';

                                                            $stmt = $conn->prepare("select * from proposal where Department_level='Approved' and Rcc_level!='Rejected' and Status!='On Progress' and Department=? and Type=? and ID!=? and Status!='On Progress' and Status!='Completed' and Status!='Merged' and date like '" . $Year . "%' order by date DESC");

                                                            $stmt->bind_param("sss",  $Department, $Type, $ID);
                                                            $stmt->execute();
                                                            $merging_Proposals = $stmt->get_result();
                                                            ?>
                                                           <input> </input>
                                                           <label class="control-label">Select Proposals (<small>press Control For
                                                                   multiple selection </small>)</label>
                                                           <select multiple="multiple" id="proposals" size="50" style="height: 100px; overflow-y: scroll;" data-live-search="true" data-size="10" class="form-control" name="ary[]">
                                                               <?php
                                                                if ($merging_Proposals->num_rows) {
                                                                    while ($row = $merging_Proposals->fetch_assoc()) {
                                                                ?>
                                                                       <option value="<?php echo $row['ID'] . "/" . $data["ID"] . "/" . $Type . "/" . $Department ?>"><?php echo $row['Title']  ?>(<?php echo $row['ID']  ?>)</option>
                                                               <?php
                                                                    }
                                                                }
                                                                ?>
                                                           </select>
                                                       </center>
                                                   </div>
                                                   <div class="clearfix"></div>
                                               </div>
                                               <div class="modal-footer">
                                                   <button type="submit" name="Submit" class="btn btn-success">Merg</button>
                                                   <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                               </div>
                                       </form>

                                   </div>

                               </div>
                           </div>
                       </td>
                   </tr>
               <?php
                }
            } else {
                ?>
               <tr>
                   <td colspan="8">
                       <center>No Record</center>
                   </td>
               </tr>
               <?php
            }
        } elseif (isset($_GET['Merge_modal'])) {
            $Year = date('Y');
            $stmt = $conn->prepare("select * from proposal where Department_level='Approved'  and Status!='On Progress' and Status!='Merged' and Status!='Completed' and ID!=? and Department=?  and Type=? and date like '" . $Year . "%' order by date DESC");
            $Department = $_GET["Department"];

            $parameter = explode("/", $_GET['v']);
            $Type = $parameter[0];
            $Proposal_ID = $parameter[1];
            // echo 'the value is '. $parameter[0]."  and  ".$parameter[1].'done';
            $stmt->bind_param("iss", $Proposal_ID, $Department, $Type);
            if ($stmt->execute()) {
                $res_modal = $stmt->get_result();
                if ($res_modal->num_rows > 0) {
                    while ($data_modal = $res_modal->fetch_assoc()) {
                ?>
                       <option value="<?php echo $data_modal['ID'] . "/" . $Proposal_ID . "/" . $Type . "/" . $Department ?>"><?php echo $data_modal['Title']  ?>(<?php echo $data_modal['ID']  ?>)</option>

                   <?php
                    }
                } else {
                    ?>
                   <option value="">No Records Found</option>
               <?php
                }
            } else {
                ?>
               <option value="">No Records Found</option>
       <?php
            }
        }

        ?>
       <script>
           function merge() {
               // alert("hello");
           }
       </script>
   <?php
    } else {
        header("Location: http://localhost/system/page-login.php");
    } ?>