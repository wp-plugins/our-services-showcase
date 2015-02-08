<?php

/*
 * Short description
 * @author bilal hassan <info@smartcatdesign.net>
 * 
 */
$args = array(
    'post_type' => 'service',
    'meta_key' => 'sc_member_order',
    'orderby' => 'meta_value',
    'order' => 'ASC'
);
$services = new WP_Query($args);
?>
<div id="sc_our_services" class="widget">
    <?php
    if ($services->have_posts()) {
        while ($services->have_posts()) {
            $services->the_post();
//                            echo wp_get_attachment_url( get_post_thumbnail_id(get_the_ID() ));
            ?>
            <div itemscope itemtype="http://schema.org/Person" class="sc_service">
                <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
                    <?php if (has_post_thumbnail())
                            echo the_post_thumbnail('medium');
                        else
                            echo '<img src="' . SC_SERVICES_PATH .'img/noprofile.jpg" class="attachment-medium wp-post-image"/>';?>  
                </a>
                    <div class="sc_service_overlay">
                        <div itemprop="name" class="sc_service_name">
                            <?php the_title() ?>
                        </div>
                        <div itemprop="jobtitle" class="sc_service_jobtitle">
                            <?php echo get_post_meta(get_the_ID(), 'service_title', true); ?>
                        </div>
                        <div class='icons'>

                            <?php // the_content(); ?>
                            <?php
                            
                            $facebook = get_post_meta(get_the_ID(), 'service_facebook', true);
                            $twitter = get_post_meta(get_the_ID(), 'service_twitter', true);
                            $linkedin = get_post_meta(get_the_ID(), 'service_linkedin', true);
                            $gplus = get_post_meta(get_the_ID(), 'service_gplus', true);
                            $email = get_post_meta(get_the_ID(), 'service_email', true);
                            
                            


                            ?>

                        </div>
                    </div>
            </div>
        <?php
        }
    } else {
        echo 'There are no services members to display';
    }
    ?>
</div>
