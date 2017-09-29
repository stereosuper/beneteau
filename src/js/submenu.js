var $ = require('jquery');

require('gsap/CSSPlugin');
require('gsap/ScrollToPlugin');
var TweenLite = require('gsap/TweenLite');

window.requestAnimFrame = require('./requestAnimFrame.js');
var throttle = require('./throttle.js');


module.exports = function( submenu, windowHeight ){
    if( !submenu.length ) return;

    var scrollTop;
    var thisSection;
    var thisLi;

    var brandsImg = $('#brandsImg');

    function detectSectionsTop(){
        if( $(this).attr('href').lastIndexOf('#', 0) !== 0 ) return;

        thisSection = $($(this).attr('href'));
        thisSection.data('top', thisSection.offset().top);
    }

    submenu.on('click', 'a', function(e){
        if( $(this).attr('href').lastIndexOf('#', 0) === 0 ){
            e.preventDefault();
            TweenLite.to(window, 0.5, {scrollTo: $($(this).attr('href')).data('top') - 100});
        }
    }).find('a').each(detectSectionsTop);

    $(document).on('scroll', throttle(function(){
        scrollTop = $(document).scrollTop();

        submenu.find('a').each(function(){
            if( $(this).attr('href').lastIndexOf('#', 0) === 0 ){
                thisLi = $(this).parent();

                scrollTop >= $($(this).attr('href')).data('top') - windowHeight/3 ? thisLi.addClass('active').siblings().removeClass('active') : thisLi.removeClass('active');

                if( brandsImg.length && thisLi.hasClass('active') ){
                    brandsImg.find('img').eq(thisLi.index()).addClass('on').siblings().removeClass('on');
                }
            }
        });
    }, 60));

    $(window).on('resize', throttle(function(){
        requestAnimFrame(function(){
            windowHeight = $(window).height();

            submenu.find('a').each(detectSectionsTop);
        });
    }, 60));
}