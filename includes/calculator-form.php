<?php

/**
 * Handles the form markup rendered in the browser
 * 
 * @package QuotationForm
 */

defined('ABSPATH') || exit;

?>

<form id="distance-form" method="POST">
    <div id="step-1">
        <div class="form-group">
            <label for="location1">
                <?php _e('Pick up', 'quotation-form'); ?>
            </label>
            <div class="form-field">
                <input type="text" id="location1" class="location" name="location1" placeholder="<?php esc_attr_e('Enter Pick up location', 'quotation-form'); ?>" required>
                <span class="form-error location1"></span>
            </div>
        </div>
        <div class="form-group">
            <label for="location2">
                <?php _e('Destination',  'quotation-form'); ?>
            </label>
            <div class="form-field">
                <input type="text" id="location2" class="location" name="location2" placeholder="<?php esc_attr_e('Enter Destination', 'quotation-form'); ?>" required>
                <span class="form-error location2"></span>
            </div>
        </div>
        <div class="two-cols">
            <div class="form-group">
                <label for="date">
                    <?php _e('Pick up date', 'quotation-form'); ?>
                </label>
                <div class="form-field">
                    <input type="text" id="datepicker" name="date_picker" placeholder="<?php esc_attr_e('Select Date', 'quotation-form'); ?>" required>
                    <span class="form-error date_picker"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="date">
                    <?php _e('Pick up time', 'quotation-form'); ?>
                </label>
                <div class="form-field">
                    <input type="time" id="timepicker" name="time_picker" placeholder="<?php esc_attr_e('Pickup Time', 'quotation-form'); ?>" required>
                    <span class="form-error time_picker"></span>
                </div>
            </div>
        </div>

        <div class="submit">
            <button id="calculate-distance"><?php _e('Calculate Price', 'quotation-form'); ?></button>
        </div>
    </div>

    <div id="step-2">
        <div class="form-details-step-2">
            <div class="form-group">
                <label for="full_name">
                    <?php _e('Name', 'quotation-form'); ?>
                </label>
                <div class="form-field">
                    <input type="text" id="full_name" name="full_name" placeholder="<?php esc_attr_e('Your Full Name', 'quotation-form'); ?>" required>
                    <span class="form-error full_name"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="email">
                    <?php _e('Email', 'quotation-form'); ?>
                </label>
                <div class="form-field">
                    <input type="email" id="email" name="email" placeholder="<?php esc_attr_e('Your Email Address', 'quotation-form') ?>" required>
                    <span class="form-error email"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="phone">
                    <?php _e('Phone', 'quotation-form'); ?>
                </label>
                <div class="form-field">
                    <input type="tel" id="phone" name="phone" placeholder="<?php esc_attr_e('Your Phone Number', 'quotation-form'); ?>">
                    <span class="form-error phone"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="passengers">
                    <?php _e('Passengers', 'quotation-form'); ?>
                </label>
                <div class="form-field">
                    <select name="passengers" id="passengers">
                        <option disabled selected><?php _e('Total Passengers', 'quotation-form'); ?></option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                    <span class="form-error passengers"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="suitcases">
                    <?php _e('Suitcases', 'quotation-form'); ?>
                </label>
                <div class="form-field">
                    <select name="suitcases" id="suitcases">
                        <option disabled selected><?php _e('Total Suitcases', 'quotation-form'); ?></option>
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                    <span class="form-error suitcases"></span>
                </div>
            </div>
        </div>
        <div class="proceed">
            <a href="#" class="button" id="confirm-btn"><?php _e('Confirm', 'quotation-form'); ?></a>
        </div>
    </div>

    <div id="step-3">
        <div class="summary">
            <h3><?php _e('Summary', 'quotation-form'); ?></h3>
            <div class="summary-row">
                <div class="label"><?php _e('Date:', 'quotation-form'); ?></div>
                <div class="value date"></div>
            </div>
            <div class="summary-row">
                <div class="label"><?php _e('Time:', 'quotation-form'); ?></div>
                <div class="value time"></div>
            </div>
            <div class="summary-row">
                <div class="label"><?php _e('Pickup from:', 'quotation-form'); ?></div>
                <div class="value pickup"></div>
            </div>
            <div class="summary-row">
                <div class="label"><?php _e('Dropoff to:', 'quotation-form'); ?></div>
                <div class="value dropoff"></div>
            </div>
            <div class="summary-row">
                <div class="label"><?php _e('Passengers:', 'quotation-form'); ?></div>
                <div class="value passengers"></div>
            </div>
            <div class="summary-row">
                <div class="label"><?php _e('Suitcases:', 'quotation-form'); ?></div>
                <div class="value suitcases"></div>
            </div>
            <div class="summary-row">
                <div class="label"><?php _e('Total:', 'quotation-form'); ?></div>
                <div class="value total"></div>
            </div>

        </div>
        <div class="form-submit">
            <input type="submit" class="button">
        </div>
    </div>
</form>

<div class="data_fetched">
    <div id="price-result"></div>
    <div class="btn-wrapper"><a href="#" class="button proceed-btn" id="book-btn"><?php _e('Book Now', 'quotation-form'); ?></a></div>
</div>

<?php
