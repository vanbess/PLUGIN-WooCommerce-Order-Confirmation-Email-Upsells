<?php

/**
 * Add email upsells to footer of WooCommerce order confirmation email
 */
add_action('woocommerce_email_footer', function ($email) {

    // get order id
    $order_id = $email->object->get_id();

    // set default language
    $lang = 'en';

    // get order language (polylang)
    if (function_exists('pll_get_post_language')) :
        $lang = pll_get_post_language($order_id);
    endif;

    // get email id
    $email_id = $email->id;

    // if is customer new order or customer processing order or customer on hold order email
    if ($email_id == 'customer_on_hold_order' || $email_id == 'customer_processing_order') :

        // Get currently active
        $active = get_option('sbwc_email_upsell_active_tab');

        // Get the IDs of the pages to display as upsells
        $page_ids       = get_option('sbwc_email_upsell_page_ids_' . $lang);
        $page_ids_image = get_option('sbwc_email_upsell_page_ids_image_' . $lang);

        // Get the IDs of the products to display as upsells
        $product_upsell_ids = get_option('sbwc_email_upsell_product_ids_' . $lang);

        // -------------------
        // render page upsells
        // -------------------
        if (!empty($page_ids) && !empty($page_ids_image) && $active == 'page-ids') : ?>

            <h2 style="text-align: center; margin-bottom: 30px; margin-top: 30px;"><?php _e('Current Special Offers:', 'woocommerce'); ?></h2>

            <div style="display: flex; flex-wrap: wrap; justify-content: space-between;">

                <?php for ($i = 0; $i < count($page_ids); $i++) :

                    // get page data
                    $page_id       = $page_ids[$i];
                    $page_url      = get_permalink($page_id) . '?utm_source=order-confirmation&utm_medium=email&utm_campaign=upsell';
                    $page_title    = get_the_title($page_id);
                    $page_image    = $page_ids_image[$i];

                    // get body text color
                    $body_text_color = get_option('woocommerce_email_text_color');

                    // update send count in tracking table if present, else create new row and set send count to 1
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'sbwc_email_upsell_conversions';
                    $wpdb->query($wpdb->prepare("INSERT INTO $table_name (post_id, send_count) VALUES (%d, %d) ON DUPLICATE KEY UPDATE send_count = send_count + 1", $page_id, 1));

                ?>

                    <div style="flex-basis: calc(50% - 10px); margin-bottom: 40px; border: 1px solid #cccccc; border-radius: 3px; padding: 10px;">
                        <a style="text-decoration: none; color: <?php echo $body_text_color ?>;" href="<?php echo esc_url($page_url); ?>">
                            <img src="<?php echo esc_url($page_image); ?>" alt="<?php esc_attr_e($page_title); ?>" style="width: 210px; height: auto; object-fit: cover; display: block; margin: 0 auto; border-radius: 3px;"><br>
                            <span style="display: block; max-width: 210px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; text-align: center; padding-top: 0px; padding-bottom:5px; font-weight: bold; font-size: 16px;">
                                <?php esc_attr_e($page_title); ?>
                            </span>
                        </a>
                    </div>

                <?php endfor; ?>

            </div>

        <?php endif;

        // -----------------------
        // render product upsells
        // -----------------------
        if (!empty($product_upsell_ids) && $active == 'product-ids') : ?>

            <h2 style="text-align: center; margin-bottom: 30px; margin-top: 30px;"><?php _e('You Might Be Interested In:', 'woocommerce'); ?></h2>

            <div style="display: flex; flex-wrap: wrap; justify-content: space-between;">

                <?php foreach ($product_upsell_ids as $product_id) :

                    // get product data
                    $product          = wc_get_product($product_id);
                    $product_url      = $product->get_permalink() . '?utm_source=order-confirmation&utm_medium=email&utm_campaign=upsell';
                    $product_title    = $product->get_name();
                    $product_image    = wp_get_attachment_image_src($product->get_image_id(), 'large');
                    $price_html       = $product->get_price_html();

                    // get body text color
                    $body_text_color = get_option('woocommerce_email_text_color');

                    // update send count in tracking table if present, else create new row and set send count to 1
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'sbwc_email_upsell_conversions';
                    $wpdb->query($wpdb->prepare("INSERT INTO $table_name (post_id, send_count) VALUES (%d, %d) ON DUPLICATE KEY UPDATE send_count = send_count + 1", $product_id, 1));

                ?>

                    <div style="flex-basis: calc(50% - 10px); margin-bottom: 40px; border: 1px solid #cccccc; border-radius: 3px; padding: 10px;">
                        <a style="text-decoration: none; color: <?php echo $body_text_color ?>;" href="<?php echo esc_url($product_url); ?>">
                            <img src="<?php echo esc_url($product_image[0]); ?>" alt="<?php esc_attr_e($product_title); ?>" style="width: 210px; height: auto; object-fit: cover; display: block; margin: 0 auto; border-radius: 3px;"><br>
                            <span style="display: block; max-width: 210px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; text-align: center; padding-top: 10px; padding-bottom:10px;font-weight: bold; font-size: 16px;">
                                <?php esc_attr_e($product_title); ?>
                            </span>
                            <span style="display: block; text-align: center;padding-bottom:10px; font-weight: bold;">
                                <?php echo $price_html; ?>
                            </span>
                        </a>
                    </div>

                <?php endforeach; ?>

            </div>

<?php endif;

    endif;
});
