var $ = require('jquery');

require('gsap/CSSPlugin');
var TweenLite = require('gsap/TweenLite');


module.exports = function( slider ){
    if( !slider.length ) return;

    var slides = slider.find('img');

    if( slides.length < 2 ) return;

    var activeSlide = slider.find('.on'), newActiveSlide;


    function slide(){
        newActiveSlide = activeSlide.next('img').length ? activeSlide.next('img') : slides.eq(0);

        activeSlide.removeClass('on');
        newActiveSlide.addClass('on');

        activeSlide = slider.find('.on');

        setSliderTimeout();
    }

    function setSliderTimeout(){
        TweenLite.delayedCall( 4, slide );
    }


    setSliderTimeout();


    $(window).on('focusout', function(){
        TweenLite.killDelayedCallsTo( slide );
    }).on('focusin', setSliderTimeout);
}