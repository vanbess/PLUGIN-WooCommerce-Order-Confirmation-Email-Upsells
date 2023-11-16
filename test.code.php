<?php
// Add the following code to your plugin or functions.php file

// Enqueue select2 script and styles
function enqueue_select2() {
    wp_enqueue_script( 'sbwc_email_upsell_select2', plugin_dir_url(__FILE__).'inc/assets/select2.min.js', array( 'jquery' ), '4.0.13', true );
    wp_enqueue_style( 'sbwc_email_upsell_select2', plugin_dir_url(__FILE__).'inc/assets/select2.min.css', array(), '4.0.13' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_select2' );

// Add AJAX callback for product search
function product_search_callback() {
    $search_term = $_GET['q'];
    $args = array(
        'post_type' => 'product',
        's' => $search_term,
        'posts_per_page' => -1,
    );
    $products = get_posts( $args );
    $results = array();
    foreach ( $products as $product ) {
        $results[] = array(
            'id' => $product->ID,
            'text' => $product->post_title,
        );
    }
    wp_send_json( $results );
}
add_action( 'wp_ajax_product_search', 'product_search_callback' );
add_action( 'wp_ajax_nopriv_product_search', 'product_search_callback' );

// Add select2 field to your form
?>
<select class="product-select" multiple="multiple"></select>
<script>
jQuery(document).ready(function($) {
    $('.product-select').select2({
        ajax: {
            url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    action: 'product_search'
                };
            },
            processResults: function (data) {
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

