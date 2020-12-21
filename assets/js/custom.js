$(document).ready(function() {

    // JS file to handle the live responses of the application

    $('#addContactForm').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();


        $.ajax({
            url: './controller/operations.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(data) {

                $('#addContactForm')[0].reset();
                $('#reload').load(document.URL + ' #reload');

                $('#addContact').modal('toggle');

                $('#notifyText').text('Contact added !!');

                $('#notify').delay(500).slideDown('medium').delay(2000)
                    .slideUp('medium');
            }
        });



    });


    $('#searchText').on('keyup', function() {
        var text = $(this).val();

        $.ajax({
            url: './controller/operations.php',
            method: 'POST',
            data: { searchText: text, methodCall: 'search' },
            dataType: 'json',
            success: function(response) {

                $('#displayResults').html(response);

            },
        });
    });


    $(document).on('click', '#sortFNameAsc', function() {
        var searchText = $('#searchText').val();

        $.ajax({
            url: './controller/operations.php',
            method: 'POST',
            data: { searchText: searchText, sort: 'firstNameAscending', methodCall: 'sort' },
            dataType: 'json',
            success: function(response) {
                $('#displayResults').html(response);
            },
        });
    });


    $(document).on('click', '#sortFNameDsc', function() {
        var searchText = $('#searchText').val();

        $.ajax({
            url: './controller/operations.php',
            method: 'POST',
            data: { searchText: searchText, sort: 'firstNameDescending', methodCall: 'sort' },
            dataType: 'json',
            success: function(response) {
                // $(response).find('#tableData').html(response);
                $('#displayResults').html(response);
            },
        });
    });


    $(document).on('click', '#sortLNameAsc', function() {
        var searchText = $('#searchText').val();

        $.ajax({
            url: './controller/operations.php',
            method: 'POST',
            data: { searchText: searchText, sort: 'lastNameAscending', methodCall: 'sort' },
            dataType: 'json',
            success: function(response) {
                // $(response).find('#tableData').html(response);
                $('#displayResults').html(response);
            },
        });
    });


    $(document).on('click', '#sortLNameDsc', function() {
        var searchText = $('#searchText').val();

        $.ajax({
            url: './controller/operations.php',
            method: 'POST',
            data: { searchText: searchText, sort: 'lastNameDescending', methodCall: 'sort' },
            dataType: 'json',
            success: function(response) {
                $('#displayResults').html(response);
            },
        });
    });


    $(document).on('click', '#sortEmailAsc', function() {
        var searchText = $('#searchText').val();

        $.ajax({
            url: './controller/operations.php',
            method: 'POST',
            data: { searchText: searchText, sort: 'emailAscending', methodCall: 'sort' },
            dataType: 'json',
            success: function(response) {
                $('#displayResults').html(response);
            },
        });
    });


    $(document).on('click', '#sortEmailDsc', function() {
        var searchText = $('#searchText').val();

        $.ajax({
            url: './controller/operations.php',
            method: 'POST',
            data: { searchText: searchText, sort: 'emailDescending', methodCall: 'sort' },
            dataType: 'json',
            success: function(response) {
                $('#displayResults').html(response);
            },
        });
    });


    $(document).on('click', '#sortCodeAsc', function() {
        var searchText = $('#searchText').val();

        $.ajax({
            url: './controller/operations.php',
            method: 'POST',
            data: { searchText: searchText, sort: 'postcodeAscending', methodCall: 'sort' },
            dataType: 'json',
            success: function(response) {
                $('#displayResults').html(response);
            },
        });
    });


    $(document).on('click', '#sortCodeDsc', function() {
        var searchText = $('#searchText').val();

        $.ajax({
            url: './controller/operations.php',
            method: 'POST',
            data: { searchText: searchText, sort: 'postcodeDescending', methodCall: 'sort' },
            dataType: 'json',
            success: function(response) {
                $('#displayResults').html(response);
            },
        });
    });


    $(document).on('click', '.edit-contact', function() {

        var id = $(this).attr('data-id');

        $.ajax({
            url: './controller/operations.php',
            method: 'POST',
            dataType: 'json',
            data: { id: id, methodCall: 'get' },
            success: function(data) {

                $('#edContactId').val(data.contact_id);
                $('#edFirstName').val(data.first_name);
                $('#edLastName').val(data.last_name);
                $('#edPhoneNum').val(data.mobile);
                $('#edEmailAddress').val(data.email);
                $('#edPostCode').val(data.post_code);

            },
        });

    });


    $('#editContactForm').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();


        $.ajax({
            url: './controller/operations.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            complete: function(data) {

                $('#editContactForm')[0].reset();
                $('#reload').load(document.URL + ' #reload');

                $('#searchText').val('');

                $('#editContact').modal('toggle');

                $('#notifyText').text('Details updated !!');

                $('#notify').delay(500).slideDown('medium').delay(2000)
                    .slideUp('medium');
            },
        });

    });


    $(document).on('click', '.delete-contact', function() {
        var id = $(this).attr('data-id');

        $('#deleteId').val(id);

    });


    $('#deleteBtn').on('click', function() {
        var delId = $('#deleteId').val();

        $.ajax({
            url: './controller/operations.php',
            method: 'POST',
            data: { contactId: delId, methodCall: 'delete' },
            dataType: 'json',
            success: function(data) {

                $('#reload').load(document.URL + ' #reload');

                $('#searchText').val('');

                $('#deleteContact').modal('toggle');

                $('#notifyText').text('Contact deleted !!');

                $('#notify').delay(500).slideDown('medium').delay(2000)
                    .slideUp('medium');
            },
        });

    });


});
