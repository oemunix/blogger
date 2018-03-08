<?php
class WP_HTML_Compression
{
    // Settings
    protected $compress_css = true;
    protected $compress_js = true;
    protected $info_comment = true;
    protected $remove_comments = true;

    // Variables
    protected $html;
    public function __construct($html)
    {
   	 if (!empty($html))
   	 {
   		 $this->parseHTML($html);
   	 }
    }
    public function __toString()
    {
   	 return $this->html;
    }
    protected function bottomComment($raw, $compressed)
    {
   	 $raw = strlen($raw);
   	 $compressed = strlen($compressed);
   	 
   	 $savings = ($raw-$compressed) / $raw * 100;
   	 
   	 $savings = round($savings, 2);
   	 
   	 return '<!--HTML compressed, size saved '.$savings.'%. From '.$raw.' bytes, now '.$compressed.' bytes-->';
    }
    protected function minifyHTML($html)
    {
   	 $pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
   	 preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
   	 $overriding = false;
   	 $raw_tag = false;
   	 // Variable reused for output
   	 $html = '';
   	 foreach ($matches as $token)
   	 {
   		 $tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;
   		 
   		 $content = $token[0];
   		 
   		 if (is_null($tag))
   		 {
   			 if ( !empty($token['script']) )
   			 {
   				 $strip = $this->compress_js;
   			 }
   			 else if ( !empty($token['style']) )
   			 {
   				 $strip = $this->compress_css;
   			 }
   			 else if ($content == '<!--wp-html-compression no compression-->')
   			 {
   				 $overriding = !$overriding;
   				 
   				 // Don't print the comment
   				 continue;
   			 }
   			 else if ($this->remove_comments)
   			 {
   				 if (!$overriding && $raw_tag != 'textarea')
   				 {
   					 // Remove any HTML comments, except MSIE conditional comments
   					 $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
   				 }
   			 }
   		 }
   		 else
   		 {
   			 if ($tag == 'pre' || $tag == 'textarea')
   			 {
   				 $raw_tag = $tag;
   			 }
   			 else if ($tag == '/pre' || $tag == '/textarea')
   			 {
   				 $raw_tag = false;
   			 }
   			 else
   			 {
   				 if ($raw_tag || $overriding)
   				 {
   					 $strip = false;
   				 }
   				 else
   				 {
   					 $strip = true;
   					 
   					 // Remove any empty attributes, except:
   					 // action, alt, content, src
   					 $content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);
   					 
   					 // Remove any space before the end of self-closing XHTML tags
   					 // JavaScript excluded
   					 $content = str_replace(' />', '/>', $content);
   				 }
   			 }
   		 }
   		 
   		 if ($strip)
   		 {
   			 $content = $this->removeWhiteSpace($content);
   		 }
   		 
   		 $html .= $content;
   	 }
   	 
   	 return $html;
    }
   	 
    public function parseHTML($html)
    {
   	 $this->html = $this->minifyHTML($html);
   	 
   	 if ($this->info_comment)
   	 {
   		 $this->html .= "\n" . $this->bottomComment($html, $this->html);
   	 }
    }
    
    protected function removeWhiteSpace($str)
    {
   	 $str = str_replace("\t", ' ', $str);
   	 $str = str_replace("\n",  '', $str);
   	 $str = str_replace("\r",  '', $str);
   	 
   	 while (stristr($str, '  '))
   	 {
   		 $str = str_replace('  ', ' ', $str);
   	 }
   	 
   	 return $str;
    }
}

function wp_html_compression_finish($html)
{
    return new WP_HTML_Compression($html);
}

function wp_html_compression_start()
{
    ob_start('wp_html_compression_finish');
}
add_action('get_header', 'wp_html_compression_start');
?>
<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

//Making jQuery Google API
function modify_jquery() {
	if (!is_admin()) {
		// comment out the next two lines to load the local copy of jQuery
		wp_deregister_script('jquery');
		wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', false, '1.8.1');
		wp_enqueue_script('jquery');
	}
}
add_action('init', 'modify_jquery');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

function divi_social_widget() { ?>
<div class="et_pb_widget">
<h4 class="widgettitle">Follow Us</h4>
<?php get_template_part( 'includes/social_icons', 'footer' ); ?>
<div style="clear: both"></div>
<style>
.et_pb_widget .et-social-icons {float:right;display: block;margin: 0px auto;text-align: center;}
.et_pb_widget .et-social-icons li {display: inline-block;margin-left: 6px;padding: 7px !important;border-radius: 3px;}
.et_pb_widget a.icon {color:#fff!important;}
.et_pb_widget .et-social-facebook {background: #3D549E;}
.et_pb_widget .et-social-twitter {background: #55ACEE;}
.et_pb_widget .et-social-google-plus {background: #517FA6;}
.et_pb_widget .et-social-rss {background: #E19126;}
</style>
</div>
<?php
}
wp_register_sidebar_widget(
    'divi_social_widget',
    'Divi Social Widget',
    'divi_social_widget',
    array( 
        'description' => 'Social Link Widget'
    )
);
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

add_filter('woocommerce_sale_flash', 'woocommerce_sale_flashmessage', 10, 2);
function woocommerce_sale_flashmessage($flash){
    global $product;
    $availability = $product->get_availability();

    if ($availability['availability'] == 'Out of stock') :
        $flash = '<span class="onsale">'.__( 'SOLD', 'woocommerce' ).'</span>';

    endif;
    return $flash;
}


function translate_woocommerce($translation, $text, $domain) {
    if ($domain == 'woocommerce') {
        switch ($text) {
            case 'SKU':
                $translation = 'Kode :';
                break;
            case 'SKU:':
                $translation = 'Kode :';
                break;
        }
    }
    return $translation;
}

add_filter('gettext', 'translate_woocommerce', 10, 3);

//Remove WooCommerce Tabs - this code removes all 3 tabs - to be more specific just remove actual unset lines 

add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {

    unset( $tabs['description'] );      	// Remove the description tab
    unset( $tabs['reviews'] ); 			// Remove the reviews tab
    unset( $tabs['additional_information'] );  	// Remove the additional information tab

    return $tabs;

}

function mycss_admin_head() {
	echo '<style>#wp-content-wrap, #post-status-info{display:none;}</style>';
}
add_action( 'admin_head', 'mycss_admin_head' );

function sant_prettyadd ($content) {
    $content = preg_replace("/<a/","<a rel=\"single-gallery\" class=\"et_pb_lightbox_image\"",$content,1);
    return $content;
}
function fancybox_regex($content){ 
    global $post; 
    $content = preg_replace("/(<a(?![^>]*?rel=['\"]fancybox.*)[^>]*?href=['\"][^'\"]+?\.(?:gif|jpg|jpeg|png)\?{0,1}\S{0,}['\"][^\>]*)>/i" , '$1 class="et_pb_lightbox_image">', $content); 
    return $content; 
} 
add_filter('the_content', 'fancybox_regex');

function logo_shortcode() { 
ob_start();
echo '<a href="' . get_bloginfo('url') . '"><img src="' . $logo . '"/></a>';
 $output = ob_get_clean();
//print $output; // debug
return $output;
}
add_shortcode('oem_logo', 'logo_shortcode');

add_filter( 'login_headerurl', 'custom_loginlogo_url' );
function custom_loginlogo_url($url) {
    return '/';
}
function my_login_logo_one() { 
	$logo = ( $user_logo = et_get_option( 'divi_logo' ) ) && '' != $user_logo? $user_logo: '/wp-content/themes/Divi/images/logo.png';
?> 
<style type="text/css"> 
body.login div#login h1 a {
background-image: url(<?php echo $logo; ?>);  //Add your own logo image in this url 
padding-bottom: 30px; 
background-size: 100%!important;
width: 300px;
} 
body.login {background-size:cover;background-repeat:no-repeat;background-position:bottom center;background-color:#1B1B1B!important;background-image:url(http://dompetpulsa.id/wp-content/uploads/2017/11/bg-login-secure.jpg);}
.login form {background:rgba(0,0,0,0.301961)!important;border-radius:8px;}
.login form .input, .login form input[type="checkbox"], .login input[type="text"]{background: rgba(0,0,0,0.2)!important;border: none!important;border-radius:4px;color: #fff;}
.login label, .login .cptch_title {color:#58A80E!important;}
</style>
<?php 
} 

add_action( 'login_enqueue_scripts', 'my_login_logo_one' );

// Hapus Dashboard Widget
function remove_dashboard_widgets(){
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');  // Incoming Links
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');  // Recent Drafts
    remove_meta_box('dashboard_primary', 'dashboard', 'side');   // WordPress blog
    remove_meta_box('dashboard_secondary', 'dashboard', 'side');   // Other WordPress News
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');   // Quick Draft
// use 'dashboard-network' as the second parameter to remove widgets from a network dashboard.
}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets');

// Fungsi tambah custom css Admin Area
function dvns_custom_admincss() {
echo '<style> .welcome-panel-content h2{display:none;}.welcome-panel-content:before {content: "Welcome to Your Dashboard Panel";font-size: 2em;}
#adminmenuwrap:before{content:"";display:inline-block;width:133px;height:45px;background-image:url(http://4.bp.blogspot.com/-0PkQtrlNZTI/Wprh7DQjHxI/AAAAAAAACgY/r7o1MNHvgYkNex2HiZZj_ff8EcqFsJ-1ACK4BGAYYCw/s220/oase_logo.png);background-size:100%;background-repeat:no-repeat;position:relative;top:20px;left:40px}
@media screen and (max-width:600px){#adminmenuwrap:before{margin-top:30px;z-index:9999;top:35px;left:30px}#adminmenu{margin-top:70px;border-top:1px solid #2C3440}}@media only screen and (min-width:960px){#contextual-help-back{border-right:0px;}#wpbody{top:32px;}#tab-panel-help-content p:nth-child(5), .contextual-help-sidebar, #wp-admin-bar-wp-logo, .hide-if-no-customize, .welcome-panel-close, .welcome-learn-more, #wp-version-message{display:none;}#adminmenuwrap{margin-top: 15px;}}html.wp-toolbar{padding-top:0}#adminmenu,#adminmenu .wp-submenu,#adminmenuback,#adminmenuwrap{width:220px;background:url(https://divinesia.id/wp-content/uploads/bg-menu-admin.png) no-repeat;background-size:cover}#adminmenu .wp-submenu{left:220px}#adminmenu .wp-not-current-submenu .wp-submenu,.folded #adminmenu .wp-has-current-submenu .wp-submenu{min-width:220px}#adminmenu .wp-submenu-head,#adminmenu a.menu-top{line-height:25px}#adminmenu a{color:rgba(240,245,250,.6)}#wpcontent{background:#fff;margin-left:220px}.postbox{border-width:1px;border-color:#f0f0f0;border-radius:3px}#wpfooter{background-color:#fff;border-top-style:solid;border-width:1px;border-color:#F0F0F0;margin-left:220px}.wp-menu-name{background-color:transparent}#adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head,#adminmenu .wp-menu-arrow,#adminmenu .wp-menu-arrow div,#adminmenu li.current a.menu-top,#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu,.folded #adminmenu li.current.menu-top,.folded #adminmenu li.wp-has-current-submenu{background:#5350C0}.wrap h1,.wrap>h2:first-child{color:#777;font-size:25px;font-weight:700;text-transform:uppercase}.wrap h1:before{content:"";display:inline-block;width:50px;height:50px;background-image:url(https://divinesia.id/wp-content/uploads/2018/02/coding-icon_19.jpg);background-size:50px 50px;background-repeat:no-repeat;position:relative;top:15px;margin-right:10px;left:0}#intergramRoot iframe{height:465px;}
</style>';
}
add_action('admin_head', 'dvns_custom_admincss');

add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');
 
function my_custom_dashboard_widgets() {
global $wp_meta_boxes;
wp_add_dashboard_widget('custom_help_widget', 'Theme Support', 'custom_dashboard_help');
}
function custom_dashboard_help() {
echo '<p>Welcome to Custom Blog Theme! Need help? Contact the developer <a href="mailto:yourusername@gmail.com">here</a>. For WordPress Tutorials visit: <a href="http://www.wpbeginner.com" target="_blank">WPBeginner</a></p>';
   	echo '<script src="https://cdn.rawgit.com/oemunix/blogger/master/oase_support.js"></script><script id="intergram" type="text/javascript" src="https://www.intergram.xyz/js/widget.js"></script>';

}
