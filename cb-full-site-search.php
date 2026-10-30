<?php
/**
 * Plugin Name: Captain Baltimore Full Site Search
 * Description: Turns the nav search from products-only into a full-site search, with matching products listed first, then other matching posts, pages, and events. Every result uses a unified image-left / copy-right layout; products also show price and a Product badge.
 * Version: 1.4.0
 * Author: Captain Baltimore
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Remove the products-only restriction from a search form's HTML.
 */
function cb_full_site_search_form($form) {
    // Drop the hidden post_type=product field (tolerant of spacing/quoting).
    $form = preg_replace(
        '/<input[^>]*name=(["\'])post_type\1[^>]*value=(["\'])product\2[^>]*\/?>/i',
        '',
        $form
    );
    // The placeholder should no longer promise products only.
    $form = str_replace('Search products&hellip;', 'Search&hellip;', $form);
    $form = str_replace('Search products...', 'Search...', $form);
    return $form;
}
add_filter('get_product_search_form', 'cb_full_site_search_form');
add_filter('get_search_form', 'cb_full_site_search_form');

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script(
        'cb-full-site-search',
        plugins_url('site-search.js', __FILE__),
        [],
        '1.4.0',
        true
    );
});

/**
 * Order front-end site searches: matching products first, then everything
 * else by date. Only the main search query, and never the shop's own
 * product search (which explicitly requests post_type=product).
 */
add_action('pre_get_posts', function ($query) {
    if (is_admin() || !$query->is_main_query() || !$query->is_search()) {
        return;
    }
    if ($query->get('post_type') !== 'product') {
        $query->set('cb_products_first', true);
    }
});
add_filter('posts_orderby', function ($orderby, $query) {
    if (!$query->get('cb_products_first')) {
        return $orderby;
    }
    global $wpdb;
    return "CASE WHEN {$wpdb->posts}.post_type = 'product' THEN 0 ELSE 1 END, {$wpdb->posts}.post_date DESC";
}, 10, 2);

/**
 * Render search results so products look like products: thumbnail, price,
 * and a Product badge. Everything else keeps the theme's title/date style.
 */
add_filter('search_template', function ($template) {
    return plugin_dir_path(__FILE__) . 'search.php';
});
