   <?php
    session_start();
    if (isset($_SESSION['StaffId']) && $_SESSION['StaffType'] === 'RCC' || $_SESSION['StaffType'] === 'RCD' || $_SESSION['StaffType'] === 'Researcher'|| $_SESSION['StaffType'] === 'Department'|| $_SESSION['StaffType'] === 'Faculty') {
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
            $reciveddata = explode('/', $_REQUEST["Department"]);
            $stmt = $conn->prepare("SELECT DISTINCT s.ID,s.First_Name,s.Middle_Name,s.Last_Name,p.ID,p.Title,p.File,pa.Role,pa.Agreement,pa.Staff_ID,p.Cost,p.RCC_Agreement_Status FROM participant as pa INNER JOIN proposal as p ON pa.Proposal_ID = p.ID INNER JOIN staffs as s  ON s.ID=pa.Staff_ID   and p.Type=? and p.Department=? and date like ? and p.Status='Waiting for Agreement Approval' ORDER BY pa.Role ASC");
            $year = Date("Y") . '%';
            $Department = $reciveddata[0];
            $Type = $reciveddata[1];
            // echo $Type . ' ' . $Faculty . ' ' . $Department;
            $stmt->bind_param("sss", $Type, $Department, $year);
            $stmt->execute();
            $result = $stmt->get_result();
            $participants = array();
            if ($result->num_rows) {

                while ($data = $result->fetch_assoc()) {
                    $participants[] = $data["First_Name"] . $data["Middle_Name"] . $data["Last_Name"] . '(' . $data["ID"] . ')';
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
                               <center><?php echo $data["RCC_Agreement_Status"] ?></center>
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
            $stmt = $conn->prepare("select DISTINCT * from Proposal as p where  p.Department_level='Approved' and p.Status!='Completed' and p.Status!='On Progress' and p.Status!='Completed' and  p.Type=? and p.Faculty=? and p.Department=? order by p.Rcc_level ASC");
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

            $year = Date("Y");
            // $stmt = $conn->prepare("select * from proposal where  Status!='On Progress' and Status!='Completed'  and Department_level='Approved' and Rcc_level='Approved' and  Department=?  and Type=? and date like '" . $year . "%' order by date DESC");
            $Type = $_REQUEST["v"];
            $Faculty = '';
            $Department = $_REQUEST["Department"];
            if ($_SESSION['StaffType'] === 'RCD') {
                $Faculty = $_REQUEST["Faculty"];
                $stmt = $conn->prepare("select * from proposal where  Status!='On Progress' and Status!='Completed'  and Department_level='Approved' and Rcc_level='Approved'  and  Department=?  and Faculty=? and Type=? and date like '" . $year . "%' order by date DESC");
                $stmt->bind_param("sss", $Department, $Faculty, $Type);
            } else if ($_SESSION['StaffType'] === 'RCC') {
                $Faculty = $_SESSION["Faculty"];
                $stmt = $conn->prepare("select * from proposal where  Status!='On Progress' and Status!='Completed'  and Department_level='Approved' and Rcc_level='Approved'  and  Department=? and Faculty=?  and Type=? and date like '" . $year . "%' order by date DESC");
                $stmt->bind_param("sss", $Department, $Faculty, $Type);
            } else if ($_SESSION['StaffType'] === 'Researcher') {
                $Faculty = $_SESSION["Faculty"];
                $StaffID = $_SESSION["StaffId"];
                $stmt = $conn->prepare("select * from proposal as p INNER JOIN participant as pa ON p.ID=pa.Proposal_ID and pa.Staff_ID=? and p.Status!='On Progress' and p.Status!='Completed' and p.Status!='Merged' and p.Department_level!='Approved' and p.Rcc_level!='Approved' and p.Rcd_level!='Approved' and p.Department=? and p.Faculty=? and p.Type=? and date like '" . $year . "%' order by date DESC");
                $stmt->bind_param("ssss", $StaffID, $Department, $Faculty, $Type);
            }

            


            $stmt->execute();
            $result = $stmt->get_result();
            // echo $Type . ' ' . $Faculty . ' ' . $Department.' '.$result->num_rows;
            $color = 0;
            $colorvalue = '';
            if ($result->num_rows) {
               

                while ($data = $result->fetch_assoc()) {
                    $color++;
                ?>
                   <div id="accordion<?php echo $data['ID'];  ?>" class="">
                       <div class="card">
                           <div class="card-header p-3 mb-2 " style="background-color: <?php $color % 2 === 0 ? $colorvalue = '#e1ebfc' : $colorvalue = '#ffeee8';
                                                                                        echo $colorvalue ?>;" id="heading<?php echo $data['ID']; ?>">
                               <h5 class="mb-0">
                                   <button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?php echo $data['ID']; ?>" aria-expanded="true" aria-controls="collapseOne">

                                       <?php echo $data["ID"] ?>-

                                       <?php echo $data["Title"] ?>
                                   </button>
                               </h5>
                           </div>

                           <div id="collapse<?php echo $data['ID']; ?>" class="collapse" aria-labelledby="heading<?php echo $data['ID']; ?>" data-parent="#accordion<?php echo $data['ID']; ?>">
                               <div class="card-body">

                                   <table class=" tabledit table table-sm table-bordered">
                                       <thead class="table-light">
                                           <tr>
                                               <th class="ID">
                                                   <center>#</center>
                                               </th>
                                               <th class="Allocated_Buget">
                                                   <center>Allocated Buget With detail</center>
                                               </th>
                                               <th class="numbers">
                                                   <center>No of days,Sites,Trips,no of investigators</center>
                                               </th>
                                               <th class="Total_Birr">
                                                   <center>Total Birr</center>
                                               </th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                           <?php
                                            $year = Date("Y");
                                            $proposalID = $data['ID'];
                                            if ($Type === "Community Service") {
                                                $stmt = $conn->prepare("SELECT * FROM proposal as p INNER JOIN community_service_budget as b ON b.Proposal_ID = p.ID and  p.ID=? and p.date LIKE '" . $year . "%'");
                                                $stmt->bind_param("i", $proposalID);
                                            } else {
                                                $stmt = $conn->prepare("SELECT * FROM proposal as p INNER JOIN budget as b ON b.Proposal_ID = p.ID and  p.ID=? and p.date LIKE '" . $year . "%'");
                                                $stmt->bind_param("i", $proposalID);
                                            }
                                            $stmt->execute();
                                            $Result1 = $stmt->get_result();
                                            $data1;
                                            $data2;
                                            if ($Result1->num_rows > 0) {
                                                $data1 = $Result1->fetch_assoc();
                                                if ($Type === "Community Service") {
                                                    $stmt = $conn->prepare("SELECT * FROM proposal as p INNER JOIN community_service_budget_details as b ON b.Proposal_ID = p.ID and  p.ID=? and p.date LIKE '" . $year . "%'");
                                                    $stmt->bind_param("i", $proposalID);
                                                } else {
                                                    $stmt = $conn->prepare("SELECT * FROM proposal as p INNER JOIN budget_detail as b ON b.Proposal_ID = p.ID and  p.ID=? and p.date LIKE '" . $year . "%'");
                                                    $stmt->bind_param("i", $proposalID);
                                                }
                                                $stmt->execute();
                                                $Result2 = $stmt->get_result();
                                                if ($Result2->num_rows > 0) {
                                                    $data2 = $Result2->fetch_assoc();
                                                    if ($Type === 'Research' || $Type === 'Technology Transfer') {
                                                                ?>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Investigators_for_experience_sharing'; ?></td>
                                                           <th>Investigators perdim for Expirianse Sharing</th>
                                                           <td><?php echo $data2["Investigators_for_experience_sharing"]; ?></td>
                                                           <td><?php echo $data1["Investigators_for_experience_sharing"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Investigators_perdim_for_Follow_up'; ?></td>
                                                           <th>Investigators perdim for Follow up</th>
                                                           <td><?php echo $data2["Investigators_perdim_for_Follow_up"]; ?></td>
                                                           <td><?php echo $data1["Investigators_perdim_for_Follow_up"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Data_collector_perdim'; ?></td>
                                                           <th scope="row">Data Collector Perdim</th>
                                                           <td><?php echo $data2["Data_collector_perdim"]; ?></td>
                                                           <td><?php echo $data1["Data_collector_perdim"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'System_analysis_design_implementation'; ?></td>
                                                           <th scope="row">System analysis, design <br />and implementation</th>
                                                           <td><?php echo $data2["System_analysis_design_implementation"]; ?></td>
                                                           <td><?php echo $data1["System_analysis_design_implementation"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Traineer_perdim'; ?></td>
                                                           <th scope="row">Traineer Perdim</th>
                                                           <td><?php echo $data2["Traineer_perdim"]; ?></td>
                                                           <td><?php echo $data1["Traineer_perdim"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'data_collector_perdim_for_training_pretest'; ?></td>
                                                           <th scope="row">Data Collector Perdim <br />for Tranning</th>
                                                           <td><?php echo $data2["data_collector_perdim_for_training_pretest"]; ?></td>
                                                           <td><?php echo $data1["data_collector_perdim_for_training_pretest"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Data_entry'; ?></td>
                                                           <th scope="row">Data Entry</th>
                                                           <td><?php echo $data2["Data_entry"]; ?></td>
                                                           <td><?php echo $data1["Data_entry"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Transport_for_expiriace_sharing'; ?></td>
                                                           <th scope="row">Transport cost for <br />expiriace sharing</th>
                                                           <td><?php echo $data2["Transport_for_expiriace_sharing"]; ?></td>
                                                           <td><?php echo $data1["Transport_for_expiriace_sharing"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Lab_technician_cost'; ?></td>
                                                           <th scope="row">Lab Technician Cost</th>
                                                           <td><?php echo $data2["Lab_technician_cost"]; ?></td>
                                                           <td><?php echo $data1["Lab_technician_cost"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'User_mannual'; ?></td>
                                                           <td>User Mannual</td>
                                                           <td><?php echo $data2["User_mannual"]; ?></td>
                                                           <td><?php echo $data1["User_mannual"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Professional_for_Testing_financial_standard'; ?></td>
                                                           <th scope="row">Professional cost for <br />Testing and financial standard</th>
                                                           <th><?php echo $data2["Professional_for_Testing_financial_standard"]; ?></th>
                                                           <td><?php echo $data1["Professional_for_Testing_financial_standard"]; ?></td>
                                                       </tr>
                                                       <?php

                                                        $sub_total = 0.00;
                                                        foreach ($data1 as $key => $value) {
                                                            if ($key === "Investigators_for_experience_sharing" || $key === "Investigators_perdim_for_Follow_up" || $key === "Data_collector_perdim" || $key === "System_analysis_design_implementation" || $key === "Traineer_perdim" || $key === "data_collector_perdim_for_training_pretest" || $key === "Data_entry" || $key === "Transport_for_expiriace_sharing" || $key === "Lab_technician_cost" || $key === "User_mannual" || $key === "Professional_for_Testing_financial_standard") {
                                                                $sub_total += (int)$value;
                                                            }
                                                        }
                                                        $contingency = $sub_total * 0.05;
                                                        $grand_Cost = $sub_total + $contingency;
                                                        ?>
                                                       <tr>
                                                           <td><?php echo $proposalID; ?></td>
                                                           <th scope="row"> <b>Sub-total</b></th>

                                                           <th colspan="2"><b><?php echo $sub_total; ?></b></th>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID; ?></td>
                                                           <th scope="row">Contingency Cost(5%)</th>
                                                           <th colspan="2"><?php echo $contingency; ?></th>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID; ?></td>
                                                           <th scope="row"><b>Grand Cost</b></th>
                                                           <th colspan="2"><b><?php echo $grand_Cost; ?></b></th>
                                                       </tr>
                                                      <?php
                                                    } else {
                                                    ?>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Duplication_and_Stationery' . '/' . $Type; ?></td>
                                                           <th>Duplication and Stationery (pen, paper, etc.)</th>
                                                           <td><?php echo $data2["Duplication_and_Stationery"]; ?></td>
                                                           <td><?php echo $data1["Duplication_and_Stationery"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Investigators_perdiem_for_supervision' . '/' . $Type; ?></td>
                                                           <th>Investigators per diem for supervision</th>
                                                           <td><?php echo $data2["Investigators_perdiem_for_supervision"]; ?></td>
                                                           <td><?php echo $data1["Investigators_perdiem_for_supervision"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Investigators_perdiem_for_training_and_pre_test' . '/' . $Type; ?></td>
                                                           <th scope="row">Investigators per diem for training and pre-test</th>
                                                           <td><?php echo $data2["Investigators_perdiem_for_training_and_pre_test"]; ?></td>
                                                           <td><?php echo $data1["Investigators_perdiem_for_training_and_pre_test"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Data_collectors_perdiem_for_training_and_pre_test' . '/' . $Type; ?></td>
                                                           <th scope="row">Data collectors per diem for training and pre test</th>
                                                           <td><?php echo $data2["Data_collectors_perdiem_for_training_and_pre_test"]; ?></td>
                                                           <td><?php echo $data1["Data_collectors_perdiem_for_training_and_pre_test"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Data_collectors_perdiem_for_data_collection' . '/' . $Type; ?></td>
                                                           <th scope="row">Data collectors per diem for data collection
                                                               <br />(Sample data collectors, surveyors, GPS, water quality, solid waste, <br />flow measurement, soil)
                                                           </th>
                                                           <td><?php echo $data2["Data_collectors_perdiem_for_data_collection"]; ?></td>
                                                           <td><?php echo $data1["Data_collectors_perdiem_for_data_collection"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'identification_of_eligible_study' . '/' . $Type; ?></td>
                                                           <th scope="row">Number of questionnaires to be collected per day for<br /> identification of eligible study population</th>
                                                           <td><?php echo $data2["identification_of_eligible_study"]; ?></td>
                                                           <td><?php echo $data1["identification_of_eligible_study"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'data_entry' . '/' . $Type; ?></td>
                                                           <th scope="row">Payment rate per questionnaire for data entry</th>
                                                           <td><?php echo $data2["data_entry"]; ?></td>
                                                           <td><?php echo $data1["data_entry"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Transport_cost' . '/' . $Type; ?></td>
                                                           <th scope="row">Transport cost</th>
                                                           <td><?php echo $data2["Transport_cost"]; ?></td>
                                                           <td><?php echo $data1["Transport_cost"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Transport_cost_for_purchasing' . '/' . $Type; ?></td>
                                                           <th scope="row">Transport cost for purchasing (if required)</th>
                                                           <td><?php echo $data2["Transport_cost_for_purchasing"]; ?></td>
                                                           <td><?php echo $data1["Transport_cost_for_purchasing"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Perdiem_for_purchasing' . '/' . $Type; ?></td>
                                                           <td>Per diem for purchasing (if required)</td>
                                                           <td><?php echo $data2["Perdiem_for_purchasing"]; ?></td>
                                                           <td><?php echo $data1["Perdiem_for_purchasing"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Perdiem_for_laboratory_work' . '/' . $Type; ?></td>
                                                           <th scope="row">Per diem for laboratory work (if required)</th>
                                                           <th><?php echo $data2["Perdiem_for_laboratory_work"]; ?></th>
                                                           <td><?php echo $data1["Perdiem_for_laboratory_work"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Materials_tobe_Purchased' . '/' . $Type; ?></td>
                                                           <th scope="row">Materials /Resources to be Purchased (Animals, seed, fertilizer, Lab chemicals,<br />
                                                               equipment, feed, soft wares, data etc.)</th>
                                                           <th><?php echo $data2["Materials_tobe_Purchased"]; ?></th>
                                                           <td><?php echo $data1["Materials_tobe_Purchased"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Software_development' . '/' . $Type; ?></td>
                                                           <th scope="row">Software development</th>
                                                           <th><?php echo $data2["Software_development"]; ?></th>
                                                           <td><?php echo $data1["Software_development"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Daily_labourer_payment' . '/' . $Type; ?></td>
                                                           <th scope="row">Daily labourer payment </th>
                                                           <th><?php echo $data2["Daily_labourer_payment"]; ?></th>
                                                           <td><?php echo $data1["Daily_labourer_payment"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Perdiem_for_laboratory_work' . '/' . $Type; ?></td>
                                                           <th scope="row">Land rent (if any)</th>
                                                           <th><?php echo $data2["Perdiem_for_laboratory_work"]; ?></th>
                                                           <td><?php echo $data1["Perdiem_for_laboratory_work"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Land_rent' . '/' . $Type; ?></td>
                                                           <th scope="row">Per diem for laboratory work (if required)</th>
                                                           <th><?php echo $data2["Land_rent"]; ?></th>
                                                           <td><?php echo $data1["Land_rent"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Laboratory_setup_cost' . '/' . $Type; ?></td>
                                                           <th scope="row">Laboratory setup cost (if applicable)</th>
                                                           <th><?php echo $data2["Laboratory_setup_cost"]; ?></th>
                                                           <td><?php echo $data1["Laboratory_setup_cost"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Laboratory_Technician_cost' . '/' . $Type; ?></td>
                                                           <th scope="row">Laboratory Technician cost (if applicable) </th>
                                                           <th><?php echo $data2["Laboratory_Technician_cost"]; ?></th>
                                                           <td><?php echo $data1["Laboratory_Technician_cost"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Focused_group_discussion' . '/' . $Type; ?></td>
                                                           <th scope="row">Focused group discussion (FGD)</th>
                                                           <th><?php echo $data2["Focused_group_discussion"]; ?></th>
                                                           <td><?php echo $data1["Focused_group_discussion"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Local_transport' . '/' . $Type; ?></td>
                                                           <th scope="row">Local transport</th>
                                                           <th><?php echo $data2["Local_transport"]; ?></th>
                                                           <td><?php echo $data1["Local_transport"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Guider_cost' . '/' . $Type; ?></td>
                                                           <th scope="row">Guider cost (if applicable) </th>
                                                           <th><?php echo $data2["Guider_cost"]; ?></th>
                                                           <td><?php echo $data1["Guider_cost"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Security_cost' . '/' . $Type; ?></td>
                                                           <th scope="row">Security cost (if applicable)</th>
                                                           <th><?php echo $data2["Security_cost"]; ?></th>
                                                           <td><?php echo $data1["Security_cost"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Boat_rent' . '/' . $Type; ?></td>
                                                           <th scope="row">Boat rent (for water sampling in a water body like in a lake)<br /> and traditional transport cost</th>
                                                           <th><?php echo $data2["Boat_rent"]; ?></th>
                                                           <td><?php echo $data1["Boat_rent"]; ?></td>
                                                       </tr>
                                                       <?php

                                                        $sub_total = 0.00;
                                                        foreach ($data1 as $key => $value) {
                                                            if ($key === "Duplication_and_Stationery" || $key === "Investigators_perdiem_for_supervision" || $key === "Investigators_perdiem_for_training_and_pre_test" || $key === "Data_collectors_perdiem_for_training_and_pre_test" || $key === "Data_collectors_perdiem_for_data_collection" || $key === "identification_of_eligible_study" || $key === "data_entry" || $key === "Transport_cost" || $key === "Transport_cost_for_purchasing" || $key === "Perdiem_for_purchasing" || $key === "Perdiem_for_laboratory_work" || $key === "Materials_tobe_Purchased" || $key === "Software_development" || $key === "Daily_labourer_payment" || $key === "Land_rent" || $key === "Laboratory_setup_cost" || $key === "Laboratory_Technician_cost" || $key === "Focused_group_discussion" || $key === "Local_transport" || $key === "Guider_cost" || $key === "Security_cost" || $key === "Boat_rent") {
                                                                $sub_total += (int)$value;
                                                            }
                                                        }
                                                        $contingency = $sub_total * 0.05;
                                                        $grand_Cost = $sub_total + $contingency;
                                                        ?>
                                                       <tr>
                                                           <td><?php echo $submitedProposal; ?></td>
                                                           <th scope="row"> <b>Sub-total</b></th>

                                                           <th colspan="2"><b><?php echo $sub_total; ?></b></th>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID; ?></td>
                                                           <th scope="row">Contingency Cost(5%)</th>
                                                           <th colspan="2"><?php echo $contingency; ?></th>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID; ?></td>
                                                           <th scope="row"><b>Grand Cost</b></th>
                                                           <th colspan="2"><b><?php echo $grand_Cost; ?></b></th>
                                                       </tr>
                                                   <?php
                                                    }
                                                } else {
                                                    ?>
                                                   <tr>
                                                       <td colspan="3">
                                                           <center>Enter and submit proposal information first</center>
                                                       </td>
                                                   </tr>
                                               <?php
                                                }
                                            } else {
                                                ?>
                                               <tr>
                                                   <td colspan="3">
                                                       <center>Enter and submit proposal information first</center>
                                                   </td>
                                               </tr>
                                           <?php
                                            }
                                            ?>
                                       </tbody>
                                   </table>
                               </div>
                           </div>
                       </div>
                   </div>
               <?php
                }
            } else {
                ?>
                         <div id="accordion" class="">
                       <div class="card">
                           <div class="card-header p-3 mb-2 " style="background-color: <?php $color % 2 === 0 ? $colorvalue = '#e1ebfc' : $colorvalue = '#ffeee8';
                                                                                        echo $colorvalue ?>;" id="heading">
                            <h1>No Records Found</h1>
                               </h5>
                           </div>
                       </div>
                   </div>
               <?php
            }
        }else if (isset($_REQUEST['Budget_Researcher'])) {

            $year = Date("Y");
            $year = Date("Y");
            // $stmt = $conn->prepare("select * from proposal where  Status!='On Progress' and Status!='Completed'  and Department_level='Approved' and Rcc_level='Approved' and  Department=?  and Type=? and date like '" . $year . "%' order by date DESC");
            $Type = $_REQUEST["v"];
            $Faculty = $_SESSION["Faculty"];
            $Department = $_REQUEST["Department"];
            $StaffID = $_SESSION["StaffId"];
            $stmt = $conn->prepare("select * from proposal as p INNER JOIN participant as pa ON p.ID=pa.Proposal_ID and pa.Staff_ID=? and p.Status!='On Progress' and p.Status!='Completed' and p.Status!='Merged' and p.Department_level!='Approved'  and p.Department=? and p.Faculty=? and p.Type=? and date like '" . $year . "%' order by date DESC");
            $stmt->bind_param("ssss", $StaffID, $Department, $Faculty, $Type);
            $stmt->execute();
            $result = $stmt->get_result();
            // echo 'num-rows are : '.$result->num_rows;
            if ($result->num_rows) {
                $color = 0;
                $colorvalue = '';
                while ($data = $result->fetch_assoc()) {
                    $color++;
                                    ?>
                   <div id="accordion<?php echo $data['ID'];  ?>" class="">
                       <div class="card">
                           <div class="card-header p-3 mb-2 " style="background-color: <?php $color % 2 === 0 ? $colorvalue = '#e1ebfc' : $colorvalue = '#ffeee8';
                                                                                        echo $colorvalue ?>;" id="heading<?php echo $data['ID']; ?>">
                               <h5 class="mb-0">
                                   <button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?php echo $data['ID']; ?>" aria-expanded="true" aria-controls="collapseOne">

                                       <?php echo $data["ID"] ?>-

                                       <?php echo $data["Title"]?>
                                       
                                   </button>
                               </h5>
                           </div>

                           <div id="collapse<?php echo $data['ID']; ?>" class="collapse" aria-labelledby="heading<?php echo $data['ID']; ?>" data-parent="#accordion<?php echo $data['ID']; ?>">
                               <div class="card-body">
                               <form action='Update_Budget_Researcher.php' method='post' enctype='multipart/form-data'>
                                                    <div class='row'>
                                                        <div class='col-lg-4'>
                                                            <label class='control-label'>Enter Title </label>
                                                            <input type='text' name='Title' class='form-control' value="<?php echo  $data["Title"];?> ">
                                                        </div>
                                                        <div class='col-lg-3'>
                                                            <label class='control-label'>Select Type</label>

                                                            <select class='form-control' name='Type' required>
                                                                <option value='0/<?php echo $data["ID"];?>'>Select Proposal Type</option>
                                                                <option value='Research/<?php echo $data["ID"];?>'<?php echo  ($data["Type"]==="Research")?' Selected':'';?> >Research</option>
                                                                <option value='Technology Transfer/<?php echo $data["ID"];?>' <?php echo  ($data["Type"]==="Technology Transfer")?' Selected':'';?> >Technology Transfer</option>
                                                                <option value='Community Service/<?php echo $data["ID"];?>'<?php echo  ($data["Type"]==="Community Service")?' Selected':'';?> >Community Service</option>
                                                                <option value='Thesis/<?php echo $data["ID"];?>'<?php echo  ($data["Type"]==="Thesis")?' Selected':'';?> >Thesis</option>
                                                                <option value='Project/<?php echo $data["ID"];?>'<?php echo  ($data["Type"]==="Project")?' Selected':'';?> >Project</option>
                                                                <option value='Other/<?php echo $data["ID"];?>'<?php echo  ($data["Type"]==="Other")?' Selected':'';?> >Other</option>
                                                            </select>
                                                        </div>

                                                        <div class='col-lg-2'>
                                                            <label class='control-label'>Select Terms</label>
                                                              <?php $selected='selected;'?>
                                                            <select class='form-control' name='Term' required>
                                                                <option value=''>Select Term</option>
                                                                <option value='1'<?php echo  ($data["Term"]===1)?' Selected':'';?>>One Term</option>
                                                                <option value='2' <?php echo  ($data["Term"]===2)?' Selected':'';?> >Two Term</option>
                                                                <option value='3' <?php echo  ($data["Term"]===3)?' Selected':'';?> >Three Term</option>
                                                            </select>
                                                        </div>

                                                        <div class='col-lg-3'>
                                                            <label class='control-label'>Upload The Proposal</label>
                                                            <div class='col-sm-10'>
                                                                <input type='file' name='File' class='form-control'>
                                                            </div>
                                                        </div>
                                                        <div class='col-lg-6'>
                                                            <label class='control-label'>Abstract</label>
                                                            <textarea class='form-control' name='Abstract' value="<?php echo  $data['Abstract']?> " rows='5'></textarea>

                                                        </div>
                                                        <div class='col-lg-2'>
                                                            <label class='control-label'>Select Year</label>
                                                            <select class='form-control' name='Year'>
                                                                <?php $FiscalYear=explode('-',$data["date"]);?>
                                                            <option value=''>Select Fiscal Year</option>
                                                                <?php 
                                                                $year=Date("Y")+1;
                                                                while($year>=2001){
                                                                    
                                                                    ?>
                                                                      <option value='<?php echo $year;?>' <?php echo  ($year==$FiscalYear[0])?' Selected':'';?> ><?php echo $year;?></option>
                                                                    <?php
                                                                    $year--;
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        
                                                        <?php
                                                        $proposalID = $data['ID'];
                                                        $stmtpart = $conn->prepare("select DISTINCT  pa.Proposal_ID,s.First_Name,s.Last_Name,pa.Staff_ID from  participant  as pa ,staffs as s,proposal as p where pa.Proposal_ID=p.ID and pa.Proposal_ID=? and s.ID=pa.Staff_ID and pa.Role!='PI' and pa.Staff_ID!=? and  p.Status!='On Progress' and p.Status!='Completed' and p.Status!='Merged' and p.Department_level!='Approved'  and p.Department=? and p.Faculty=?  and p.Type=? and date like '".$FiscalYear[0]."%' order by date DESC");
                                                        $stmtpart->bind_param("issss", $proposalID,$StaffID, $Department, $Faculty, $Type);
                                                        $stmtpart->execute();
                                                        $Resultpart = $stmtpart->get_result();?>
                                                        <?php
                                                        $AlreadyExistingParCount=0;
                                                        while($dataPar=$Resultpart->fetch_assoc()){
                                                            $AlreadyExistingParCount++;
                                                                ?>
                                                                <div class='col-lg-3' id=parent>
                                                                <label class='control-label'>(<Small>Enter The ID Who replace this Reseracher</Small>)</label>
                                                                  <input type='text'  name='par_<?php echo $AlreadyExistingParCount;?>' class='form-control' value="<?php echo  $dataPar["First_Name"].'-'.$dataPar["Staff_ID"].'';?> ">
                                                                </div>
                                                               <?php
                                                        }
                                                        ?>
                                                        <div class='col-lg-4' id=parent>
                                                            <label class='control-label'>Select Number of Participants </label>
                                                            <div class='col-sm-10'>
                                                                <select class='form-control' onchange='Participants(this.value)' name='participants'>
                                                                    <option value='<?php echo $AlreadyExistingParCount;?>/0'>Select Participants</option>
                                                                    <option value='<?php echo $AlreadyExistingParCount;?>/1'>1</option>
                                                                    <option value='<?php echo $AlreadyExistingParCount;?>/2'>2</option>
                                                                    <option value='<?php echo $AlreadyExistingParCount;?>/3'>3</option>
                                                                    <option value='<?php echo $AlreadyExistingParCount;?>/4'>4</option>
                                                                    <option value='<?php echo $AlreadyExistingParCount;?>/5'>5</option>
                                                                    <option value='<?php echo $AlreadyExistingParCount;?>/6'>6</option>
                                                                    <option value='<?php echo $AlreadyExistingParCount;?>/7'>7</option>
                                                                    <option value='<?php echo $AlreadyExistingParCount;?>/8'>8</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class='row col-lg-12' id='par'>

                                                        </div>
                                                        <div class='form-group'>
                                                            <div class='col-sm-offset-2 col-sm-10'>
                                                                <button type='submit' name='Submit' value='Submit' class='btn btn-default'>Save Change
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                   <table class=" tabledit table table-sm table-bordered">
                                       <thead class="table-light">
                                           <tr>
                                               <th class="ID">
                                                   <center>#</center>
                                               </th>
                                               <th class="Allocated_Buget">
                                                   <center>Allocated Buget With detail</center>
                                               </th>
                                               <th class="numbers">
                                                   <center>No of days,Sites,Trips,no of investigators</center>
                                               </th>
                                               <th class="Total_Birr">
                                                   <center>Total Birr</center>
                                               </th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                           <?php
                                            $year = Date("Y");
                                           
                                            if ($Type === "Community Service") {
                                                $stmt = $conn->prepare("SELECT * FROM proposal as p INNER JOIN community_service_budget as b ON b.Proposal_ID = p.ID and  p.ID=? and p.date LIKE '" . $year . "%'");
                                                $stmt->bind_param("i", $proposalID);
                                            } else {
                                                $stmt = $conn->prepare("SELECT * FROM proposal as p INNER JOIN budget as b ON b.Proposal_ID = p.ID and  p.ID=? and p.date LIKE '" . $year . "%'");
                                                $stmt->bind_param("i", $proposalID);
                                            }
                                            $stmt->execute();
                                            $Result1 = $stmt->get_result();
                                            $data1;
                                            $data2;
                                            if ($Result1->num_rows > 0) {
                                                $data1 = $Result1->fetch_assoc();
                                                if ($Type === "Community Service") {
                                                    $stmt = $conn->prepare("SELECT * FROM proposal as p INNER JOIN community_service_budget_details as b ON b.Proposal_ID = p.ID and  p.ID=? and p.date LIKE '" . $year . "%'");
                                                    $stmt->bind_param("i", $proposalID);
                                                } else {
                                                    $stmt = $conn->prepare("SELECT * FROM proposal as p INNER JOIN budget_detail as b ON b.Proposal_ID = p.ID and  p.ID=? and p.date LIKE '" . $year . "%'");
                                                    $stmt->bind_param("i", $proposalID);
                                                }
                                                $stmt->execute();
                                                $Result2 = $stmt->get_result();
                                                if ($Result2->num_rows > 0) {
                                                    $data2 = $Result2->fetch_assoc();
                                                    if ($Type === 'Research' || $Type === 'Technology Transfer') {
                                                                ?>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Investigators_for_experience_sharing'; ?></td>
                                                           <th>Investigators perdim for Expirianse Sharing</th>
                                                           <td><?php echo $data2["Investigators_for_experience_sharing"]; ?></td>
                                                           <td><?php echo $data1["Investigators_for_experience_sharing"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Investigators_perdim_for_Follow_up'; ?></td>
                                                           <th>Investigators perdim for Follow up</th>
                                                           <td><?php echo $data2["Investigators_perdim_for_Follow_up"]; ?></td>
                                                           <td><?php echo $data1["Investigators_perdim_for_Follow_up"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Data_collector_perdim'; ?></td>
                                                           <th scope="row">Data Collector Perdim</th>
                                                           <td><?php echo $data2["Data_collector_perdim"]; ?></td>
                                                           <td><?php echo $data1["Data_collector_perdim"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'System_analysis_design_implementation'; ?></td>
                                                           <th scope="row">System analysis, design <br />and implementation</th>
                                                           <td><?php echo $data2["System_analysis_design_implementation"]; ?></td>
                                                           <td><?php echo $data1["System_analysis_design_implementation"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Traineer_perdim'; ?></td>
                                                           <th scope="row">Traineer Perdim</th>
                                                           <td><?php echo $data2["Traineer_perdim"]; ?></td>
                                                           <td><?php echo $data1["Traineer_perdim"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'data_collector_perdim_for_training_pretest'; ?></td>
                                                           <th scope="row">Data Collector Perdim <br />for Tranning</th>
                                                           <td><?php echo $data2["data_collector_perdim_for_training_pretest"]; ?></td>
                                                           <td><?php echo $data1["data_collector_perdim_for_training_pretest"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Data_entry'; ?></td>
                                                           <th scope="row">Data Entry</th>
                                                           <td><?php echo $data2["Data_entry"]; ?></td>
                                                           <td><?php echo $data1["Data_entry"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Transport_for_expiriace_sharing'; ?></td>
                                                           <th scope="row">Transport cost for <br />expiriace sharing</th>
                                                           <td><?php echo $data2["Transport_for_expiriace_sharing"]; ?></td>
                                                           <td><?php echo $data1["Transport_for_expiriace_sharing"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Lab_technician_cost'; ?></td>
                                                           <th scope="row">Lab Technician Cost</th>
                                                           <td><?php echo $data2["Lab_technician_cost"]; ?></td>
                                                           <td><?php echo $data1["Lab_technician_cost"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'User_mannual'; ?></td>
                                                           <td>User Mannual</td>
                                                           <td><?php echo $data2["User_mannual"]; ?></td>
                                                           <td><?php echo $data1["User_mannual"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Professional_for_Testing_financial_standard'; ?></td>
                                                           <th scope="row">Professional cost for <br />Testing and financial standard</th>
                                                           <th><?php echo $data2["Professional_for_Testing_financial_standard"]; ?></th>
                                                           <td><?php echo $data1["Professional_for_Testing_financial_standard"]; ?></td>
                                                       </tr>
                                                       <?php

                                                        $sub_total = 0.00;
                                                        foreach ($data1 as $key => $value) {
                                                            if ($key === "Investigators_for_experience_sharing" || $key === "Investigators_perdim_for_Follow_up" || $key === "Data_collector_perdim" || $key === "System_analysis_design_implementation" || $key === "Traineer_perdim" || $key === "data_collector_perdim_for_training_pretest" || $key === "Data_entry" || $key === "Transport_for_expiriace_sharing" || $key === "Lab_technician_cost" || $key === "User_mannual" || $key === "Professional_for_Testing_financial_standard") {
                                                                $sub_total += (int)$value;
                                                            }
                                                        }
                                                        $contingency = $sub_total * 0.05;
                                                        $grand_Cost = $sub_total + $contingency;
                                                        ?>
                                                       <tr>
                                                           <td><?php echo $proposalID; ?></td>
                                                           <th scope="row"> <b>Sub-total</b></th>

                                                           <th colspan="2"><b><?php echo $sub_total; ?></b></th>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID; ?></td>
                                                           <th scope="row">Contingency Cost(5%)</th>
                                                           <th colspan="2"><?php echo $contingency; ?></th>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID; ?></td>
                                                           <th scope="row"><b>Grand Cost</b></th>
                                                           <th colspan="2"><b><?php echo $grand_Cost; ?></b></th>
                                                       </tr>
                                                      <?php
                                                    } else {
                                                    ?>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Duplication_and_Stationery' . '/' . $Type; ?></td>
                                                           <th>Duplication and Stationery (pen, paper, etc.)</th>
                                                           <td><?php echo $data2["Duplication_and_Stationery"]; ?></td>
                                                           <td><?php echo $data1["Duplication_and_Stationery"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Investigators_perdiem_for_supervision' . '/' . $Type; ?></td>
                                                           <th>Investigators per diem for supervision</th>
                                                           <td><?php echo $data2["Investigators_perdiem_for_supervision"]; ?></td>
                                                           <td><?php echo $data1["Investigators_perdiem_for_supervision"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Investigators_perdiem_for_training_and_pre_test' . '/' . $Type; ?></td>
                                                           <th scope="row">Investigators per diem for training and pre-test</th>
                                                           <td><?php echo $data2["Investigators_perdiem_for_training_and_pre_test"]; ?></td>
                                                           <td><?php echo $data1["Investigators_perdiem_for_training_and_pre_test"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Data_collectors_perdiem_for_training_and_pre_test' . '/' . $Type; ?></td>
                                                           <th scope="row">Data collectors per diem for training and pre test</th>
                                                           <td><?php echo $data2["Data_collectors_perdiem_for_training_and_pre_test"]; ?></td>
                                                           <td><?php echo $data1["Data_collectors_perdiem_for_training_and_pre_test"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Data_collectors_perdiem_for_data_collection' . '/' . $Type; ?></td>
                                                           <th scope="row">Data collectors per diem for data collection
                                                               <br />(Sample data collectors, surveyors, GPS, water quality, solid waste, <br />flow measurement, soil)
                                                           </th>
                                                           <td><?php echo $data2["Data_collectors_perdiem_for_data_collection"]; ?></td>
                                                           <td><?php echo $data1["Data_collectors_perdiem_for_data_collection"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'identification_of_eligible_study' . '/' . $Type; ?></td>
                                                           <th scope="row">Number of questionnaires to be collected per day for<br /> identification of eligible study population</th>
                                                           <td><?php echo $data2["identification_of_eligible_study"]; ?></td>
                                                           <td><?php echo $data1["identification_of_eligible_study"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'data_entry' . '/' . $Type; ?></td>
                                                           <th scope="row">Payment rate per questionnaire for data entry</th>
                                                           <td><?php echo $data2["data_entry"]; ?></td>
                                                           <td><?php echo $data1["data_entry"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Transport_cost' . '/' . $Type; ?></td>
                                                           <th scope="row">Transport cost</th>
                                                           <td><?php echo $data2["Transport_cost"]; ?></td>
                                                           <td><?php echo $data1["Transport_cost"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Transport_cost_for_purchasing' . '/' . $Type; ?></td>
                                                           <th scope="row">Transport cost for purchasing (if required)</th>
                                                           <td><?php echo $data2["Transport_cost_for_purchasing"]; ?></td>
                                                           <td><?php echo $data1["Transport_cost_for_purchasing"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Perdiem_for_purchasing' . '/' . $Type; ?></td>
                                                           <td>Per diem for purchasing (if required)</td>
                                                           <td><?php echo $data2["Perdiem_for_purchasing"]; ?></td>
                                                           <td><?php echo $data1["Perdiem_for_purchasing"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Perdiem_for_laboratory_work' . '/' . $Type; ?></td>
                                                           <th scope="row">Per diem for laboratory work (if required)</th>
                                                           <th><?php echo $data2["Perdiem_for_laboratory_work"]; ?></th>
                                                           <td><?php echo $data1["Perdiem_for_laboratory_work"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Materials_tobe_Purchased' . '/' . $Type; ?></td>
                                                           <th scope="row">Materials /Resources to be Purchased (Animals, seed, fertilizer, Lab chemicals,<br />
                                                               equipment, feed, soft wares, data etc.)</th>
                                                           <th><?php echo $data2["Materials_tobe_Purchased"]; ?></th>
                                                           <td><?php echo $data1["Materials_tobe_Purchased"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Software_development' . '/' . $Type; ?></td>
                                                           <th scope="row">Software development</th>
                                                           <th><?php echo $data2["Software_development"]; ?></th>
                                                           <td><?php echo $data1["Software_development"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Daily_labourer_payment' . '/' . $Type; ?></td>
                                                           <th scope="row">Daily labourer payment </th>
                                                           <th><?php echo $data2["Daily_labourer_payment"]; ?></th>
                                                           <td><?php echo $data1["Daily_labourer_payment"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Perdiem_for_laboratory_work' . '/' . $Type; ?></td>
                                                           <th scope="row">Land rent (if any)</th>
                                                           <th><?php echo $data2["Perdiem_for_laboratory_work"]; ?></th>
                                                           <td><?php echo $data1["Perdiem_for_laboratory_work"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Land_rent' . '/' . $Type; ?></td>
                                                           <th scope="row">Per diem for laboratory work (if required)</th>
                                                           <th><?php echo $data2["Land_rent"]; ?></th>
                                                           <td><?php echo $data1["Land_rent"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Laboratory_setup_cost' . '/' . $Type; ?></td>
                                                           <th scope="row">Laboratory setup cost (if applicable)</th>
                                                           <th><?php echo $data2["Laboratory_setup_cost"]; ?></th>
                                                           <td><?php echo $data1["Laboratory_setup_cost"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Laboratory_Technician_cost' . '/' . $Type; ?></td>
                                                           <th scope="row">Laboratory Technician cost (if applicable) </th>
                                                           <th><?php echo $data2["Laboratory_Technician_cost"]; ?></th>
                                                           <td><?php echo $data1["Laboratory_Technician_cost"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Focused_group_discussion' . '/' . $Type; ?></td>
                                                           <th scope="row">Focused group discussion (FGD)</th>
                                                           <th><?php echo $data2["Focused_group_discussion"]; ?></th>
                                                           <td><?php echo $data1["Focused_group_discussion"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Local_transport' . '/' . $Type; ?></td>
                                                           <th scope="row">Local transport</th>
                                                           <th><?php echo $data2["Local_transport"]; ?></th>
                                                           <td><?php echo $data1["Local_transport"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Guider_cost' . '/' . $Type; ?></td>
                                                           <th scope="row">Guider cost (if applicable) </th>
                                                           <th><?php echo $data2["Guider_cost"]; ?></th>
                                                           <td><?php echo $data1["Guider_cost"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Security_cost' . '/' . $Type; ?></td>
                                                           <th scope="row">Security cost (if applicable)</th>
                                                           <th><?php echo $data2["Security_cost"]; ?></th>
                                                           <td><?php echo $data1["Security_cost"]; ?></td>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID . '/' . 'Boat_rent' . '/' . $Type; ?></td>
                                                           <th scope="row">Boat rent (for water sampling in a water body like in a lake)<br /> and traditional transport cost</th>
                                                           <th><?php echo $data2["Boat_rent"]; ?></th>
                                                           <td><?php echo $data1["Boat_rent"]; ?></td>
                                                       </tr>
                                                       <?php

                                                        $sub_total = 0.00;
                                                        foreach ($data1 as $key => $value) {
                                                            if ($key === "Duplication_and_Stationery" || $key === "Investigators_perdiem_for_supervision" || $key === "Investigators_perdiem_for_training_and_pre_test" || $key === "Data_collectors_perdiem_for_training_and_pre_test" || $key === "Data_collectors_perdiem_for_data_collection" || $key === "identification_of_eligible_study" || $key === "data_entry" || $key === "Transport_cost" || $key === "Transport_cost_for_purchasing" || $key === "Perdiem_for_purchasing" || $key === "Perdiem_for_laboratory_work" || $key === "Materials_tobe_Purchased" || $key === "Software_development" || $key === "Daily_labourer_payment" || $key === "Land_rent" || $key === "Laboratory_setup_cost" || $key === "Laboratory_Technician_cost" || $key === "Focused_group_discussion" || $key === "Local_transport" || $key === "Guider_cost" || $key === "Security_cost" || $key === "Boat_rent") {
                                                                $sub_total += (int)$value;
                                                            }
                                                        }
                                                        $contingency = $sub_total * 0.05;
                                                        $grand_Cost = $sub_total + $contingency;
                                                        ?>
                                                       <tr>
                                                           <td><?php echo $submitedProposal; ?></td>
                                                           <th scope="row"> <b>Sub-total</b></th>

                                                           <th colspan="2"><b><?php echo $sub_total; ?></b></th>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID; ?></td>
                                                           <th scope="row">Contingency Cost(5%)</th>
                                                           <th colspan="2"><?php echo $contingency; ?></th>
                                                       </tr>
                                                       <tr>
                                                           <td><?php echo $proposalID; ?></td>
                                                           <th scope="row"><b>Grand Cost</b></th>
                                                           <th colspan="2"><b><?php echo $grand_Cost; ?></b></th>
                                                       </tr>
                                                   <?php
                                                    }
                                                } else {
                                                    ?>
                                                   <tr>
                                                       <td colspan="3">
                                                           <center>Enter and submit proposal information first</center>
                                                       </td>
                                                   </tr>
                                               <?php
                                                }
                                            } else {
                                                ?>
                                               <tr>
                                                   <td colspan="3">
                                                       <center>Enter and submit proposal information first</center>
                                                   </td>
                                               </tr>
                                           <?php
                                            }
                                            ?>
                                       </tbody>
                                   </table>
                               </div>
                           </div>
                       </div>
                   </div>
               <?php
                }
            } else {
                ?>
                <div id="accordion" class="">
              <div class="card">
                  <div class="card-header p-3 mb-2 " style="background-color: <?php $color % 2 === 0 ? $colorvalue = '#e1ebfc' : $colorvalue = '#ffeee8';
                                                                               echo $colorvalue ?>;" id="heading">
                   <h1>No Records Found</h1>
                      </h5>
                  </div>
              </div>
          </div>
              <?php
            }
        }else if (isset($_REQUEST['Modify_Proposal'])) {
            $year = $_REQUEST["Year"];
            $stmt = $conn->prepare("select DISTINCT * from  Proposal as p where  p.Rcc_level='Approved' and p.Department_level='Approved'  and p.status!='Completed'  and  p.Type=? and p.Faculty=? and p.Department=? and date  like '" . $year . "%' order by date ASC");
            $TypeData=explode("/",$_REQUEST["v"]);
            $Type = $_REQUEST["v"];
            
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
                       <center>No Record </center>
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
                                                            $stmt = $conn->prepare("select * from proposal where Department_level='Approved' and Rcc_level!='Rejected' and Status!='On Progress' and Department=? and Type=? and ID!=? and Status!='On Progress' and Status!='Completed' and Status!='Merged' and date like '" . $Year . "%' order by date DESC");

                                                            $stmt->bind_param("sss",  $Department, $Type, $ID);
                                                            $stmt->execute();
                                                            $merging_Proposals = $stmt->get_result();
                                                            ?>
                                                           
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
           
       </script>
   <?php
    } else {
        header("Location: http://localhost/system/page-login.php");
    } ?>