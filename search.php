<?php
/**
 * Search results template: unified image-left / copy-right layout for
 * every result type. Products also show price and a Product badge;
 * posts, pages, and events show title + date/author meta.
 */
get_header();
?>
<style>
.cb-search-item{display:flex;gap:20px;align-items:flex-start;margin-bottom:30px;}
.cb-search-thumb{flex:0 0 140px;}
.cb-search-thumb img{max-width:100%;height:auto;display:block;}
.cb-search-copy{flex:1;min-width:0;}
.cb-search-copy .entry-title-archive{margin-top:0;}
.cb-search-badge{display:inline-block;background:#111;color:#fff;font-size:11px;letter-spacing:1px;text-transform:uppercase;padding:3px 8px;border-radius:3px;margin-left:10px;vertical-align:middle;}
.cb-search-price{margin:6px 0;font-size:16px;}
@media (max-width:600px){.cb-search-item{flex-direction:column;}.cb-search-thumb{flex-basis:auto;}}
</style>
<div id="primary" class="content-area search-results">
    <div class="row">
        <div class="large-9 large-centered columns">
            <header class="page-header">
                <h1 class="page-title search-results-title"><span class="top-page-excerpt">Search Results for <span class="page-title"><?php echo esc_html(get_search_query()); ?></span></span></h1>
            </header>
        </div>
    </div>
    <div id="content" class="site-content" role="main">
        <div class="row">
            <div class="large-9 large-centered columns">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <?php if ('product' === get_post_type() && function_exists('wc_get_product')) :
                    $cb_product = wc_get_product(get_the_ID());
                ?>
                <div class="search_result_item cb-search-item">
                    <?php if (has_post_thumbnail()) : ?>
                    <div class="cb-search-thumb">
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
                    </div>
                    <?php endif; ?>
                    <div class="cb-search-copy">
                        <h1 class="entry-title-archive">
                            <a href="<?php the_permalink(); ?>" class="thumbnail_archive"><span><?php the_title(); ?></span></a>
                            <span class="cb-search-badge">Product</span>
                        </h1>
                        <?php if ($cb_product) : ?>
                            <div class="cb-search-price"><?php echo wp_kses_post($cb_product->get_price_html()); ?></div>
                        <?php endif; ?>
                        <div class="post_meta_archive"> on <a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark"><time class="entry-date" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time></a></div>
                    </div>
                </div>
                <?php else : ?>
                <div class="search_result_item cb-search-item">
                    <?php if (has_post_thumbnail()) : ?>
                    <div class="cb-search-thumb">
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
                    </div>
                    <?php endif; ?>
                    <div class="cb-search-copy">
                        <h1 class="entry-title-archive">
                            <a href="<?php the_permalink(); ?>" class="thumbnail_archive"><span><?php the_title(); ?></span></a>
                        </h1>
                        <div class="post_meta_archive">
                            <?php if ('post' === get_post_type()) : ?>
                                by <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" title="View all posts by <?php echo esc_attr(get_the_author()); ?>" rel="author"><?php the_author(); ?></a>
                            <?php endif; ?>
                            on <a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark"><time class="entry-date" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time></a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            <?php endwhile; ?>
                <?php the_posts_pagination(); ?>
            <?php else : ?>
                <div class="search_result_item">
                    <p>No results found for "<?php echo esc_html(get_search_query()); ?>". Try a different search.</p>
                </div>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
