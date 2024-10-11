<?php

/**
 * Handles API calls for retrieving and updating data.
 * 
 * This file contains functions that make two distinct API calls:
 * 1. Fetch data from Nominatim Open Source API to retrieve locations from given address.
 * 2. Send data to OSRM Open Source API endpoint to retrieve the distance between two given addresses.
 * 
 * Each API call includes error handling to ensure that responses are properly managed.
 * 
 * @package QuotationForm
 */


/**
 * Retrieves location data from Nominatim Open Source API.
 * 
 * This Function takes an address as input, makes a GET request to the Nominatim API, 

 * 
 * @return null
 */

function get_distance_via_nominatim()
{
    // Verify the nonce
    if (! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'fetch_locations')) {
        wp_send_json_error('Nonce verification failed!');
        wp_die();
    }

    if (!isset($_POST['location1']) || !isset($_POST['location2'])) {
        wp_send_json_error('Invalid input');
    }

    $location1 = sanitize_text_field($_POST['location1']);
    $location2 = sanitize_text_field($_POST['location2']);
    //$location2 = sanitize_text_field($_POST['location2']);



    $url = 'https://nominatim.openstreetmap.org/search?q=' . urlencode($location1) . '&format=json&limit=8';

    $url2 = 'https://nominatim.openstreetmap.org/search?q=' . urlencode($location2) . '&format=json&limit=8';

    $response = wp_remote_get($url);
    $response2 = wp_remote_get($url2);

    $body = wp_remote_retrieve_body($response);
    $body2 = wp_remote_retrieve_body($response2);

    $data = json_decode($body);
    $data2 = json_decode($body2);

    $datas = array(
        'location1' => $data,
        'location2' => $data2
    );

    wp_send_json_success($datas);
}

add_action('wp_ajax_get_distance', 'get_distance_via_nominatim');
add_action('wp_ajax_nopriv_get_distance', 'get_distance_via_nominatim');




/**
 * Retrieves Distance data from OSRM Open Source API.
 * 
 * This function gets the coordinates via Nominatim API 
 * and then makes a GET request to the OSRM API to get the distance data.
 *
 * @return null
 */
function get_distance_via_osrm()
{
    // Verify the nonce
    if (! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'get_distance')) {
        wp_send_json_error('Nonce verification failed!');
        wp_die();
    }

    if (!isset($_POST['location1']) || !isset($_POST['location2'])) {
        wp_send_json_error('Invalid input');
    }

    // Sanitize input
    $location1 = sanitize_text_field($_POST['location1']);
    $location2 = sanitize_text_field($_POST['location2']);

    // Use Nominatim to get coordinates for both locations
    $nominatim_url1 = 'https://nominatim.openstreetmap.org/search?q=' . urlencode($location1) . '&format=json&limit=1';
    $nominatim_url2 = 'https://nominatim.openstreetmap.org/search?q=' . urlencode($location2) . '&format=json&limit=1';

    $response1 = wp_remote_get($nominatim_url1);
    $response2 = wp_remote_get($nominatim_url2);

    if (is_wp_error($response1) || is_wp_error($response2)) {
        wp_send_json_error('Error fetching location data.');
    }

    // Retrieve and decode the response
    $body1 = wp_remote_retrieve_body($response1);
    $body2 = wp_remote_retrieve_body($response2);

    $data1 = json_decode($body1, true);
    $data2 = json_decode($body2, true);

    if (empty($data1) || empty($data2)) {
        wp_send_json_error('Could not retrieve location data.');
    }

    // Get the latitude and longitude from the first result
    $lat1 = $data1[0]['lat'];
    $lon1 = $data1[0]['lon'];
    $lat2 = $data2[0]['lat'];
    $lon2 = $data2[0]['lon'];

    // Construct the OSRM API URL for distance calculation
    $osrm_url = 'http://router.project-osrm.org/route/v1/driving/' . $lon1 . ',' . $lat1 . ';' . $lon2 . ',' . $lat2 . '?overview=false';

    // Fetch the distance from OSRM
    $osrm_response = wp_remote_get($osrm_url);

    if (is_wp_error($osrm_response)) {
        wp_send_json_error('Error fetching distance data.');
    }

    // Decode OSRM response
    $osrm_body = wp_remote_retrieve_body($osrm_response);
    $osrm_data = json_decode($osrm_body, true);

    if (empty($osrm_data['routes'])) {
        wp_send_json_error('Could not calculate the distance.');
    }

    // Get the distance in meters
    $distance_in_meters = $osrm_data['routes'][0]['distance'];

    // Convert distance to Miles
    $distance_in_miles = $distance_in_meters  * 0.000621371192;

    // Return the distance
    wp_send_json_success([
        'price' => '£' . round(calculate_price_based_on_miles($distance_in_miles)),
        'distance' => $distance_in_miles
    ]);
}


add_action('wp_ajax_get_distance_via_osrm', 'get_distance_via_osrm');
add_action('wp_ajax_nopriv_get_distance_via_osrm', 'get_distance_via_osrm');



/**
 * 
 * Calulates distance in miles and returns the price based on the distance.
 * Feel free to modify the conditions according to your needs.
 * 
 * @param mixed  $distance_in_miles
 * 
 * @return int|Float

 */
function calculate_price_based_on_miles($distance_in_miles)
{
    // Check for different distance ranges and return the corresponding price
    if ($distance_in_miles <= 20) {
        return 60;
    } elseif ($distance_in_miles >= 21 && $distance_in_miles <= 29) {
        return 70;
    } elseif ($distance_in_miles >= 30 && $distance_in_miles <= 39) {
        return 90;
    } elseif ($distance_in_miles >= 40 && $distance_in_miles <= 50) {
        return 130;
    } elseif ($distance_in_miles >= 51 && $distance_in_miles <= 70) {
        return 160;
    } elseif ($distance_in_miles >= 71 && $distance_in_miles <= 99) {
        return 180;
    } elseif ($distance_in_miles >= 100 && $distance_in_miles <= 199) {
        // £2 per mile for distances over 100 miles up to 199 miles
        return $distance_in_miles * 2;
    } elseif ($distance_in_miles >= 200) {
        return $distance_in_miles * 1.5;
    }

    // Return 0 if distance is somehow invalid
    return 0;
}

/**
 * Handles the Form Submission
 * 
 * This function handles the form submission and sends email notification to user and admin.
 * 
 * @package QuotationForm
 * 
 */


function quotation_form_submit_callback()
{
    // Verify the nonce
    if (! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'form_submit')) {
        wp_send_json_error('Nonce verification failed!');
        wp_die();
    }

    // Check if required fields are present and valid
    if (
        empty($_POST['full_name']) ||
        empty($_POST['email']) ||
        empty($_POST['phone']) ||
        empty($_POST['location1']) ||
        empty($_POST['location2']) ||
        empty($_POST['date_picker']) ||
        empty($_POST['time_picker']) ||
        empty($_POST['passengers']) ||
        empty($_POST['suitcases'])
    ) {
        wp_send_json_error('Please fill in all required fields.');
        wp_die();
    }

    // Get form data from the AJAX request
    $full_name = sanitize_text_field($_POST['full_name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $location1 = sanitize_text_field($_POST['location1']);
    $location2 = sanitize_text_field($_POST['location2']);
    $date_picker = sanitize_text_field($_POST['date_picker']);
    $time_picker = sanitize_text_field($_POST['time_picker']);
    $passengers = sanitize_text_field($_POST['passengers']);
    $suitcases = sanitize_text_field($_POST['suitcases']);

    // Include the email template
    ob_start();
    include PLUGIN_FILE_PATH . 'includes/email-template.php';
    $email_content = ob_get_clean();

    // Set email subject and headers
    $subject = 'Booking Confirmation';
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: Your Website <no-reply@yourwebsite.com>'  // Replace with your site email
    );

    // Send the email
    $email_sent = wp_mail($email, $subject, $email_content, $headers);

    // Check if email was sent successfully and respond accordingly
    if ($email_sent) {
        wp_send_json_success('Booking submitted successfully.');
    } else {
        wp_send_json_error('There was an error sending the booking confirmation email.');
    }

    wp_die();
}

add_action('wp_ajax_quotation_form_submit', 'quotation_form_submit_callback');
add_action('wp_ajax_nopriv_quotation_form_submit', 'quotation_form_submit_callback');
