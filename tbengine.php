<?php
    /*
    Plugin Name: tobook Hotel Booking Engine
    Plugin URI: https://www.tobook.com/en/WordPressEngine
    Description: **FREE** tobook WordPress Hotel Booking Engine - drive price-parity beating commission-free bookings through your website
    Version: 1.9
    Author: tobook
    Author URI: https://www.tobook.com/
    License: GPLv2 or later
    License URI: https://www.gnu.org/licenses/gpl-2.0.html
    Text Domain: tobook-hotel-booking-engine
    Domain Path: /languages
    */

    $tbengine_options          = array();

    $tbengine_default_options  = array(
         'hotel_id_field'      => 100
        ,'cvc_required_field'  => 1 
        ,'langid_field'        => 1
        ,'booking_path_field'  => 'https://www.tobook.com/wordpress/make-booking/'
        ,'search_path_field'   => 'https://www.tobook.com/wordpress/book-rooms/'
    );

    $tbengine_params_init      = false;
    $tbengine_explicit_lang    = false;

    $tbengine_book_hasparams   = false;
    $tbengine_book_cartjson    = '';
    $tbengine_book_LangID      = 1;

    $tbengine_cart_hasparams   = false;
    $tbengine_cart_LangID      = 1;
    $tbengine_cart_CurrencyID  = 1;
    $tbengine_cart_NumAdults   = 2;
    $tbengine_cart_NumChildren = 0;
    $tbengine_cart_Arr         = date('Y-m-d');
    $tbengine_cart_Dep         = (new DateTime(date('Y-m-d')))->modify('+1 day')->format('Y-m-d');

    $tbengine_cal_hasparams    = false;
    $tbengine_cal_LangID       = 1;
    $tbengine_cal_opens        = 'right';
    $tbengine_cal_drops        = 'down';
    $tbengine_cal_today        = '';
    $tbengine_cal_tomorrow     = '';

    include(plugin_dir_path( __FILE__ ) . 'tbengine-settings.php');

    function tbengine_load_textdomain() {
        load_plugin_textdomain(
             'tobook-hotel-booking-engine'
            ,false
            ,dirname(plugin_basename( __FILE__ )) . '/languages'
        );
    }

    function tbengine_loadSettings() {
        global
             $tbengine_options
            ,$tbengine_default_options
            ,$tbengine_explicit_lang
            ,$tbengine_cal_LangID
            ,$tbengine_cart_LangID
            ,$tbengine_book_LangID;

        $settings = get_option('tbengine_plugin_settings', $tbengine_default_options);

        if (
            (isset($settings['hotel_id_field']))
        ) {
            $tbengine_options += array('hotel_id_field'     => absint($settings['hotel_id_field']));
        } else {
            $tbengine_options += array('hotel_id_field'     => $tbengine_default_options['hotel_id_field']);
        }

        if (isset($settings['cvc_required_field'])) {
            $tbengine_options += array('cvc_required_field' => absint($settings['cvc_required_field']));
        } else {
            $tbengine_options += array('cvc_required_field' => $tbengine_default_options['cvc_required_field']);
        }

        if (isset($settings['langid_field'])) {
            $tbengine_options += array('langid_field'       => absint($settings['langid_field']));
        } else {
            $tbengine_options += array('langid_field'       => $tbengine_default_options['langid_field']);
        }

        if (
            (isset($settings['booking_path_field']))
        &&  (esc_url($settings['booking_path_field']) != '')
        ) {
            $tbengine_options += array('booking_path_field' => esc_url($settings['booking_path_field']));
        } else {
            $tbengine_options += array('booking_path_field' => $tbengine_default_options['booking_path_field']);
        }

        if (
            (isset($settings['search_path_field']))
        &&  (esc_url($settings['search_path_field']) != '')
        ) {
            $tbengine_options += array('search_path_field'  => esc_url($settings['search_path_field']));
        } else {
            $tbengine_options += array('search_path_field'  => $tbengine_default_options['search_path_field']);
        }

        if (!(
            $tbengine_options['cvc_required_field'] == 1
        ||  $tbengine_options['cvc_required_field'] == 0
        )) {
            $tbengine_options['cvc_required_field'] = 1;
        }

        if (!(
            $tbengine_options['langid_field'] > 0
        &&  $tbengine_options['langid_field'] < 10
        )) {
            $tbengine_options['langid_field'] = 1;
        }

        if (!$tbengine_explicit_lang) {
            $tbengine_cal_LangID  = absint($tbengine_options['langid_field']);
            $tbengine_cart_LangID = absint($tbengine_options['langid_field']);
            $tbengine_book_LangID = absint($tbengine_options['langid_field']);
        }
    }

    function tbengine_validateDate($str_date) {
        $date = DateTime::createFromFormat('Y-m-d', $str_date);

        return (($date) && ($date->format('Y-m-d') === $str_date));
    }

    function tbengine_request_params() {
	global
             $tbengine_book_hasparams
            ,$tbengine_book_cartjson
            ,$tbengine_explicit_lang
            ,$tbengine_book_LangID
            ,$tbengine_cart_LangID
            ,$tbengine_cal_LangID
            ,$tbengine_cart_CurrencyID
            ,$tbengine_cart_NumAdults
            ,$tbengine_cart_NumChildren
            ,$tbengine_cart_Arr
            ,$tbengine_cart_Dep;

        if (
            isset($_POST['IsEngineRequest'])
        &&  (absint($_POST['IsEngineRequest']) == 1)
        &&  isset($_POST['Cart'])
        ) {
            $tbengine_book_hasparams = true;
            $tbengine_book_cartjson  = wp_unslash(sanitize_text_field($_POST['Cart']) ?? '');

            if (isset($_POST['LangID'])) {
                $tbengine_book_LangID   = absint($_POST['LangID']) ?? 1;
                $tbengine_explicit_lang = true;
            }
        }

        if (
            isset($_GET['IsEngineRequest'])
        &&  (absint($_GET['IsEngineRequest']) == 1)
        &&  tbengine_validateDate(sanitize_text_field($_GET['Arr']))
        &&  tbengine_validateDate(sanitize_text_field($_GET['Dep']))
        ) {
            $tbengine_book_hasparams = true;

            $cart_data = [array( 
                 'IsOnRequest' => (absint($_GET['IsOnRequest'] == 1)) ? true : false
                ,'Arr'         => sanitize_text_field($_GET['Arr'])
                ,'Dep'         => sanitize_text_field($_GET['Dep'])
                ,'CurrencyID'  => absint($_GET["CurrencyID"])
                ,'NumRooms'    => 1
                ,'Offer'       => array(
                     'RoomTypeID'                          => absint($_GET['RoomTypeID'])
                    ,'RoomTypeName'                        => sanitize_text_field($_GET['RoomTypeName'])
                    ,'PersonsNormal'                       => absint($_GET['PersonsNormal'])
                    ,'MaxPersonsNormal'                    => absint($_GET['MaxPersonsNormal'])
                    ,'OfferTypeID'                         => absint($_GET['OfferTypeID'])
                    ,'OfferTypeName'                       => sanitize_text_field($_GET['OfferTypeName'])
                    ,'PaymentSchemeID'                     => absint($_GET['PaymentSchemeID'])
                    ,'PaymentSchemeNameShort'              => sanitize_text_field($_GET['PaymentSchemeNameShort'])
                    ,'MaxPersons'                          => absint($_GET['MaxPersons'])
                    ,'MaxPersonsRateName'                  => sanitize_text_field($_GET['MaxPersonsRateName'])
                    ,'PriceExclusive'                      => floatval($_GET['PriceExclusive'])
                    ,'PriceInclusive'                      => floatval($_GET['PriceInclusive'])
                    ,'PriceExclusiveHotelCurrency'         => floatval($_GET['PriceExclusiveHotelCurrency'])
                    ,'PriceInclusiveHotelCurrency'         => floatval($_GET['PriceInclusiveHotelCurrency'])
                    ,'PriceInclusiveEuros'                 => floatval($_GET['PriceInclusiveEuros'])
                    ,'NumDiscountDays'                     => absint($_GET['NumDiscountDays'])
                    ,'PriceExclusiveDiscount'              => floatval($_GET['PriceExclusiveDiscount'])
                    ,'PriceInclusiveDiscount'              => floatval($_GET['PriceInclusiveDiscount'])
                    ,'PriceExclusiveHotelCurrencyDiscount' => floatval($_GET['PriceExclusiveHotelCurrencyDiscount'])
                    ,'PriceInclusiveHotelCurrencyDiscount' => floatval($_GET['PriceInclusiveHotelCurrencyDiscount'])
                    ,'PriceInclusiveEurosDiscount'         => floatval($_GET['PriceInclusiveEurosDiscount'])
                    ,'HasLastMinute'                       => absint($_GET['HasLastMinute'])
                    ,'PriceExclusivePLM'                   => floatval($_GET['PriceExclusivePLM'])
                    ,'PriceInclusivePLM'                   => floatval($_GET['PriceInclusivePLM'])
                    ,'PriceExclusiveHotelCurrencyPLM'      => floatval($_GET['PriceExclusiveHotelCurrencyPLM'])
                    ,'PriceInclusiveHotelCurrencyPLM'      => floatval($_GET['PriceInclusiveHotelCurrencyPLM'])
                    ,'PriceInclusiveEurosPLM'              => floatval($_GET['PriceInclusiveEurosPLM'])
                    ,'NumRooms'                            => absint($_GET['NumRooms'])
                    ,'MaxRoomsToSell'                      => 1
                    ,'CapacityCoversRequest'               => absint($_GET['CapacityCoversRequest'])
                    ,'InsufficientAllotment'               => absint($_GET['InsufficientAllotment'])
                    ,'MLOS'                                => absint($_GET['MLOS'])
                    ,'MaxStay'                             => absint($_GET['MaxStay'])
                    ,'AdvanceDays'                         => absint($_GET['AdvanceDays'])
                    ,'NoArr'                               => absint($_GET['NoArr'])
                    ,'NoDep'                               => absint($_GET['NoDep'])
                    ,'MatchLevel'                          => absint($_GET['MatchLevel'])
                    ,'MaxMatchLevel'                       => absint($_GET['MaxMatchLevel'])
                    ,'IsMarkedAsMatch'                     => absint($_GET['IsMarkedAsMatch'])
                    ,'IsWalkIn'                            => absint($_GET['IsWalkIn'])
                )
            )];

            $tbengine_book_cartjson = json_encode(array('Cart' => $cart_data));

            if (isset($_GET['LangID'])) {
                $tbengine_book_LangID   = absint($_GET['LangID']) ?? 1;
                $tbengine_explicit_lang = true;
            }
        }

        if (
            (!isset($_GET[ 'IsEngineRequest']))
        &&  (!isset($_POST['IsEngineRequest']))
        ) {
            if (isset($_GET['LangID'])) {
                $tbengine_cart_LangID      = absint($_GET['LangID'])      ?? 1;
                $tbengine_cal_LangID       = $tbengine_cart_LangID;
                $tbengine_explicit_lang    = true;
            }

            if (isset($_GET['CurrencyID'])) {
                $tbengine_cart_CurrencyID  = absint($_GET['CurrencyID'])  ?? 1;
            }

            if (isset($_GET['NumAdults'])) {
                $tbengine_cart_NumAdults   = absint($_GET['NumAdults'])   ?? 1;
            }

            if (isset($_GET['NumChildren'])) {
                $tbengine_cart_NumChildren = absint($_GET['NumChildren']) ?? 1;
            }

            if (
                isset($_GET['Arr'])
            &&  isset($_GET['Dep'])
            &&  tbengine_validateDate(sanitize_text_field($_GET['Arr']))
            &&  tbengine_validateDate(sanitize_text_field($_GET['Dep']))
            ) {
                $tbengine_cart_Arr = sanitize_text_field($_GET['Arr']);
                $tbengine_cart_Dep = sanitize_text_field($_GET['Dep']);
            }
        }
    }

    add_action( 'init', 'tbengine_load_textdomain' );
    add_action( 'init', 'tbengine_request_params' );

    function tbengine_scripts() {
        wp_register_style( 'tbengine-lib-grid'      ,plugins_url('lib-css/bootstrap-grid.min.css'    ,__FILE__)    ,null ,null);
        wp_register_style( 'tbengine-lib-cal'       ,plugins_url('lib-css/daterangepicker.css'       ,__FILE__)    ,null ,null);
        wp_register_style( 'tbengine-app-engine'    ,plugins_url('app-css/engine.css'                ,__FILE__)    ,null ,null);
        wp_register_style( 'tbengine-app-cart'      ,plugins_url('app-css/offer-list.css'            ,__FILE__)    ,null ,null);
        wp_register_style( 'tbengine-app-book'      ,plugins_url('app-css/make-booking.css'          ,__FILE__)    ,null ,null);
        wp_register_style( 'tbengine-app-cal'       ,plugins_url('app-css/calendar.css'              ,__FILE__)    ,null ,null);
        wp_register_style( 'tbengine-custom-engine' ,plugins_url('app-css/custom/engine.css'         ,__FILE__)    ,null ,null);
        wp_register_style( 'tbengine-custom-cart'   ,plugins_url('app-css/custom/offer-list.css'     ,__FILE__)    ,null ,null);
        wp_register_style( 'tbengine-custom-book'   ,plugins_url('app-css/custom/make-booking.css'   ,__FILE__)    ,null ,null);
        wp_register_style( 'tbengine-custom-cal'    ,plugins_url('app-css/custom/calendar.css'       ,__FILE__)    ,null ,null);

        wp_register_script('tbengine-vue'           ,plugins_url('lib-js/vue.js'                     ,__FILE__)    ,null ,null);
        wp_register_script('tbengine-moment'        ,plugins_url('lib-js/moment-with-locales.min.js' ,__FILE__)    ,null ,null);
        wp_register_script('tbengine-cal'           ,plugins_url('lib-js/daterangepicker.min.js'     ,__FILE__)    ,null ,null);

        wp_register_script('tbengine-suppress'      ,plugins_url('app-js/suppress.js'                ,__FILE__)    ,null ,null);

        wp_register_script('tbengine-cal-loaded'    ,plugins_url('app-js/calendar/loaded.js'         ,__FILE__)    ,null ,null);

        wp_register_script('tbengine-offer-list'    ,'https://www.tobook.com/app-js/Engine/offer-list/bundle.js'   ,null ,null);
        wp_register_script('tbengine-cart-loaded'   ,plugins_url('app-js/offer-list/loaded.js'       ,__FILE__)    ,null ,null);

        wp_register_script('tbengine-make-booking'  ,'https://www.tobook.com/app-js/Engine/make-booking/bundle.js' ,null ,null);
        wp_register_script('tbengine-book-loaded'   ,plugins_url('app-js/make-booking/loaded.js'     ,__FILE__)    ,null ,null);
    }

    add_action('wp_enqueue_scripts' ,'tbengine_scripts');

    function tbengine_footer_cart() {
        echo ' 
<div style="display: none" id="signInOutVueSession"></div>
<div style="display: none" id="searchParamsVue"></div>
<div style="display: none" id="searchAndCartVue"></div>
<div style="display: none" id="cartSummaryFloat"></div>
<div style="display: none" id="descriptionDynamic"></div>
<div style="display: none" id="descriptionHeader"></div>
<div style="display: none" id="offerListRazor"></div>
<div style="display: none" id="signInHint"></div>
<div style="display: none" id="signInHintSpacer"></div>
<div style="display: none" id="cartVue"></div>
<div style="display: none" id="ListContent"></div>
';
    }

    function tbengine_footer_book() {
        echo '
<div style="display: none" id="signInOutVue"></div>
<div style="display: none" id="signInOutVueSession"></div>
<div style="display: none" id="signInOutVueNav"></div>
';
    }

    function tbengine_calendar_shortcode($attributes) {
        global
             $tbengine_options
            ,$tbengine_params_init
            ,$tbengine_book_hasparams
            ,$tbengine_cart_hasparams
            ,$tbengine_cal_hasparams
            ,$tbengine_cal_LangID
            ,$tbengine_cal_opens
            ,$tbengine_cal_drops
            ,$tbengine_cal_today
            ,$tbengine_cal_tomorrow;

        if (!$tbengine_params_init) { tbengine_loadSettings(); }

        $calendar_attributes = shortcode_atts(array(
             'langID' => $tbengine_cal_LangID
            ,'opens'  => 'right'
            ,'drops'  => 'down'
        ) ,$attributes);

        if (
            absint($calendar_attributes['langID']) > 0
        &&  absint($calendar_attributes['langID']) < 10
        ) {
            $tbengine_cal_LangID = absint($calendar_attributes['langID']);
        }

        if (
            sanitize_text_field($calendar_attributes['opens']) == 'left'
        ||  sanitize_text_field($calendar_attributes['opens']) == 'center'
        ) {
            $tbengine_cal_opens = sanitize_text_field($calendar_attributes['opens']);
        }

        if (
            sanitize_text_field($calendar_attributes['drops']) == 'up'
        ||  sanitize_text_field($calendar_attributes['drops']) == 'auto'
        ) {
            $tbengine_cal_drops = sanitize_text_field($calendar_attributes['drops']);
        }

        wp_enqueue_style('tbengine-lib-cal');

        if (!$tbengine_params_init) {
            wp_enqueue_style('tbengine-lib-grid');
            wp_enqueue_style('tbengine-app-engine');
            wp_enqueue_style('tbengine-custom-engine');
        }

        wp_enqueue_style('tbengine-app-cal');
        wp_enqueue_style('tbengine-custom-cal');

        if (!$tbengine_params_init) {
            wp_enqueue_script('tbengine-suppress');
            wp_enqueue_script('jquery');
        }

        wp_enqueue_script('tbengine-moment');
        wp_enqueue_script('tbengine-cal');
        wp_enqueue_script('tbengine-cal-loaded');

        $tbengine_book_hasparams = false;
        $tbengine_cart_hasparams = false;
        $tbengine_cal_hasparams  = true;

        $tbengine_cal_today      = date('Y-m-d');
        $tbengine_cal_tomorrow   = date('Y-m-d', strtotime('tomorrow'));

        ob_start();

        include( plugin_dir_path( __FILE__ ) . 'tbengine-params.php');
        include( plugin_dir_path( __FILE__ ) . 'app-html/cal.html');

        $output = ob_get_clean();

        $tbengine_params_init = true;

        return $output;
    }

    function tbengine_offerlist_shortcode() {
        global
             $tbengine_options
            ,$tbengine_params_init
            ,$tbengine_book_hasparams
            ,$tbengine_cart_hasparams
            ,$tbengine_cal_hasparams
            ,$tbengine_cart_LangID
            ,$tbengine_cart_CurrencyID
            ,$tbengine_cart_NumAdults
            ,$tbengine_cart_NumChildren
            ,$tbengine_cart_Arr
            ,$tbengine_cart_Dep;

        if (!$tbengine_params_init) { tbengine_loadSettings(); }

        $tbengine_book_hasparams = false;
        $tbengine_cart_hasparams = true;
        $tbengine_cal_hasparams  = false;

        if (!$tbengine_params_init) {
            wp_enqueue_style('tbengine-lib-grid');
            wp_enqueue_style('tbengine-app-engine');
            wp_enqueue_style('tbengine-custom-engine');
        }

        wp_enqueue_style('tbengine-app-cart');
        wp_enqueue_style('tbengine-custom-cart');

        if (!$tbengine_params_init) {
            wp_enqueue_script('tbengine-suppress');
            wp_enqueue_script('jquery');
        }

        wp_enqueue_script('tbengine-vue');
        wp_enqueue_script('tbengine-offer-list');
        wp_enqueue_script('tbengine-cart-loaded');

        add_action('wp_footer' ,'tbengine_footer_cart');

        ob_start();

        include( plugin_dir_path( __FILE__ ) . 'tbengine-params.php');
	include( plugin_dir_path( __FILE__ ) . 'app-html/sign-in.html');
        include( plugin_dir_path( __FILE__ ) . 'app-html/cart.html');

        $output = ob_get_clean();

        $tbengine_params_init = true;

        return $output;
    }

    function tbengine_makebooking_shortcode() {
        global
             $tbengine_options
            ,$tbengine_params_init
            ,$tbengine_book_hasparams
            ,$tbengine_cart_hasparams
            ,$tbengine_cal_hasparams
            ,$tbengine_book_cartjson
            ,$tbengine_book_LangID;

        if (!$tbengine_book_hasparams) {
            echo "No booking request found.";
            return;
        }

        if (!$tbengine_params_init) { tbengine_loadSettings(); }

        $tbengine_cart_hasparams = false;
        $tbengine_cal_hasparams  = false;

        if (!$tbengine_params_init) {
            wp_enqueue_style('tbengine-lib-grid');
            wp_enqueue_style('tbengine-app-engine');
            wp_enqueue_style('tbengine-custom-engine');
        }

        wp_enqueue_style('tbengine-app-book');
        wp_enqueue_style('tbengine-custom-book');

        if (!$tbengine_params_init) {
            wp_enqueue_script('tbengine-suppress');
            wp_enqueue_script('jquery');
        }

        wp_enqueue_script('tbengine-vue');
        wp_enqueue_script('tbengine-make-booking');
        wp_enqueue_script('tbengine-book-loaded');

        add_action('wp_footer' ,'tbengine_footer_book');

        ob_start();

        include( plugin_dir_path( __FILE__ ) . 'tbengine-params.php');
        include( plugin_dir_path( __FILE__ ) . 'app-html/sign-in.html');
        include( plugin_dir_path( __FILE__ ) . 'app-html/book.html');

        $output = ob_get_clean();

        $tbengine_params_init = true;

        return $output;
    }

    add_shortcode("tbengine_calendar"    ,"tbengine_calendar_shortcode");
    add_shortcode("tbengine_offerlist"   ,"tbengine_offerlist_shortcode");
    add_shortcode("tbengine_makebooking" ,"tbengine_makebooking_shortcode");
?>
