<?php
/*
 * Short description
 * @author bilal hassan <info@smartcatdesign.net>
 * 
 */
$args = $this->sc_get_args( $group );
$members = new WP_Query( $args );
?>
<div id="sc_our_services" class="smartcat_<?php echo $template == '' ? $this->options[ 'template' ] : $template; ?>">
    <div class="clear"></div>
    <?php
    if ( $members->have_posts() ) {
        while ( $members->have_posts() ) {
            $members->the_post();
            ?>
            <div class="sc_service sc-col-sm-4">
                <div class="sc_service_inner">
                    
                    <a href="<?php the_permalink() ?>" rel="bookmark" class=""> 
                        <?php
                        if ( has_post_thumbnail() )
                            echo the_post_thumbnail( 'medium' );
                        else {
                            echo '<img src="' . SC_SERVICES_URL . 'inc/img/noprofile.jpg" class="attachment-medium wp-post-image"/>';
                        }
                        ?>
                        <img src="<?php echo SC_SERVICES_URL . 'inc/img/more.png' ?>" class="sc_services_more"/>  
                        <span class="sc-overlay"></span>
                    </a>

                    <?php //if( 'yes' == $this->options['name'] ) : ?>
                    <div itemprop="name" class="sc_service_name">
                        <a href="<?php the_permalink() ?>" rel="bookmark" >                            
                            <?php the_title() ?>
                        </a>
                    </div>
                    <?php //endif; ?>


<!--                    <div class="sc_services_content">
                        <?php echo wp_trim_words( get_the_content(), 10 ); ?>
                    </div>-->



                </div>
            </div>
            <?php
        }
    } else {
        echo 'There are no services members to display';
    }
    wp_reset_postdata();
    ?>
    <div class="clear"></div>
</div>
