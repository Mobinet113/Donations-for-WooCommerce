<?php
/**
 * Simple function to add a UK GiftAid checkbox on
 * the checkout page if a donation item is present
 */

// Hook in
add_filter( 'woocommerce_checkout_fields' , 'add_giftaid_field', 100 );

// Our hooked in function - $fields is passed via the filter!
function add_giftaid_field( $fields ) {

  // If the UK Giftaid setting is enabled, create a new checkbox in the checkout
  if ( hm_wcdon_get_option('enable_uk_giftaid_field') ) {
    $fields['billing']['giftaid'] = array(
      'type' => 'checkbox',
      'label' => __('Giftaid it', 'woocommerce'),
      'placeholder' => _x('Giftaid it', 'placeholder', 'woocommerce'),
      'required' => false,
      'class' => array('form-row-wide', 'wc-donation-giftaid'),
      'clear' => true
    );
  }

  return $fields;
}
