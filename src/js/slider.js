var $ = require('jquery');

require('gsap/CSSPlugin');
var TweenLite = require('gsap/TweenLite');

window.requestAnimFrame = require('./requestAnimFrame.js');
var throttle = require('./throttle.js');


module.exports = function( slider, windowWidth ){
    if( !slider.length ) return;

    var slidesImg = slider.find('.slide-img');
    var slidesTxt = slider.find('.slide-txt');

    if( slidesImg.length < 2 ) return;

    var activeSlideImg = slider.find('.slide-img.first-on'), newActiveSlideImg;
    var activeSlideTxt = slider.find('.slide-txt.first-on'), newActiveSlideTxt;

    var sliderNav = slider.find('.slider-nav');

    var timeOut;


    function slide(index, button){
        setSliderTimeout();

        if( index ){
            newActiveSlideImg = slidesImg.eq(index);
            newActiveSlideTxt = slidesTxt.eq(index);
        }else{
            newActiveSlideImg = activeSlideImg.next('.slide-img').length ? activeSlideImg.next('.slide-img') : slidesImg.eq(0);
            newActiveSlideTxt = activeSlideTxt.next('.slide-txt').length ? activeSlideTxt.next('.slide-txt') : slidesTxt.eq(0);
        }

        if( !button ){
            button = sliderNav.find('.on').parent().next().length ? sliderNav.find('.on').parent().next().find('button') : sliderNav.find('li').eq(0).find('button');
        }

        TweenLite.to(activeSlideTxt.find('.title'), 0.5, {opacity: 0, x: '50px'});
        TweenLite.to(activeSlideTxt.find('.txt'), 0.5, {opacity: 0, x: '50px', delay: 0.1});
        TweenLite.to(activeSlideTxt.find('.button'), 0.5, {opacity: 0, x: '50px', delay: 0.2});

        TweenLite.to(activeSlideImg, 0.7, {opacity: 0, delay: 0.5});
        
        activeSlideImg.removeClass('on');
        activeSlideTxt.removeClass('on');

        newActiveSlideImg.addClass('on');
        TweenLite.to(newActiveSlideImg, 0.7, {opacity: 1});

        newActiveSlideTxt.addClass('on');
        TweenLite.fromTo(newActiveSlideTxt.find('.title'), 0.5, {opacity: 0, x: '-50px'}, {opacity: 1, x: 0, delay: 0.5});
        TweenLite.fromTo(newActiveSlideTxt.find('.txt'), 0.5, {opacity: 0, x: '-50px'}, {opacity: 1, x: 0, delay: 0.6});
        TweenLite.fromTo(newActiveSlideTxt.find('.button'), 0.5, {opacity: 0, x: '-50px'}, {opacity: 1, x: 0, delay: 0.7});

        activeSlideImg = slider.find('.slide-img.on');
        activeSlideTxt = slider.find('.slide-txt.on');

        windowWidth > 780 ? slider.attr('style', '') : slider.height(activeSlideTxt.height());

        button.addClass('on').parent().siblings().find('button').removeClass('on');
    }

    function setSliderTimeout(){
        clearTimeout( timeOut );
        timeOut = setTimeout( slide, 8000 );
    }


    activeSlideImg.removeClass('first-on').addClass('on').css('opacity', 1);

    activeSlideTxt.removeClass('first-on').addClass('on').find('.title');
    TweenLite.set([activeSlideTxt.find('.title'), activeSlideTxt.find('.txt'), activeSlideTxt.find('.button')], {opacity: 1});

    windowWidth > 780 ? slider.attr('style', '') : slider.height(activeSlideTxt.height());

    setSliderTimeout();


    slider.on('click', 'button', function(e){
        e.preventDefault();
        if( $(this).hasClass('on') ) return;
        slide($(this).parent().index(), $(this));        
    });

    $(window).on('focusout', function(){
        clearTimeout(timeOut);
    }).on('focusin', setSliderTimeout).on('resize', throttle(function(){
        requestAnimFrame( function(){
            windowWidth = window.outerWidth;
            windowWidth > 780 ? slider.attr('style', '') : slider.height(activeSlideTxt.height());
        } );
    }, 60));
}