
$(document).ready(function(e) {
   
    /* wwwroot est forcé via balise script dans login. */
    if (typeof  M !== 'undefined') {
        if (typeof wwwroot === 'undefined' && typeof  M.cfg.wwwroot !== 'undefined' ) {
            wwwroot = M.cfg.wwwroot;
        } else {
            // alert("Modal video. Cannot resolve wwwroot.");
        }
    }

   function getVideoHtml(videoId, videoHeight) {
        $('#video_container').hide();

        var date = new Date();
        var uniqueTime = date.getTime();
        
        var baseUrl = "https://player.vimeo.com/video/";
        var fixChrome = "?fixChromeBug="+uniqueTime+'&autoplay=1';          
            
        var vimeoframe = document.createElement('iframe');
        vimeoframe.setAttribute('id', 'vimeoframe');
        vimeoframe.setAttribute('type', 'text/html');
        vimeoframe.setAttribute('allowFullScreen', '');
        vimeoframe.setAttribute('width', 526);
        vimeoframe.setAttribute('frameBorder', 0);
        vimeoframe.setAttribute('height', videoHeight);
        vimeoframe.setAttribute('src', baseUrl + videoId + fixChrome);
        
        $("#video_container").append(vimeoframe);

    }

    
/*        
        capsule_visit
        <iframe src="https://player.vimeo.com/video/200361623" width="640" height="450" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>

        platform_visit
        <iframe src="https://player.vimeo.com/video/200361237" width="640" height="359" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
*/   
    
    $("#platform_visit").on("click", function(ev){
        // getVideoHtml("platform_visit"); 
        getVideoHtml("254524312",294);
    });

    $("#capsule_visit").on("click", function(ev){
        // getVideoHtml("capsule_visit");
        //getVideoHtml("254526498",370);
		 getVideoHtml("340672959",370);
    });
    
    $('#btnno').click(function() { 
       $.unblockUI(); 
       return false; 
    });

    $('#myModal').modal({
        backdrop:"static",
        show:false

    });

    $('#myModal').on('show', function () {
      $('#video_container').show();
    }); 

    /* Handlers need to be registered everytime a new instance is created. */
    $('#myModal').on('hide', function () {
        /* This should work even with 3rd party video player if they are html5 compatible */
        try {
            $('#vimeoframe')[0].pause();
        } catch(e) {
            // Video removed before play.
        }
    });   
    
    $('#myModal').on('hidden', function () {
                
        $( "#video_container" ).empty();
    });
    
}); 