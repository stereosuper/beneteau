var $ = require('jquery');
global.jQuery = $;

require('gsap/CSSPlugin');
require('gsap/ScrollToPlugin');
var TweenLite = require('gsap/TweenLite');

window.requestAnimFrame = require('./requestAnimFrame.js');
var throttle = require('./throttle.js');


module.exports = function( submenu, windowHeight ){
    if( !submenu.length ) return;

    var scrollTop;
    var thisSection;

    submenu.on('click', 'a', function(e){
        e.preventDefault();
        TweenLite.to(window, 0.5, {scrollTo: $($(this).attr('href')).data('top') - 100});
    }).find('a').each(function(){
        thisSection = $($(this).attr('href'));
        thisSection.data('top', thisSection.offset().top);
    });

    $(document).on('scroll', throttle(function(){
        scrollTop = $(document).scrollTop();

        submenu.find('a').each(function(){
            if( scrollTop >= $($(this).attr('href')).data('top') - windowHeight/3 ){
                $(this).parent().addClass('active').siblings().removeClass('active');
            }else{
                $(this).parent().removeClass('active');
            }
        });
    }, 60));

    $(window).on('resize', throttle(function(){
        requestAnimFrame(function(){
            windowHeight = $(window).height();

            submenu.find('a').each(function(){
                thisSection = $($(this).attr('href'));
                thisSection.data('top', thisSection.offset().top);
            });
        });
    }, 60));
}