var $ = require('jquery');

module.exports = function initVideo( wrapperVideos ){
    if( !wrapperVideos.length ) return;

    const players = [], tag = document.createElement('script'), firstScriptTag = document.getElementsByTagName('script')[0];

    function onPlayerReady( wrapperVideoParent ){
        wrapperVideoParent.on('click', function() {
            $(this).find('.overlay').removeClass('on').delay(300).hide();
            players[$(this).index('.js-video')].playVideo();
        }).find('.overlay').addClass('on');
    }

    global.onYouTubeIframeAPIReady = function(){
        wrapperVideos.each(function( i ){
            players[i] = new YT.Player(
                $(this).find('.iframe').get(0), {
                    videoId: $(this).data('id'),
                    playerVars: {
                        modestbranding: 1,
                        color: 'white',
                        rel: 0,
                        showinfo: 0,
                    },
                    events: {
                        onReady: onPlayerReady( $(this) ),
                    },
                }
            );
        });
    };

    tag.src = 'https://www.youtube.com/iframe_api';
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
};
