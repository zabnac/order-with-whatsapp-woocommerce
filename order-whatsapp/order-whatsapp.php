<?php
/*
Plugin Name: Order with WhatsApp
Description: Adds an "Order with WhatsApp" button to the WooCommerce single product page. 
Version: 1.0
Author: Omer Canbaz
Mail: oscanbaz@gmail.com
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: order-with-whatsapp
Domain Path: /languages
*/

if (! defined('ABSPATH')) {
    exit;
}

// Adding styles
function whatsapp_order_button_enqueue_styles()
{
    wp_enqueue_style('whatsapp-order-button-style', plugin_dir_url(__FILE__) . 'css/style.css');
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
}
add_action('wp_enqueue_scripts', 'whatsapp_order_button_enqueue_styles');

// Adding WhatsApp button to single product page
function add_whatsapp_order_button()
{
    if (is_product()) {
        global $product;
        $product_url = get_permalink($product->get_id());
        $product_name = $product->get_name();
        $whatsapp_number = get_option('whatsapp_order_button_number');
        $whatsapp_message = 'Hi! I am interested in this product: ' . $product_name . ' (' . $product_url . ')';
        $whatsapp_url = 'https://wa.me/' . $whatsapp_number . '?text=' . urlencode($whatsapp_message);

        echo '<a href="' . esc_url($whatsapp_url) . '" class="button alt whatsapp-order-button" target="_blank" style="margin-left: 10px;"><i class="fab fa-whatsapp"></i> Order via WhatsApp</a>';
    }
}
add_action('woocommerce_after_add_to_cart_button', 'add_whatsapp_order_button');

// Adds an empty value to database inside wp_options 
function whatsapp_order_button_register_settings()
{
    add_option('whatsapp_order_button_number', '');
    register_setting('whatsapp_order_button_options_group', 'whatsapp_order_button_number');
}
add_action('admin_init', 'whatsapp_order_button_register_settings');

// Add settings page under Tools
function whatsapp_order_button_register_options_page()
{
    add_menu_page('WhatsApp Order Button Settings', 'WhatsApp Order Button', 'manage_options', 'whatsapp-order-button', 'whatsapp_order_button_options_page', 'dashicons-whatsapp', 81);
}
add_action('admin_menu', 'whatsapp_order_button_register_options_page');

// Content within the tools/buy via whatsapp section
function whatsapp_order_button_options_page()
{
?>
    <div class="wrap">
        <h1>WhatsApp Order Button Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('whatsapp_order_button_options_group'); ?>
            <table class="tool-table">
                <tr>
                    <th><label for="whatsapp_order_button_number">Please enter your WhatsApp number:</label></th>
                </tr>
                <tr>
                    <td><input type="text" placeholder="+31 000 00 00" id="whatsapp_order_button_number" name="whatsapp_order_button_number" value="<?php echo get_option('whatsapp_order_button_number'); ?>" /></td>
                </tr>
            </table>
            <h4>For support, feel free to contact <a href="https://www.linkedin.com/in/omer-serif-canbaz/" target="_blank">me</a>.</h4>
            <?php submit_button(); ?>
        </form>
    </div>

<?php
}
?>