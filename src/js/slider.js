var $ = require('jquery');

require('gsap/CSSPlugin');
var TweenLite = require('gsap/TweenLite');

var Hammer = require('hammerjs');

window.requestAnimFrame = require('./requestAnimFrame.js');
var throttle = require('./throttle.js');

var checkIfInView = require('./checkIfInView.js');


module.exports = function( slider, windowWidth ){
    if( !slider.length ) return;

    var slidesImg = slider.find('.slide-img');
    var slidesTxt = slider.find('.slide-txt');

    if( slidesImg.length < 2 ) return;

    var activeSlideImg = slider.find('.slide-img.first-on'), newActiveSlideImg;
    var activeSlideTxt = slider.find('.slide-txt.first-on'), newActiveSlideTxt;

    var sliderNav = slider.find('.slider-nav');

    var done = true;

    var slides, indexActive, nbSlides, nextIndex;


    function slide(index, button){
        done = false;
        
        if( index > -1 ){
            newActiveSlideImg = slidesImg.eq(index);
            newActiveSlideTxt = slidesTxt.eq(index);
            
        }else{
            newActiveSlideImg = activeSlideImg.next('.slide-img').length ? activeSlideImg.next('.slide-img') : slidesImg.eq(0);
            newActiveSlideTxt = activeSlideTxt.next('.slide-txt').length ? activeSlideTxt.next('.slide-txt') : slidesTxt.eq(0);
        }

        if( !button ){
            button = sliderNav.find('.on').parent().next().length ? sliderNav.find('.on').parent().next().find('button') : sliderNav.find('li').eq(0).find('button');
        }

        TweenLite.fromTo(activeSlideTxt.find('.title'), 0.5, {opacity: 1, x: 0}, {opacity: 0, x: '50px', overwrite: true});
        TweenLite.fromTo(activeSlideTxt.find('.txt'), 0.5, {opacity: 1, x: 0}, {opacity: 0, x: '50px', delay: 0.1, overwrite: true});
        TweenLite.fromTo(activeSlideTxt.find('.button'), 0.5, {opacity: 1, x: 0}, {opacity: 0, x: '50px', delay: 0.2, overwrite: true});

        TweenLite.fromTo(activeSlideImg, 0.7, {opacity: 1}, {opacity: 0, delay: 0.5, overwrite: true});
        
        activeSlideImg.removeClass('on');
        activeSlideTxt.removeClass('on');

        newActiveSlideImg.addClass('on');
        TweenLite.fromTo(newActiveSlideImg, 0.7, {opacity: 0}, {opacity: 1, overwrite: true});

        newActiveSlideTxt.addClass('on');
        TweenLite.fromTo(newActiveSlideTxt.find('.title'), 0.5, {opacity: 0, x: '-50px'}, {opacity: 1, x: 0, delay: 0.5, overwrite: true});
        TweenLite.fromTo(newActiveSlideTxt.find('.txt'), 0.5, {opacity: 0, x: '-50px'}, {opacity: 1, x: 0, delay: 0.6, overwrite: true});
        TweenLite.fromTo(newActiveSlideTxt.find('.button'), 0.5, {opacity: 0, x: '-50px'}, {opacity: 1, x: 0, delay: 0.7, onComplete: function(){
            done = true;
        }, overwrite: true});

        activeSlideImg = slider.find('.slide-img.on');
        activeSlideTxt = slider.find('.slide-txt.on');

        windowWidth > 780 ? slider.attr('style', '') : slider.height(activeSlideTxt.height());

        button.addClass('on').parent().siblings().find('button').removeClass('on');

        setSliderTimeout();
    }

    function setSliderTimeout(){
        TweenLite.killDelayedCallsTo( slide );
        
        if( checkIfInView.check(slider) ){
            TweenLite.delayedCall( 8, slide );
        }
    }

    function callSwipe(direction){
        slides = slider.find('.slider-img li');
        nbSlides = slides.length;
        if( !slider.hasClass('on') && done ){
            TweenLite.killDelayedCallsTo( slide );
            indexActive = slider.find('li.on').index();
            if(direction === 'left'){
                nextIndex = indexActive+1;
                if(nextIndex > (nbSlides - 1)){
                    nextIndex = 0;
                }
            }else{
                nextIndex = indexActive-1;
                if(nextIndex < 0){
                    nextIndex = nbSlides-1;
                }
            }
            slide(nextIndex);
        }
    }


    activeSlideImg.removeClass('first-on').addClass('on').css('opacity', 1);

    activeSlideTxt.removeClass('first-on').addClass('on').find('.title');
    TweenLite.set([activeSlideTxt.find('.title'), activeSlideTxt.find('.txt'), activeSlideTxt.find('.button')], {opacity: 1});

    windowWidth > 780 ? slider.attr('style', '') : slider.height(activeSlideTxt.height());

    checkIfInView.init(slider);
    setSliderTimeout();


    slider.on('click', 'button', function(e){
        e.preventDefault();
        if( !$(this).hasClass('on') && done ){
            TweenLite.killDelayedCallsTo( slide );
            slide($(this).parent().index(), $(this));
        }
    });

    $(window).on('focusout', function(){
        TweenLite.killDelayedCallsTo( slide );
    }).on('focusin', setSliderTimeout).on('resize', throttle(function(){
        requestAnimFrame( function(){
            windowWidth = window.outerWidth;
            windowWidth > 780 ? slider.attr('style', '') : slider.height(activeSlideTxt.height());
            setSliderTimeout();
        } );
    }, 60));

    var hammertime = new Hammer(slider.get(0));
    hammertime.on('swipeleft', function(){
        callSwipe('left');
    }).on('swiperight', function(){
        callSwipe('right');
    });

    $(document).on('scroll', throttle(function(){
        requestAnimFrame(setSliderTimeout);
    }, 10));
}