<?php

//Main view file for the application

include ("controller/connection.php");

$contactList = mysqli_query($con, "SELECT * FROM contacts");

?>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="assets/css/all.css">

    <!-- Custom CSS Styling -->
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">

    <title>Bussiness Contact Recorder</title>


</head>
<body>

<div id="notify" class="notify-panel bg-info">
    <center>
        <p>
            <span id="notifyText"></span>
        </p>
    </center>
</div>

<div class="jumbotron jumbotron-fluid custom-jumbotron">
    <div class="container">

        <h2 class="header-text">Business Contact Recorder</h2>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <button type="button" class="btn btn-info btn-lg btn-block btn-custom" data-toggle="modal" data-target="#addContact">Add new contact</button>
        </div>
        <div class=col-md-8>
            <input id="searchText" type="text" class="form-control form-control-lg" placeholder="Search contact">
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12">
            <div id="reload" class="table-responsive">
                <table class="table table-bordered" id="displayResults">
                    <thead class="thead-light">
                        <tr>
                            <th>
                                First Name
                                <div class="float-right">
                                    <i id="sortFNameAsc" class="sort-button fa fa-sort-alpha-down" title="Sort in ascending order"></i>
                                    &nbsp; <i id="sortFNameDsc" class="sort-button fa fa-sort-alpha-down-alt" title="Sort in descending order"></i>
                                </div>
                            </th>
                            <th>Last Name
                                <div class="float-right">
                                    <i id="sortLNameAsc" class="sort-button fa fa-sort-alpha-down" title="Sort in ascending order"></i>
                                    &nbsp; <i id="sortLNameDsc" class="sort-button fa fa-sort-alpha-down-alt" title="Sort in descending order"></i>
                                </div>
                            </th>
                            <th>Mobile</th>
                            <th>Email
                                <div class="float-right">
                                    <i id="sortEmailAsc" class="sort-button fa fa-sort-alpha-down" title="Sort in ascending order"></i> &nbsp;
                                    <i id="sortEmailDsc" class="sort-button fa fa-sort-alpha-down-alt" title="Sort in descending order"></i>
                                </div>
                            </th>
                            <th>Postcode
                                <div class="float-right">
                                    <i id="sortCodeAsc" class="sort-button fa fa-sort-numeric-down" title="Sort in ascending order"></i> &nbsp;
                                    <i id="sortCodeDsc" class="sort-button fa fa-sort-numeric-down-alt" title="Sort in descending order"></i>
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($contactList as $contact): ?>
                            <tr>
                                <td><?php echo $contact['first_name']; ?></td>
                                <td><?php echo $contact['last_name']; ?></td>
                                <td><?php echo $contact['mobile']; ?></td>
                                <td><?php echo $contact['email']; ?></td>
                                <td><?php echo $contact['post_code']; ?></td>
                                <td><button data-id="<?php echo $contact['contact_id']; ?>" class="edit-contact btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#editContact">Edit</button>
                                    <button data-id="<?php echo $contact['contact_id']; ?>" class="delete-contact btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#deleteContact">Delete</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="addContact" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="addContactForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add new contact</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="exampleFormControlInput1">Name</label>
                            </div>
                            <input type="hidden" name="methodCall" value="add" readonly>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input name="firstName" id="firstName" type="text" class="form-control" placeholder="First name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input name="lastName" id="lastName" type="text" class="form-control" placeholder="Last name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="phoneNum">Phone</label>
                                    <input name="phoneNum" id="phoneNum" type="tel" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="emailAddress">Email</label>
                                    <input name="emailAddress" id="emailAddress" type="email" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="postcode">Postcode</label>
                                    <input name="postcode" id="postcode" type="number" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-info" value="Save contact">
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editContact" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
<div class="modal-dialog" role="document">
    <form id="editContactForm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit contact</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <input type="hidden" id="edContactId" name="contactId" readonly>
                    <input type="hidden" name="methodCall" value="update" readonly>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="exampleFormControlInput1">Name</label>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" id="edFirstName" name="firstName" class="form-control" placeholder="First name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input name="lastName" id="edLastName" type="text" class="form-control" placeholder="Last name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Phone</label>
                                <input type="tel" name="phoneNum" id="edPhoneNum" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Email</label>
                                <input type="email" name="emailAddress" id="edEmailAddress" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Postcode</label>
                                <input type="number" name="postCode" id="edPostCode" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </form>
</div>
</div>

<div class="modal fade" id="deleteContact" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Delete contact</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h6>Are you sure, you want to delete this contact?</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" id="deleteId" readonly>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" id="deleteBtn" class="btn btn-danger">Yes</button>
        </div>
    </div>
</div>
</div>


<script src="assets/js/jquery.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<script src="assets/js/all.js"></script>

<script src="assets/js/custom.js"></script>

</body>
</html>
