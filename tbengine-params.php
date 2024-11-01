<script type="text/javascript">
    <?php if (!$tbengine_params_init): ?>
    window.RQApp                  = { };
    window.RQPageParams           = { };

    RQApp.IsPageLoaded            = false;

    RQPageParams.HotelID          = <?php echo esc_js(absint($tbengine_options['hotel_id_field'])) ?>;
    RQPageParams.IsWordPress      = 1;
    <?php endif ?>

    <?php if ($tbengine_book_hasparams): ?>
    RQPageParams.IsViaOTA         = 0;
    RQPageParams.IsEngineRequest  = 1;
    RQPageParams.LangID           = <?php echo esc_js(absint($tbengine_book_LangID))      ?>;
    RQPageParams.CVCRequired      = <?php echo esc_js(absint($tbengine_options['cvc_required_field'])) ?>;
    RQPageParams.BackToSearchPath = <?php echo json_encode($tbengine_options['search_path_field'] ,JSON_UNESCAPED_SLASHES) ?>;
    RQPageParams.CartJSON         = <?php echo json_encode($tbengine_book_cartjson)       ?>;
    <?php endif ?>

    <?php if ($tbengine_cart_hasparams): ?>
    RQPageParams.IsViaOTA         = 0;
    RQPageParams.IsEngineRequest  = 1;
    RQPageParams.LangID           = <?php echo esc_js(absint($tbengine_cart_LangID))      ?>;
    RQPageParams.CurrencyID       = <?php echo esc_js(absint($tbengine_cart_CurrencyID))  ?>;
    RQPageParams.NumAdults        = <?php echo esc_js(absint($tbengine_cart_NumAdults))   ?>;
    RQPageParams.NumChildren      = <?php echo esc_js(absint($tbengine_cart_NumChildren)) ?>;
    RQPageParams.Arr              = <?php echo json_encode($tbengine_cart_Arr)            ?>;
    RQPageParams.Dep              = <?php echo json_encode($tbengine_cart_Dep)            ?>;
    RQPageParams.MakeBookingPath  = <?php echo json_encode($tbengine_options['booking_path_field'] ,JSON_UNESCAPED_SLASHES) ?>;
    <?php endif ?>

    <?php if ($tbengine_cal_hasparams): ?>
    RQPageParams.LangID           = <?php echo esc_js(absint($tbengine_cal_LangID))       ?>;
    RQPageParams.CartPath         = <?php echo json_encode($tbengine_options['search_path_field'] ,JSON_UNESCAPED_SLASHES) ?>;
    RQPageParams.ArrInit          = <?php echo json_encode($tbengine_cal_today)           ?>;
    RQPageParams.DepInit          = <?php echo json_encode($tbengine_cal_tomorrow)        ?>;
    RQPageParams.Opens            = <?php echo json_encode($tbengine_cal_opens)           ?>;
    RQPageParams.Drops            = <?php echo json_encode($tbengine_cal_drops)           ?>;
    <?php endif ?>
</script>
