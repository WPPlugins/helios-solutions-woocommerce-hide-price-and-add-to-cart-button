<?php
/**
 * Plugin Name: Helios Solutions WooCommerce Hide Price and Add to Cart button
 * Plugin URI: http://heliossolutions.in/
 * Description: A plugin use for Hide price and add to cart button for woocommerce site.
 * Author: heliossolutions
 * Author URI: http://heliossolutions.in/
 * Version: 1.0
 *
 */
class WC_visibility_Tab{

    /**
     * Bootstraps the class and hooks required actions & filters.
     *
     */
    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_settings_tab_visibility', __CLASS__ . '::settings_tab' );
        add_action( 'woocommerce_update_options_settings_tab_visibility', __CLASS__ . '::update_settings' );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 2 );
	    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 1 );
		add_action('after_setup_theme', __CLASS__ . '::activate_filter',53) ;
    }
    
    
	
    /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     */
    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['settings_tab_visibility'] = __( 'Visibility', 'woocommerce-settings-tab-visibility' );
        return $settings_tabs;
    }


    /**
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::get_settings()
     */
    public static function settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }


    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public static function update_settings() {
        woocommerce_update_options( self::get_settings() );
    }


    /**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public static function get_settings() {

        $settings = array(
            'section_title' => array(
                'name'     => __( 'Hide Price and Cart button section', 'woocommerce-settings-tab-visibility' ),
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'wc_settings_tab_visibility_section_title'
            ),
            'title' => array(
                'name' => __( 'Product Price', 'woocommerce-settings-tab-visibility' ),
                'type' => 'checkbox',
                'desc' => __( 'Product price disable login user and guest user ', 'woocommerce-settings-tab-visibility' ),
                'id'   => 'wc_settings_tab_visibility_title'
            ),
		   'product' => array(
                'name' => __( 'Product price', 'woocommerce-settings-tab-visibilitypro' ),
                'type' => 'checkbox',
                'desc' => __( 'Product price option disable only guest user(non logged users)', 'woocommerce-settings-tab-visibilitypro' ),
                'id'   => 'wc_settings_tab_product_price_disable_product'
            ),
			'cart_button' => array(
                'name' => __( 'Add to cart button', 'woocommerce-settings-tab-visibilitypro' ),
                'type' => 'checkbox',
                'desc' => __( 'Add to cart button disable for login user and guest user', 'woocommerce-settings-tab-visibilitypro' ),
                'id'   => 'wc_settings_tab_product_cart_button'
            ),
			'add_to_cart' => array(
                'name' => __( 'Add to cart button ', 'woocommerce-settings-tab-visibilitypro' ),
                'type' => 'checkbox',
                'desc' => __( 'Add to cart button disable only guest user(non logged users)', 'woocommerce-settings-tab-visibilitypro' ),
                'id'   => 'wc_settings_tab_product_add_to_cart'
            ),
           	


		   'section_end' => array(
                 'type' => 'sectionend',
                 'id' => 'wc_settings_tab_demo_section_end'
            )
        );
		 
        return apply_filters( 'wc_settings_tab_demo_settings', $settings );
    }
			
				  
				function activate_filter(){
					 $pice_option=get_option('wc_settings_tab_visibility_title');
					 add_filter('woocommerce_get_price_html',  __CLASS__ . '::show_price_logged');
					
				}
				 
				function show_price_logged($price){
						$pice_option=get_option('wc_settings_tab_visibility_title');
						$disable_product_price=get_option('wc_settings_tab_product_price_disable_product');
						$cart_button=get_option('wc_settings_tab_product_cart_button');
						$add_to_cart=get_option('wc_settings_tab_product_add_to_cart');
						
						
						
						
					/* Add to cart button disable for non logged users and logged users*/
					if(is_user_logged_in() ){
						
							if($cart_button=='yes'){
								remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart',10 );
								remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
							}
						}else{
							if($add_to_cart=='yes'){
								remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart',10 );
								remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
							
							}else if($cart_button=='yes'){
								remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart',10 );
								remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

							}
					}	
				
					/* disable product price option  login user and non logged user  */
					if(is_user_logged_in() ){
							if($pice_option=='yes'){
								remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
								remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
							}else{
								return $price;
							}
						}else{
						if($disable_product_price=='yes'){
								remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
								remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
							
							}else if($pice_option=='yes'){
								remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
								remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
							}else{
								return $price;
							}
					}
					//aaall:
					
					//goto bbbb;
						//remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart',10 );
						//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
						//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
							//remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
						//return '<a href="' . get_permalink(woocommerce_get_page_id('myaccount')) . '">Login to See Prices</a>';
					}
			
			
					
			
			
			

}

WC_visibility_Tab::init();
