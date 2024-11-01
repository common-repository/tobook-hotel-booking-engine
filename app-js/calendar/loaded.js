(function (jQuery) {
    window.$ = jQuery.noConflict();
})(jQuery);

var langIDToLocale = [
     ''
    ,'en'
    ,'fr'
    ,'de'
    ,'it'
    ,'es'
    ,'pt'
    ,'tr'
    ,'hu'
    ,'nl'
];

$(document).ready(function () {
    $(function() {
        moment.locale(langIDToLocale[RQPageParams.LangID]);

        var minDate   = RQPageParams.ArrInit;
        var startDate = (RQPageParams.Arr) ? RQPageParams.Arr : RQPageParams.ArrInit;
        var endDate   = (RQPageParams.Dep) ? RQPageParams.Dep : RQPageParams.DepInit;

        $('input[name="tbengine_dates"]').daterangepicker(
            {
                 autoApply:       true
                ,autoUpdateInput: true
                ,locale: {
                      format:      'YYYY-MM-DD'
                     ,separator:   ' / '
                }
                ,minDate:         minDate
                ,startDate:       startDate
                ,endDate:         endDate
                ,showDropdowns:   true
                ,opens:           RQPageParams.Opens
                ,drops:           RQPageParams.Drops
            }
            ,function(start ,end ,label) {
                if (end.isAfter(start, 'day')) {
                    window.location.href =
                        RQPageParams.CartPath
                          + '?Arr='
                          + start.format('YYYY-MM-DD')
                          + '&Dep='
                          + end.format('YYYY-MM-DD');
                }
            }
        );
    });
});
