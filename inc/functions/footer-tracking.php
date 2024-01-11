<?php

/**
 * Update tracking if relevant arguments are present in URL
 */
add_action('wp_footer', function () {

    if (isset($_GET['utm_campaign']) && $_GET['utm_campaign'] == 'sbwc_email_upsell') :

        $post_id = isset($_GET['post_id']) && is_numeric($_GET['post_id']) ? $_GET['post_id'] : null;

        if (!$post_id) :
            return;
        endif;

        global $wpdb;
        $table_name = $wpdb->prefix . 'sbwc_email_upsell_conversions';

        // update send count in tracking table if present, else create new row and set send count to 1
        $wpdb->query($wpdb->prepare("INSERT INTO $table_name (post_id, click_count) VALUES (%d, %d) ON DUPLICATE KEY UPDATE click_count = click_count + 1", $post_id, 1));

        // update conversion_rate percentage
        $wpdb->update(
            $table_name,
            array('conversion_rate' => "(`click_count` / `send_count`) * 100"),
            array('post_id' => $post_id),
            array('%s'),
            array('%d')
        );

    endif;
});
