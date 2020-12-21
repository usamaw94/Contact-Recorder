<?php

//Main controller file to handle all the operations of data transaction between view and the database

require ("connection.php");

if(isset($_POST['methodCall'])) {
    $methodToCall = $_POST['methodCall'];
}
else {
    $methodToCall = '';
}



//Switch statement to perform required operation

switch ($methodToCall) {
    case "add":

        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $phoneNum = $_POST['phoneNum'];
        if(empty($_POST['emailAddress'])) {
            $emailAddress = '';
        } else {
            $emailAddress = $_POST['emailAddress'];
        }
        if (empty($_POST['postcode'])){
            $postCode = '';
        } else {
            $postCode = $_POST['postcode'];
        }

        add_contact($con,$firstName,$lastName,$phoneNum,$emailAddress,$postCode);
        break;

    case "update":

        $contactId = $_POST['contactId'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $phoneNum = $_POST['phoneNum'];
        if (empty($_POST['emailAddress'])){
            echo "Null";
            $emailAddress = '';
        } else {
            $emailAddress = $_POST['emailAddress'];
        }
        if (empty($_POST['postCode'])){
            echo "Null";
            $postCode = '';
        } else {
            $postCode = $_POST['postCode'];
        }

        update_contact($con, $contactId, $firstName,$lastName,$phoneNum,$emailAddress,$postCode);

        break;

    case "get":

        $id = $_POST['id'];
        get_contact($con, $id);

        break;

    case "delete":

        $contactId = $_POST['contactId'];

        delete_contact($con, $contactId);

        break;

    case "search":

        $text = $_POST['searchText'];

        search_contact($con, $text);

        break;

    case "sort":

        $text = $_POST['searchText'];
        $sort = $_POST['sort'];
        sort_contacts($con, $sort ,$text);

        break;

    default:

        echo "Error";

        break;

}



//Function to add new contact

function add_contact($con,$firstName,$lastName,$phoneNum,$emailAddress,$postCode){
    $sql = "INSERT INTO contacts (first_name, last_name, mobile, email, post_code)
    VALUES ('$firstName', '$lastName', '$phoneNum', '$emailAddress', '$postCode')";

    $execute_sql = mysqli_query($con, $sql);

    if(!$execute_sql){
        echo("Error description: " . mysqli_error($con));
    }

    echo $execute_sql;
}



//Function to update existing contact

function update_contact($con, $contactId ,$firstName,$lastName,$phoneNum,$emailAddress,$postCode){
    $sql = "UPDATE contacts 
    SET first_name = '$firstName', last_name = '$lastName', mobile = $phoneNum, email = '$emailAddress', post_code = '$postCode'
    WHERE contact_id = $contactId";

    $execute_sql = mysqli_query($con, $sql);

    echo json_encode($execute_sql);
}



//Function to get the details of a contact

function get_contact($con, $id){

    $query = "SELECT * FROM contacts WHERE contact_id = $id LIMIT 1";

    $result = mysqli_query($con, $query);

    if(!$result){
        echo("Error description: " . mysqli_error($con));
    }

    $row = mysqli_fetch_array($result);
    echo json_encode($row);
}



//Function to delete a contact

function delete_contact($con, $contactId){
    $sql = "DELETE FROM contacts WHERE contact_id = $contactId";

    $execute_sql = mysqli_query($con, $sql);

    echo json_encode($execute_sql);
}



//Function to do filter searching

function search_contact($con, $text){
    if(isset($text)){
        $search = $text;

        $query = "SELECT * FROM contacts WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR mobile LIKE '%$search%' OR email LIKE '%$search%' OR post_code LIKE '%$search%'";

    }

    else {

        $query = "SELECT * FROM contacts";

    }

    $result = mysqli_query($con, $query);

    if(!$result){
        echo("Error description: " . mysqli_error($con));
    }

    if ($result->num_rows > 0){
        $output = "<thead class='thead-light'>
                        <tr>
                            <th>
                            First Name
                                <div class='float-right'>
                                    <i id='sortFNameAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortFNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>
                            Last Name
                                <div class='float-right'>
                                    <i id='sortLNameAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortLNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>Mobile</th>
                            <th>
                            Email
                                <div class='float-right'>
                                    <i id='sortEmailAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortFNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>
                            Postcode
                                <div class='float-right'>
                                    <i id='sortCodeAsc' class='sort-button fa fa-sort-numeric-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortCodeDsc' class='sort-button fa fa-sort-numeric-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>";
        while ($row = $result->fetch_assoc()) {
            $output .="
                        <tr>
                            <td>".$row['first_name']."</td>
                                <td>".$row['last_name']."</td>
                                <td>".$row['mobile']."</td>
                                <td>".$row['email']."</td>
                                <td>".$row['post_code']."</td>
                                <td><button data-id=".$row['contact_id']." class='edit-contact btn btn-outline-primary btn-sm' data-toggle='modal' data-target='#editContact'>Edit</button> 
                                <button data-id=".$row['contact_id']." class='delete-contact btn btn-outline-danger btn-sm' data-toggle='modal' data-target='#deleteContact'>Delete</button></td>
                        </tr>";
        }
        $output .="</tbody>";

    } else {

        $output = "<h3>No record found</h3>";

    }

    echo json_encode($output);
}


//Function to perform sorting operations

function sort_contacts($con, $sort , $text) {


    switch ($sort) {
        case "firstNameAscending":

            sort_first_name_ascending($con, $text);

            break;
        case "firstNameDescending":

            sort_first_name_descending($con, $text);

            break;
        case "lastNameAscending":

            sort_last_name_ascending($con, $text);

            break;
        case "lastNameDescending":

            sort_last_name_descending($con, $text);

            break;
        case "emailAscending":

            sort_email_ascending($con, $text);

            break;
        case "emailDescending":

            sort_email_descending($con, $text);

            break;
        case "postcodeAscending":

            sort_postcode_ascending($con, $text);

            break;
        case "postcodeDescending":

            sort_postcode_descending($con, $text);

            break;
        default:

            echo "Error";

            break;

    }

}



//Function to sort first name alphabetically

function sort_first_name_ascending($con, $text){
    if(isset($text)){
        $search = $text;

        $query = "SELECT * FROM contacts WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR mobile LIKE '%$search%' OR email LIKE '%$search%' OR post_code LIKE '%$search%' ORDER BY first_name ASC";

    }

    else {

        $query = "SELECT * FROM contacts ORDER BY first_name ASC";

    }

    $result = mysqli_query($con, $query);

    if(!$result){
        echo("Error description: " . mysqli_error($con));
    }

    if ($result->num_rows > 0){
        $output = "<thead class='thead-light'>
                        <tr>
                            <th>
                            First Name
                                <div class='float-right'>
                                    <i id='sortFNameAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortFNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>
                            Last Name
                                <div class='float-right'>
                                    <i id='sortLNameAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortLNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>Mobile</th>
                            <th>
                            Email
                                <div class='float-right'>
                                    <i id='sortEmailAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortFNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>
                            Postcode
                                <div class='float-right'>
                                    <i id='sortCodeAsc' class='sort-button fa fa-sort-numeric-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortCodeDsc' class='sort-button fa fa-sort-numeric-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>";
        while ($row = $result->fetch_assoc()) {
            $output .="
                        <tr>
                            <td>".$row['first_name']."</td>
                                <td>".$row['last_name']."</td>
                                <td>".$row['mobile']."</td>
                                <td>".$row['email']."</td>
                                <td>".$row['post_code']."</td>
                                <td><button data-id=".$row['contact_id']." class='edit-contact btn btn-outline-primary btn-sm' data-toggle='modal' data-target='#editContact'>Edit</button> 
                                <button data-id=".$row['contact_id']." class='delete-contact btn btn-outline-danger btn-sm' data-toggle='modal' data-target='#deleteContact'>Delete</button></td>
                        </tr>
                        ";
        }
        $output .="</tbody>";

    } else {

        $output = "<h3>No record found</h3>";

    }

    echo json_encode($output);
}



//Function to sort first name in reverse alphabetical order

function sort_first_name_descending($con, $text){
    if(isset($text)){
        $search = $text;

        $query = "SELECT * FROM contacts WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR mobile LIKE '%$search%' OR email LIKE '%$search%' OR post_code LIKE '%$search%' ORDER BY first_name DESC";

    }

    else {

        $query = "SELECT * FROM contacts ORDER BY first_name DESC";

    }

    $result = mysqli_query($con, $query);

    if(!$result){
        echo("Error description: " . mysqli_error($con));
    }

    if ($result->num_rows > 0){
        $output = "<thead class='thead-light'>
                        <tr>
                            <th>
                            First Name
                                <div class='float-right'>
                                    <i id='sortFNameAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortFNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>
                            Last Name
                                <div class='float-right'>
                                    <i id='sortLNameAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortLNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>Mobile</th>
                            <th>
                            Email
                                <div class='float-right'>
                                    <i id='sortEmailAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortFNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>
                            Postcode
                                <div class='float-right'>
                                    <i id='sortCodeAsc' class='sort-button fa fa-sort-numeric-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortCodeDsc' class='sort-button fa fa-sort-numeric-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>";
        while ($row = $result->fetch_assoc()) {
            $output .="
                        <tr>
                            <td>".$row['first_name']."</td>
                                <td>".$row['last_name']."</td>
                                <td>".$row['mobile']."</td>
                                <td>".$row['email']."</td>
                                <td>".$row['post_code']."</td>
                                <td><button data-id=".$row['contact_id']." class='edit-contact btn btn-outline-primary btn-sm' data-toggle='modal' data-target='#editContact'>Edit</button> 
                                <button data-id=".$row['contact_id']." class='delete-contact btn btn-outline-danger btn-sm' data-toggle='modal' data-target='#deleteContact'>Delete</button></td>
                        </tr>
                        ";
        }
        $output .="</tbody>";

    } else {

        $output = "No record found";

    }

    echo json_encode($output);
}



//Function to sort last name alphabetically

function sort_last_name_ascending($con, $text){
    if(isset($text)){
        $search = $text;

        $query = "SELECT * FROM contacts WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR mobile LIKE '%$search%' OR email LIKE '%$search%' OR post_code LIKE '%$search%' ORDER BY last_name ASC";

    }

    else {

        $query = "SELECT * FROM contacts ORDER BY last_name ASC";

    }

    $result = mysqli_query($con, $query);

    if(!$result){
        echo("Error description: " . mysqli_error($con));
    }

    if ($result->num_rows > 0){
        $output = "<thead class='thead-light'>
                        <tr>
                            <th>
                            First Name
                                <div class='float-right'>
                                    <i id='sortFNameAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortFNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>
                            Last Name
                                <div class='float-right'>
                                    <i id='sortLNameAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortLNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>Mobile</th>
                            <th>
                            Email
                                <div class='float-right'>
                                    <i id='sortEmailAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortFNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>
                            Postcode
                                <div class='float-right'>
                                    <i id='sortCodeAsc' class='sort-button fa fa-sort-numeric-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortCodeDsc' class='sort-button fa fa-sort-numeric-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>";
        while ($row = $result->fetch_assoc()) {
            $output .="
                        <tr>
                            <td>".$row['first_name']."</td>
                                <td>".$row['last_name']."</td>
                                <td>".$row['mobile']."</td>
                                <td>".$row['email']."</td>
                                <td>".$row['post_code']."</td>
                                <td><button data-id=".$row['contact_id']." class='edit-contact btn btn-outline-primary btn-sm' data-toggle='modal' data-target='#editContact'>Edit</button> 
                                <button data-id=".$row['contact_id']." class='delete-contact btn btn-outline-danger btn-sm' data-toggle='modal' data-target='#deleteContact'>Delete</button></td>
                        </tr>
                        ";
        }
        $output .="</tbody>";

    } else {

        $output = "No record found";

    }

    echo json_encode($output);
}



//Function to sort last name in reverse alphabetical order

function sort_last_name_descending($con, $text){
    if(isset($text)){
        $search = $text;

        $query = "SELECT * FROM contacts WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR mobile LIKE '%$search%' OR email LIKE '%$search%' OR post_code LIKE '%$search%' ORDER BY last_name DESC";

    }

    else {

        $query = "SELECT * FROM contacts ORDER BY last_name DESC";

    }

    $result = mysqli_query($con, $query);

    if(!$result){
        echo("Error description: " . mysqli_error($con));
    }

    if ($result->num_rows > 0){
        $output = "<thead class='thead-light'>
                        <tr>
                            <th>
                            First Name
                                <div class='float-right'>
                                    <i id='sortFNameAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortFNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>
                            Last Name
                                <div class='float-right'>
                                    <i id='sortLNameAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortLNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>Mobile</th>
                            <th>
                            Email
                                <div class='float-right'>
                                    <i id='sortEmailAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortFNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>
                            Postcode
                                <div class='float-right'>
                                    <i id='sortCodeAsc' class='sort-button fa fa-sort-numeric-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortCodeDsc' class='sort-button fa fa-sort-numeric-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>";
        while ($row = $result->fetch_assoc()) {
            $output .="
                        <tr>
                            <td>".$row['first_name']."</td>
                                <td>".$row['last_name']."</td>
                                <td>".$row['mobile']."</td>
                                <td>".$row['email']."</td>
                                <td>".$row['post_code']."</td>
                                <td><button data-id=".$row['contact_id']." class='edit-contact btn btn-outline-primary btn-sm' data-toggle='modal' data-target='#editContact'>Edit</button> 
                                <button data-id=".$row['contact_id']." class='delete-contact btn btn-outline-danger btn-sm' data-toggle='modal' data-target='#deleteContact'>Delete</button></td>
                        </tr>
                        ";
        }
        $output .="</tbody>";

    } else {

        $output = "No record found";

    }

    echo json_encode($output);
}



//Function to sort email alphabetically

function sort_email_ascending($con, $text){
    if(isset($text)){
        $search = $text;

        $query = "SELECT * FROM contacts WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR mobile LIKE '%$search%' OR email LIKE '%$search%' OR post_code LIKE '%$search%' ORDER BY email ASC";

    }

    else {

        $query = "SELECT * FROM contacts ORDER BY email ASC";

    }

    $result = mysqli_query($con, $query);

    if(!$result){
        echo("Error description: " . mysqli_error($con));
    }

    if ($result->num_rows > 0){
        $output = "<thead class='thead-light'>
                        <tr>
                            <th>
                            First Name
                                <div class='float-right'>
                                    <i id='sortFNameAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortFNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>
                            Last Name
                                <div class='float-right'>
                                    <i id='sortLNameAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortLNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>Mobile</th>
                            <th>
                            Email
                                <div class='float-right'>
                                    <i id='sortEmailAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortFNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>
                            Postcode
                                <div class='float-right'>
                                    <i id='sortCodeAsc' class='sort-button fa fa-sort-numeric-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortCodeDsc' class='sort-button fa fa-sort-numeric-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>";
        while ($row = $result->fetch_assoc()) {
            $output .="
                        <tr>
                            <td>".$row['first_name']."</td>
                                <td>".$row['last_name']."</td>
                                <td>".$row['mobile']."</td>
                                <td>".$row['email']."</td>
                                <td>".$row['post_code']."</td>
                                <td><button data-id=".$row['contact_id']." class='edit-contact btn btn-outline-primary btn-sm' data-toggle='modal' data-target='#editContact'>Edit</button> 
                                <button data-id=".$row['contact_id']." class='delete-contact btn btn-outline-danger btn-sm' data-toggle='modal' data-target='#deleteContact'>Delete</button></td>
                        </tr>
                        ";
        }
        $output .="</tbody>";

    } else {

        $output = "No record found";

    }

    echo json_encode($output);
}



//Function to sort email in reverse alphabetical order

function sort_email_descending($con, $text){
    if(isset($text)){
        $search = $text;

        $query = "SELECT * FROM contacts WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR mobile LIKE '%$search%' OR email LIKE '%$search%' OR post_code LIKE '%$search%' ORDER BY email DESC";

    }

    else {

        $query = "SELECT * FROM contacts ORDER BY email DESC";

    }

    $result = mysqli_query($con, $query);

    if(!$result){
        echo("Error description: " . mysqli_error($con));
    }

    if ($result->num_rows > 0){
        $output = "<thead class='thead-light'>
                        <tr>
                            <th>
                            First Name
                                <div class='float-right'>
                                    <i id='sortFNameAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortFNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>
                            Last Name
                                <div class='float-right'>
                                    <i id='sortLNameAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortLNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>Mobile</th>
                            <th>
                            Email
                                <div class='float-right'>
                                    <i id='sortEmailAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortFNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>
                            Postcode
                                <div class='float-right'>
                                    <i id='sortCodeAsc' class='sort-button fa fa-sort-numeric-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortCodeDsc' class='sort-button fa fa-sort-numeric-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>";
        while ($row = $result->fetch_assoc()) {
            $output .="
                        <tr>
                            <td>".$row['first_name']."</td>
                                <td>".$row['last_name']."</td>
                                <td>".$row['mobile']."</td>
                                <td>".$row['email']."</td>
                                <td>".$row['post_code']."</td>
                                <td><button data-id=".$row['contact_id']."  class='edit-contact btn btn-outline-primary btn-sm' data-toggle='modal' data-target='#editContact'>Edit</button>
                                 <button data-id=".$row['contact_id']." class='delete-contact btn btn-outline-danger btn-sm' data-toggle='modal' data-target='#deleteContact'>Delete</button></td>
                        </tr>
                        ";
        }
        $output .="</tbody>";

    } else {

        $output = "No record found";

    }

    echo json_encode($output);
}



//Function to sort postcode in ascending order

function sort_postcode_ascending($con, $text){
    if(isset($text)){
        $search = $text;

        $query = "SELECT * FROM contacts WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR mobile LIKE '%$search%' OR email LIKE '%$search%' OR post_code LIKE '%$search%' ORDER BY post_code ASC";

    }

    else {

        $query = "SELECT * FROM contacts ORDER BY post_code ASC";

    }

    $result = mysqli_query($con, $query);

    if(!$result){
        echo("Error description: " . mysqli_error($con));
    }

    if ($result->num_rows > 0){
        $output = "<thead class='thead-light'>
                        <tr>
                            <th>
                            First Name
                                <div class='float-right'>
                                    <i id='sortFNameAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortFNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>
                            Last Name
                                <div class='float-right'>
                                    <i id='sortLNameAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortLNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>Mobile</th>
                            <th>
                            Email
                                <div class='float-right'>
                                    <i id='sortEmailAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortFNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>
                            Postcode
                                <div class='float-right'>
                                    <i id='sortCodeAsc' class='sort-button fa fa-sort-numeric-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortCodeDsc' class='sort-button fa fa-sort-numeric-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>";
        while ($row = $result->fetch_assoc()) {
            $output .="
                        <tr>
                            <td>".$row['first_name']."</td>
                                <td>".$row['last_name']."</td>
                                <td>".$row['mobile']."</td>
                                <td>".$row['email']."</td>
                                <td>".$row['post_code']."</td>
                                <td><button data-id=".$row['contact_id']." class='edit-contact btn btn-outline-primary btn-sm' data-toggle='modal' data-target='#editContact'>Edit</button> 
                                <button data-id=".$row['contact_id']." class='delete-contact btn btn-outline-danger btn-sm' data-toggle='modal' data-target='#deleteContact'>Delete</button></td>
                        </tr>
                        ";
        }
        $output .="</tbody>";

    } else {

        $output = "No record found";

    }

    echo json_encode($output);
}



//Function to sort postcode in descending order

function sort_postcode_descending($con, $text){
    if(isset($text)){
        $search = $text;

        $query = "SELECT * FROM contacts WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR mobile LIKE '%$search%' OR email LIKE '%$search%' OR post_code LIKE '%$search%' ORDER BY post_code DESC";

    }

    else {

        $query = "SELECT * FROM contacts ORDER BY post_code DESC";

    }

    $result = mysqli_query($con, $query);

    if(!$result){
        echo("Error description: " . mysqli_error($con));
    }

    if ($result->num_rows > 0){
        $output = "<thead class='thead-light'>
                        <tr>
                            <th>
                            First Name
                                <div class='float-right'>
                                    <i id='sortFNameAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortFNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>
                            Last Name
                                <div class='float-right'>
                                    <i id='sortLNameAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortLNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>Mobile</th>
                            <th>
                            Email
                                <div class='float-right'>
                                    <i id='sortEmailAsc' class='sort-button fa fa-sort-alpha-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortFNameDsc' class='sort-button fa fa-sort-alpha-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>
                            Postcode
                                <div class='float-right'>
                                    <i id='sortCodeAsc' class='sort-button fa fa-sort-numeric-down' title='Sort in ascending order'></i>
                                    &nbsp; <i id='sortCodeDsc' class='sort-button fa fa-sort-numeric-down-alt' title='Sort in descending order'></i>
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>";
        while ($row = $result->fetch_assoc()) {
            $output .="
                        <tr>
                            <td>".$row['first_name']."</td>
                                <td>".$row['last_name']."</td>
                                <td>".$row['mobile']."</td>
                                <td>".$row['email']."</td>
                                <td>".$row['post_code']."</td>
                                <td><button data-id=".$row['contact_id']." class='edit-contact btn btn-outline-primary btn-sm' data-toggle='modal' data-target='#editContact'>Edit</button> 
                                <button data-id=".$row['contact_id']." class='delete-contact btn btn-outline-danger btn-sm' data-toggle='modal' data-target='#deleteContact'>Delete</button></td>
                        </tr>
                        ";
        }
        $output .="</tbody>";

    } else {

        $output = "No record found";

    }

    echo json_encode($output);
}

?>
