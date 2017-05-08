<?php
/*
  Plugin Name: Envío Contrareembolso
  Description: Activa el método de envío contrareembolso
  Version: 1.0
  Author: Nahuel José
 */

 if ( ! defined( 'WPINC' ) ) {
  die;
 }

function leal_envio_contrareembolso_init() {
    if ( ! class_exists( 'Leal_Envio_Contrareembolso' ) ) {

      /**
       * Envío Contrareembolso Shipping Method.
       *
       * Permite al cliente abonar el envío al momento de la entrega
       *
       * @class 		WC_Shipping_Local_Pickup
       * @version		1.0.0
       * @author 		Nahuel José
       */
      class Leal_Envio_Contrareembolso extends WC_Shipping_Method {


        /**
         * Constructor.
         */
        public function __construct( $instance_id = 0 ) {
          $this->id                    = 'leal_envio_contrareembolso';
          $this->instance_id 			     = absint( $instance_id );
          $this->method_title          = __( 'Envío contra reembolso', 'woocommerce' );
          $this->title                 = __( 'Envío contra reembolso', 'woocommerce' );
          $this->method_description    = __( 'Permite al cliente abonar el envío al momento de la entrega', 'woocommerce' );
          $this->supports              = array(
            'shipping-zones',
            'instance-settings',
            'instance-settings-modal',
          );
          $this->init();
        }

        /**
         * Initialize local pickup.
         */
        public function init() {

          // Load the settings.
          $this->init_form_fields();
          $this->init_settings();

          // Define user set variables
          $this->title		     = $this->get_option( 'title' );
          $this->tax_status	     = $this->get_option( 'tax_status' );
          $this->cost	             = $this->get_option( 'cost' );

          // Actions
          add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
        }

        /**
         * calculate_shipping function.
         * Calculate local pickup shipping.
         */
        public function calculate_shipping( $package = array() ) {
          $this->add_rate( array(
            'label' 	 => $this->title,
            'package'    => $package,
            'cost'       => $this->cost,
          ) );
        }

        /**
         * Init form fields.
         */
        public function init_form_fields() {
          $this->instance_form_fields = array(
            'title' => array(
              'title'       => __( 'Title', 'woocommerce' ),
              'type'        => 'text',
              'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
              'default'     => __( 'Envío contra reembolso', 'woocommerce' ),
              'desc_tip'    => true,
            ),
            'tax_status' => array(
              'title' 		=> __( 'Tax status', 'woocommerce' ),
              'type' 			=> 'select',
              'class'         => 'wc-enhanced-select',
              'default' 		=> 'taxable',
              'options'		=> array(
                'taxable' 	=> __( 'Taxable', 'woocommerce' ),
                'none' 		=> _x( 'None', 'Tax status', 'woocommerce' ),
              ),
            ),
            'cost' => array(
              'title' 		=> __( 'Cost', 'woocommerce' ),
              'type' 			=> 'text',
              'placeholder'	=> '0',
              'description'	=> __( 'Costo opcional para el envío.', 'woocommerce' ),
              'default'		=> '',
              'desc_tip'		=> true,
            ),
          );
        }
      }

    }
}  // <-- note that the function is closed before the add_action('woocommerce_shipping_init')

// note "" prepended to the attached function
add_action( 'woocommerce_shipping_init', 'leal_envio_contrareembolso_init' );

// note "" prepended to the shipping method class name
function add_your_shipping_method( $methods ) {
    $methods['leal_envio_contrareembolso'] = 'Leal_Envio_Contrareembolso';
    return $methods;
}

// note "" prepended to the attached function
add_filter( 'woocommerce_shipping_methods', 'add_your_shipping_method' );
