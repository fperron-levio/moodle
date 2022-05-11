(function ($, device, window) {
    if (device.mobile()) {
        $('#rsg-orientation-overlay').css('display', 'block');
        $('#rsg-orientation-overlay #mobile').css('display', 'block');
        //console.log('phone');
    }else if (device.tablet() && device.portrait()) {
        $('#rsg-orientation-overlay').css('display', 'block');
        $('#rsg-orientation-overlay #tablet').css('display', 'block');
        //console.log('tablet');
    }else {
        //console.log('full');
    }

    $(window).on('orientationchange', function (event) {
        if (device.portrait()) {
            $('#rsg-orientation-overlay').css('display', 'block');
            $('#rsg-orientation-overlay #tablet').css('display', 'block');
            //console.log('tablet portrait');
        }
        else {
            $('#rsg-orientation-overlay').css('display', 'none');
            $('#rsg-orientation-overlay #tablet').css('display', 'none');
        }
    });
}(jQuery, device, window));
$(function() {
    if(device.tablet() && device.portrait()){
        $('#rsg-orientation-overlay').css('display', 'block');
        $('#rsg-orientation-overlay #tablet').css('display', 'block');
    }
});