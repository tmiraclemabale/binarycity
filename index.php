<!DOCTYPE html>
<?php include "./db/binarycity_db.php"; ?>
<html>
    <head>
        <title>Client Management</title>
        <link rel="stylesheet" href="./css/style.css">
        <script>
            function confirmUnlink(){
                return confirm("Are you sure you want to unlink this client from selected contact?");
            }
        </script>
    </head>

    <body>
        <div class="container">
            <h1>Client Contacts Management System</h1>
            <h3>Binary City Assessment</h3>
            <h5><a href="mailto:tmiraclemabale@gmail.com" style="color: white;">By: Tinyiko Miracle Mabale</a></h5>
            <div class="tabs">
                <button onclick="showTab('clients')">Clients</button>
                <button onclick="showTab('contacts')">Contacts</button>
            </div>

            <div id="clients" class="tab">
                <h2>Clients</h2>
                <form action="./pages/client_create.php" method="POST" name="clientForm">
                    <div class="row col-lg-12">
                        <h4>Client Form</h4>
                        
                        <div class="input-group">
                            <label for="clientName">First Name</label>
                            <input id="clientName" name="first_name" placeholder="Client Name" required>
                        </div>
                        <div class="input-group">
                            <label for="clientSurname">Last Name</label>
                            <input id="clientSurname" name="last_name" placeholder="Client Surname" required>
                        </div>
                        <button type="submit">Save Client</button>
                    </div>
                </form>
                <h4>Client List</h4>
                <?php
                    if(isset($_GET['client_status']) && $_GET['client_status'] == "success"){
                        echo "<p style='color:green'>Client created successfully!</p>";
                    }else if(isset($_GET['client_status']) && $_GET['client_status'] == "error"){
                        echo "<p style='color:red'>Failed to create client. Please try again.</p>";
                    }
                ?>
                <input type="text" id="searchClient" onkeyup="searchClients()" placeholder="Search by First Name..">
                <table>
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Client Code</th>
                            <th style="text-align:center">Linked Contacts</th>
                        </tr>
                    </thead>
                    <!-- <tbody id="clientTable"> -->
                    <tbody id="clientTable">
                        <?php

                            // $sql = "SELECT c.id, c.client_code, c.first_name, c.last_name, COUNT(cc.contact_id) as contacts FROM clients c LEFT JOIN clients_contact cc ON c.id = cc.client_id GROUP BY c.id ORDER BY c.first_name ASC";
                            $sql = "SELECT c.id, c.first_name, c.last_name, c.client_code, ct.id AS contact_id, COUNT(ct.id) AS contact_count FROM clients c LEFT JOIN contacts ct ON ct.client_id = c.id GROUP BY c.id ORDER BY c.first_name ASC";

                            $res=$conn->query($sql);

                            $data=[];
                            
                            if($res->num_rows > 0){;
                                while($row=$res->fetch_assoc()){
                                    $client_id = $row['id'];
                                    $contact_id = $row['contact_id'];
                                    echo "<tr>
                                        <td style='text-align:left'>".$row['first_name']."</td>
                                        <td style='text-align:left'>".$row['last_name']."</td>
                                        <td style='text-align:left'>".$row['client_code']."</td>
                                        <td style='text-align:center'>".$row['contact_count']."</td>
                                    </tr>";
                                }
                            }else{
                                echo "<tr><td colspan='3' style='text-align:center'>No client(s) found</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>

            <div id="contacts" class="tab">
                <h2>Contacts</h2>
                <form action="./pages/contact_create.php" method="POST" name="contactForm">
                    <div class="row col-lg-12">
                        <h4>Contact Form</h4>
                        
                        <div class="input-group">
                            <div class="form-group">
                                <label for="clientSelect">Select Client:</label>
                                <select name="client_id" id="clientSelect" required>
                                    <option value="">--Choose Client--</option>
                                    <?php
                                        $client_sel = $conn->query("SELECT id, CONCAT(last_name,' ',first_name) AS full_name FROM clients ORDER BY last_name ASC");
                                        while($cl_row = $client_sel->fetch_assoc()){
                                            echo "<option value='{$cl_row['id']}'>{$cl_row['full_name']}</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="input-group">
                                <label for="contactEmail">Email Address</label>
                                <input id="contactEmail" name="email_address" placeholder="Contact Email" required>
                            </div>
                            <button type="submit">Link Client</button>
                        </div>
                    </div>
                </form>
                <h4>Contact List</h4>
                <?php
                    if(isset($_GET['contact_status']) && $_GET['contact_status'] == "success"){
                        echo "<p style='color:green'>Contact created successfully!</p>";
                    }else if(isset($_GET['contact_status']) && $_GET['contact_status'] == "error"){
                        echo "<p style='color:red'>Failed to create contact. Please try again.</p>";
                    }
                    if(isset($_GET['unlink_status']) && $_GET['unlink_status'] == "success"){
                        echo "<p style='color:green'>Client unlinked from contact successfully!</p>";
                    }else if(isset($_GET['unlink_status']) && $_GET['unlink_status'] == "error"){
                        echo "<p style='color:red'>Failed to unlink client from contact. Please try again.</p>";   
                    }
                    if(isset($_GET['no-client-selected']) && $_GET['no-client-selected'] == "true"){
                        echo "<p style='color:green'>No client selected for unlinking.</p>";
                    }
                ?>
                <input type="text" id="searchContact" onkeyup="searchContacts()" placeholder="Search by Full Name..">
                <table>
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Email Address</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="contactTable">
                        <?php
                            // Show existing links
                            $sql = "SELECT cl.id, ct.client_id, ct.first_name, ct.last_name, CONCAT(cl.last_name,' ',cl.first_name) AS full_name, ct.email_address FROM clients cl JOIN contacts ct ON cl.id = ct.client_id ORDER BY cl.last_name, ct.last_name";

                            $result = $conn->query($sql);
                            while($row = $result->fetch_assoc()){
                                $client_id = $row['id'];
                                $contact_id = $row['client_id'];
                                echo "<tr id='row_{$row['client_id']}_{$row['id']}'>
                                    <td style='text-align:left'>{$row['full_name']}</td>
                                    <td style='text-align:left'>{$row['email_address']}</td>
                                    <td style='text-align:center'>
                                        <form method='POST' action='./pages/contact_unlink.php' onsubmit='return confirmUnlink()'>
                                        <input type='hidden' name='client_id' value='{$client_id}'>
                                        <button type='submit' name='unlink_client_contact' class='unlink'>Unlink Client</button>
                                        </form>
                                    </td>
                                </tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script src="./js/script.js"></script>
    </body>
</html>