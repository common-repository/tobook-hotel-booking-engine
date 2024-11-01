(function (jQuery) {
    window.$ = jQuery.noConflict();
})(jQuery);

RQApp.ViewModel.mlText.RegisterView(75 ,'BookingEngineAppTexts');
RQApp.ViewModel.mlText.RegisterView(78 ,'BookingEngineGuestDetails');
RQApp.ViewModel.mlText.RegisterView(79 ,'BookingEngineCCDetails');
RQApp.ViewModel.mlText.RegisterView(80 ,'BookingEngineConfirmation');

InConfirmBooking = false;
ConfirmHasRun    = false;

BackToSearch = function () {
    window.location.href = RQPageParams.BackToSearchPath;
}

SendBookingRequest = function () {
    var postBookingAction = function (success) {
        var navActionButton    = document.getElementById('NavActionButton');
        var footerActionButton = document.getElementById('FooterActionButton');
        var navActionLabel     = document.getElementById('NavActionLabel');
        var footerActionLabel  = document.getElementById('FooterActionLabel');
        var navHeader          = document.getElementById('NavHeader');
        var result             = document.getElementById('resultVue');

        navActionLabel.innerText     = RQApp.ViewModel.Confirmation.ActionLabel;
        footerActionLabel.innerText  = RQApp.ViewModel.Confirmation.ActionLabel;

        if        ((success)  && (RQApp.ViewModel.GuestDetails.Cart.IsOnRequest)) {
            navHeader.innerText = RQApp.ViewModel.Confirmation.app.Text(
                 '2Of2RequestSentOK'
                ,'(2/2) Request Sent OK'
            );
        } else if ((success)  && (!RQApp.ViewModel.GuestDetails.Cart.IsOnRequest)) {
            navHeader.innerText = RQApp.ViewModel.Confirmation.app.Text(
                 '2Of2ConfirmedOK'
                ,'(2/2) Confirmed OK'
            );
        } else if ((!success) && (RQApp.ViewModel.GuestDetails.Cart.IsOnRequest)) {
            navHeader.innerText = RQApp.ViewModel.Confirmation.app.Text(
                 '2Of2RequestNOTSent'
                ,'(2/2) Request NOT Sent'
            );
        } else {
            navHeader.innerText = RQApp.ViewModel.Confirmation.app.Text(
                 '2Of2NOTConfirmed'
                ,'(2/2) NOT Confirmed'
            );
        }

        resultVue.style.display = 'block';

        Vue.nextTick(function () {
            $('html,body').scrollTop(0);

            InConfirmBooking = false;

            return;
        });
    };

    RQApp.ViewModel.Confirmation.ConfirmBooking(postBookingAction);

    return;
}

ConfirmBooking = function (event) {
    event.preventDefault();

    if (InConfirmBooking) { return; }

    if (ConfirmHasRun) { BackToSearch(); return; }

    InConfirmBooking = true;

    RQApp.ViewModel.GuestDetails.ResHolder.FirstName = (
        document.getElementById('FirstName')
    ).value;

    RQApp.ViewModel.GuestDetails.ResHolder.LastName = (
        document.getElementById('LastName')
    ).value;

    RQApp.ViewModel.GuestDetails.ResHolder.CountryID = (
        document.getElementById('CountryID')
    ).value;

    RQApp.ViewModel.GuestDetails.ResHolder.Email = (
        document.getElementById('Email')
    ).value;

    RQApp.ViewModel.GuestDetails.ResHolder.Mobile = (
        document.getElementById('Mobile')
    ).value;

    RQApp.ViewModel.GuestDetails.ResHolder.GuestComments = (
        document.getElementById('GuestComments')
    ).value;

    RQApp.ViewModel.GuestDetails.ResHolder.CCTypeID = (
        document.getElementById('CCTypeID')
    ).value;

    RQApp.ViewModel.GuestDetails.ResHolder.CCNumber = (
        document.getElementById('CCNumber')
    ).value;

    RQApp.ViewModel.GuestDetails.ResHolder.CCName = (
        document.getElementById('CCName')
    ).value;

    RQApp.ViewModel.GuestDetails.ResHolder.CCExpiryYear = (
        document.getElementById('CCExpiryYear')
    ).value;

    RQApp.ViewModel.GuestDetails.ResHolder.CCExpiryMonth = (
        document.getElementById('CCExpiryMonth')
    ).value;

    if (RQPageParams.CVCRequired == 1) { 
        RQApp.ViewModel.GuestDetails.ResHolder.CCCVC = (
            document.getElementById('CCCVC')
        ).value;
    }

    var detailsResult = RQApp.ViewModel.GuestDetails.ResHolder.ValidateGuestDetails(
        RQApp.ViewModel.GuestDetails.CountryNameByID
    );

    if (!detailsResult.Status) {
        RQApp.ViewModel.GuestDetails.ShowAlert(
            RQApp.ViewModel.GuestDetails.app.Text(
                 'GuestDetails'
                ,'Guest Details'
            )
            ,detailsResult.Message
        );

        InConfirmBooking = false;

        return;
    }

    var ccResult = RQApp.ViewModel.GuestDetails.ResHolder.ValidateCCDetails(
        RQApp.ViewModel.CCDetails.CCTypeNameByID
    );

    if (!ccResult.Status) {
        RQApp.ViewModel.GuestDetails.ShowAlert(
            RQApp.ViewModel.GuestDetails.app.Text(
                 'CCDetails'
                ,'Credit Card Details'
            )
            ,ccResult.Message
        );

        InConfirmBooking = false;

        return;
    }

    RQApp.ViewModel.GuestDetails.ResHolder.ValidateGuestSession(
        function (pNeedSession) {
            if (pNeedSession) {
                RQApp.ViewModel.GuestDetails.ShowAlert(
                    RQApp.ViewModel.GuestDetails.app.Text(
                         'GuestDetails'
                        ,'Guest Details'
                    )
                    ,RQApp.ViewModel.GuestDetails.app.Text(
                         'EmailRegistered'
                        ,'Email registered: please sign in'
                    )
                    ,function () {
                        RQApp.ViewModel.GuestDetails.ResHolder.Email = (
                            document.getElementById('Email')
                        ).value;

                        var signIn = document.getElementById('modalSignIn');

                        signIn.style.display = "block";
                    }
                );

                InConfirmBooking = false;

                return;
            } else {
                var details      = document.getElementById('guestDetailsVue');
                var confirmation = document.getElementById('formContentVue');

                details.style.display      = 'none';
                confirmation.style.display = 'block';

                $('html,body').scrollTop(0);

                RQApp.ViewModel.DeclareOnConfView();

                ConfirmHasRun = true;

                SendBookingRequest();
            }
        }
    );
};

$(document).ready(function () {
    RQApp.ViewModel.GuestDetails.InitialiseMLTexts(
        function () {
            RQApp.ViewModel.GuestDetails.InitialiseMLBrokers();
            RQApp.NavViewModel.InitialiseMLBroker();
            RQApp.ViewModel.CCDetails.InitialiseMLBrokers();
            RQApp.ViewModel.Confirmation.InitialiseMLBrokers();
            RQApp.ViewModel.GuestDetails.ResHolder.ReInitSignInOutLabel();

            RQApp.ViewModel.GuestDetails.RegisterOffersUCase(
                 RQApp.ViewModel.Offers
                ,function() {

                    RQApp.ViewModel.Confirmation.SyncPartnerToCart();

                    RQApp.IsPageLoaded = true;
                }
            );

            RQApp.ViewModel.GuestDetails.PopulateCountryListRaw();
            RQApp.ViewModel.CCDetails.PopulateCCTypeListRaw();

            document.getElementById('modalAlertAction').innerText = RQApp.ViewModel.GuestDetails.ml.Text(
                 'OK'
                ,'OK'
            );
        }
    );

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

