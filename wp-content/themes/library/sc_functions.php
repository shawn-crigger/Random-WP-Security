<?php

// prevent direct script action.
if ( isset( $_SERVER['SCRIPT_FILENAME'] ) && ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) === basename( $_SERVER['SCRIPT_FILENAME'] ) ) :
    die ('Please do not load this page directly. Thanks!');
endif;

// ------------------------------------------------------------------------

if ( ! function_exists( 'dump') ) :

  /**
    * Debug Helper
    *
    * Outputs the given variable(s) with formatting and location
    *
    * @access        public
    * @param        mixed    variables to be output
    */
  function dump()
  {
      list($callee) = debug_backtrace();
      $arguments = func_get_args();
      $total_arguments = count($arguments);

      echo '<fieldset style="background: #fefefe !important; border:2px red solid; padding:5px">';
      echo '<legend style="background:lightgrey; padding:5px;">'.$callee['file'].' @ line: '.$callee['line'].'</legend><pre>';
      $i = 0;
      foreach ($arguments as $argument)
      {
          echo '<br/><strong>Debug #'.(++$i).' of '.$total_arguments.'</strong>: ';
          var_dump($argument);
      }

      echo "</pre>";
      echo "</fieldset>";
  }

endif;

// ------------------------------------------------------------------------

/**
 * Removes links from the WP admin menu, I use this to remove un-needed optons for end users
 * @return void
 */
function sc_remove_menus()
{

	remove_menu_page( 'edit.php' );                   //Posts
	remove_menu_page( 'edit-comments.php' );          //Comments
	remove_menu_page( 'tools.php' );                  //Tools
	remove_menu_page( 'link-manager.php' );           //Links

	remove_submenu_page( 'themes.php', 'theme-editor.php' );
	remove_submenu_page( 'themes.php', 'themes.php' );
	remove_submenu_page( 'themes.php', 'customize.php' );
	//remove_menu_page( 'themes.php' );                 //Appearance
	//remove_menu_page( 'plugins.php' );                //Plugins
	//remove_menu_page( 'users.php' );                  //Users
	//remove_menu_page( 'edit.php?post_type=page' );    //Pages
	//remove_menu_page( 'options-general.php' );        //Settings
}

// ------------------------------------------------------------------------

/**
 * Fixs empty meta titles for home page and any other page that title is not in database.
 * @link   http://codex.wordpress.org/Function_Reference/wp_title
 * @param  string $title
 * @return string
 */
function sc_fix_title( $title = '' )
{
	if( empty( $title ) && ( is_home() OR is_front_page() ) )
	{
		return get_bloginfo( 'description' ) . ' | ' . __( 'Home', 'theme_domain' );
	}

	return $title;
}

// ------------------------------------------------------------------------


/**
 * remove_jquery_cache_busters
 * @uses   add_filter( 'script_loader_src' )
 * @uses   add_filter( 'style_loader_src' )
 * @param  string $src
 * @return string
 */
function _remove_script_version( $src )
{
    $parts = explode( '?', $src );
    return $parts[0];
}

// ------------------------------------------------------------------------

/**
 * View PDF Shortcode
 *
 * Simple embedding PDF, Presentation file to blog post with Google view.
 * @author Street Walker <street.walker@masedi.net>
 * @link http://googlesystem.blogspot.com/2009/09/embeddable-google-document-viewer.html
 * @link http://www.wprecipes.com/wordpress-tip-create-a-pdf-viewer-shortcode
 * @link  http://docs.google.com/viewer?url=http://infolab.stanford.edu/pub/papers/google.pdf&embedded=true
 * @link  http://docs.google.com/gview?url=http://infolab.stanford.edu/pub/papers/google.pdf&embedded=true
 * @internal  [embedpdf width="600px" height="500px"]http://infolab.stanford.edu/pub/papers/google.pdf[/embedpdf]
 *
 * @since 2.0
 *
 * Supported attributes are 'id', 'url', 'width' and 'height'.
 * @param $atts::id Define the HTML class. Default is 'default-embend'.
 * @param $atts::url Define the ahref source of the PDF. Can be HTTP or FTP.
 * @param $atts::width Define the width of the PDF inline on page or post.
 * @param $atts::height Define the height of the PDF inline on page or post.
 *
 * @param $atts Attributes for the shortcode.
 * @param string $content Not Optional. Shortcode content.
 * @return string
 */
function viewpdf($atts, $content = null)
{
	$url = get_site_url();
	$url = trailingslashit( $url ) . 'wp-content/uploads/2011/10/';
//http://cardepotmb.com/wp-content/uploads/2011/10/SBAUTO2690.pdf
    extract(shortcode_atts(array(
        'id'     => 'default-embed',
        'url'    => $url . 'SBAUTO2690.pdf',
        'width'  => '595px',
        'height' => '446px'
    ), $atts));
    return '<iframe id="' . $id . '" src="http://docs.google.com/viewer?url=' . $url . '&embedded=true" style="width:' . $width . '; height:' . $height . ';" frameborder="0">Your browser should support iFrame to view this PDF document</iframe>';
}
add_shortcode('embedpdf', 'viewpdf');
// end of viewpdf()


// ------------------------------------------------------------------------

/**
 * SEO Meta tags
 * @return void
 */
function sc_add_meta_tags()
{
    global $post;

    if ( is_home() OR ! is_single() )
    {
    	$meta = get_bloginfo('description', 'display');
	} else
    {
        $meta = strip_tags( $post->post_content );
        $meta = strip_shortcodes( $post->post_content );
        $meta = str_replace( array("\n", "\r", "\t"), ' ', $meta );
        $meta = substr( $meta, 0, 125 );
        $keywords = get_the_category( $post->ID );
        $metakeywords = '';
        foreach ( $keywords as $keyword )
        {
            $metakeywords .= $keyword->cat_name . ", ";
        }
    }

    if ( isset( $meta ) && ! empty( $meta ) )
    {
    	echo '<meta name="description" content="' . $meta . '" />' . "\n";
    }

    if ( isset( $metakeywords ) && ! empty( $metakeywords ) )
    {
	    echo '<meta name="keywords" content="' . $metakeywords . '" />' . "\n";
    }

/*
	$meta_author = get_option( 'ptthemes_meta_author' );
	if ( ! empty( $meta_author ) )
	{
		$meta_author = stripslashes( $meta_author );
		echo "\t<meta name=\"author\" content=\"{$meta_author}\" />\n";
	}
*/

	// robots
	if ( is_category() OR is_tag() )
	{
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		if ( $paged > 1 )
		{
			echo  "\t<meta name=\"robots\" content=\"noindex,follow\" /> \n";
		} else {
			echo  "\t<meta name=\"robots\" content=\"index,follow\" />\n";
		}
	} else if ( is_home() OR is_singular() )
	{
		echo  "\t<meta name=\"robots\" content=\"index,follow\" />\n";
	} else {
		echo  "\t<meta name=\"robots\" content=\"noindex,follow\" />\n";
	}

}

// ------------------------------------------------------------------------

/**
 * CUSTOM MENU LINK FOR ALL SETTINGS - WILL ONLY APPEAR FOR ADMIN
 * @uses   add_action('admin_menu', '')
 * @return void
 */
function all_settings_link()
{
	add_options_page(__('All Settings'), __('All Settings'), 'administrator', 'options.php');
}

// ------------------------------------------------------------------------
// ACTIONS and FILTERS
// ------------------------------------------------------------------------

@date_default_timezone_set(get_option('timezone_string')); // }}}

// ------------------------------------------------------------------------
// Optional but handy
// ------------------------------------------------------------------------


// adds a ALL settings link in the wp-admin area
add_action('admin_menu', 'all_settings_link');
// quick and easy WP meta tags
add_action( 'wp_head', 'sc_add_meta_tags' , 2 );
// fixs title on home page
add_filter( 'wp_title', 'sc_fix_title' );

// removes various admin menu links to make it easier for end user.
add_action( 'admin_menu', 'sc_remove_menus' );
// ------------------------------------------------------------------------

// removes script versions to allow
add_filter( 'script_loader_src', '_remove_script_version', 15, 1 );
add_filter( 'style_loader_src',  '_remove_script_version', 15, 1 );

remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
remove_action( 'wp_head', 'index_rel_link' ); // index link
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post.
remove_action( 'wp_head', 'wp_generator' ); // Display the XHTML generator that is generated on the wp_head hook, WP version


// ------------------------------------------------------------------------
// Theme support I highly suggest changing the post thumbnail size to one you use.
// ------------------------------------------------------------------------

add_theme_support( 'post-thumbnails' );
add_image_size( 'post-thumbnail-140x130', 140, 130 );
