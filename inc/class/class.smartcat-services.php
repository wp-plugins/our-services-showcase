<?php

function sc_services_update_order() {
    $post_id = $_POST[ 'id' ];
    $sc_member_order = $_POST[ 'sc_member_order' ];
    update_post_meta( $post_id, 'sc_member_order', $sc_member_order );
    exit();
}

add_action( 'wp_ajax_sc_services_update_order', 'sc_services_update_order' );
add_action( 'wp_ajax_sc_services_update_order', 'sc_services_update_order' );

class SmartcatServicesPlugin {

    const VERSION = '1.2';
    const NAME = 'Our Services Showcase';

    private static $instance;
    private $options;

    public static function instance() {
        if ( !self::$instance ) :
            self::$instance = new self;
            self::$instance->get_options();
            self::$instance->add_hooks();
        endif;
    }

    public static function activate() {

        $options = array(
            'template'              => 'icons',
            'title'                 => 'yes',
            'profile_link'          => 'yes',
            'title_size'            => 20,
            'member_count'          => -1,
            'text_color'            => '1F7DCF',
            'main_color'            => '1F7DCF',
            'accent_color'            => '1F7DCF',
            'margin'                => 5,
            'height'                => 400,
            'link_text'             => 'Click Here >>',
            'single_template'       => 'standard',
            'redirect'              => true,
            'word_count'            => 10,

        );

        if ( !get_option( 'smartcat_services_options' ) ) {
            add_option( 'smartcat_services_options', $options );
            $options[ 'redirect' ] = true;
            update_option( 'smartcat_services_options', $options );
        }


    }

    public static function deactivate() {
        
    }

    private function add_hooks() {
        add_action( 'init', array( $this, 'services' ) );
        add_action( 'init', array( $this, 'localize' ) );
        add_action( 'init', array( $this, 'service_positions' ), 0 );
        add_action( 'admin_init', array( $this, 'smartcat_services_activation_redirect' ) );
        add_action( 'admin_menu', array( $this, 'smartcat_services_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'smartcat_services_load_admin_styles_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'smartcat_services_load_styles_scripts' ) );
        add_shortcode( 'our-services', array( $this, 'set_our_services' ) );
        add_action( 'add_meta_boxes', array( $this, 'smartcat_service_info_box' ) );
        add_action( 'save_post', array( $this, 'service_box_save' ) );
        add_action( 'wp_ajax_smartcat_services_update_pm', array( $this, 'smartcat_services_update_order' ) );
        add_action( 'wp_head', array( $this, 'sc_custom_styles' ) );
        
    }

    private function get_options() {
        if ( get_option( 'smartcat_services_options' ) ) :
            $this->options = get_option( 'smartcat_services_options' );
        endif;
    }

    /**
     * @todo check if redirect option is set and redirect
     */
    public function smartcat_services_activation_redirect() {
        if ( $this->options[ 'redirect' ] ) :
            $old_val = $this->options;
            $old_val[ 'redirect' ] = false;
            update_option( 'smartcat_services_options', $old_val );
            wp_safe_redirect( admin_url( 'edit.php?post_type=service&page=smartcat_services_settings' ) );
        endif;
    }

    public function localize() {
        load_plugin_textdomain( 'smartcat-services', false, dirname( plugin_basename( __FILE__ ) ) );
    }

    public function smartcat_services_menu() {

        add_submenu_page( 'edit.php?post_type=service', 'Settings', 'Settings', 'administrator', 'smartcat_services_settings', array( $this, 'smartcat_services_settings' ) );
        add_submenu_page( 'edit.php?post_type=service', 'Re-Order Services', 'Re-Order Services', 'administrator', 'smartcat_services_reorder', array( $this, 'smartcat_services_reorder' ) );
    }

    public function smartcat_services_reorder() {
        include_once SC_SERVICES_PATH . 'admin/reorder.php';
    }

    public function smartcat_services_settings() {

        if ( isset( $_REQUEST[ 'sc_our_services_save' ] ) && $_REQUEST[ 'sc_our_services_save' ] == 'Update' ) :
            update_option( 'smartcat_services_options', $_REQUEST[ 'smartcat_services_options' ] );
        endif;

        include_once SC_SERVICES_PATH . 'admin/options.php';
    }

    public function smartcat_services_load_admin_styles_scripts( $hook ) {
        wp_enqueue_style( 'smartcat_services_admin_style', SC_SERVICES_URL . 'inc/style/sc_our_services_admin.css' );
        wp_enqueue_style( 'smartcat_services_fontawesome', SC_SERVICES_URL . 'inc/style/font-awesome.min.css', false, self::VERSION );

        wp_enqueue_script( 'smartcat_services_color_script', SC_SERVICES_URL . 'inc/script/jscolor/jscolor.js', array( 'jquery' ) );
        wp_enqueue_script( 'smartcat_services_script', SC_SERVICES_URL . 'inc/script/sc_our_services_admin.js', array( 'jquery' ) );
    }

    function smartcat_services_load_styles_scripts() {

        // plugin main style
        wp_enqueue_style( 'smartcat_services_default_style', SC_SERVICES_URL . 'inc/style/sc_our_services.css', false, self::VERSION );
        wp_enqueue_style( 'smartcat_services_fontawesome', SC_SERVICES_URL . 'inc/style/font-awesome.min.css', false, self::VERSION );
        wp_enqueue_style( 'smartcat_services_animate', SC_SERVICES_URL . 'inc/style/animate.min.css', false, self::VERSION );

        // plugin main script
        wp_enqueue_script( 'smartcat_services_default_script', SC_SERVICES_URL . 'inc/script/sc_our_services.js', array( 'jquery' ), self::VERSION );
    }

    function set_our_services( $atts ) {
        extract( shortcode_atts( array(
            'group'         => '',
            'template'      => '',
                        ), $atts ) );
        global $content;

        ob_start();

        

        if ( $template == '' ) :
            if ( $this->options[ 'template' ] === false or $this->options[ 'template' ] == '' ) :
                include SC_SERVICES_PATH . 'inc/template/icons.php';
            else :
                include SC_SERVICES_PATH . 'inc/template/' . $this->options[ 'template' ] . '.php';
            endif;
        else :
            include SC_SERVICES_PATH . 'inc/template/' . $template . '.php';
        endif;

        $output = ob_get_clean();
        return $output;
    }

    function services() {
        $labels = array(
            'name' => _x( 'Services', 'smartcat-services' ),
            'singular_name' => _x( 'Services Member', 'smartcat-services' ),
            'add_new' => _x( 'Add New', 'smartcat-services' ),
            'add_new_item' => __( 'Add New Service', 'smartcat-services' ),
            'edit_item' => __( 'Edit Service', 'smartcat-services' ),
            'new_item' => __( 'New Service', 'smartcat-services' ),
            'all_items' => __( 'All Services', 'smartcat-services' ),
            'view_item' => __( 'View Services', 'smartcat-services' ),
            'search_items' => __( 'Search Services', 'smartcat-services' ),
            'not_found' => __( 'No service found', 'smartcat-services' ),
            'not_found_in_trash' => __( 'No service found in the Trash', 'smartcat-services' ),
            'parent_item_colon' => '',
            'menu_name' => 'Our Services'
        );
        $args = array(
            'labels' => $labels,
            'description' => __( 'Holds our services specific data', 'smartcat-services' ),
            'public' => true,
            'menu_icon' => SC_SERVICES_URL . 'inc/img/icon.png',
            'supports' => array( 'title', 'editor', 'thumbnail' ),
            'has_archive' => false,
        );
        register_post_type( 'service', $args );
        flush_rewrite_rules();
    }

    public function service_positions() {
        $labels = array(
            'name' => _x( 'Categories', 'taxonomy general name' ),
            'singular_name' => _x( 'Group', 'taxonomy singular name' ),
            'search_items' => __( 'Search Categories' ),
            'all_items' => __( 'All Categories' ),
            'parent_item' => __( 'Parent Category' ),
            'parent_item_colon' => __( 'Parent Category:' ),
            'edit_item' => __( 'Edit Category' ),
            'update_item' => __( 'Update Category' ),
            'add_new_item' => __( 'Add New Category' ),
            'new_item_name' => __( 'New Service Category' ),
            'menu_name' => __( 'Category' ),
        );
        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
        );
        register_taxonomy( 'service_position', 'service', $args );
    }

    public function smartcat_service_info_box() {

//        add_meta_box(
//                'smartcat_service_info_box', __( 'Additional Information', 'myplugin_textdomain' ), array( $this, 'smartcat_service_info_box_content' ), 'service', 'normal', 'high'
//        );

        add_meta_box(
                'smartcat_service_icons_box', __( 'Icon', 'smartcat-services' ), array( $this, 'smartcat_service_icon_box_content' ), 'service', 'side', 'high'
        );
    }

    public function smartcat_service_icon_box_content( $post ) {
        echo 'Current Icon: <i class="fa-2x ' . get_post_meta( $post->ID, 'service_icon', TRUE ) . '"></i><br><hr>'
        . '<input type="hidden" id="service_icon" name="service_icon" value="' . get_post_meta( $post->ID, 'service_icon', TRUE ) . '" />';
        echo '<div id="sc_services_icons">';
        echo '<i class="fa fa-search"></i><i class="fa fa-times hide"></i><i class="fa fa-glass"></i><i class="fa fa-music"></i><i class="fa fa-search"></i><i class="fa fa-envelope-o"></i><i class="fa fa-heart"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-user"></i><i class="fa fa-film"></i><i class="fa fa-th-large"></i><i class="fa fa-th"></i><i class="fa fa-th-list"></i><i class="fa fa-check"></i><i class="fa fa-times"></i><i class="fa fa-search-plus"></i><i class="fa fa-search-minus"></i><i class="fa fa-power-off"></i><i class="fa fa-signal"></i><i class="fa fa-cog"></i><i class="fa fa-trash-o"></i><i class="fa fa-home"></i><i class="fa fa-file-o"></i><i class="fa fa-clock-o"></i><i class="fa fa-road"></i><i class="fa fa-download"></i><i class="fa fa-arrow-circle-o-down"></i><i class="fa fa-arrow-circle-o-up"></i><i class="fa fa-inbox"></i><i class="fa fa-play-circle-o"></i><i class="fa fa-repeat"></i><i class="fa fa-refresh"></i><i class="fa fa-list-alt"></i><i class="fa fa-lock"></i><i class="fa fa-flag"></i><i class="fa fa-headphones"></i><i class="fa fa-volume-off"></i><i class="fa fa-volume-down"></i><i class="fa fa-volume-up"></i><i class="fa fa-qrcode"></i><i class="fa fa-barcode"></i><i class="fa fa-tag"></i><i class="fa fa-tags"></i><i class="fa fa-book"></i><i class="fa fa-bookmark"></i><i class="fa fa-print"></i><i class="fa fa-camera"></i><i class="fa fa-font"></i><i class="fa fa-bold"></i><i class="fa fa-italic"></i><i class="fa fa-text-height"></i><i class="fa fa-text-width"></i><i class="fa fa-align-left"></i><i class="fa fa-align-center"></i><i class="fa fa-align-right"></i><i class="fa fa-align-justify"></i><i class="fa fa-list"></i><i class="fa fa-outdent"></i><i class="fa fa-indent"></i><i class="fa fa-video-camera"></i><i class="fa fa-picture-o"></i><i class="fa fa-pencil"></i><i class="fa fa-map-marker"></i><i class="fa fa-adjust"></i><i class="fa fa-tint"></i><i class="fa fa-pencil-square-o"></i><i class="fa fa-share-square-o"></i><i class="fa fa-check-square-o"></i><i class="fa fa-arrows"></i><i class="fa fa-step-backward"></i><i class="fa fa-fast-backward"></i><i class="fa fa-backward"></i><i class="fa fa-play"></i><i class="fa fa-pause"></i><i class="fa fa-stop"></i><i class="fa fa-forward"></i><i class="fa fa-fast-forward"></i><i class="fa fa-step-forward"></i><i class="fa fa-eject"></i><i class="fa fa-chevron-left"></i><i class="fa fa-chevron-right"></i><i class="fa fa-plus-circle"></i><i class="fa fa-minus-circle"></i><i class="fa fa-times-circle"></i><i class="fa fa-check-circle"></i><i class="fa fa-question-circle"></i><i class="fa fa-info-circle"></i><i class="fa fa-crosshairs"></i><i class="fa fa-times-circle-o"></i><i class="fa fa-check-circle-o"></i><i class="fa fa-ban"></i><i class="fa fa-arrow-left"></i><i class="fa fa-arrow-right"></i><i class="fa fa-arrow-up"></i><i class="fa fa-arrow-down"></i><i class="fa fa-share"></i><i class="fa fa-expand"></i><i class="fa fa-compress"></i><i class="fa fa-plus"></i><i class="fa fa-minus"></i><i class="fa fa-asterisk"></i><i class="fa fa-exclamation-circle"></i><i class="fa fa-gift"></i><i class="fa fa-leaf"></i><i class="fa fa-fire"></i><i class="fa fa-eye"></i><i class="fa fa-eye-slash"></i><i class="fa fa-exclamation-triangle"></i><i class="fa fa-plane"></i><i class="fa fa-calendar"></i><i class="fa fa-random"></i><i class="fa fa-comment"></i><i class="fa fa-magnet"></i><i class="fa fa-chevron-up"></i><i class="fa fa-chevron-down"></i><i class="fa fa-retweet"></i><i class="fa fa-shopping-cart"></i><i class="fa fa-folder"></i><i class="fa fa-folder-open"></i><i class="fa fa-arrows-v"></i><i class="fa fa-arrows-h"></i><i class="fa fa-bar-chart"></i><i class="fa fa-twitter-square"></i><i class="fa fa-facebook-square"></i><i class="fa fa-camera-retro"></i><i class="fa fa-key"></i><i class="fa fa-cogs"></i><i class="fa fa-comments"></i><i class="fa fa-thumbs-o-up"></i><i class="fa fa-thumbs-o-down"></i><i class="fa fa-star-half"></i><i class="fa fa-heart-o"></i><i class="fa fa-sign-out"></i><i class="fa fa-linkedin-square"></i><i class="fa fa-thumb-tack"></i><i class="fa fa-external-link"></i><i class="fa fa-sign-in"></i><i class="fa fa-trophy"></i><i class="fa fa-github-square"></i><i class="fa fa-upload"></i><i class="fa fa-lemon-o"></i><i class="fa fa-phone"></i><i class="fa fa-square-o"></i><i class="fa fa-bookmark-o"></i><i class="fa fa-phone-square"></i><i class="fa fa-twitter"></i><i class="fa fa-facebook"></i><i class="fa fa-github"></i><i class="fa fa-unlock"></i><i class="fa fa-credit-card"></i><i class="fa fa-rss"></i><i class="fa fa-hdd-o"></i><i class="fa fa-bullhorn"></i><i class="fa fa-bell"></i><i class="fa fa-certificate"></i><i class="fa fa-hand-o-right"></i><i class="fa fa-hand-o-left"></i><i class="fa fa-hand-o-up"></i><i class="fa fa-hand-o-down"></i><i class="fa fa-arrow-circle-left"></i><i class="fa fa-arrow-circle-right"></i><i class="fa fa-arrow-circle-up"></i><i class="fa fa-arrow-circle-down"></i><i class="fa fa-globe"></i><i class="fa fa-wrench"></i><i class="fa fa-tasks"></i><i class="fa fa-filter"></i><i class="fa fa-briefcase"></i><i class="fa fa-arrows-alt"></i><i class="fa fa-users"></i><i class="fa fa-link"></i><i class="fa fa-cloud"></i><i class="fa fa-flask"></i><i class="fa fa-scissors"></i><i class="fa fa-files-o"></i><i class="fa fa-paperclip"></i><i class="fa fa-floppy-o"></i><i class="fa fa-square"></i><i class="fa fa-bars"></i><i class="fa fa-list-ul"></i><i class="fa fa-list-ol"></i><i class="fa fa-strikethrough"></i><i class="fa fa-underline"></i><i class="fa fa-table"></i><i class="fa fa-magic"></i><i class="fa fa-truck"></i><i class="fa fa-pinterest"></i><i class="fa fa-pinterest-square"></i><i class="fa fa-google-plus-square"></i><i class="fa fa-google-plus"></i><i class="fa fa-money"></i><i class="fa fa-caret-down"></i><i class="fa fa-caret-up"></i><i class="fa fa-caret-left"></i><i class="fa fa-caret-right"></i><i class="fa fa-columns"></i><i class="fa fa-sort"></i><i class="fa fa-sort-desc"></i><i class="fa fa-sort-asc"></i><i class="fa fa-envelope"></i><i class="fa fa-linkedin"></i><i class="fa fa-undo"></i><i class="fa fa-gavel"></i><i class="fa fa-tachometer"></i><i class="fa fa-comment-o"></i><i class="fa fa-comments-o"></i><i class="fa fa-bolt"></i><i class="fa fa-sitemap"></i><i class="fa fa-umbrella"></i><i class="fa fa-clipboard"></i><i class="fa fa-lightbulb-o"></i><i class="fa fa-exchange"></i><i class="fa fa-cloud-download"></i><i class="fa fa-cloud-upload"></i><i class="fa fa-user-md"></i><i class="fa fa-stethoscope"></i><i class="fa fa-suitcase"></i><i class="fa fa-bell-o"></i><i class="fa fa-coffee"></i><i class="fa fa-cutlery"></i><i class="fa fa-file-text-o"></i><i class="fa fa-building-o"></i><i class="fa fa-hospital-o"></i><i class="fa fa-ambulance"></i><i class="fa fa-medkit"></i><i class="fa fa-fighter-jet"></i><i class="fa fa-beer"></i><i class="fa fa-h-square"></i><i class="fa fa-plus-square"></i><i class="fa fa-angle-double-left"></i><i class="fa fa-angle-double-right"></i><i class="fa fa-angle-double-up"></i><i class="fa fa-angle-double-down"></i><i class="fa fa-angle-left"></i><i class="fa fa-angle-right"></i><i class="fa fa-angle-up"></i><i class="fa fa-angle-down"></i><i class="fa fa-desktop"></i><i class="fa fa-laptop"></i><i class="fa fa-tablet"></i><i class="fa fa-mobile"></i><i class="fa fa-circle-o"></i><i class="fa fa-quote-left"></i><i class="fa fa-quote-right"></i><i class="fa fa-spinner"></i><i class="fa fa-circle"></i><i class="fa fa-reply"></i><i class="fa fa-github-alt"></i><i class="fa fa-folder-o"></i><i class="fa fa-folder-open-o"></i><i class="fa fa-smile-o"></i><i class="fa fa-frown-o"></i><i class="fa fa-meh-o"></i><i class="fa fa-gamepad"></i><i class="fa fa-keyboard-o"></i><i class="fa fa-flag-o"></i><i class="fa fa-flag-checkered"></i><i class="fa fa-terminal"></i><i class="fa fa-code"></i><i class="fa fa-reply-all"></i><i class="fa fa-star-half-o"></i><i class="fa fa-location-arrow"></i><i class="fa fa-crop"></i><i class="fa fa-code-fork"></i><i class="fa fa-chain-broken"></i><i class="fa fa-question"></i><i class="fa fa-info"></i><i class="fa fa-exclamation"></i><i class="fa fa-superscript"></i><i class="fa fa-subscript"></i><i class="fa fa-eraser"></i><i class="fa fa-puzzle-piece"></i><i class="fa fa-microphone"></i><i class="fa fa-microphone-slash"></i><i class="fa fa-shield"></i><i class="fa fa-calendar-o"></i><i class="fa fa-fire-extinguisher"></i><i class="fa fa-rocket"></i><i class="fa fa-maxcdn"></i><i class="fa fa-chevron-circle-left"></i><i class="fa fa-chevron-circle-right"></i><i class="fa fa-chevron-circle-up"></i><i class="fa fa-chevron-circle-down"></i><i class="fa fa-html5"></i><i class="fa fa-css3"></i><i class="fa fa-anchor"></i><i class="fa fa-unlock-alt"></i><i class="fa fa-bullseye"></i><i class="fa fa-ellipsis-h"></i><i class="fa fa-ellipsis-v"></i><i class="fa fa-rss-square"></i><i class="fa fa-play-circle"></i><i class="fa fa-ticket"></i><i class="fa fa-minus-square"></i><i class="fa fa-minus-square-o"></i><i class="fa fa-level-up"></i><i class="fa fa-level-down"></i><i class="fa fa-check-square"></i><i class="fa fa-pencil-square"></i><i class="fa fa-external-link-square"></i><i class="fa fa-share-square"></i><i class="fa fa-compass"></i><i class="fa fa-caret-square-o-down"></i><i class="fa fa-caret-square-o-up"></i><i class="fa fa-caret-square-o-right"></i><i class="fa fa-eur"></i><i class="fa fa-gbp"></i><i class="fa fa-usd"></i><i class="fa fa-inr"></i><i class="fa fa-jpy"></i><i class="fa fa-rub"></i><i class="fa fa-krw"></i><i class="fa fa-btc"></i><i class="fa fa-file"></i><i class="fa fa-file-text"></i><i class="fa fa-sort-alpha-asc"></i><i class="fa fa-sort-alpha-desc"></i><i class="fa fa-sort-amount-asc"></i><i class="fa fa-sort-amount-desc"></i><i class="fa fa-sort-numeric-asc"></i><i class="fa fa-sort-numeric-desc"></i><i class="fa fa-thumbs-up"></i><i class="fa fa-thumbs-down"></i><i class="fa fa-youtube-square"></i><i class="fa fa-youtube"></i><i class="fa fa-xing"></i><i class="fa fa-xing-square"></i><i class="fa fa-youtube-play"></i><i class="fa fa-dropbox"></i><i class="fa fa-stack-overflow"></i><i class="fa fa-instagram"></i><i class="fa fa-flickr"></i><i class="fa fa-adn"></i><i class="fa fa-bitbucket"></i><i class="fa fa-bitbucket-square"></i><i class="fa fa-tumblr"></i><i class="fa fa-tumblr-square"></i><i class="fa fa-long-arrow-down"></i><i class="fa fa-long-arrow-up"></i><i class="fa fa-long-arrow-left"></i><i class="fa fa-long-arrow-right"></i><i class="fa fa-apple"></i><i class="fa fa-windows"></i><i class="fa fa-android"></i><i class="fa fa-linux"></i><i class="fa fa-dribbble"></i><i class="fa fa-skype"></i><i class="fa fa-foursquare"></i><i class="fa fa-trello"></i><i class="fa fa-female"></i><i class="fa fa-male"></i><i class="fa fa-gratipay"></i><i class="fa fa-sun-o"></i><i class="fa fa-moon-o"></i><i class="fa fa-archive"></i><i class="fa fa-bug"></i><i class="fa fa-vk"></i><i class="fa fa-weibo"></i><i class="fa fa-renren"></i><i class="fa fa-pagelines"></i><i class="fa fa-stack-exchange"></i><i class="fa fa-arrow-circle-o-right"></i><i class="fa fa-arrow-circle-o-left"></i><i class="fa fa-caret-square-o-left"></i><i class="fa fa-dot-circle-o"></i><i class="fa fa-wheelchair"></i><i class="fa fa-vimeo-square"></i><i class="fa fa-try"></i><i class="fa fa-plus-square-o"></i><i class="fa fa-space-shuttle"></i><i class="fa fa-slack"></i><i class="fa fa-envelope-square"></i><i class="fa fa-wordpress"></i><i class="fa fa-openid"></i><i class="fa fa-university"></i><i class="fa fa-graduation-cap"></i><i class="fa fa-yahoo"></i><i class="fa fa-google"></i><i class="fa fa-reddit"></i><i class="fa fa-reddit-square"></i><i class="fa fa-stumbleupon-circle"></i><i class="fa fa-stumbleupon"></i><i class="fa fa-delicious"></i><i class="fa fa-digg"></i><i class="fa fa-pied-piper"></i><i class="fa fa-pied-piper-alt"></i><i class="fa fa-drupal"></i><i class="fa fa-joomla"></i><i class="fa fa-language"></i><i class="fa fa-fax"></i><i class="fa fa-building"></i><i class="fa fa-child"></i><i class="fa fa-paw"></i><i class="fa fa-spoon"></i><i class="fa fa-cube"></i><i class="fa fa-cubes"></i><i class="fa fa-behance"></i><i class="fa fa-behance-square"></i><i class="fa fa-steam"></i><i class="fa fa-steam-square"></i><i class="fa fa-recycle"></i><i class="fa fa-car"></i><i class="fa fa-taxi"></i><i class="fa fa-tree"></i><i class="fa fa-spotify"></i><i class="fa fa-deviantart"></i><i class="fa fa-soundcloud"></i><i class="fa fa-database"></i><i class="fa fa-file-pdf-o"></i><i class="fa fa-file-word-o"></i><i class="fa fa-file-excel-o"></i><i class="fa fa-file-powerpoint-o"></i><i class="fa fa-file-image-o"></i><i class="fa fa-file-archive-o"></i><i class="fa fa-file-audio-o"></i><i class="fa fa-file-video-o"></i><i class="fa fa-file-code-o"></i><i class="fa fa-vine"></i><i class="fa fa-codepen"></i><i class="fa fa-jsfiddle"></i><i class="fa fa-life-ring"></i><i class="fa fa-circle-o-notch"></i><i class="fa fa-rebel"></i><i class="fa fa-empire"></i><i class="fa fa-git-square"></i><i class="fa fa-git"></i><i class="fa fa-hacker-news"></i><i class="fa fa-tencent-weibo"></i><i class="fa fa-qq"></i><i class="fa fa-weixin"></i><i class="fa fa-paper-plane"></i><i class="fa fa-paper-plane-o"></i><i class="fa fa-history"></i><i class="fa fa-circle-thin"></i><i class="fa fa-header"></i><i class="fa fa-paragraph"></i><i class="fa fa-sliders"></i><i class="fa fa-share-alt"></i><i class="fa fa-share-alt-square"></i><i class="fa fa-bomb"></i><i class="fa fa-futbol-o"></i><i class="fa fa-tty"></i><i class="fa fa-binoculars"></i><i class="fa fa-plug"></i><i class="fa fa-slideshare"></i><i class="fa fa-twitch"></i><i class="fa fa-yelp"></i><i class="fa fa-newspaper-o"></i><i class="fa fa-wifi"></i><i class="fa fa-calculator"></i><i class="fa fa-paypal"></i><i class="fa fa-google-wallet"></i><i class="fa fa-cc-visa"></i><i class="fa fa-cc-mastercard"></i><i class="fa fa-cc-discover"></i><i class="fa fa-cc-amex"></i><i class="fa fa-cc-paypal"></i><i class="fa fa-cc-stripe"></i><i class="fa fa-bell-slash"></i><i class="fa fa-bell-slash-o"></i><i class="fa fa-trash"></i><i class="fa fa-copyright"></i><i class="fa fa-at"></i><i class="fa fa-eyedropper"></i><i class="fa fa-paint-brush"></i><i class="fa fa-birthday-cake"></i><i class="fa fa-area-chart"></i><i class="fa fa-pie-chart"></i><i class="fa fa-line-chart"></i><i class="fa fa-lastfm"></i><i class="fa fa-lastfm-square"></i><i class="fa fa-toggle-off"></i><i class="fa fa-toggle-on"></i><i class="fa fa-bicycle"></i><i class="fa fa-bus"></i><i class="fa fa-ioxhost"></i><i class="fa fa-angellist"></i><i class="fa fa-cc"></i><i class="fa fa-ils"></i><i class="fa fa-meanpath"></i><i class="fa fa-buysellads"></i><i class="fa fa-connectdevelop"></i><i class="fa fa-dashcube"></i><i class="fa fa-forumbee"></i><i class="fa fa-leanpub"></i><i class="fa fa-sellsy"></i><i class="fa fa-shirtsinbulk"></i><i class="fa fa-simplybuilt"></i><i class="fa fa-skyatlas"></i><i class="fa fa-cart-plus"></i><i class="fa fa-cart-arrow-down"></i><i class="fa fa-diamond"></i><i class="fa fa-ship"></i><i class="fa fa-user-secret"></i><i class="fa fa-motorcycle"></i><i class="fa fa-street-view"></i><i class="fa fa-heartbeat"></i><i class="fa fa-venus"></i><i class="fa fa-mars"></i><i class="fa fa-mercury"></i><i class="fa fa-transgender"></i><i class="fa fa-transgender-alt"></i><i class="fa fa-venus-double"></i><i class="fa fa-mars-double"></i><i class="fa fa-venus-mars"></i><i class="fa fa-mars-stroke"></i><i class="fa fa-mars-stroke-v"></i><i class="fa fa-mars-stroke-h"></i><i class="fa fa-neuter"></i><i class="fa fa-facebook-official"></i><i class="fa fa-pinterest-p"></i><i class="fa fa-whatsapp"></i><i class="fa fa-server"></i><i class="fa fa-user-plus"></i><i class="fa fa-user-times"></i><i class="fa fa-bed"></i><i class="fa fa-viacoin"></i><i class="fa fa-train"></i><i class="fa fa-subway"></i><i class="fa fa-medium"></i><i class="fa fa-warning margin-right-sm"></i><i class="fa fa-exclamation-circle margin-right-sm"></i><i class="fa fa-bed"></i><i class="fa fa-buysellads"></i><i class="fa fa-cart-arrow-down"></i><i class="fa fa-cart-plus"></i><i class="fa fa-connectdevelop"></i><i class="fa fa-dashcube"></i><i class="fa fa-diamond"></i><i class="fa fa-facebook-official"></i><i class="fa fa-forumbee"></i><i class="fa fa-heartbeat"></i><i class="fa fa-hotel"></i><i class="fa fa-leanpub"></i><i class="fa fa-mars"></i><i class="fa fa-mars-double"></i><i class="fa fa-mars-stroke"></i><i class="fa fa-mars-stroke-h"></i><i class="fa fa-mars-stroke-v"></i><i class="fa fa-medium"></i><i class="fa fa-mercury"></i><i class="fa fa-motorcycle"></i><i class="fa fa-neuter"></i><i class="fa fa-pinterest-p"></i><i class="fa fa-sellsy"></i><i class="fa fa-server"></i><i class="fa fa-ship"></i><i class="fa fa-shirtsinbulk"></i><i class="fa fa-simplybuilt"></i><i class="fa fa-skyatlas"></i><i class="fa fa-street-view"></i><i class="fa fa-subway"></i><i class="fa fa-train"></i><i class="fa fa-transgender"></i><i class="fa fa-transgender-alt"></i><i class="fa fa-user-plus"></i><i class="fa fa-user-secret"></i><i class="fa fa-user-times"></i><i class="fa fa-venus"></i><i class="fa fa-venus-double"></i><i class="fa fa-venus-mars"></i><i class="fa fa-viacoin"></i><i class="fa fa-whatsapp"></i><i class="fa fa-adjust"></i><i class="fa fa-anchor"></i><i class="fa fa-archive"></i><i class="fa fa-area-chart"></i><i class="fa fa-arrows"></i><i class="fa fa-arrows-h"></i><i class="fa fa-arrows-v"></i><i class="fa fa-asterisk"></i><i class="fa fa-at"></i><i class="fa fa-automobile"></i><i class="fa fa-ban"></i><i class="fa fa-bank"></i><i class="fa fa-bar-chart"></i><i class="fa fa-bar-chart-o"></i><i class="fa fa-barcode"></i><i class="fa fa-bars"></i><i class="fa fa-bed"></i><i class="fa fa-beer"></i><i class="fa fa-bell"></i><i class="fa fa-bell-o"></i><i class="fa fa-bell-slash"></i><i class="fa fa-bell-slash-o"></i><i class="fa fa-bicycle"></i><i class="fa fa-binoculars"></i><i class="fa fa-birthday-cake"></i><i class="fa fa-bolt"></i><i class="fa fa-bomb"></i><i class="fa fa-book"></i><i class="fa fa-bookmark"></i><i class="fa fa-bookmark-o"></i><i class="fa fa-briefcase"></i><i class="fa fa-bug"></i><i class="fa fa-building"></i><i class="fa fa-building-o"></i><i class="fa fa-bullhorn"></i><i class="fa fa-bullseye"></i><i class="fa fa-bus"></i><i class="fa fa-cab"></i><i class="fa fa-calculator"></i><i class="fa fa-calendar"></i><i class="fa fa-calendar-o"></i><i class="fa fa-camera"></i><i class="fa fa-camera-retro"></i><i class="fa fa-car"></i><i class="fa fa-caret-square-o-down"></i><i class="fa fa-caret-square-o-left"></i><i class="fa fa-caret-square-o-right"></i><i class="fa fa-caret-square-o-up"></i><i class="fa fa-cart-arrow-down"></i><i class="fa fa-cart-plus"></i><i class="fa fa-cc"></i><i class="fa fa-certificate"></i><i class="fa fa-check"></i><i class="fa fa-check-circle"></i><i class="fa fa-check-circle-o"></i><i class="fa fa-check-square"></i><i class="fa fa-check-square-o"></i><i class="fa fa-child"></i><i class="fa fa-circle"></i><i class="fa fa-circle-o"></i><i class="fa fa-circle-o-notch"></i><i class="fa fa-circle-thin"></i><i class="fa fa-clock-o"></i><i class="fa fa-close"></i><i class="fa fa-cloud"></i><i class="fa fa-cloud-download"></i><i class="fa fa-cloud-upload"></i><i class="fa fa-code"></i><i class="fa fa-code-fork"></i><i class="fa fa-coffee"></i><i class="fa fa-cog"></i><i class="fa fa-cogs"></i><i class="fa fa-comment"></i><i class="fa fa-comment-o"></i><i class="fa fa-comments"></i><i class="fa fa-comments-o"></i><i class="fa fa-compass"></i><i class="fa fa-copyright"></i><i class="fa fa-credit-card"></i><i class="fa fa-crop"></i><i class="fa fa-crosshairs"></i><i class="fa fa-cube"></i><i class="fa fa-cubes"></i><i class="fa fa-cutlery"></i><i class="fa fa-dashboard"></i><i class="fa fa-database"></i><i class="fa fa-desktop"></i><i class="fa fa-diamond"></i><i class="fa fa-dot-circle-o"></i><i class="fa fa-download"></i><i class="fa fa-edit"></i><i class="fa fa-ellipsis-h"></i><i class="fa fa-ellipsis-v"></i><i class="fa fa-envelope"></i><i class="fa fa-envelope-o"></i><i class="fa fa-envelope-square"></i><i class="fa fa-eraser"></i><i class="fa fa-exchange"></i><i class="fa fa-exclamation"></i><i class="fa fa-exclamation-circle"></i><i class="fa fa-exclamation-triangle"></i><i class="fa fa-external-link"></i><i class="fa fa-external-link-square"></i><i class="fa fa-eye"></i><i class="fa fa-eye-slash"></i><i class="fa fa-eyedropper"></i><i class="fa fa-fax"></i><i class="fa fa-female"></i><i class="fa fa-fighter-jet"></i><i class="fa fa-file-archive-o"></i><i class="fa fa-file-audio-o"></i><i class="fa fa-file-code-o"></i><i class="fa fa-file-excel-o"></i><i class="fa fa-file-image-o"></i><i class="fa fa-file-movie-o"></i><i class="fa fa-file-pdf-o"></i><i class="fa fa-file-photo-o"></i><i class="fa fa-file-picture-o"></i><i class="fa fa-file-powerpoint-o"></i><i class="fa fa-file-sound-o"></i><i class="fa fa-file-video-o"></i><i class="fa fa-file-word-o"></i><i class="fa fa-file-zip-o"></i><i class="fa fa-film"></i><i class="fa fa-filter"></i><i class="fa fa-fire"></i><i class="fa fa-fire-extinguisher"></i><i class="fa fa-flag"></i><i class="fa fa-flag-checkered"></i><i class="fa fa-flag-o"></i><i class="fa fa-flash"></i><i class="fa fa-flask"></i><i class="fa fa-folder"></i><i class="fa fa-folder-o"></i><i class="fa fa-folder-open"></i><i class="fa fa-folder-open-o"></i><i class="fa fa-frown-o"></i><i class="fa fa-futbol-o"></i><i class="fa fa-gamepad"></i><i class="fa fa-gavel"></i><i class="fa fa-gear"></i><i class="fa fa-gears"></i><i class="fa fa-genderless"></i><i class="fa fa-gift"></i><i class="fa fa-glass"></i><i class="fa fa-globe"></i><i class="fa fa-graduation-cap"></i><i class="fa fa-group"></i><i class="fa fa-hdd-o"></i><i class="fa fa-headphones"></i><i class="fa fa-heart"></i><i class="fa fa-heart-o"></i><i class="fa fa-heartbeat"></i><i class="fa fa-history"></i><i class="fa fa-home"></i><i class="fa fa-hotel"></i><i class="fa fa-image"></i><i class="fa fa-inbox"></i><i class="fa fa-info"></i><i class="fa fa-info-circle"></i><i class="fa fa-institution"></i><i class="fa fa-key"></i><i class="fa fa-keyboard-o"></i><i class="fa fa-language"></i><i class="fa fa-laptop"></i><i class="fa fa-leaf"></i><i class="fa fa-legal"></i><i class="fa fa-lemon-o"></i><i class="fa fa-level-down"></i><i class="fa fa-level-up"></i><i class="fa fa-life-bouy"></i><i class="fa fa-life-buoy"></i><i class="fa fa-life-ring"></i><i class="fa fa-life-saver"></i><i class="fa fa-lightbulb-o"></i><i class="fa fa-line-chart"></i><i class="fa fa-location-arrow"></i><i class="fa fa-lock"></i><i class="fa fa-magic"></i><i class="fa fa-magnet"></i><i class="fa fa-mail-forward"></i><i class="fa fa-mail-reply"></i><i class="fa fa-mail-reply-all"></i><i class="fa fa-male"></i><i class="fa fa-map-marker"></i><i class="fa fa-meh-o"></i><i class="fa fa-microphone"></i><i class="fa fa-microphone-slash"></i><i class="fa fa-minus"></i><i class="fa fa-minus-circle"></i><i class="fa fa-minus-square"></i><i class="fa fa-minus-square-o"></i><i class="fa fa-mobile"></i><i class="fa fa-mobile-phone"></i><i class="fa fa-money"></i><i class="fa fa-moon-o"></i><i class="fa fa-mortar-board"></i><i class="fa fa-motorcycle"></i><i class="fa fa-music"></i><i class="fa fa-navicon"></i><i class="fa fa-newspaper-o"></i><i class="fa fa-paint-brush"></i><i class="fa fa-paper-plane"></i><i class="fa fa-paper-plane-o"></i><i class="fa fa-paw"></i><i class="fa fa-pencil"></i><i class="fa fa-pencil-square"></i><i class="fa fa-pencil-square-o"></i><i class="fa fa-phone"></i><i class="fa fa-phone-square"></i><i class="fa fa-photo"></i><i class="fa fa-picture-o"></i><i class="fa fa-pie-chart"></i><i class="fa fa-plane"></i><i class="fa fa-plug"></i><i class="fa fa-plus"></i><i class="fa fa-plus-circle"></i><i class="fa fa-plus-square"></i><i class="fa fa-plus-square-o"></i><i class="fa fa-power-off"></i><i class="fa fa-print"></i><i class="fa fa-puzzle-piece"></i><i class="fa fa-qrcode"></i><i class="fa fa-question"></i><i class="fa fa-question-circle"></i><i class="fa fa-quote-left"></i><i class="fa fa-quote-right"></i><i class="fa fa-random"></i><i class="fa fa-recycle"></i><i class="fa fa-refresh"></i><i class="fa fa-remove"></i><i class="fa fa-reorder"></i><i class="fa fa-reply"></i><i class="fa fa-reply-all"></i><i class="fa fa-retweet"></i><i class="fa fa-road"></i><i class="fa fa-rocket"></i><i class="fa fa-rss"></i><i class="fa fa-rss-square"></i><i class="fa fa-search"></i><i class="fa fa-search-minus"></i><i class="fa fa-search-plus"></i><i class="fa fa-send"></i><i class="fa fa-send-o"></i><i class="fa fa-server"></i><i class="fa fa-share"></i><i class="fa fa-share-alt"></i><i class="fa fa-share-alt-square"></i><i class="fa fa-share-square"></i><i class="fa fa-share-square-o"></i><i class="fa fa-shield"></i><i class="fa fa-ship"></i><i class="fa fa-shopping-cart"></i><i class="fa fa-sign-in"></i><i class="fa fa-sign-out"></i><i class="fa fa-signal"></i><i class="fa fa-sitemap"></i><i class="fa fa-sliders"></i><i class="fa fa-smile-o"></i><i class="fa fa-soccer-ball-o"></i><i class="fa fa-sort"></i><i class="fa fa-sort-alpha-asc"></i><i class="fa fa-sort-alpha-desc"></i><i class="fa fa-sort-amount-asc"></i><i class="fa fa-sort-amount-desc"></i><i class="fa fa-sort-asc"></i><i class="fa fa-sort-desc"></i><i class="fa fa-sort-down"></i><i class="fa fa-sort-numeric-asc"></i><i class="fa fa-sort-numeric-desc"></i><i class="fa fa-sort-up"></i><i class="fa fa-space-shuttle"></i><i class="fa fa-spinner"></i><i class="fa fa-spoon"></i><i class="fa fa-square"></i><i class="fa fa-square-o"></i><i class="fa fa-star"></i><i class="fa fa-star-half"></i><i class="fa fa-star-half-empty"></i><i class="fa fa-star-half-full"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star-o"></i><i class="fa fa-street-view"></i><i class="fa fa-suitcase"></i><i class="fa fa-sun-o"></i><i class="fa fa-support"></i><i class="fa fa-tablet"></i><i class="fa fa-tachometer"></i><i class="fa fa-tag"></i><i class="fa fa-tags"></i><i class="fa fa-tasks"></i><i class="fa fa-taxi"></i><i class="fa fa-terminal"></i><i class="fa fa-thumb-tack"></i><i class="fa fa-thumbs-down"></i><i class="fa fa-thumbs-o-down"></i><i class="fa fa-thumbs-o-up"></i><i class="fa fa-thumbs-up"></i><i class="fa fa-ticket"></i><i class="fa fa-times"></i><i class="fa fa-times-circle"></i><i class="fa fa-times-circle-o"></i><i class="fa fa-tint"></i><i class="fa fa-toggle-down"></i><i class="fa fa-toggle-left"></i><i class="fa fa-toggle-off"></i><i class="fa fa-toggle-on"></i><i class="fa fa-toggle-right"></i><i class="fa fa-toggle-up"></i><i class="fa fa-trash"></i><i class="fa fa-trash-o"></i><i class="fa fa-tree"></i><i class="fa fa-trophy"></i><i class="fa fa-truck"></i><i class="fa fa-tty"></i><i class="fa fa-umbrella"></i><i class="fa fa-university"></i><i class="fa fa-unlock"></i><i class="fa fa-unlock-alt"></i><i class="fa fa-unsorted"></i><i class="fa fa-upload"></i><i class="fa fa-user"></i><i class="fa fa-user-plus"></i><i class="fa fa-user-secret"></i><i class="fa fa-user-times"></i><i class="fa fa-users"></i><i class="fa fa-video-camera"></i><i class="fa fa-volume-down"></i><i class="fa fa-volume-off"></i><i class="fa fa-volume-up"></i><i class="fa fa-warning"></i><i class="fa fa-wheelchair"></i><i class="fa fa-wifi"></i><i class="fa fa-wrench"></i><i class="fa fa-ambulance"></i><i class="fa fa-automobile"></i><i class="fa fa-bicycle"></i><i class="fa fa-bus"></i><i class="fa fa-cab"></i><i class="fa fa-car"></i><i class="fa fa-fighter-jet"></i><i class="fa fa-motorcycle"></i><i class="fa fa-plane"></i><i class="fa fa-rocket"></i><i class="fa fa-ship"></i><i class="fa fa-space-shuttle"></i><i class="fa fa-subway"></i><i class="fa fa-taxi"></i><i class="fa fa-train"></i><i class="fa fa-truck"></i><i class="fa fa-wheelchair"></i><i class="fa fa-circle-thin"></i><i class="fa fa-genderless"></i><i class="fa fa-mars"></i><i class="fa fa-mars-double"></i><i class="fa fa-mars-stroke"></i><i class="fa fa-mars-stroke-h"></i><i class="fa fa-mars-stroke-v"></i><i class="fa fa-mercury"></i><i class="fa fa-neuter"></i><i class="fa fa-transgender"></i><i class="fa fa-transgender-alt"></i><i class="fa fa-venus"></i><i class="fa fa-venus-double"></i><i class="fa fa-venus-mars"></i><i class="fa fa-file"></i><i class="fa fa-file-archive-o"></i><i class="fa fa-file-audio-o"></i><i class="fa fa-file-code-o"></i><i class="fa fa-file-excel-o"></i><i class="fa fa-file-image-o"></i><i class="fa fa-file-movie-o"></i><i class="fa fa-file-o"></i><i class="fa fa-file-pdf-o"></i><i class="fa fa-file-photo-o"></i><i class="fa fa-file-picture-o"></i><i class="fa fa-file-powerpoint-o"></i><i class="fa fa-file-sound-o"></i><i class="fa fa-file-text"></i><i class="fa fa-file-text-o"></i><i class="fa fa-file-video-o"></i><i class="fa fa-file-word-o"></i><i class="fa fa-file-zip-o"></i><i class="fa fa-info-circle"></i><i class="fa fa-circle-o-notch"></i><i class="fa fa-cog"></i><i class="fa fa-gear"></i><i class="fa fa-refresh"></i><i class="fa fa-spinner"></i><i class="fa fa-check-square"></i><i class="fa fa-check-square-o"></i><i class="fa fa-circle"></i><i class="fa fa-circle-o"></i><i class="fa fa-dot-circle-o"></i><i class="fa fa-minus-square"></i><i class="fa fa-minus-square-o"></i><i class="fa fa-plus-square"></i><i class="fa fa-plus-square-o"></i><i class="fa fa-square"></i><i class="fa fa-square-o"></i><i class="fa fa-cc-amex"></i><i class="fa fa-cc-discover"></i><i class="fa fa-cc-mastercard"></i><i class="fa fa-cc-paypal"></i><i class="fa fa-cc-stripe"></i><i class="fa fa-cc-visa"></i><i class="fa fa-credit-card"></i><i class="fa fa-google-wallet"></i><i class="fa fa-paypal"></i><i class="fa fa-area-chart"></i><i class="fa fa-bar-chart"></i><i class="fa fa-bar-chart-o"></i><i class="fa fa-line-chart"></i><i class="fa fa-pie-chart"></i><i class="fa fa-bitcoin"></i><i class="fa fa-btc"></i><i class="fa fa-cny"></i><i class="fa fa-dollar"></i><i class="fa fa-eur"></i><i class="fa fa-euro"></i><i class="fa fa-gbp"></i><i class="fa fa-ils"></i><i class="fa fa-inr"></i><i class="fa fa-jpy"></i><i class="fa fa-krw"></i><i class="fa fa-money"></i><i class="fa fa-rmb"></i><i class="fa fa-rouble"></i><i class="fa fa-rub"></i><i class="fa fa-ruble"></i><i class="fa fa-rupee"></i><i class="fa fa-shekel"></i><i class="fa fa-sheqel"></i><i class="fa fa-try"></i><i class="fa fa-turkish-lira"></i><i class="fa fa-usd"></i><i class="fa fa-won"></i><i class="fa fa-yen"></i><i class="fa fa-align-center"></i><i class="fa fa-align-justify"></i><i class="fa fa-align-left"></i><i class="fa fa-align-right"></i><i class="fa fa-bold"></i><i class="fa fa-chain"></i><i class="fa fa-chain-broken"></i><i class="fa fa-clipboard"></i><i class="fa fa-columns"></i><i class="fa fa-copy"></i><i class="fa fa-cut"></i><i class="fa fa-dedent"></i><i class="fa fa-eraser"></i><i class="fa fa-file"></i><i class="fa fa-file-o"></i><i class="fa fa-file-text"></i><i class="fa fa-file-text-o"></i><i class="fa fa-files-o"></i><i class="fa fa-floppy-o"></i><i class="fa fa-font"></i><i class="fa fa-header"></i><i class="fa fa-indent"></i><i class="fa fa-italic"></i><i class="fa fa-link"></i><i class="fa fa-list"></i><i class="fa fa-list-alt"></i><i class="fa fa-list-ol"></i><i class="fa fa-list-ul"></i><i class="fa fa-outdent"></i><i class="fa fa-paperclip"></i><i class="fa fa-paragraph"></i><i class="fa fa-paste"></i><i class="fa fa-repeat"></i><i class="fa fa-rotate-left"></i><i class="fa fa-rotate-right"></i><i class="fa fa-save"></i><i class="fa fa-scissors"></i><i class="fa fa-strikethrough"></i><i class="fa fa-subscript"></i><i class="fa fa-superscript"></i><i class="fa fa-table"></i><i class="fa fa-text-height"></i><i class="fa fa-text-width"></i><i class="fa fa-th"></i><i class="fa fa-th-large"></i><i class="fa fa-th-list"></i><i class="fa fa-underline"></i><i class="fa fa-undo"></i><i class="fa fa-unlink"></i><i class="fa fa-angle-double-down"></i><i class="fa fa-angle-double-left"></i><i class="fa fa-angle-double-right"></i><i class="fa fa-angle-double-up"></i><i class="fa fa-angle-down"></i><i class="fa fa-angle-left"></i><i class="fa fa-angle-right"></i><i class="fa fa-angle-up"></i><i class="fa fa-arrow-circle-down"></i><i class="fa fa-arrow-circle-left"></i><i class="fa fa-arrow-circle-o-down"></i><i class="fa fa-arrow-circle-o-left"></i><i class="fa fa-arrow-circle-o-right"></i><i class="fa fa-arrow-circle-o-up"></i><i class="fa fa-arrow-circle-right"></i><i class="fa fa-arrow-circle-up"></i><i class="fa fa-arrow-down"></i><i class="fa fa-arrow-left"></i><i class="fa fa-arrow-right"></i><i class="fa fa-arrow-up"></i><i class="fa fa-arrows"></i><i class="fa fa-arrows-alt"></i><i class="fa fa-arrows-h"></i><i class="fa fa-arrows-v"></i><i class="fa fa-caret-down"></i><i class="fa fa-caret-left"></i><i class="fa fa-caret-right"></i><i class="fa fa-caret-square-o-down"></i><i class="fa fa-caret-square-o-left"></i><i class="fa fa-caret-square-o-right"></i><i class="fa fa-caret-square-o-up"></i><i class="fa fa-caret-up"></i><i class="fa fa-chevron-circle-down"></i><i class="fa fa-chevron-circle-left"></i><i class="fa fa-chevron-circle-right"></i><i class="fa fa-chevron-circle-up"></i><i class="fa fa-chevron-down"></i><i class="fa fa-chevron-left"></i><i class="fa fa-chevron-right"></i><i class="fa fa-chevron-up"></i><i class="fa fa-hand-o-down"></i><i class="fa fa-hand-o-left"></i><i class="fa fa-hand-o-right"></i><i class="fa fa-hand-o-up"></i><i class="fa fa-long-arrow-down"></i><i class="fa fa-long-arrow-left"></i><i class="fa fa-long-arrow-right"></i><i class="fa fa-long-arrow-up"></i><i class="fa fa-toggle-down"></i><i class="fa fa-toggle-left"></i><i class="fa fa-toggle-right"></i><i class="fa fa-toggle-up"></i><i class="fa fa-arrows-alt"></i><i class="fa fa-backward"></i><i class="fa fa-compress"></i><i class="fa fa-eject"></i><i class="fa fa-expand"></i><i class="fa fa-fast-backward"></i><i class="fa fa-fast-forward"></i><i class="fa fa-forward"></i><i class="fa fa-pause"></i><i class="fa fa-play"></i><i class="fa fa-play-circle"></i><i class="fa fa-play-circle-o"></i><i class="fa fa-step-backward"></i><i class="fa fa-step-forward"></i><i class="fa fa-stop"></i><i class="fa fa-youtube-play"></i><i class="fa fa-warning"></i><i class="fa fa-adn"></i><i class="fa fa-android"></i><i class="fa fa-angellist"></i><i class="fa fa-apple"></i><i class="fa fa-behance"></i><i class="fa fa-behance-square"></i><i class="fa fa-bitbucket"></i><i class="fa fa-bitbucket-square"></i><i class="fa fa-bitcoin"></i><i class="fa fa-btc"></i><i class="fa fa-buysellads"></i><i class="fa fa-cc-amex"></i><i class="fa fa-cc-discover"></i><i class="fa fa-cc-mastercard"></i><i class="fa fa-cc-paypal"></i><i class="fa fa-cc-stripe"></i><i class="fa fa-cc-visa"></i><i class="fa fa-codepen"></i><i class="fa fa-connectdevelop"></i><i class="fa fa-css3"></i><i class="fa fa-dashcube"></i><i class="fa fa-delicious"></i><i class="fa fa-deviantart"></i><i class="fa fa-digg"></i><i class="fa fa-dribbble"></i><i class="fa fa-dropbox"></i><i class="fa fa-drupal"></i><i class="fa fa-empire"></i><i class="fa fa-facebook"></i><i class="fa fa-facebook-f"></i><i class="fa fa-facebook-official"></i><i class="fa fa-facebook-square"></i><i class="fa fa-flickr"></i><i class="fa fa-forumbee"></i><i class="fa fa-foursquare"></i><i class="fa fa-ge"></i><i class="fa fa-git"></i><i class="fa fa-git-square"></i><i class="fa fa-github"></i><i class="fa fa-github-alt"></i><i class="fa fa-github-square"></i><i class="fa fa-gittip"></i><i class="fa fa-google"></i><i class="fa fa-google-plus"></i><i class="fa fa-google-plus-square"></i><i class="fa fa-google-wallet"></i><i class="fa fa-gratipay"></i><i class="fa fa-hacker-news"></i><i class="fa fa-html5"></i><i class="fa fa-instagram"></i><i class="fa fa-ioxhost"></i><i class="fa fa-joomla"></i><i class="fa fa-jsfiddle"></i><i class="fa fa-lastfm"></i><i class="fa fa-lastfm-square"></i><i class="fa fa-leanpub"></i><i class="fa fa-linkedin"></i><i class="fa fa-linkedin-square"></i><i class="fa fa-linux"></i><i class="fa fa-maxcdn"></i><i class="fa fa-meanpath"></i><i class="fa fa-medium"></i><i class="fa fa-openid"></i><i class="fa fa-pagelines"></i><i class="fa fa-paypal"></i><i class="fa fa-pied-piper"></i><i class="fa fa-pied-piper-alt"></i><i class="fa fa-pinterest"></i><i class="fa fa-pinterest-p"></i><i class="fa fa-pinterest-square"></i><i class="fa fa-qq"></i><i class="fa fa-ra"></i><i class="fa fa-rebel"></i><i class="fa fa-reddit"></i><i class="fa fa-reddit-square"></i><i class="fa fa-renren"></i><i class="fa fa-sellsy"></i><i class="fa fa-share-alt"></i><i class="fa fa-share-alt-square"></i><i class="fa fa-shirtsinbulk"></i><i class="fa fa-simplybuilt"></i><i class="fa fa-skyatlas"></i><i class="fa fa-skype"></i><i class="fa fa-slack"></i><i class="fa fa-slideshare"></i><i class="fa fa-soundcloud"></i><i class="fa fa-spotify"></i><i class="fa fa-stack-exchange"></i><i class="fa fa-stack-overflow"></i><i class="fa fa-steam"></i><i class="fa fa-steam-square"></i><i class="fa fa-stumbleupon"></i><i class="fa fa-stumbleupon-circle"></i><i class="fa fa-tencent-weibo"></i><i class="fa fa-trello"></i><i class="fa fa-tumblr"></i><i class="fa fa-tumblr-square"></i><i class="fa fa-twitch"></i><i class="fa fa-twitter"></i><i class="fa fa-twitter-square"></i><i class="fa fa-viacoin"></i><i class="fa fa-vimeo-square"></i><i class="fa fa-vine"></i><i class="fa fa-vk"></i><i class="fa fa-wechat"></i><i class="fa fa-weibo"></i><i class="fa fa-weixin"></i><i class="fa fa-whatsapp"></i><i class="fa fa-windows"></i><i class="fa fa-wordpress"></i><i class="fa fa-xing"></i><i class="fa fa-xing-square"></i><i class="fa fa-yahoo"></i><i class="fa fa-yelp"></i><i class="fa fa-youtube"></i><i class="fa fa-youtube-play"></i><i class="fa fa-youtube-square"></i><i class="fa fa-ambulance"></i><i class="fa fa-h-square"></i><i class="fa fa-heart"></i><i class="fa fa-heart-o"></i><i class="fa fa-heartbeat"></i><i class="fa fa-hospital-o"></i><i class="fa fa-medkit"></i><i class="fa fa-plus-square"></i><i class="fa fa-stethoscope"></i><i class="fa fa-user-md"></i><i class="fa fa-wheelchair"></i>';
        echo '</div>';
    }

    public function smartcat_service_info_box_content( $post ) {
        //nonce
        wp_nonce_field( plugin_basename( __FILE__ ), 'smartcat_service_info_box_content_nonce' );

        //social

        echo '<p><em>Fields that are left blank, will simply not display any output</em></p>';

        echo '<div class="sc_options_table">';

        echo '<table>';

        echo '<tr><td><lablel for="service_title">Job Title</lablel></td>';
        echo '<td><input type="text" value="' . get_post_meta( $post->ID, 'service_title', true ) . '" id="service_title" name="service_title" placeholder="Enter Job Title"/></td></tr>';

        echo '<tr><td><lablel for="service_email"><img src="' . SC_SERVICES_URL . 'inc/img/email.png" width="20px"/></lablel></td>';
        echo '<td><input type="text" value="' . get_post_meta( $post->ID, 'service_email', true ) . '" id="service_email" name="service_email" placeholder="Enter Email Address"/></td></tr>';

        echo '<tr><td><lablel for="service_facebook"><img src="' . SC_SERVICES_URL . 'inc/img/fb.png" width="20px"/></lablel></td>';
        echo '<td><input type="text" value="' . get_post_meta( $post->ID, 'service_facebook', true ) . '" id="service_facebook" name="service_facebook" placeholder="Enter Facebook URL"/></td></tr>';

        echo '<tr><td><label for="service_twitter"><img src="' . SC_SERVICES_URL . 'inc/img/twitter.png" width="20px"/></lablel></td>';
        echo '<td><input type="text" value="' . get_post_meta( $post->ID, 'service_twitter', true ) . '" id="service_twitter" name="service_twitter" placeholder="Enter Twitter URL"/></td></tr>';

        echo '<tr><td><lablel for="service_linkedin"><img src="' . SC_SERVICES_URL . 'inc/img/linkedin.png" width="20px"/></lablel></td>';
        echo '<td><input type="text" value="' . get_post_meta( $post->ID, 'service_linkedin', true ) . '" id="service_linkedin" name="service_linkedin" placeholder="Enter Linkedin URL"/></td></tr>';

        echo '<tr><td><lablel for="service_gplus"><img src="' . SC_SERVICES_URL . 'inc/img/google.png" width="20px"/></lablel></td>';
        echo '<td><input type="text" value="' . get_post_meta( $post->ID, 'service_gplus', true ) . '" id="service_gplus" name="service_gplus" placeholder="Enter Google Plus URL"/></td></tr>';

        echo '</table>';
        echo '</div>';


        echo '<div class="sc_options_table">'
        . '<h2>Skills</h2>'
        . '<p><strong><em>Pro Version</em></strong></p>';

        echo '</div>'
        . '<div class="clear"></div>';
    }

    public function service_box_save( $post_id ) {

        $slug = 'service';

        if ( isset( $_POST[ 'post_type' ] ) ) {
            if ( $slug != $_POST[ 'post_type' ] ) {
                return;
            }
        }

        // get var values
        if ( get_post_meta( $post_id, 'sc_member_order', true ) == '' || get_post_meta( $post_id, 'sc_member_order', true ) === FALSE )
            update_post_meta( $post_id, 'sc_member_order', 0 );

        if ( isset( $_REQUEST[ 'service_icon' ] ) ) {
            $icon = $_POST[ 'service_icon' ];
            update_post_meta( $post_id, 'service_icon', $icon );
        }
    }

    public function sc_services_switch_skill( $value ) {
        if ( $value < 0 )
            return 0;
        elseif ( $value > 10 )
            return 10;
        else
            return $value;
    }

    public function wpb_load_widget() {
        register_widget( 'smartcat_services_widget' );
    }

    public function posts_columns( $defaults ) {
        $defaults[ 'riv_post_thumbs' ] = __( 'Profile Picture' );
        return $defaults;
    }

    public function posts_custom_columns( $column_name, $id ) {
        if ( $column_name === 'riv_post_thumbs' ) {
            echo the_post_thumbnail( 'thumbnail' );
        }
    }

    public function smartcat_services_update_order() {
        $post_id = $_POST[ 'id' ];
        $sc_member_order = $_POST[ 'sc_member_order' ];
        update_post_meta( $post_id, 'sc_member_order', $sc_member_order );
    }

    public function sc_custom_styles() {
        ?>
        <style>
            #sc_our_services .sc_service{ max-height: <?php echo $this->options[ 'height' ]; ?>px; }
            #sc_our_services .sc_service .sc_service_name a{ color: #<?php echo $this->options[ 'main_color' ]; ?>; }
            #sc_our_services .sc_service .sc_services_content, 
            #sc_our_services.smartcat_slide .sc_services_read_more a{ color: #<?php echo $this->options[ 'text_color' ]; ?>; }
            #sc_our_services .sc_service .sc_service_name a { font-size: <?php echo $this->options[ 'title_size' ]; ?>px; }
            #sc_our_services.smartcat_icons .fa,
            #sc_our_services.smartcat_columns .sc_service .fa{ color: #<?php echo $this->options[ 'main_color' ]; ?>; border: 2px solid #<?php echo $this->options[ 'main_color' ]; ?> }
            #sc_our_services.smartcat_icons .sc_service:hover .fa,
            #sc_our_services.smartcat_slide .sc_service_name{ background: #<?php echo $this->options[ 'main_color' ]; ?> }
            #sc_our_services.smartcat_slide .sc_services_content{ color: #fff; background: #<?php echo $this->options[ 'text_color' ]; ?> }
            #sc_our_services.smartcat_icons a{ color: #<?php echo $this->options['main_color']; ?> }
            #sc_our_services.smartcat_zoomOut .sc_service .sc_service_inner .sc_services_read_more a,
            #sc_our_services.smartcat_slide .sc_service_name a{ color: #fff; }
            #sc_our_services.smartcat_zoomOut .sc_service .sc_service_inner .sc_services_read_more a{ background: #<?php echo $this->options['accent_color']; ?>; }
            #sc_our_services .sc_service .sc_services_read_more a{ color : #<?php echo $this->options['accent_color'] ?> }
            
        </style>
        <?php
    }

    public function smartcat_set_single_content( $content ) {
        global $post;

//        if ( is_single() ) :
//            if ( $post->post_type == 'service' &&
//                    $this->options[ 'single_template' ] == 'standard' &&
//                    $this->options[ 'single_social' ] == 'yes'
//            ) :
//                $facebook = get_post_meta( get_the_ID(), 'service_facebook', true );
//                $twitter = get_post_meta( get_the_ID(), 'service_twitter', true );
//                $linkedin = get_post_meta( get_the_ID(), 'service_linkedin', true );
//                $gplus = get_post_meta( get_the_ID(), 'service_gplus', true );
//                $email = get_post_meta( get_the_ID(), 'service_email', true );
//
//                $content .= '<div class="clear"></div>'
//                        . '<div class="smartcat_services_single_icons">';
//                $content .= $this->smartcat_get_social_content( $facebook, $twitter, $linkedin, $gplus, $email );
//                $content .= '</div>';
//            endif;
//        else :
//
//        endif;

        return $content;
    }

    public function get_social( $facebook, $twitter, $linkedin, $gplus, $email ) {
        if ( $facebook != '' )
            echo '<a href="' . $facebook . '"><img src="' . SC_SERVICES_URL . 'inc/img/fb.png" class="sc-social"/></a>';
        if ( $twitter != '' )
            echo '<a href="' . $twitter . '"><img src="' . SC_SERVICES_URL . 'inc/img/twitter.png" class="sc-social"/></a>';
        if ( $linkedin != '' )
            echo '<a href="' . $linkedin . '"><img src="' . SC_SERVICES_URL . 'inc/img/linkedin.png" class="sc-social"/></a>';
        if ( $gplus != '' )
            echo '<a href="' . $gplus . '"><img src="' . SC_SERVICES_URL . 'inc/img/google.png" class="sc-social"/></a>';
        if ( $email != '' )
            echo '<a href=mailto:"' . $email . '"><img src="' . SC_SERVICES_URL . 'inc/img/email.png" class="sc-social"/></a>';
    }

    public function smartcat_get_social_content( $facebook, $twitter, $linkedin, $gplus, $email ) {

        $content = null;

        if ( 'yes' == $this->options[ 'social' ] ) {
            if ( $facebook != '' )
                $content .= '<a href="' . $facebook . '"><img src="' . SC_SERVICES_URL . 'inc/img/fb.png" class="sc-social"/></a>';
            if ( $twitter != '' )
                $content .= '<a href="' . $twitter . '"><img src="' . SC_SERVICES_URL . 'inc/img/twitter.png" class="sc-social"/></a>';
            if ( $linkedin != '' )
                $content .= '<a href="' . $linkedin . '"><img src="' . SC_SERVICES_URL . 'inc/img/linkedin.png" class="sc-social"/></a>';
            if ( $gplus != '' )
                $content .= '<a href="' . $gplus . '"><img src="' . SC_SERVICES_URL . 'inc/img/google.png" class="sc-social"/></a>';
            if ( $email != '' )
                $content .= '<a href=mailto:"' . $email . '"><img src="' . SC_SERVICES_URL . 'inc/img/email.png" class="sc-social"/></a>';
        }
        return $content;
    }

    public function get_single_social( $social ) {
        if ( 'yes' == $this->options[ 'social' ] ) :
            if ( $social != '' )
                echo '<li><a href="' . $social . '"><img src="' . SC_SERVICES_URL . 'inc/img/fb.png" class="sc-social"/></a></li>';

        endif;
    }

    public function sc_get_args( $group ) {
        $args = array(
            'post_type' => 'service',
            'meta_key' => 'sc_member_order',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'service_position' => $group,
            'posts_per_page' => $this->options[ 'member_count' ],
        );
        return $args;
    }

    public function smartcat_services_get_single_template( $single_template ) {

        global $post;


        return $single_template;
    }
 
    
}
