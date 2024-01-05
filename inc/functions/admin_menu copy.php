<?php

/**
 * Add admin menu
 */
add_action('admin_menu', 'sbwc_email_upsell_admin_menu');

function sbwc_email_upsell_admin_menu()
{
    add_menu_page(
        __('SBWC Email Upsell Settings', 'domain'),
        __('SBWC Email Upsells', 'domain'),
        'manage_options',
        'sbwc-email-upsell-settings',
        'sbwc_email_upsell_settings_page',
        'dashicons-email-alt',
        20
    );
}

/**
 * Enqueue select2 script and styles
 */
add_action('admin_footer', function () {
    wp_enqueue_script('sbwc_email_upsell_select2', SBWC_EMAIL_UPSELL_PLUGIN_URI . 'inc/assets/select2.min.js', array('jquery'), '4.0.13', false);
    wp_enqueue_style('sbwc_email_upsell_select2', SBWC_EMAIL_UPSELL_PLUGIN_URI . 'inc/assets/select2.min.css', array(), '4.0.13');
});

/**
 * Render settings page
 *
 * @return void
 */
function sbwc_email_upsell_settings_page()
{


?>
    <div class="wrap">
        <h1><?php _e('SBWC Email Upsell Settings', 'default'); ?></h1>

        <!-- nav tabs -->
        <h2 id="sbwc_email_us_admin_tab_links" class="nav-tab-wrapper">
            <a id="product-ids-link" href="#product-ids" class="nav-tab"><?php _e('Product IDs', 'default'); ?></a>
            <a id="page-ids-link" href="#page-ids" class="nav-tab"><?php _e('Page IDs', 'default'); ?></a>
        </h2>

        <!-- options form -->
        <form method="post" action="options.php">

            <?php settings_fields('sbwc_email_upsell_settings_group'); ?>

            <!-- product ids tab -->
            <div id="product-ids" class="tab-content">

                <p><i><b><?php _e('Enter the product IDs which you want to display as upsells in new order emails', 'default'); ?></b></i></p>

                <select class="sbwc_email_upsell_product_ids" name="sbwc_email_upsell_product_ids[]" multiple="multiple" style="width:400px;">

                    <?php

                    // Get product IDs
                    $product_ids = get_option('sbwc_email_upsell_product_ids');

                    // display selected products
                    foreach ($product_ids as $product_id) :
                        $product = wc_get_product($product_id);
                        if ($product) :
                    ?>
                            <option value="<?php echo $product_id; ?>" selected="selected"><?php echo $product->get_name(); ?></option>
                    <?php
                        endif;
                    endforeach;
                    ?>

                </select>

                <script>
                    jQuery(document).ready(function($) {
                        $('.sbwc_email_upsell_product_ids').select2({
                            ajax: {
                                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                dataType: 'json',
                                delay: 250,
                                data: function(params) {
                                    return {
                                        q: params.term,
                                        action: 'product_search'
                                    };
                                },
                                processResults: function(data) {
                                    return {
                                        results: data
                                    };
                                },
                                cache: true
                            },
                            minimumInputLength: 3
                        });
                    });
                </script>

            </div>

            <!-- page ids and image tab -->
            <div id="page-ids" class="tab-content">
                <p><i><b><?php _e('Enter the page IDs and image URLs you want to display upsells for in new order emails.', 'default'); ?></b></i></p>

                <?php

                // get list of polylang languages
                function_exists('pll_languages_list') ? $languages = pll_languages_list() : $languages = array('en');

            //    if is array $languages, create tab and associated content area for each language
                if (is_array($languages) && count($languages) > 0) :
                    foreach ($languages as $language) : ?>

                        <!-- language tab -->
                        <h2 id="sbwc_email_us_admin_tab_links" class="nav-tab-wrapper">
                            <a id="<?php echo $language; ?>-link" href="#<?php echo $language; ?>" class="nav-tab"><?php echo strtoupper($language); ?></a>
                        </h2>

                        <!-- language tab content -->
                        <div id="<?php echo $language; ?>" class="tab-content">

                            <!-- page id input set (page id + image + add button) -->
                            <div class="page-id-input-set">
                                <input type="text" name="sbwc_email_upsell_page_ids[]" value="" placeholder="<?php _e('Page ID', 'default'); ?>">
                                <input type="text" name="sbwc_email_upsell_page_ids_image[]" value="" placeholder="<?php _e('Image URL', 'default'); ?>">
                                <button class="button button-primary sbwc-email-upsell-add-page-id"><?php _e('Add', 'default'); ?></button>
                                <button class="button button-secondary sbwc-email-upsell-rem-page-id"><?php _e('Remove', 'default'); ?></button>
                            </div>

                        </div>

                    <?php endforeach;


                $page_ids       = get_option('sbwc_email_upsell_page_ids');
                $page_ids_image = get_option('sbwc_email_upsell_page_ids_image');

                if (is_countable($page_ids) && count($page_ids) > 0) :
                    for ($i = 0; $i < count($page_ids); $i++) : ?>
                        <!-- page id input set (page id + image + add button) -->
                        <div class="page-id-input-set">
                            <input type="text" name="sbwc_email_upsell_page_ids[]" value="<?php echo esc_attr($page_ids[$i]); ?>" placeholder="<?php _e('Page ID', 'default'); ?>">
                            <input type="text" name="sbwc_email_upsell_page_ids_image[]" value="<?php echo esc_attr($page_ids_image[$i]); ?>" placeholder="<?php _e('Image URL', 'default'); ?>">
                            <button class="button button-primary sbwc-email-upsell-add-page-id"><?php _e('Add', 'default'); ?></button>
                            <button class="button button-secondary sbwc-email-upsell-rem-page-id"><?php _e('Remove', 'default'); ?></button>
                        </div>
                    <?php endfor;
                else : ?>

                    <!-- page id input set (page id + image + add button) -->
                    <div class="page-id-input-set">
                        <input type="text" name="sbwc_email_upsell_page_ids[]" value="" placeholder="<?php _e('Page ID', 'default'); ?>">
                        <input type="text" name="sbwc_email_upsell_page_ids_image[]" value="" placeholder="<?php _e('Image URL', 'default'); ?>">
                        <button class="button button-primary sbwc-email-upsell-add-page-id"><?php _e('Add', 'default'); ?></button>
                        <button class="button button-secondary sbwc-email-upsell-rem-page-id"><?php _e('Remove', 'default'); ?></button>
                    </div>
                <?php endif; ?>

            </div>

            <!-- upsells to display -->
            <div class="tab-selector">
                <p><i><b><?php _e('Select which type of upsells you want to display in new order emails:', 'woocommerce'); ?></b></i></p>

                <select name="sbwc_email_upsell_active_tab" id="sbwc_email_upsell_active_tab" style="width: 150px;">
                    <option value=""><?php _e('Please select...', 'woocommerce'); ?></option>
                    <option value="product-ids" <?php selected('product-ids', get_option('sbwc_email_upsell_active_tab')) ?>><?php _e('Product IDs', 'woocommerce'); ?></option>
                    <option value="page-ids" <?php selected('page-ids', get_option('sbwc_email_upsell_active_tab')) ?>><?php _e('Page IDs', 'woocommerce'); ?></option>
                </select>

            </div>

            <!-- submit -->
            <?php submit_button(); ?>
        </form>

    </div>

    <!-- JS -->
    <script>
        jQuery(document).ready(function($) {

            // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            // Hide all tab content except the first one
            // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            $('.tab-content').not(':first').hide();

            // ~~~~~~~~~~~~~~~~~~
            // Handle tab clicks
            // ~~~~~~~~~~~~~~~~~~
            $('.nav-tab').click(function() {
                // Remove active class from all tabs
                $('.nav-tab').removeClass('nav-tab-active');

                // Add active class to clicked tab
                $(this).addClass('nav-tab-active');

                // Hide all tab content
                $('.tab-content').hide();

                // Show the corresponding tab content
                $($(this).attr('href')).show();

                // Update the active tab input value
                $('input[name="sbwc_email_upsell_active_tab_val"]').val($(this).attr('href').replace('#', ''));

                // scroll to top
                $('html, body').animate({
                    scrollTop: 0
                }, 500);
            });

            // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            // Set the active tab on page load
            // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            $('.nav-tab[href="#<?php echo get_option('sbwc_email_upsell_active_tab', 'product-ids'); ?>"]').click();

            // ~~~~~~~~~~~~~~~~~~~~~~
            // Add page id input set
            // ~~~~~~~~~~~~~~~~~~~~~~
            $(document).on('click', '.sbwc-email-upsell-add-page-id', function(e) {
                e.preventDefault();

                // Clone the first page id input set
                var newPageIdInputSet = $('.page-id-input-set:first').clone();

                // Clear the values
                newPageIdInputSet.find('input').val('');

                // Append the new page id input set
                newPageIdInputSet.appendTo('.tab-content#page-ids');

                // append remove button
                newPageIdInputSet.append('<button class="button button-secondary sbwc-email-upsell-rem-page-id">Remove</button>');

            });

            // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            // Don't show remove button for the first page id input set
            // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            $('.page-id-input-set:first .sbwc-email-upsell-rem-page-id').hide();

            // ~~~~~~~~~~~~~~~~~~~~~~~~~
            // Remove page id input set
            // ~~~~~~~~~~~~~~~~~~~~~~~~~
            $(document).on('click', '.sbwc-email-upsell-rem-page-id', function(e) {
                e.preventDefault();

                // Remove the page id input set
                $(this).closest('.page-id-input-set').remove();
            });

        });
    </script>

    <!-- CSS -->
    <style>
        .nav-tab-wrapper {
            border-bottom: 1px solid #ccc;
            margin-bottom: 20px;
        }

        .nav-tab {
            display: inline-block;
            margin-right: 5px;
            padding: 10px 20px;
            border: 1px solid #ccc;
            border-bottom: none;
            background-color: #f7f7f7;
            cursor: pointer;
        }

        .nav-tab-active {
            background-color: #fff;
            border-color: #ccc;
            border-bottom: none;
        }

        .tab-content {
            margin-bottom: 20px;
            padding: 30px;
            background: white;
            border-radius: 5px;
        }

        .tab-content h3 {
            margin-top: 0;
        }

        .tab-content textarea {
            width: 400px;
            height: 32px;
        }

        .tab-selector {
            margin-bottom: 20px;
        }

        .tab-selector label {
            margin-right: 10px;
        }

        .page-id-input-set {
            margin-bottom: 10px;
        }

        .page-id-input-set input {
            margin-right: 10px;
        }

        .page-id-input-set button {
            margin-right: 10px;
        }

        .page-id-input-set:last-child {
            margin-bottom: 0;
        }

        .page-id-input-set input {
            width: 100px;
        }

        .page-id-input-set input[name="sbwc_email_upsell_page_ids_image[]"] {
            min-width: 300px;
            width: 30%;
        }

        .page-id-input-set button {
            width: auto;
        }

        .page-id-input-set button:hover {
            background-color: #eee;
        }

        .page-id-input-set button:focus {
            outline: none;
        }

        .page-id-input-set button:active {
            background-color: #ddd;
        }

        .page-id-input-set button:disabled {
            background-color: #ccc;
        }
    </style>
<?php
}

/**
 * Register/save settings
 */
add_action('admin_init', 'sbwc_email_upsell_settings_init');

function sbwc_email_upsell_settings_init()
{
    register_setting(
        'sbwc_email_upsell_settings_group',
        'sbwc_email_upsell_product_ids'
    );
    register_setting(
        'sbwc_email_upsell_settings_group',
        'sbwc_email_upsell_page_ids'
    );
    register_setting(
        'sbwc_email_upsell_settings_group',
        'sbwc_email_upsell_page_ids_image'
    );
    register_setting(
        'sbwc_email_upsell_settings_group',
        'sbwc_email_upsell_active_tab'
    );
}


/**
 * Add AJAX callback for product search
 */
function product_search_callback()
{

    // Get search term
    $search_term = $_GET['q'];

    // Query products
    $args = array(
        'post_type'      => 'product',
        's'              => $search_term,
        'posts_per_page' => -1,
    );

    // Get products
    $products = get_posts($args);

    // Holds the results
    $results = array();

    // Loop through products and add to results array
    foreach ($products as $product) {

        // get polylang post language
        function_exists('pll_get_post_language') ? $lang = pll_get_post_language($product->ID) : $lang = 'en';

        $results[] = array(
            'id'   => $product->ID,
            'text' => $product->post_title . ' [' . strtoupper($lang) . ']',
        );
    }

    // Send results as JSON
    wp_send_json($results);
}

add_action('wp_ajax_product_search', 'product_search_callback');
add_action('wp_ajax_nopriv_product_search', 'product_search_callback');
