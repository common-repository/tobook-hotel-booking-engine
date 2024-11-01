(function (jQuery) {
    window.$ = jQuery.noConflict();
})(jQuery);

RQApp.ViewModel.mlText.RegisterView(75 ,'BookingEngineAppTexts');
RQApp.ViewModel.mlText.RegisterView(76 ,'BookingEngineOfferList');

$(document).ready(function () {
    RQApp.IsPageLoaded = true;

    window.onclick = function(event) {
        if (!event.target.matches('.dropbutton')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");

            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];

                if (openDropdown.classList.contains('tb-show')) {
                    openDropdown.classList.remove('tb-show');
                }
            }
        }
    }
});

