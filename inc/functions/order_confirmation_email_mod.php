<?php

/**
 * Add email upsells to footer of WooCommerce order confirmation email
 */
add_action('woocommerce_email_footer', function ($email) {

    // get email id
    $email_id = $email->id;

    // if is customer new order or customer processing order or customer on hold order email
    // if ($email_id == 'customer_on_hold_order' || $email_id == 'customer_processing_order') :

    // ob_start();

    // Get currently active
    $active = get_option('sbwc_email_upsell_active_tab');

    // Get the IDs of the pages to display as upsells
    $page_ids = get_option('sbwc_email_upsell_page_ids');
    $page_ids_image = get_option('sbwc_email_upsell_page_ids_image');

    // Get the IDs of the products to display as upsells
    $product_upsell_ids = get_option('sbwc_email_upsell_product_ids');

    // -------------------
    // render page upsells
    // -------------------
    if (!empty($page_ids) && !empty($page_ids_image) && $active == 'page-ids') :

        // Determine the number of columns to use
        $num_columns = count($page_ids) > 1 ? 2 : 1;

        // Start the div container
        $container_style = 'display: flex; flex-wrap: wrap;';

        // setup columns
        if ($num_columns == 2) :
            $container_style .= 'justify-content: space-between;';
        endif;

        // open container
        $container = '<div style="' . $container_style . '">';

        // Add the items
        $item_style = 'flex-basis: calc(50% - 10px); margin-bottom: 20px;';

        // loop through pages
        for ($i = 0; $i < count($page_ids); $i++) :

            // get page data
            $page_id       = $page_ids[$i];
            $page_url      = get_permalink($page_id);
            $page_title    = get_the_title($page_id);
            $page_image    = $page_ids_image[$i];
            $page_img_html = '<img src="' . $page_image . '" alt="' . $page_title . '" style="width: 225px; height: auto; display: block; margin: 0 auto;">';

            // add page data
            $item = '<div style="' . $item_style . '">';
            $item .= '<a href="' . $page_url . '">' . $page_img_html . '<br>' . $page_title . '</a>';
            $item .= '</div>';

            $container .= $item;

        endfor;

        // End the container
        $container .= '</div>';

        // Output the container
        echo $container;

    endif;

    // -----------------------
    // render product upsells
    // -----------------------
    if (!empty($product_upsell_ids) && $active == 'product-ids') : ?>

        <h2 style="text-align: center; margin-bottom: 30px; margin-top: 30px;"><?php _e('You Might Be Interested In:', 'woocommerce'); ?></h2>

        <div style="display: flex; flex-wrap: wrap; justify-content: space-between;">

            <?php foreach ($product_upsell_ids as $product_id) :

                // get product data
                $product          = wc_get_product($product_id);
                $product_url      = $product->get_permalink();
                $product_title    = $product->get_name();
                $product_image    = wp_get_attachment_image_src($product->get_image_id(), 'large');
                $price_html       = $product->get_price_html();

                // get body text color
                $body_text_color = get_option('woocommerce_email_text_color');

            ?>

                <div style="flex-basis: calc(50% - 10px); margin-bottom: 30px; border: 1px solid #cccccc; border-radius: 3px; padding: 10px;">
                    <a style="text-decoration: none; color: <?php echo $body_text_color ?>;" href="<?php echo esc_url($product_url); ?>">
                        <img src="<?php echo esc_url($product_image[0]); ?>" alt="<?php esc_attr_e($product_title); ?>" style="width: 210px; height: auto; object-fit: cover; display: block; margin: 0 auto; border-radius: 3px;"><br>
                        <span style="display: block; max-width: 210px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; text-align: center; padding-top: 10px; padding-bottom:10px;"><?php esc_attr_e($product_title); ?></span>
                        <span style="display: block; text-align: center;padding-bottom:10px; font-weight: bold;"><?php echo $price_html; ?></span>
                    </a>
                </div>

            <?php endforeach; ?>

        </div>


<?php endif;

    // output $container to file upsell.html
    // file_put_contents(SBWC_EMAIL_UPSELL_PLUGIN_PATH . 'upsell.html', $container);



    // endif;
});
