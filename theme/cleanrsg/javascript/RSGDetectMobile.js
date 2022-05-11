(function ($, device, window) {
    if (device.mobile()) {
        $('#overlay').css('display', 'block');
        $('#mobile').css('display', 'block');
        //console.log('phone');
    }
    else if ((device.mobile() && device.portrait())||(device.mobile() && device.landscape())) {
        $('#overlay').css('display', 'block');
        $('#mobile').css('display', 'block');
        //console.log('tablet');
    }
    //else {
    //    console.log('full');
    //}



    if (device.mobile()) {
        $('a.next').css('right', '0px');
        $('a.prev').css('left', '0px');
    }

    // Désactivation du responsive sur Ordi
    if (!device.mobile() && !device.tablet()) {
        $('.container').css('width', 960);
    }
}(jQuery, device, window));