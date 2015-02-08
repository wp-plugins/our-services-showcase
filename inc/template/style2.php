<?php
/*
 * Short description
 * @author bilal hassan <info@smartcatdesign.net>
 * 
 */
$args = $this->sc_get_args( $group );
$members = new WP_Query( $args );
?>
<div id="sc_our_services" class="<?php echo $template == '' ? $this->options[ 'template' ] : $template; ?>">
    <div class="clear"></div>
    <?php
    if ( $members->have_posts() ) {
        while ( $members->have_posts() ) {
            $members->the_post(); ?>
            <div class="sc_service sc-col-sm-4 center">

                <a href="<?php the_permalink() ?>" rel="bookmark" class=""> 
                    <?php echo '<i class="' . get_post_meta( get_the_ID(), 'service_icon', TRUE ) . '"></i>'; ?> 
                </a>

                <?php //if( 'yes' == $this->options['name'] ) : ?>
                <div itemprop="name" class="sc_service_name">
                    <a href="<?php the_permalink() ?>" rel="bookmark" >                            
                        <?php the_title() ?>
                    </a>
                </div>
                <?php //endif; ?>


                <div class="sc_services_content">
                    <?php echo wp_trim_words( get_the_content(), 10 ); ?>
                </div>

                <?php if ( count( $this->options['link_text'] ) ) : ?>
                    <div class="sc_services_read_more">
                        <a href="<?php echo the_permalink(); ?>"><?php echo $this->options['link_text']; ?></a>
                    </div>
                <?php endif; ?>


            </div>
            <?php
        }
    } else {
        echo 'There are no services members to display';
    }
    ?>
    <div class="clear"></div>
</div>
