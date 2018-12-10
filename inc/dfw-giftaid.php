<?php
/**
 * Simple function to add a UK GiftAid checkbox on
 * the checkout page if a donation item is present
 */

// Hook in
add_filter( 'woocommerce_checkout_fields' , 'add_giftaid_field', 20 );

// Our hooked in function - $fields is passed via the filter!
function add_giftaid_field( $fields ) {
  $fields['billing']['giftaid'] = array(
    'type'      => 'checkbox',
    'label'     => __('Giftaid it', 'woocommerce'),
    'placeholder'   => _x('Giftaid it', 'placeholder', 'woocommerce'),
    'required'  => false,
    'class'     => array('form-row-wide', 'wc-donation-giftaid'),
    'clear'     => true
  );

  return $fields;
}
