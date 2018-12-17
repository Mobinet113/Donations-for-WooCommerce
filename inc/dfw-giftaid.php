<?php
/**
 * Simple function to add a UK GiftAid checkbox on
 * the checkout page if a donation item is present
 */

/**
 * Display the Giftaid checkbox in the checkout
 */

add_filter('woocommerce_checkout_fields', 'add_giftaid_field', 100);

function add_giftaid_field($fields)
{
  global $woocommerce;

  // Get the cart contents
  $items = $woocommerce->cart->get_cart();
  $isDonation = false;

  // Check to see if a donation item is in the cart
  foreach ($items as $item) {
    if ($item['data']->product_type === 'donation') {
      $isDonation = true;
      break;
    }
  }

  // If there is a donation item in the cart AND
  // the UK Giftaid setting is enabled, create a new checkbox in the checkout
  if ($isDonation && hm_wcdon_get_option('enable_uk_giftaid_field')) {
    $fields['billing']['giftaid'] = array(
      'type' => 'checkbox',
      'label' => __(
        '<span>Giftaid it</span>
         <p>Yes, I want to Gift Aid my donation made today and any donations I make in the future or have made in the past four years.</p>
         <p>I am a UK taxpayer and understand that if I pay less income tax and/or capital gains tax in a tax year than the amount of Gift Aid claimed on all my donations in that tax year, it is my responsibility to pay any difference. I will notify the Sir Arthur Sullivan Society if I want to cancel this declaration, change my name or home address or no longer pay sufficient tax on my income and/or capital gains.</p>',

        'woocommerce'),
      'placeholder' => _x('Giftaid it', 'placeholder', 'woocommerce'),
      'required' => false,
      'class' => array('form-row-wide', 'wc-donation-giftaid'),
      'clear' => true
    );
  }

  return $fields;

}

/**
 * Update the order meta with field value
 */

add_action('woocommerce_checkout_update_order_meta', 'giftaid_field_update_order_meta');

function giftaid_field_update_order_meta($order_id)
{
  if (!empty($_POST['giftaid'])) {
    update_post_meta($order_id, '_giftaid', sanitize_text_field($_POST['giftaid']));
  }
}

/**
 * Display field value on the order edit page
 */

add_action('woocommerce_admin_order_data_after_shipping_address', 'giftaid_field_display_admin_order_meta', 10, 1);

function giftaid_field_display_admin_order_meta($order)
{
  $giftaid = get_post_meta($order->get_id(), '_giftaid', true);

  echo '<div class="wc-donation-giftaid">';
  if ($giftaid === "1") {
    echo '<p><strong>' . __('UK Giftaid Added') . ':</strong> Yes, Giftaid added</p>';
  } else {
    echo '<p><strong>' . __('UK Giftaid Added') . ':</strong> No</p>';
  }
  echo '</div>';
}

/**
 * Add a custom field (in an order) to the emails
 */
add_filter( 'woocommerce_email_order_meta_fields', 'custom_woocommerce_email_order_meta_fields', 10, 3 );

function custom_woocommerce_email_order_meta_fields( $fields, $sent_to_admin, $order ) {
  $fields['_giftaid'] = array(
    'label' => __( 'Giftaid Added' ),
    'value' => get_post_meta( $order->id, '_giftaid', true ),
  );
  return $fields;
}