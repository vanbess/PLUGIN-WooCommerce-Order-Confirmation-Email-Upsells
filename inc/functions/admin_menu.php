<?php

/**
 * Add admin menu
 */
add_action('admin_menu', 'sbwc_email_upsell_admin_menu');

function sbwc_email_upsell_admin_menu()
{
    add_menu_page(
        __('SBWC Email Upsell Settings', 'woocommerce'),
        __('SBWC Email Upsells', 'woocommerce'),
        'manage_options',
        'sbwc-email-upsell-settings',
        'sbwc_email_upsell_settings_page',
        'dashicons-email-alt',
        20
    );
}

/**
 * Render page id inputs
 *
 * @param array $languages
 * @return void
 */
function sbwc_email_upsell_page_id_inputs($languages)
{
    // if is array $languages, create tab and associated content area for each language
    if (is_array($languages) && count($languages) > 0) : ?>

        <!-- tab links -->
        <h3 id="sbwc_email_us_admin_tab_links" class="nav-tab-wrapper">
            <?php foreach ($languages as $index => $language) : ?>
                <!-- language tab -->
                <a id="<?php echo $language; ?>-page-ids-link" href="#<?php echo $language; ?>-page-ids" class="nav-tab nav-tab-pages <?php echo ($index === 0) ? 'nav-tab-active' : ''; ?>"><?php echo strtoupper($language); ?></a>
            <?php endforeach; ?>
        </h3>

        <!-- tab content -->
        <?php foreach ($languages as $index => $language) : ?>

            <!-- language tab content -->
            <div id="<?php echo $language; ?>-page-ids" class="tab-content tab-content-pages <?php echo ($index === 0) ? 'nav-content-active' : ''; ?>">

                <?php
                // RENDER EXISTING

                // get saved page ids
                $page_ids = get_option('sbwc_email_upsell_page_ids_' . $language);

                // get saved page id images
                $page_ids_image = get_option('sbwc_email_upsell_page_ids_image_' . $language);

                // if page ids exist, render inputs
                if (is_array($page_ids) && count($page_ids) > 0) :

                    // loop through page ids and render inputs
                    foreach ($page_ids as $index => $page_id) : ?>
                        <div class="page-id-input-set">
                            <input type="text" class="sbwc_email_upsell_page_id_input" name="sbwc_email_upsell_page_ids_<?php echo $language ?>[]" value="<?php echo $page_id; ?>" placeholder="<?php _e('Page ID', 'woocommerce'); ?>">
                            <input type="text" class="sbwc_email_upsell_page_id_image_url_input" name="sbwc_email_upsell_page_ids_image_<?php echo $language ?>[]" value="<?php echo $page_ids_image[$index]; ?>" placeholder="<?php _e('Image URL', 'woocommerce'); ?>">
                            <button class="button button-primary sbwc-email-upsell-add-page-id"><?php _e('Add', 'woocommerce'); ?></button>
                            <button class="button button-secondary sbwc-email-upsell-rem-page-id"><?php _e('Remove', 'woocommerce'); ?></button>
                        </div>
                <?php endforeach;
                endif; ?>

                <!-- page id input set (page id + image + add button) -->
                <div class="page-id-input-set">
                    <input type="text" class="sbwc_email_upsell_page_id_input" name="sbwc_email_upsell_page_ids_<?php echo $language ?>[]" value="" placeholder="<?php _e('Page ID', 'woocommerce'); ?>">
                    <input type="text" class="sbwc_email_upsell_page_id_image_url_input" name="sbwc_email_upsell_page_ids_image_<?php echo $language ?>[]" value="" placeholder="<?php _e('Image URL', 'woocommerce'); ?>">
                    <button class="button button-primary sbwc-email-upsell-add-page-id"><?php _e('Add', 'woocommerce'); ?></button>
                    <button class="button button-secondary sbwc-email-upsell-rem-page-id"><?php _e('Remove', 'woocommerce'); ?></button>
                </div>

            </div>

        <?php endforeach;

    // else if is string $languages, create tab and associated content area for that language
    elseif (is_string($languages)) : ?>

        <!-- page id input set (page id + image + add button) -->
        <div class="page-id-input-set">
            <input type="text" class="sbwc_email_upsell_page_id_input" name="sbwc_email_upsell_page_ids[]" value="" placeholder="<?php _e('Page ID', 'woocommerce'); ?>">
            <input type="text" class="sbwc_email_upsell_page_id_image_url_input" name="sbwc_email_upsell_page_ids_image[]" value="" placeholder="<?php _e('Image URL', 'woocommerce'); ?>">
            <button class="button button-primary sbwc-email-upsell-add-page-id"><?php _e('Add', 'woocommerce'); ?></button>
            <button class="button button-secondary sbwc-email-upsell-rem-page-id"><?php _e('Remove', 'woocommerce'); ?></button>
        </div>

    <?php endif;
}

/**
 * Render product id inputs     
 *
 * @param array $languages
 * @return void
 */
function sbwc_email_upsell_product_id_inputs($languages)
{
    // if is array $languages, create tab and associated content area for each language
    if (is_array($languages) && count($languages) > 0) : ?>

        <!-- tab links -->
        <h3 id="sbwc_email_us_admin_tab_links" class="nav-tab-wrapper">
            <?php foreach ($languages as $index => $language) : ?>
                <!-- language tab -->
                <a id="<?php echo $language; ?>-product-ids-link" href="#<?php echo $language; ?>-product-ids" class="nav-tab nav-tab-prods <?php echo ($index === 0) ? 'nav-tab-active' : ''; ?>"><?php echo strtoupper($language); ?></a>
            <?php endforeach; ?>
        </h3>

        <!-- tab content -->
        <?php foreach ($languages as $index => $language) : ?>

            <!-- language tab content -->
            <div id="<?php echo $language; ?>-product-ids" class="tab-content tab-content-prods <?php echo ($index === 0) ? 'nav-content-active' : ''; ?>">

                <select class="sbwc_email_upsell_product_ids_<?php echo $language ?>" name="sbwc_email_upsell_product_ids_<?php echo $language ?>[]" multiple="multiple" style="width:400px;">

                    <?php

                    // Get product IDs
                    $product_ids = get_option('sbwc_email_upsell_product_ids_' . $language);

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
                        $('.sbwc_email_upsell_product_ids_<?php echo $language ?>').select2({
                            ajax: {
                                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                dataType: 'json',
                                delay: 250,
                                data: function(params) {
                                    return {
                                        q: params.term,
                                        lang: '<?php echo $language; ?>',
                                        action: 'sbwc_email_upsells_product_search'
                                    };
                                },
                                processResults: function(data) {
                                    return {
                                        results: data
                                    };
                                },
                                cache: true
                            },
                            minimumInputLength: 3,
                            placeholder: '<?php _e('Search for a product', 'woocommerce'); ?>'
                        });
                    });
                </script>

            </div>

        <?php endforeach;

    // else if is string $languages, create tab and associated content area for that language
    elseif (is_string($languages)) : ?>

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
                                lang: '',
                                action: 'sbwc_email_upsells_product_search'
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

    <?php endif;
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

    // get list of polylang languages
    function_exists('pll_languages_list') ? $languages = pll_languages_list() : $languages = array('en');

    ?>
    <div class="wrap">

        <h1 id="sbwc_email_upsells_admin_page_title"><?php _e('SBWC Email Upsell Settings', 'woocommerce'); ?></h1>

        <!-- nav tabs -->
        <h2 id="sbwc_email_us_admin_tab_links" class="nav-tab-wrapper">
            <a id="product-ids-link" href="#product-ids" class="nav-tab nav-tab-main"><?php _e('Product IDs', 'woocommerce'); ?></a>
            <a id="page-ids-link" href="#page-ids" class="nav-tab nav-tab-main"><?php _e('Page IDs', 'woocommerce'); ?></a>
            <a id="tracking-link" href="#tracking" class="nav-tab nav-tab-main"><?php _e('Tracking', 'woocommerce'); ?></a>
        </h2>

        <!-- tracking tab -->
        <div id="tracking" class="tab-content tab-content-main">

            <h3><?php _e('Tracking', 'woocommerce'); ?></h3>

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th class="column-title column-primary"><b><?php _e('Product/Page ID', 'woocommerce'); ?></b></th>
                        <th class="column-title column-primary"><b><?php _e('Visits', 'woocommerce'); ?></b></th>
                        <th class="column-title column-primary"><b><?php _e('Emails Sent', 'woocommerce'); ?></b></th>
                        <th class="column-title column-primary"><b><?php _e('Click Through Rate', 'woocommerce'); ?></b></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Add your tracking data here -->
                </tbody>
            </table>

        </div>

        <!-- options form -->
        <form method="post" action="options.php">

            <?php settings_fields('sbwc_email_upsell_settings_group'); ?>

            <!-- product ids tab -->
            <div id="product-ids" class="tab-content tab-content-main">

                <p><i><b><?php _e('Enter the product IDs which you want to display as upsells in new order emails (leave empty to disable).', 'woocommerce'); ?></b></i></p>

                <?php sbwc_email_upsell_product_id_inputs($languages) ?>

            </div>

            <!-- page ids and image tab -->
            <div id="page-ids" class="tab-content tab-content-main">

                <p><i><b><?php _e('Enter the page IDs and image URLs you want to display upsells for in new order emails (leave empty to disable). Note that image URL is required in order to display properly in emails.', 'woocommerce'); ?></b></i></p>

                <?php sbwc_email_upsell_page_id_inputs($languages) ?>

            </div>

            <!-- upsells to display -->
            <div class="tab-selector">
                <p><i><b>Select which type of upsells you want to display in new order emails:</b></i></p>
                <label><input type="radio" name="sbwc_email_upsell_active_tab" value="product-ids" <?php checked('product-ids', get_option('sbwc_email_upsell_active_tab')); ?>>Product IDs</label>
                <label><input type="radio" name="sbwc_email_upsell_active_tab" value="page-ids" <?php checked('page-ids', get_option('sbwc_email_upsell_active_tab')); ?>>Page IDs</label>
            </div>

            <!-- submit -->
            <?php submit_button(); ?>
        </form>

    </div>

    <!-- JS -->
    <script>
        jQuery(document).ready(function($) {

            // -------------------------------------------------
            // add/remove active class to page tabs and content
            $('.nav-tab-pages').click(function(e) {

                e.preventDefault();

                $('.nav-tab-pages').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');
                let target = $(this).attr('href');
                $('.tab-content-pages').removeClass('nav-content-active');
                $(target).addClass('nav-content-active');
            });

            // ---------------------------------------------------
            // add/remove active class to product tabs and content
            $('.nav-tab-prods').click(function(e) {

                e.preventDefault();

                $('.nav-tab-prods').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');
                let target = $(this).attr('href');
                $('.tab-content-prods').removeClass('nav-content-active');
                $(target).addClass('nav-content-active');
            });

            // ---------------------------------------------------
            // add/remove active class to tracking tabs and content
            $('.nav-tab-tracking').click(function(e) {

                e.preventDefault();

                $('.nav-tab-tracking').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');
                let target = $(this).attr('href');
                $('.tab-content-tracking').removeClass('nav-content-active');
                $(target).addClass('nav-content-active');
            });

            // --------------------------------------------------------
            // set active main tab based selected option (main content)
            var active_tab = $('input[name="sbwc_email_upsell_active_tab"]:checked').val();

            if (active_tab) {
                $('.nav-tab-main').removeClass('nav-tab-active');
                $('.nav-tab-main[href="#' + active_tab + '"]').addClass('nav-tab-active');
                $('.tab-content-main').removeClass('nav-content-active');
                $('#' + active_tab).addClass('nav-content-active');
            }

            // --------------------------------------------------------
            // set active tab content based on click tab button click
            $('.nav-tab-main').click(function(e) {

                e.preventDefault();

                $('.nav-tab-main').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');
                let target = $(this).attr('href');
                $('.tab-content-main').removeClass('nav-content-active');
                $(target).addClass('nav-content-active');
            });

            // -------------------------------
            // add page id input set on click
            $(document).on('click', '.sbwc-email-upsell-add-page-id', function(e) {
                e.preventDefault();

                // get language
                let language = $(this).parent().parent().attr('id').split('-')[0];

                // html
                let html = `
                    <div class="page-id-input-set">
                        <input type="text" class="sbwc_email_upsell_page_id_input" name="sbwc_email_upsell_page_ids_${language}[]" value="" placeholder="<?php _e('Page ID', 'woocommerce'); ?>">
                        <input type="text" class="sbwc_email_upsell_page_id_image_url_input" name="sbwc_email_upsell_page_ids_image_${language}[]" value="" placeholder="<?php _e('Image URL', 'woocommerce'); ?>">
                        <button class="button button-primary sbwc-email-upsell-add-page-id"><?php _e('Add', 'woocommerce'); ?></button>
                        <button class="button button-secondary sbwc-email-upsell-rem-page-id"><?php _e('Remove', 'woocommerce'); ?></button>
                    </div>
                `;

                // append html
                $(this).parent().parent().append(html);

            });

            // ---------------------------------
            // remove page id input set on click
            $(document).on('click', '.sbwc-email-upsell-rem-page-id', function(e) {
                e.preventDefault();

                // remove input set
                $(this).parent().remove();
            });

            // -------------------------------------------------------
            // check of empty page id input sets and remove any > 1
            $('.tab-content-pages').each(function(tab_index) {

                // check if inputs have empty vals and log index if found
                $(this).find('.page-id-input-set').each(function(parent_index) {

                    $(this).find('input').each(function(index) {

                        if ($(this).val() === '' && parent_index > 0) {
                            $(this).parent().remove();
                        }

                    });

                });

            });

            // -------------------------------------------------------
            // img url input on click show wordpress media library modal
            $(document).on('click', '.sbwc_email_upsell_page_id_image_url_input', function(e) {
                e.preventDefault();

                // get input
                let input = $(this);

                // create media frame
                let frame = wp.media({
                    title: 'Select or Upload Image',
                    button: {
                        text: 'Use this image'
                    },
                    multiple: false
                });

                // on select
                frame.on('select', function() {

                    // get selected attachment
                    let attachment = frame.state().get('selection').first().toJSON();

                    // set input val
                    input.val(attachment.url);
                });

                // open media frame
                frame.open();
            });

        });
    </script>

    <!-- CSS -->
    <style>
        h1#sbwc_email_upsells_admin_page_title {
            background: white;
            padding: 10px 20px;
            margin-top: -10px;
            margin-left: -21px;
            box-shadow: 0px 2px 5px lightgray;
            width: 100%;
            margin-bottom: 15px;
        }

        .tab-content-main,
        .tab-content-pages,
        .tab-content-prods {
            display: none;
        }

        .nav-content-active {
            display: block;
        }

        .nav-tab-wrapper {
            border-bottom: 1px solid #ccc;
            margin-bottom: 20px;
        }

        .nav-tab-main {
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

        .tab-content-main {
            margin-bottom: 20px;
            padding: 30px;
            background: white;
            border-radius: 5px;
        }

        .tab-content-main h3 {
            margin-top: 0;
        }

        .tab-content-main textarea {
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

        input.sbwc_email_upsell_page_id_image_url_input {
            width: 600px;
        }

        .tab-content>p {
            margin-top: 0;
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

    // get list of polylang languages if plugin is active
    if (function_exists('pll_languages_list')) {
        $languages = pll_languages_list();
    } else {
        $languages = array('en');
    }

    // if polylang is active, register settings for each language
    if (is_array($languages) && count($languages) > 0) {

        foreach ($languages as $language) {

            register_setting(
                'sbwc_email_upsell_settings_group',
                'sbwc_email_upsell_product_ids_' . $language
            );
            register_setting(
                'sbwc_email_upsell_settings_group',
                'sbwc_email_upsell_page_ids_' . $language
            );
            register_setting(
                'sbwc_email_upsell_settings_group',
                'sbwc_email_upsell_page_ids_image_' . $language
            );
        }


        // else if polylang is not active, register settings for single language
    } elseif (is_string($languages)) {

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
    }

    // register active tab setting
    register_setting(
        'sbwc_email_upsell_settings_group',
        'sbwc_email_upsell_active_tab'
    );
}


/**
 * Add AJAX callback for product search
 */
function sbwc_email_upsells_product_search_callback()
{

    // Get search term
    $search_term = $_GET['q'];
    $lang        = $_GET['lang'];

    if ($lang && $lang !== '') :

        // Query products
        $args = array(
            'post_type'      => 'product',
            's'              => $search_term,
            'posts_per_page' => -1,
            'lang'           => $lang,
        );

        // Get products
        $products = get_posts($args);

        // Holds the results
        $results = array();

        // Loop through products and add to results array
        foreach ($products as $product) {

            $results[] = array(
                'id'   => $product->ID,
                'text' => $product->post_title . ' [' . strtoupper($lang) . ']',
            );
        }

    else :
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

            $results[] = array(
                'id'   => $product->ID,
                'text' => $product->post_title
            );
        }
    endif;

    // Send results as JSON
    wp_send_json($results);
}

add_action('wp_ajax_sbwc_email_upsells_product_search', 'sbwc_email_upsells_product_search_callback');
add_action('wp_ajax_nopriv_sbwc_email_upsells_product_search', 'sbwc_email_upsells_product_search_callback');
