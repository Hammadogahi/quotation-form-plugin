<?php

/**
 * Email Template for Booking Confirmation
 * Variables available:
 * - $full_name
 * - $email
 * - $phone
 * - $location1 (Pickup Location)
 * - $location2 (Destination)
 * - $date_picker
 * - $time_picker
 * - $passengers
 * - $suitcases
 * 
 * @package QuotationForm
 */

?>

<h3><?php _e('Booking Confirmation', 'quotation-form'); ?></h3>
<p><?php _e('Dear ', 'quotation-form') ?> <?php echo esc_html($full_name); ?>,</p>

<p><?php _e('Thank you for booking with us. Below are the details of your trip:', 'quotation-form'); ?></p>

<table style="border: 1px solid #ddd; border-collapse: collapse; width: 100%;">
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd;"><?php _e('Name:', 'quotation-form'); ?></td>
        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo esc_html($full_name); ?></td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd;"><?php _e('Email:', 'quotation-form'); ?></td>
        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo esc_html($email); ?></td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd;"><?php _e('Phone:', 'quotation-form'); ?></td>
        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo esc_html($phone); ?></td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd;"><?php _e('Pick-up Location:', 'quotation-form'); ?></td>
        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo esc_html($location1); ?></td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd;"><?php _e('Destination', 'quotation-form'); ?></td>
        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo esc_html($location2); ?></td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd;"><?php _e('Pick-up Date', 'quotation-form'); ?></td>
        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo esc_html($date_picker); ?></td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd;"><?php _e('Pick-up Time:', 'quotation-form'); ?></td>
        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo esc_html($time_picker); ?></td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd;"><?php _e('Passengers:', 'quotation-form'); ?></td>
        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo esc_html($passengers); ?></td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd;"><?php _e('Suitcases:', 'quotation-form'); ?></td>
        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo esc_html($suitcases); ?></td>
    </tr>
</table>

<p><?php _e('We look forward to serving you. Have a safe and pleasant journey!', 'quotation-form'); ?></p>

<p><?php _e('Best regards,', 'quotation-form'); ?><br><?php echo get_bloginfo('name'); ?></p>