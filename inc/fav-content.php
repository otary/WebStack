<?php if (!defined('ABSPATH')) {
    exit;
}

function randomAos()
{
    $i = mt_rand(0, 6);
    $aos = array(
        "fade-up", "fade-down", "fade-zoom-in", "fade-zoom-out",
       // "fade-left", "fade-right",
        //"flip-left",
       // "zoom-out-up", "zoom-out-down", "zoom-out",
        //"zoom-out-left", "zoom-out-right",
        //"zoom-in-right", "zoom-in-left",
        "zoom-in-down", "zoom-in-up", "zoom-in");
    return $aos[$i];
}

function fav_con($mid)
{ ?>
    <h4 class="text-gray" style="display: inline-block;"><i class="icon-io-tag" style="margin-right: 27px;"
                                                            id="<?php echo $mid->name; ?>"></i><?php echo $mid->name; ?>
    </h4>
    <?php
    $site_n = io_get_option('site_n');
    $category_count = $mid->category_count;
    $count = $site_n;
    if ($site_n == 0) $count = min(get_option('posts_per_page'), $category_count);
    if ($site_n >= 0 && $count < $category_count) {
        $link = esc_url(get_term_link($mid, 'res_category'));
        echo "<a class='btn-move' href='$link'>more+</a>";
    }
    ?>
    <div class="row">
        <?php
        //定义$post为全局变量，这样之后的输出就不会是同一篇文章了
        global $post;
        //下方的posts_per_page设置最为重要
        $args = array(
            'post_type' => 'sites',        //自定义文章类型，这里为sites
            'ignore_sticky_posts' => 1,              //忽略置顶文章
            'posts_per_page' => $site_n,        //显示的文章数量
            'meta_key' => '_sites_order',
            'orderby' => array('meta_value_num' => 'DESC', 'ID' => 'DESC'),
            'tax_query' => array(
                array(
                    'taxonomy' => 'favorites',       //分类法名称
                    'field' => 'id',              //根据分类法条款的什么字段查询，这里设置为ID
                    'terms' => $mid->term_id,     //分类法条款，输入分类的ID，多个ID使用数组：array(1,2)
                )
            ),
        );


        $myposts = new WP_Query($args);
        if (!$myposts->have_posts()): ?>
            <div class="col-lg-12">
                <div class="nothing">没有内容</div>
            </div>
        <?php
        elseif ($myposts->have_posts()): while ($myposts->have_posts()): $myposts->the_post();
            $link_url = get_post_meta($post->ID, '_sites_link', true);
            $default_ico = get_template_directory_uri() . '/images/favicon.png';
            if (current_user_can('level_10') || get_post_meta($post->ID, '_visible', true) != "true"):
                ?>
                <div data-aos="<?php echo randomAos() ?>"
                     class="xe-card <?php echo io_get_option('columns') ?> <?php echo get_post_meta($post->ID, '_wechat_qr', true) ? 'wechat' : '' ?>">
                    <?php include(get_theme_file_path() . '/templates/site-card.php'); ?>
                </div>
            <?php endif; endwhile; endif;
        wp_reset_postdata(); ?>
    </div>
    <br/>

<?php } ?>


