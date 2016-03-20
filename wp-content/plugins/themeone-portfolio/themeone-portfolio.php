<?php
/*
  Plugin Name: Themeone Portfolio
  Plugin URI: themeone-portfolio
  Description: Themeone portfolio post type with taxonomies.
  Version: 1.1
  Author: ThemeOne
  Author URI: http://www.theme-one.com
  Text Domain: mobius
  Domain Path: /lang
 */



if ( ! class_exists( 'Portfolio_Post_Type' ) ) :

class Portfolio_Post_Type {

	var $version = 1;
	
	function __construct() {
		load_plugin_textdomain( 'mobius', FALSE, dirname(plugin_basename(__FILE__)).'/lang' );
		register_activation_hook( __FILE__, array( &$this, 'plugin_activation' ) );
		add_action( 'init', array( &$this, 'portfolio_init' ) );
		add_theme_support( 'post-thumbnails', array( 'portfolio' ) );
		add_filter( 'manage_edit-portfolio_columns', array( &$this, 'add_thumbnail_column'), 10, 1 );
		add_action( 'manage_posts_custom_column', array( &$this, 'display_thumbnail' ), 10, 1 );
		add_action( 'restrict_manage_posts', array( &$this, 'add_taxonomy_filters' ) );
		add_action( 'right_now_content_table_end', array( &$this, 'add_portfolio_counts' ) );
	}

	function plugin_activation() {
		$this->portfolio_init();
		flush_rewrite_rules();
	}
	
	function portfolio_init() {
	
		$labels = array(
			'name' => __( 'Portfolio', 'mobius' ),
			'singular_name' => __( 'Portfolio Item', 'mobius' ),
			'add_new' => __( 'Add New Item', 'mobius' ),
			'add_new_item' => __( 'Add New Portfolio Item', 'mobius' ),
			'edit_item' => __( 'Edit Portfolio Item', 'mobius' ),
			'new_item' => __( 'Add New Portfolio Item', 'mobius' ),
			'view_item' => __( 'View Item', 'mobius' ),
			'search_items' => __( 'Search Portfolio', 'mobius' ),
			'not_found' => __( 'No portfolio items found', 'mobius' ),
			'not_found_in_trash' => __( 'No portfolio items found in trash', 'mobius' )
		);
		
		$args = array(
	    	'labels' => $labels,
	    	'public' => true,
			'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'author' ),
			'capability_type' => 'post',
			'rewrite' => array("slug" => "portfolio"), 
			'menu_position' => 5,
			'menu_icon' => 'dashicons-format-image',
			'has_archive' => true
		);
		
		$args = apply_filters('portfolioposttype_args', $args);
	
		register_post_type( 'portfolio', $args );
		
		$taxonomy_portfolio_tag_labels = array(
			'name' => _x( 'Portfolio Tags', 'mobius' ),
			'singular_name' => _x( 'Portfolio Tag', 'mobius' ),
			'search_items' => _x( 'Search Portfolio Tags', 'mobius' ),
			'popular_items' => _x( 'Popular Portfolio Tags', 'mobius' ),
			'all_items' => _x( 'All Portfolio Tags', 'mobius' ),
			'parent_item' => _x( 'Parent Portfolio Tag', 'mobius' ),
			'parent_item_colon' => _x( 'Parent Portfolio Tag:', 'mobius' ),
			'edit_item' => _x( 'Edit Portfolio Tag', 'mobius' ),
			'update_item' => _x( 'Update Portfolio Tag', 'mobius' ),
			'add_new_item' => _x( 'Add New Portfolio Tag', 'mobius' ),
			'new_item_name' => _x( 'New Portfolio Tag Name', 'mobius' ),
			'separate_items_with_commas' => _x( 'Separate portfolio tags with commas', 'mobius' ),
			'add_or_remove_items' => _x( 'Add or remove portfolio tags', 'mobius' ),
			'choose_from_most_used' => _x( 'Choose from the most used portfolio tags', 'mobius' ),
			'menu_name' => _x( 'Portfolio Tags', 'mobius' )
		);
		
		$taxonomy_portfolio_tag_args = array(
			'labels' => $taxonomy_portfolio_tag_labels,
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_tagcloud' => true,
			'hierarchical' => false,
			'rewrite' => array( 'slug' => 'portfolio_tag' ),
			'show_admin_column' => true,
			'query_var' => true
		);
		
		register_taxonomy( 'portfolio_tag', array( 'portfolio' ), $taxonomy_portfolio_tag_args );
	
	    $taxonomy_portfolio_category_labels = array(
			'name' => _x( 'Portfolio Categories', 'mobius' ),
			'singular_name' => _x( 'Portfolio Category', 'mobius' ),
			'search_items' => _x( 'Search Portfolio Categories', 'mobius' ),
			'popular_items' => _x( 'Popular Portfolio Categories', 'mobius' ),
			'all_items' => _x( 'All Portfolio Categories', 'mobius' ),
			'parent_item' => _x( 'Parent Portfolio Category', 'mobius' ),
			'parent_item_colon' => _x( 'Parent Portfolio Category:', 'mobius' ),
			'edit_item' => _x( 'Edit Portfolio Category', 'mobius' ),
			'update_item' => _x( 'Update Portfolio Category', 'mobius' ),
			'add_new_item' => _x( 'Add New Portfolio Category', 'mobius' ),
			'new_item_name' => _x( 'New Portfolio Category Name', 'mobius' ),
			'separate_items_with_commas' => _x( 'Separate portfolio categories with commas', 'mobius' ),
			'add_or_remove_items' => _x( 'Add or remove portfolio categories', 'mobius' ),
			'choose_from_most_used' => _x( 'Choose from the most used portfolio categories', 'mobius' ),
			'menu_name' => _x( 'Portfolio Categories', 'mobius' ),
	    );
		
	    $taxonomy_portfolio_category_args = array(
			'labels' => $taxonomy_portfolio_category_labels,
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_admin_column' => true,
			'show_tagcloud' => true,
			'hierarchical' => true,
			'rewrite' => array( 'slug' => 'portfolio_category' ),
			'query_var' => true
	    );
		
	    register_taxonomy( 'portfolio_category', array( 'portfolio' ), $taxonomy_portfolio_category_args );
		
		$taxonomy_portfolio_attributes_labels = array(
			'name' => _x( 'Portfolio Attributes', 'mobius'),
			'singular_name' => _x( 'Portfolio Attribute', 'mobius'),
			'search_items' =>  _x( 'Search Portfolio Attributes', 'mobius'),
			'all_items' => _x( 'All Portfolio Attributes', 'mobius'),
			'parent_item' => _x( 'Parent Portfolio Attribute', 'mobius'),
			'edit_item' => _x( 'Edit Portfolio Attribute', 'mobius'),
			'update_item' => _x( 'Update Portfolio Attribute', 'mobius'),
			'add_new_item' => _x( 'Add New Portfolio Attribute', 'mobius'),
			'new_item_name' => _x( 'New Portfolio Attribute', 'mobius'),
			'menu_name' => _x( 'Portfolio Attributes', 'mobius')
		); 	
		
		$taxonomy_portfolio_attributes_args = array(
			'labels' => $taxonomy_portfolio_attributes_labels,
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_admin_column' => true,
			'show_tagcloud' => true,
			'hierarchical' => true,
			'rewrite' => array( 'slug' => 'portfolio_attributes' ),
			'query_var' => true
	    );
		
		register_taxonomy( 'portfolio_attributes', array( 'portfolio' ), $taxonomy_portfolio_attributes_args );
		
	}

	function add_thumbnail_column( $columns ) {
	
		$column_thumbnail = array( 'thumbnail' => __('Thumbnail','mobius' ) );
		$columns = array_slice( $columns, 0, 2, true ) + $column_thumbnail + array_slice( $columns, 1, NULL, true );
		return $columns;
	}
	
	function display_thumbnail( $column ) {
		global $post;
		switch ( $column ) {
			case 'thumbnail':
				echo get_the_post_thumbnail( $post->ID, array(60, 60) );
				break;
		}
	}
	 
	function add_taxonomy_filters() {
		global $typenow;
		$taxonomies = array( 'portfolio_category', 'portfolio_tag' );
		
		if ( $typenow == 'portfolio' ) {
			foreach ( $taxonomies as $tax_slug ) {
				$current_tax_slug = isset( $_GET[$tax_slug] ) ? $_GET[$tax_slug] : false;
				$tax_obj = get_taxonomy( $tax_slug );
				$tax_name = $tax_obj->labels->name;
				$terms = get_terms($tax_slug);
				if ( count( $terms ) > 0) {
					echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
					echo "<option value=''>$tax_name</option>";
					foreach ( $terms as $term ) {
						echo '<option value=' . $term->slug, $current_tax_slug == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
					}
					echo "</select>";
				}
			}
		}
	}
	
	function add_portfolio_counts() {
		
	        if ( ! post_type_exists( 'portfolio' ) ) {
	             return;
	        }
	
	        $num_posts = wp_count_posts( 'portfolio' );
	        $num = number_format_i18n( $num_posts->publish );
	        $text = _n( 'Portfolio Item', 'Portfolio Items', intval($num_posts->publish) );
	        if ( current_user_can( 'edit_posts' ) ) {
	            $num = "<a href='edit.php?post_type=portfolio'>$num</a>";
	            $text = "<a href='edit.php?post_type=portfolio'>$text</a>";
	        }
	        echo '<td class="first b b-portfolio">' . $num . '</td>';
	        echo '<td class="t portfolio">' . $text . '</td>';
	        echo '</tr>';
	
	        if ($num_posts->pending > 0) {
	            $num = number_format_i18n( $num_posts->pending );
	            $text = _n( 'Portfolio Item Pending', 'Portfolio Items Pending', intval($num_posts->pending) );
	            if ( current_user_can( 'edit_posts' ) ) {
	                $num = "<a href='edit.php?post_status=pending&post_type=portfolio'>$num</a>";
	                $text = "<a href='edit.php?post_status=pending&post_type=portfolio'>$text</a>";
	            }
	            echo '<td class="first b b-portfolio">' . $num . '</td>';
	            echo '<td class="t portfolio">' . $text . '</td>';
	
	            echo '</tr>';
	        }
	}
	
}

new Portfolio_Post_Type;

endif;

?>