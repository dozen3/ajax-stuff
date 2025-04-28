<?php add_filter('wp_calculate_image_srcset', '__return_false');

// add_action('send_headers', function(){
//     // Enforce the use of HTTPS
// 	header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
// 	// Prevent Clickjacking
// 	header("X-Frame-Options: SAMEORIGIN");
//     // Block Access If XSS Attack Is Suspected
// 	header("X-XSS-Protection: 1; mode=block");
// 	// Prevent MIME-Type Sniffing
// 	header("X-Content-Type-Options: nosniff");
// }, 1);

if (!function_exists('twelvethree_setup')):  
 
    function twelvethree_setup()
    {
         
      //  add_theme_support('automatic-feed-links');
      
        // Enable support for Post Thumbnails on posts and pages
        add_theme_support('post-thumbnails');
        //add_image_size( 'blog-thumb', 360, 200, true );
        // add_image_size( 'blog-full', 1024, 569, true );
        // This theme uses wp_nav_menu() in two locations.
        register_nav_menus(array(
            'primary' => __('Primary Menu', 'twelvethree'),
            'mobile' => __('Mobile Menu', 'twelvethree'),
           
        ));
        //woo
        
        // Enable support for HTML5 markup.
        add_theme_support('html5', array(
            'comment-list',
            'search-form',
            'comment-form'
        ));
        
        // Add support for title tag
        add_theme_support('title-tag');
        add_theme_support( 'responsive-embeds' );
    }
endif;  

add_action('after_setup_theme', 'twelvethree_setup');
 

function twelvethree_widgets_init()
{
    register_sidebar(array(
        'name' => __('Blog Sidebar', 'twelvethree'),
        'id' => 'blog-sidebar',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<div class="widget-title">',
        'after_title' => '</div>'
    ));
        register_sidebar(array(
        'name' => __('Page Sidebar', 'twelvethree'),
        'id' => 'page-sidebar',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<div class="widget-title">',
        'after_title' => '</div>'
    ));
   
        
}
add_action('widgets_init', 'twelvethree_widgets_init');

 add_filter( 'gform_disable_form_theme_css', '__return_true' );
 add_filter( 'gform_disable_css', '__return_true' );
//remove yoast generated
add_filter( 'wpseo_video_yandex_support', '__return_false' );
add_action( 'template_redirect', function () {
 
    if ( ! class_exists( 'WPSEO_Frontend' ) ) {
        return;
    }
 
    $instance = WPSEO_Frontend::get_instance();
 
   
    if ( ! method_exists( $instance, 'debug_mark') ) {
        return ;
    }
 
   
     remove_action( 'wpseo_head', array( $instance, 'debug_mark' ), 2 );
}, 9999 );
 

function twelvethree_scripts()
{
	 
//wp_deregister_script('jquery');
// wp_register_script('jquery', includes_url('/js/jquery/jquery.js'), false, null, true);
 wp_enqueue_script('jquery');
 

}
add_action('wp_enqueue_scripts', 'twelvethree_scripts' );
 
 
 //added for tailwind PRODUCTION inline css
 //REMOVE FOR DEV
// add_action( 'wp_head', 'inline_css' );
  function inline_css() {
    
     
      $twstylelastmod = filemtime(get_stylesheet_directory().'/style.min.css');
      $twspeed = filemtime(get_stylesheet_directory().'/speed/_css.php');
     
     
    if ($twstylelastmod > $twspeed) {
    
    $twstyles = file_get_contents( get_stylesheet_directory().'/style.min.css' );
    file_put_contents( get_stylesheet_directory().'/speed/_css.php', $twstyles );
    
                     }
      echo '<style type="text/css">';
      include_once get_stylesheet_directory().'/speed/_css.php';
      echo '</style>';
   }
 
 //keep for legacy type of work
 //add_action( 'wp_head', 'merge_include_css' );
 function merge_include_css() {
    
   $styleLastMod = filemtime(get_stylesheet_directory().'/style.css');
   $_cssLastMod = filemtime(get_stylesheet_directory().'/speed/_css.php');
 
     if ($styleLastMod > $_cssLastMod) {
     // $theme = wp_get_theme();
     $mergedCSS = '';
 
    // $cssFiles = glob(get_stylesheet_directory() . '/css/*.css');
     //$cssFiles2 = glob(get_stylesheet_directory() . '/css/blocks/*.css');
 
    // foreach($cssFiles as $file) {
     //  $mergedCSS .= file_get_contents($file);
    // }
 
    // foreach($cssFiles2 as $file) {
     ////  $mergedCSS2 .= file_get_contents($file);
    // }
    // $drawer = file_get_contents( get_stylesheet_directory().'/vendor/drawer.min.css' );
    $styles = file_get_contents( get_stylesheet_directory().'/style.css' );
    //$boot = file_get_contents( get_stylesheet_directory().'/vendor/bootstrap/css/boostrapT3.min.css' );
    $mmenu = file_get_contents( get_stylesheet_directory().'/vendor/mmenu/mmenu-light.css' );
    
     
    // $styles = str_replace( "images", site_url()."/wp-content/themes/$theme->template/images", $styles );
     // $mergedCSS2 = str_replace( "images", site_url()."/wp-content/themes/$theme->template/images", $mergedCSS2 );
    // $styles = str_replace( "fonts", site_url()."/wp-content/themes/$theme->template/fonts", $styles );
      // $mergedCSS .= $drawer;
     // $mergedCSS .= $boot;
      $mergedCSS .= $mmenu;
      $mergedCSS .= $styles;
     
 
 
     $mergedCSS = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $mergedCSS);
     $mergedCSS = str_replace(': ', ':', $mergedCSS);
     $mergedCSS = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $mergedCSS);
 
     file_put_contents( get_stylesheet_directory().'/speed/_css.php', $mergedCSS );
     file_put_contents( get_stylesheet_directory().'/speed/style.min.css', $mergedCSS );
     
     
     //echo '<style type="text/css">';
     //include_once get_stylesheet_directory().'/speed/_css.php';
     //echo '</style>';
     echo '<link rel="stylesheet"  href="'.get_template_directory_uri().'/speed/style.min.css?ver='.$_cssLastMod.'" type="text/css" media="all" />';
    } else {
     //echo '<style type="text/css">';
     //include_once get_stylesheet_directory().'/speed/_css.php';
      //echo '</style>';
      echo '<link rel="stylesheet"  href="'.get_template_directory_uri().'/speed/style.min.css?ver='.$_cssLastMod.'" type="text/css" media="all" />';
   }
 }
  
 
 //Remove Gutenberg Block Library CSS from loading on the frontend!
 function smartwp_remove_wp_block_library_css(){
  wp_dequeue_style( 'wp-block-library' );
  wp_dequeue_style( 'wp-block-library-theme' );
  wp_dequeue_style( 'wc-blocks-style' ); // Remove WooCommerce block CSS
  wp_dequeue_style( 'global-styles' );
 } 
 add_action( 'wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100 );
   


 //remove query string from scripts
function eh_remove_wp_ver_css_js($src)
{
    if (strpos($src, 'ver=' . get_bloginfo('version')))
        $src = remove_query_arg('ver', $src);
    return $src;
}
add_filter('style_loader_src', 'eh_remove_wp_ver_css_js', 9999);
add_filter('script_loader_src', 'eh_remove_wp_ver_css_js', 9999);

 
function disable_wp_emojicons()
{
    // remove all actions related to emojis
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    add_filter('emoji_svg_url', '__return_false');
    
    
}
add_action('init', 'disable_wp_emojicons');
 
//walker
 require_once get_template_directory() . '/class-wp-mobile-navwalker.php';
 require_once get_template_directory() . '/class-wp-tw-navwalker.php';

 //paging nav
 function twelvethree_posts_nav() {
 
   if( is_singular() )
     return;
 
   global $wp_query;
   
   /** Stop execution if there's only 1 page */
   if( $wp_query->max_num_pages <= 1 ) 
     return;
 
   $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
   $max   = intval( $wp_query->max_num_pages );
 
   /**	Add current page to the array */
   if ( $paged >= 1 )
     $links[] = $paged;
 
   /**	Add the pages around the current page to the array */
   if ( $paged >= 3 ) {
     $links[] = $paged - 1;
     $links[] = $paged - 2;
   }
 
   if ( ( $paged + 2 ) <= $max ) {
     $links[] = $paged + 2;
     $links[] = $paged + 1;
   }
     
     $li_class = 'w-[20px] h-[20px] m-1 p-4 text-black flex justify-center items-center [&_a]:text-white';
     $bg_class = 'bg-[#BDBDBD]';
     $bg_active_class = 'bg-secondary [&_a]:!text-black';
 
   echo '<ul class="my-10 flex justify-center items-center">' . "\n";
 
    
   if ( get_previous_posts_link() )
     printf( '<li style="width:auto" class="%s">%s</li>' . "\n", "$li_class $bg_class", get_previous_posts_link( 'Previous Page' ) );
  
   if ( ! in_array( 1, $links ) ) {
     printf( '<li class="%s"><a href="%s">%s</a></li>' . "\n", "$li_class $bg_class", esc_url( get_pagenum_link( 1 ) ), '1' );
         
     if ( ! in_array( 2, $links ) )
       echo '<li class="m-1 w-[20px] h-[20px] flex justify-center items-center [&_a]:text-white">…</li>';
   }
     
    
   sort( $links );
   foreach ( (array) $links as $link ) {
         
         if( $paged == $link ) {
             $class = "$li_class $bg_active_class";
             $open_tag = '<span>';
             $close_tag = '</span>';
         } else {
             $class = "$li_class $bg_class";
             $open_tag = sprintf( '<a href="%s">', esc_url( get_pagenum_link( $link ) ) );
             $close_tag = '</a>';
         }
     
         printf(
             '<li class="%s">%s%s%s</li>' . "\n",
             $class,
             $open_tag,
             $link,
             $close_tag
         );
   }
 
    
   if ( ! in_array( $max, $links ) ) {
     if ( ! in_array( $max - 1, $links ) )
       echo '<li class="m-1 w-[20px] h-[20px] flex justify-center items-center">…</li>' . "\n";
 
     $class = $paged == $max ? ' class="active"' : '';
     printf( '<li class="%s"><a href="%s">%s</a></li>' . "\n", "$li_class $bg_class", esc_url( get_pagenum_link( $max ) ), $max );
   }
 
  
   if ( get_next_posts_link() ) 
     printf( '<li style="width:auto" class="%s">%s</li>' . "\n", "$li_class $bg_active_class", get_next_posts_link( 'Next Page' ) );
 
   echo '</ul>' . "\n";
 
 }

 //numeric posts
function numeric_posts_nav() {
 
    if( is_singular() )
        return;
 
    global $wp_query;
 
    /** Stop execution if there's only 1 page */
    if( $wp_query->max_num_pages <= 1 )
        return;
 
    $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
    $max   = intval( $wp_query->max_num_pages );
 
    /** Add current page to the array */
    if ( $paged >= 1 )
        $links[] = $paged;
 
    /** Add the pages around the current page to the array */
    if ( $paged >= 3 ) {
        $links[] = $paged - 1;
        $links[] = $paged - 2;
    }
 
    if ( ( $paged + 2 ) <= $max ) {
        $links[] = $paged + 2;
        $links[] = $paged + 1;
    }
 
    echo '<div class="navigation"><ul>' . "\n";
 
    /** Previous Post Link */
    if ( get_previous_posts_link() )
        printf( '<li class="npl">%s</li>' . "\n", get_previous_posts_link('&laquo; Previous') );
 
    /** Link to first page, plus ellipses if necessary */
    if ( ! in_array( 1, $links ) ) {
        $class = 1 == $paged ? ' class="active"' : '';
 
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );
 
        if ( ! in_array( 2, $links ) )
            echo '<li>…</li>';
    }
 
    /** Link to current page, plus 2 pages in either direction if necessary */
    sort( $links );
    foreach ( (array) $links as $link ) {
        $class = $paged == $link ? ' class="active"' : '';
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
    }
 
    /** Link to last page, plus ellipses if necessary */
    if ( ! in_array( $max, $links ) ) {
        if ( ! in_array( $max - 1, $links ) )
            echo '<li>…</li>' . "\n";
 
        $class = $paged == $max ? ' class="active"' : '';
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
    }
 
    /** Next Post Link */
    if ( get_next_posts_link() )
        printf( '<li class="npl">%s</li>' . "\n", get_next_posts_link('Next &raquo;') );
 
    echo '</ul></div>' . "\n";
 
}

//content limit
  function content($limit) {
      $content = explode(' ', get_the_content(), $limit);
      if (count($content)>=$limit) {
        array_pop($content);
        $content = implode(" ",$content).'...';
      } else {
        $content = implode(" ",$content);
      } 
      $content = preg_replace('/\[.+\]/','', $content);
      $content = apply_filters('the_content', $content); 
      $content = str_replace(']]>', ']]&gt;', $content);
      return $content;
    }
    
 //////options page 
  if( function_exists('acf_add_options_page') ) {    
    acf_add_options_page(array(
      'page_title' 	=> 'Global Info',
      'menu_title'	=> 'Global Info',
      'menu_slug' 	=> 'global-info',
      'capability'	=> 'edit_posts',
      'redirect'		=> false
    ));
  }
  
 ///text after gravity form button
  add_filter( 'gform_submit_button_1', 'add_paragraph_below_submit', 10, 2 );
 function add_paragraph_below_submit( $button, $form ) {
 
     return $button .= "<div class='text-center'><div class='inline-block text-[#656565] text-[14px] mt-3 pl-7 bg-[url(/wp-content/themes/t3tailwind/images/shield-check.svg)] bg-center bg-left  bg-no-repeat  text-left lg:text-center'><span class='leading-[1.3] block lg:inline'>We Guarantee 100% Privacy. </span><span class='leading-[1.3] block lg:inline'>Your Information Will Not Be Shared.</span></div></div>";
 }
  
//FORM HEADER
function formhead($atts, $content = null ) {
   extract( shortcode_atts( array(
       'temp' => 'temp'
      ), $atts ) ); 
		?><?php  ob_start(); ?>

			<p>Contact Form Header</p>

		
<?php return ob_get_clean(); ?>
	<?php
}
add_shortcode('form_header', 'formhead');

function theme_slug_excerpt_length( $length ) {
        if ( is_admin() ) {
                return $length;
        }
        return 35;
}
add_filter( 'excerpt_length', 'theme_slug_excerpt_length', 999 );

// POST NAV
if ( ! function_exists( 'twelvethree_post_nav' ) ) :
 global $post;
function twelvethree_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<div class="post-navigation mt-5" role="navigation">
		 <h3 class="mb-4">More Articles For You</h3>
		 <div class="related-navigation">
			<?php /* previous_post_link( '%link', __( '< Previous', 'textdomain' ), true ); */ ?> 
			<?php /* next_post_link( '%link', __( 'Next >', 'textdomain' ), true ); */ ?> 
		 </div>
		<div class="nav-links row">
			
			<div class="col-md-6">
				<?php $prev_post = get_adjacent_post(false, '', true);
				if(!empty($prev_post)) { ?>
          <?php $prevthumbnail = get_the_post_thumbnail_url($prev_post->ID); ?>
          <?php if (!$prevthumbnail) { $prevthumbnail = '/wp-content/themes/t3tailwind/images/placeholder.jpg';} ?>
					<div class="leftlink" >
					<a href="<?php echo get_permalink($prev_post->ID);?>"><img class="img-fluid" src="<?php echo $prevthumbnail;?>"></a>
					<div class="linktitle"><a href="<?php echo get_permalink($prev_post->ID);?>"><strong><?php echo $prev_post->post_title;?></strong></a></div>
					<div class="meta"><?php echo get_the_date(); ?></div>
					</div>
				<?php } ?>
			</div>
			<div class="col-md-6">
				<?php $next_post = get_adjacent_post(false, '', false);
				if(!empty($next_post)) { ?>
          <?php $nextthumbnail = get_the_post_thumbnail_url($next_post->ID); ?>
          <?php if (!$nextthumbnail) { $nextthumbnail = '/wp-content/themes/t3tailwind/images/placeholder.jpg';} ?>
					<div class="rightlink">
					<a href="<?php echo get_permalink($next_post->ID);?>"><img  class="img-fluid" src="<?php echo $nextthumbnail;?>"></a>
					<div class="linktitle"><a href="<?php echo get_permalink($next_post->ID);?>"><strong><?php echo $next_post->post_title;?></strong></a></div>
					<div class="meta"><?php echo get_the_date(); ?></div>
					</div>
				<?php } ?>
			</div>


		</div>
	</div>
	<?php }
endif;
 

 
  
  function my_nav_menu_link_attributes( $atts, $item, $args ) {
      //if ( 'primary' === $args->theme_location ) {
          if ( '0' === $item->menu_item_parent ) {
              $atts['class'] = 'topp';
          } 
     // }
      return $atts;
  }
  add_filter( 'nav_menu_link_attributes', 'my_nav_menu_link_attributes', 10, 3 );
  

function post_thumbnail_html_fallback_image( string $html, int $post_id, int $post_thumbnail_id, string|array $size, string|array $attr ) {
  if( ! empty( $html ) ) return $html;
  $default_attr = array(
    'src'   => get_stylesheet_directory_uri() . '/images/fromen-law-office.jpg',
    'width' => '426',
    'height' => '366',
    'class' => "attachment-$size_class size-$size_class w-full h-auto object-cover",
    'alt'   => 'Fromen Law Office',
  );
  $attr = wp_parse_args( $attr, $default_attr );  
  $atts = '';
  foreach( $attr as $att => $val ) {
    $atts .= sprintf( ' %s="%s"', $att, $val );
  }
  return sprintf( '<img %s>', $atts );
}
add_filter( 'post_thumbnail_html', 'post_thumbnail_html_fallback_image', 10, 5 );
 
 /* Custom Blocks */
 add_action( 'init', function() {
   
   register_block_type( __DIR__ . '/blocks/content-form' );
   register_block_type( __DIR__ . '/blocks/results' );
   register_block_type( __DIR__ . '/blocks/cta' );
    register_block_type( __DIR__ . '/blocks/ctabottom' );
   register_block_type( __DIR__ . '/blocks/testimonials' );
   
 });
 
 
 /**
  * ACF Helper Functions
  */
 function acf_link( string|array $field, array $attributes = [] ) : void {
   if( is_string( $field ) ) {
     $acf = get_field( $field );
   }
   else if( is_array( $field ) ) {
     $acf = $field;
   }
   if( isset( $acf[ 'url' ] ) ) {
     $attributes[ 'href' ] = $acf[ 'url' ];
   }
   if( isset( $acf[ 'target' ] ) ) {
     $attributes[ 'target' ] = $acf[ 'target' ];
   }
   if( ! isset( $acf ) ) {
     return;
   }
   $atts = '';
   foreach( $attributes as $attribute => $value ) {
     $atts = $atts . sprintf( ' %s="%s"', $attribute, $value );
   }
   printf( '<a %s><span class="relative z-20">%s</span></a>', $atts, $acf[ 'title' ] ?? '' );
 }
 
function acf_image( string|array $field, array|null $attributes = [ 'loading' => 'lazy' ], $size = 'medium-large', $header_image_mobile = null ) : void {
     if( is_string( $field ) ) {
         $acf = get_field( $field );
     }
     else if( is_array( $field ) ) {
         $acf = $field;
     }
     $id = $acf[ 'ID' ] ?? null;
     
     // Get the page title for the alt tag
     $page_title = get_the_title();
     
     // Set alt attribute to the page title
     $attributes['alt'] = $page_title;
     $attributes['id'] = 'header_image';
     // Handle if field is a URL string
     if( null === $id && filter_var( $field, FILTER_VALIDATE_URL ) ){
         printf( '<img src="%s" class="absolute w-full h-full object-cover" alt="%s">', $field, esc_attr( $page_title ) );
         return;
     }
     
     // If $header_image_mobile is provided, add it as a mobile-image-src attribute
     if ( $header_image_mobile ) {
         $attributes['data-mobile-image-src'] = $header_image_mobile;
     }
 
     // Output the image with the new attributes
     echo wp_get_attachment_image( $id, $size, false, $attributes );
 }


function acf_field( string $selector, mixed $default, ?int $post_id = null ) : mixed {
  $field = get_field( $selector, $post_id );
  if( ! $field ) $field = $default;
  return $field;
}
 //BEGIN NEW CONVERSION TECHNIQUE
 function execute_custom_js_in_footer() {
     
          ?>
          <style type="text/css">
             
            .gform_ajax_spinner,.gform-loader {
              background: url('/wp-content/plugins/gravityforms/images/spinner.svg') no-repeat center;
              display: inline-block;
              width: 20px;
              height: 20px;
            }
          
          </style>
         <script type="text/javascript">
          
                 // Function to set a cookie with a unique name
                              function setDataLayerCookie(name, value, days) {
                                  var date = new Date();
                                  date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                                  var expires = "expires=" + date.toUTCString();
                                  document.cookie = name + "=" + value + ";" + expires + ";path=/";
                              }
                 
                              // Function to delete a cookie with a unique name
                              function deleteDataLayerCookie(name) {
                                  document.cookie = name + '=; Max-Age=-99999999; path=/';
                              }
                 
                 
                  //Form must redirect to 'thank-you', must be AJAX enabled
                   // Ensure this script runs after Gravity Forms' scripts are fully loaded
                   document.addEventListener('gform/theme/scripts_loaded', () => {
                       // Add an asynchronous filter to handle actions before form submission
                       gform.utils.addAsyncFilter('gform/submission/pre_submission', async (data) => {
                           const form = data.form; // The form element
                           const formId = form.getAttribute('data-formid');
                           console.log('Form submit initiated for form ID:', formId);
                   
                           // Create a FormData object from the form
                           const formData = new FormData(form);
                           const gformData = { formId: formId };
                   
                           // Process each form field
                           formData.forEach((value, key) => {
                               if (key) {
                                   const formattedKey = key.replace('.', '_'); // Replace dots with underscores
                                   const sanitizedValue = value.replace(/;/g, ''); // Remove semicolons
                                   gformData[formattedKey] = sanitizedValue;
                               }
                           });
                   
                           console.log('Processed form data:', gformData);
                   
                           // Remove existing cookie
                           deleteDataLayerCookie('GFormData');
                   
                           // Set new cookie with gformData
                           setDataLayerCookie('GFormData', JSON.stringify(gformData), 30);
                           console.log('Cookie should be set');
                   
                           // Delay the submission to ensure the cookie is set
                           await new Promise(resolve => setTimeout(resolve, 2000));
                           console.log('Continuing form submission after delay');
                   
                           return data; // Proceed with the form submission
                       });
                   }); //end new script
                  </script>
          <?php     //Thank you page
          $current_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
          
          // Check if 'thank-you' is in the URL
          if (strpos($current_url, 'thank-you') !== false) { ?>
            <script>
          function deleteFormCookie(name) {
              document.cookie = name + '=; Max-Age=-99999999; path=/';
          }
              
              function getFormCookie(name) {
                  var nameEQ = name + "=";
                  var ca = document.cookie.split(';');
                  for (var i = 0; i < ca.length; i++) {
                      var c = ca[i];
                      while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                      if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
                  }
                  return null;
              }
          
          setTimeout(function() {
              
              var gformData = getFormCookie('GFormData');
              if (gformData) {
                  gformData = JSON.parse(gformData);
                  var formId = gformData.formId;
                  console.log('made it to thank you page data from form present: ' + formId);
              }
          
              window.dataLayer = window.dataLayer || [];
          
              if (gformData) {
                  dataLayer.push({ event: 'gravity_form_submit', formId: formId, inputs: gformData });
                    
                  deleteFormCookie('GFormData');
                
                  console.log('data layer sent, made it to the end, cookie deleted');
              } 
               
          }, 2000);
            </script>
         <?php }   ?>
           
            
      
          <?php
    
  } 
  add_action('wp_footer', 'execute_custom_js_in_footer'); //END NEW CONVERSION TECHNIQUE
 

 
 // AJAX Handler for Filtering Products
 function filter_products() {
    $taxonomies = array('product_category', 'application', 'project_type', 'feature', 'usage'); // Ensure this is inside the function
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'tax_query' => array(),
    );

    $hasFilters = false;

    foreach ($taxonomies as $taxonomy) {
        if (!empty($_POST[$taxonomy])) {
            $hasFilters = true;
            $args['tax_query'][] = array(
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => $_POST[$taxonomy],
            );
        }
    }

    if ($hasFilters) {
        $args['tax_query']['relation'] = 'AND';
    }

    // Keyword Search
    if (!empty($_POST['keyword'])) {
        $keyword = sanitize_text_field($_POST['keyword']);
        $args['s'] = $keyword;
    }
    // Ensure filtering applies only within the currently viewed taxonomy term
    if (!empty($_POST['currentTaxonomy']) && !empty($_POST['currentTerm'])) {
        $args['tax_query'][] = array(
            'taxonomy' => sanitize_text_field($_POST['currentTaxonomy']),
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_POST['currentTerm']),
        );
    }

    $query = new WP_Query($args);
    ob_start();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            echo "<div class='product-item p-10 border border-black bg-bg-gray-300'>";
            echo "<h3>" . get_the_title() . "</h3>";
        
            echo "<p>" . get_the_excerpt() . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No products found.</p>";
    }

    wp_reset_postdata();

    $filter_counts = array();
    foreach ($taxonomies as $taxonomy) {
        $terms = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => false));
        $counts = array();
        foreach ($terms as $term) {
            $term_count_args = $args;
            $term_count_args['tax_query'][] = array(
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => $term->slug,
            );

            $term_query = new WP_Query($term_count_args);
            $counts[$term->slug] = $term_query->found_posts;
        }
        $filter_counts[$taxonomy] = $counts;
    }

    error_log("Product Counts: " . print_r($filter_counts, true));

    echo json_encode(array(
        'products' => ob_get_clean(),
        'counts' => $filter_counts
    ));
    die();
 }
 
 add_action('wp_ajax_filter_products', 'filter_products');
 add_action('wp_ajax_nopriv_filter_products', 'filter_products');
 
 // Enqueue Scripts and Localize AJAX URL
 function enqueue_filter_scripts() {
    wp_enqueue_script('ajax-filter', get_template_directory_uri() . '/scripts.js', array('jquery'), filemtime(get_template_directory() . '/scripts.js'), true);


     wp_localize_script('ajax-filter', 'ajax_vars', array(
         'ajaxurl' => admin_url('admin-ajax.php')
     ));
 }
 add_action('wp_enqueue_scripts', 'enqueue_filter_scripts');