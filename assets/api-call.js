jQuery(document).ready(function($) {
    $( function() {

        //Date Picker for Pick up Date
        $( "#datepicker" ).datepicker(
            {
                minDate : 0,
                dateFormat: 'dd/mm/yy'
            }
        );
      } );

    $(function() {

        // Initialize autocomplete for both location fields
        $(".location").autocomplete({
            minLength: 1, // Minimum number of characters before suggestions appear
            source: [] // Initial empty source
        });

    });
    
    // Listen to input events for both fields
    $('.location').on('input', function(e) {
        // Clear any previous timeout to avoid multiple calls
        clearTimeout($.data(this, 'timer'));
    
        // Get the current input field (location1 or location2)
        var currentField = $(this);
        var fieldId = currentField.attr('id'); // Get the id of the current input field
    
        // Get the values of location1 and location2
        var location1 = $('#location1').val();
        var location2 = $('#location2').val();
    
        // Set a new timeout
        const timer = setTimeout(() => {
            // Only send the request if either input is not empty
            if (location1 || location2) {
                $.ajax({
                    url: ajax_object.ajax_url,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'get_distance',
                        nonce: ajax_object.nonces.fetch_locations_nonce,
                        location1: location1,
                        location2: location2,
                    },
                    success: function(response) {
                        if (response.success) {
                            // Check which field is being updated and use the corresponding array from the response
                            let displayNames = [];
    
                            if (fieldId === 'location1') {
                                // Populate suggestions for location1
                                displayNames = response.data.location1.map(obj => obj.display_name);
                            } else if (fieldId === 'location2') {
                                // Populate suggestions for location2
                                displayNames = response.data.location2.map(obj => obj.display_name);
                            }
    
                            // Update the autocomplete source for the current input field
                            currentField.autocomplete("option", "source", displayNames);
                            currentField.autocomplete("search", currentField.val()); // Trigger the search with current input
                        } else {
                            console.log('Error: ' + response.data);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX Error: ' + error);
                    }
                });
            }
        }, 300); // 300 milliseconds = 0.3 seconds
    
        // Store the timer ID to clear it later
        $.data(this, 'timer', timer);
    });
    
    
    
    
    //Calculate Price Button Click

    $('#calculate-distance').on('click', function(e) {
        e.preventDefault();
        
        var location1 = $('#location1').val();
        var location2 = $('#location2').val();

        if(!location1) {
            $('.location1').text('Please enter a pickup location')
            $('.location1').css('display', 'inline-block');
            return;
        }

        if (!location2) {
            $('.location2').text('Please enter a destination')
            $('.location2').css('display', 'inline-block');
            return;
        }

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'get_distance_via_osrm',
                nonce: ajax_object.nonces.get_distance_nonce,
                location1: location1,
                location2: location2,
            },
            success: function(response) {
                if (response.success) {
                    //console.log(response.data);
                    $('.location1, .location2').text('');
                    $('.location1, .location2').hide();
                    const output = response.data.price;
                    $('#price-result').text('Total: ' + output);

                    //Handle Summary Total Here
                    $('.value.total').text(output);
                    $('.proceed-btn').css('display', 'inline-block');

                    console.log(response.data);
                    // Do something with the response data
                } else {
                    console.log('Error: ' + response.data);
                }
            },
            error: function(xhr, status, error) {

                console.log('AJAX Error: ' + error);
            }
        });
    });


    $(document).ready(function () {
        // Function to show error message
        function showError(field, message) {
            $(`.form-error.${field}`).text(message);
            $(`.form-error.${field}`).css('display', 'inline-block');
        }
    
        // Function to clear all error messages
        function clearErrors() {
            $('.form-error').text('').hide();
        }
    
        // Function to validate step 1
        function validateStep1() {
            let isValid = true;
            clearErrors();
    
            const location1 = $('#location1').val().trim();
            const location2 = $('#location2').val().trim();
            const datePicker = $('#datepicker').val().trim();
            const timePicker = $('#timepicker').val().trim();
    
            if (location1 === '') {
                showError('location1', 'Pick up location is required.');
                isValid = false;
            }
            if (location2 === '') {
                showError('location2', 'Destination is required.');
                isValid = false;
            }
            if (datePicker === '') {
                showError('date_picker', 'Pick up date is required.');
                isValid = false;
            }
            if (timePicker === '') {
                showError('time_picker', 'Pick up time is required.');
                isValid = false;
            }
    
            return isValid;
        }
    
        // Function to validate step 2
        function validateStep2() {
            let isValid = true;
            clearErrors();
    
            const fullName = $('#full_name').val().trim();
            const email = $('#email').val().trim();
            const phone = $('#phone').val().trim();
            const passengers = $('#passengers').val();
            const suitcases = $('#suitcases').val();
    
            if (fullName === '') {
                showError('full_name', 'Full name is required.');
                isValid = false;
            }
            if (email === '') {
                showError('email', 'Email is required.');
                isValid = false;
            } else if (!validateEmail(email)) {
                showError('email', 'Invalid email format.');
                isValid = false;
            }
            if (phone === '') {
                showError('phone', 'Phone number is required.');
                isValid = false;
            }
            if (passengers === null) {
                showError('passengers', 'Please select the number of passengers.');
                isValid = false;
            }
            if (suitcases === null) {
                showError('suitcases', 'Please select the number of suitcases.');
                isValid = false;
            }
    
            return isValid;
        }
    
        // Email validation function
        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    
        // Book Now Button Click
        $('#book-btn').on('click', function () {
            if (validateStep1()) {  // Only proceed if step 1 validation passes
                $('#step-1').hide();
                $('.data_fetched').hide();
                $('#step-2').show();
            }
        });
    
        // Confirm Button Click
        $('#confirm-btn').on('click', function () {
            if (validateStep2()) {  // Only proceed if step 2 validation passes
                // Grab Summary Values
                const dateValue = $('#datepicker').val();
                const timeValue = $('#timepicker').val();
                const pickupValue = $('#location1').val();
                const dropoffValue = $('#location2').val();
                const passengersValue = $('#passengers').val();
                const suitcasesValue = $('#suitcases').val();
    
                $('.value.date').text(dateValue);
                $('.value.time').text(timeValue);
                $('.value.pickup').text(pickupValue);
                $('.value.dropoff').text(dropoffValue);
                $('.value.passengers').text(passengersValue);
                $('.value.suitcases').text(suitcasesValue);
    
                $('#step-2').hide();
                $('#step-3').show();
            }
        });
    });
    
});


jQuery(document).ready(function ($) {
    $('#distance-form').on('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission

        // Create a FormData object with individual fields
        const formData = {
            full_name: $('#full_name').val(),
            email: $('#email').val(),
            phone: $('#phone').val(),
            location1: $('#location1').val(),
            location2: $('#location2').val(),
            date_picker: $('#datepicker').val(),
            time_picker: $('#timepicker').val(),
            passengers: $('#passengers').val(),
            suitcases: $('#suitcases').val()
        };

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'quotation_form_submit',
                nonce: ajax_object.nonces.form_submit_nonce,
                ...formData
            },
            beforeSend: function () {
                // Optional: Show a loading spinner or disable the submit button
            },
            success: function (response) {
                if (response.success) {
                    alert('Form submitted successfully!');
                    // Optionally, redirect to another page or show a success message
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('An unexpected error occurred. Please try again.');
            },
        });
    });
});



