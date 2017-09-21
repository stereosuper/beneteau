var $ = require('jquery');

require('gsap/CSSPlugin');
var TweenLite = require('gsap/TweenLite');

module.exports = function(slider){
    if( !slider.length ) return;


    var activeSlideImg = slider.find('.slide-img.first-on'), newActiveSlideImg;
    var activeSlideTxt = slider.find('.slide-txt.first-on'), newActiveSlideTxt;
    var timeOut;


    function slide(index, button){
        setSliderTimeout();

        activeSlideImg = slider.find('.slide-img.on');
        activeSlideTxt = slider.find('.slide-txt.on');

        if( index ){
            newActiveSlideImg = slider.find('.slide-img').eq(index);
            newActiveSlideTxt = slider.find('.slide-txt').eq(index);
        }else{
            newActiveSlideImg = activeSlideImg.next('.slide-img').length ? activeSlideImg.next('.slide-img') : slider.find('.slide-img').eq(0);
            newActiveSlideTxt = activeSlideTxt.next('.slide-txt').length ? activeSlideTxt.next('.slide-txt') : slider.find('.slide-txt').eq(0);
        }

        if( !button ){
            button = slider.find('.slide-nav').find('.on').next().length ? slider.find('.slide-nav').find('.on').next().find('button') : slider.find('.slide-nav').find('li').eq(0).find('button');
        }

        TweenLite.to(activeSlideTxt.find('.title'), 0.5, {opacity: 0, x: '50px'});
        TweenLite.to(activeSlideTxt.find('.txt'), 0.5, {opacity: 0, x: '50px', delay: 0.1});
        TweenLite.to(activeSlideTxt.find('.button'), 0.5, {opacity: 0, x: '50px', delay: 0.2});

        TweenLite.to(activeSlideImg, 0.7, {opacity: 0, delay: 0.5});
        activeSlideImg.removeClass('on');

        activeSlideTxt.removeClass('on');

        newActiveSlideImg.addClass('on');
        activeSlideImg = $('#sliderHome').find('.slide-img.on');
        TweenLite.to(activeSlideImg, 0.7, {opacity: 1});

        newActiveSlideTxt.addClass('on');
        activeSlideTxt = slider.find('.slide-txt.on');
        TweenLite.fromTo(activeSlideTxt.find('.title'), 0.5, {opacity: 0, x: '-50px'}, {opacity: 1, x: 0, delay: 0.5});
        TweenLite.fromTo(activeSlideTxt.find('.txt'), 0.5, {opacity: 0, x: '-50px'}, {opacity: 1, x: 0, delay: 0.6});
        TweenLite.fromTo(activeSlideTxt.find('.button'), 0.5, {opacity: 0, x: '-50px'}, {opacity: 1, x: 0, delay: 0.7});

        button.addClass('on').parent().siblings().find('button').removeClass('on');
    }

    function setSliderTimeout(){
        clearTimeout(timeOut);
        timeOut = setTimeout(slide, 8000);
    }


    activeSlideImg.removeClass('first-on').addClass('on').css({'opacity': 1});

    activeSlideTxt.removeClass('first-on').addClass('on').find('.title').css({'opacity': 1});
    activeSlideTxt.find('.txt').css({'opacity': 1});
    activeSlideTxt.find('.button').css({'opacity': 1});

    setSliderTimeout();

    slider.on('click', 'button', function(e){
        e.preventDefault();

        if( $(this).hasClass('on') ) return;

        slide($(this).parent().index(), $(this));        
    });
}