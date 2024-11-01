<?php
    function tbengine_add_settings_page() {
        add_options_page(
             'tobook Booking Engine Plugin Settings'
            ,'tbengine plugin'
            ,'manage_options'
            ,'tbengine-plugin'
            ,'tbengine_render_settings_page'
        );
    }

    add_action('admin_menu', 'tbengine_add_settings_page');

    function tbengine_render_settings_page() {
    ?>
        <h2>
            Manage the plugin settings for your property's own website booking engine here:
        </h2>
        <form action="options.php" method="post">
            <?php 
                settings_fields('tbengine_plugin_settings');
                do_settings_sections('tbengine_plugin');
            ?>
            <input
                type="submit"
                name="submit"
                class="button button-primary"
                value="<?php esc_attr_e( 'Save' ); ?>"
            />
        </form>
    <?php
    }

    function tbengine_register_settings() {
        register_setting(
             'tbengine_plugin_settings'
            ,'tbengine_plugin_settings'
            ,'tbengine_validate_plugin_settings'
        );

        add_settings_section(
             'property_settings'
            ,'Booking Engine Settings'
            ,'tbengine_section_one_descr'
            ,'tbengine_plugin'
        );

        add_settings_field(
             'hotel_id_field'
            ,'tobook HotelID'
            ,'tbengine_render_hotel_id_field'
            ,'tbengine_plugin'
            ,'property_settings'
        );

        add_settings_field(
             'cvc_required_field'
            ,'Require CVC'
            ,'tbengine_render_cvc_required_field'
            ,'tbengine_plugin'
            ,'property_settings'
        );

        add_settings_field(
             'langid_field'
            ,'Default LangID'
            ,'tbengine_render_langid_field'
            ,'tbengine_plugin'
            ,'property_settings'
        );

        add_settings_field(
             'booking_path_field'
            ,'Booking Form Permalink'
            ,'tbengine_render_booking_path_field'
            ,'tbengine_plugin'
            ,'property_settings'
        );

        add_settings_field(
             'search_path_field'
            ,'Cart Permalink'
            ,'tbengine_render_search_path_field'
            ,'tbengine_plugin'
            ,'property_settings'
        );
    }

    add_action( 'admin_init', 'tbengine_register_settings' );

    function tbengine_validate_plugin_settings ( $input ) {
        $output['hotel_id_field'     ] = absint(      $input['hotel_id_field'     ] );
        $output['cvc_required_field' ] = absint(      $input['cvc_required_field' ] );
        $output['langid_field'       ] = absint(      $input['langid_field'       ] );
        $output['booking_path_field' ] = esc_url_raw( $input['booking_path_field' ] );
        $output['search_path_field'  ] = esc_url_raw( $input['search_path_field'  ] );

        return $output;
    }

    function tbengine_section_one_descr() {
        echo
           '<p>tobook Engine plugin settings - refer to the plugin readme.txt or user guide at '
         . '<a href="https://www.tobook.com/en/WordPressEngine">tobook Wordpress Engine</a>'
         . ' for more information.</p>';
    }

    function tbengine_render_hotel_id_field() {
        $options = get_option('tbengine_plugin_settings');

        printf(
             '<input type="number" name="%s" value="%s" />'
            ,esc_attr('tbengine_plugin_settings[hotel_id_field]')
            ,esc_attr(absint($options['hotel_id_field']))
        );
    }

    function tbengine_render_cvc_required_field() {
        $options = get_option('tbengine_plugin_settings');

        printf(
             '<input type="number" name="%s" value="%s" />'
            ,esc_attr('tbengine_plugin_settings[cvc_required_field]')
            ,esc_attr(absint($options['cvc_required_field']))
        );
    }

    function tbengine_render_langid_field() {
        $options = get_option('tbengine_plugin_settings');

        printf(
             '<input type="number" name="%s" value="%s" />'
            ,esc_attr('tbengine_plugin_settings[langid_field]')
            ,esc_attr(absint($options['langid_field']))
        );
    }

    function tbengine_render_booking_path_field() {
        $options = get_option('tbengine_plugin_settings');

        printf(
             '<input type="text" name="%s" value="%s" />'
            ,esc_attr('tbengine_plugin_settings[booking_path_field]')
            ,esc_url($options['booking_path_field'])
        );
    }

    function tbengine_render_search_path_field() {
        $options = get_option('tbengine_plugin_settings');

        printf(
             '<input type="text" name="%s" value="%s" />'
            ,esc_attr('tbengine_plugin_settings[search_path_field]')
            ,esc_url($options['search_path_field'])
        );
    }
?>
