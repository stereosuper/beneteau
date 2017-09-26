'use strict';

var $ = require('jquery');
global.jQuery = $;
var ScrollReveal = require('scrollreveal');

require('featherlight/release/featherlight.min.js');
require('featherlight/release/featherlight.gallery.min.js');


$(function(){

    window.requestAnimFrame = require('./requestAnimFrame.js');
    var throttle = require('./throttle.js');
    
    var slider = require('./slider.js');
    var submenu = require('./submenu.js');
    var initScrollReval = require('./initScrollReveal.js');

    var body = $('body');
    var header = $('#header');
    // window.outerWidth returns the window width including the scroll, but it's not working with $(window).outerWidth
    var windowWidth = window.outerWidth, windowHeight = $(window).height();
    var scrollTop, lastScrollTop, scrollDir;


    function detectScrollDir(){
        if(scrollTop > lastScrollTop){
            scrollDir = -1;
        }else if(scrollTop < lastScrollTop){
            scrollDir = 1;
        }else{
            scrollDir = 0;
        }
        lastScrollTop = scrollTop;
    }

    function resizeHandler(){
        windowWidth = window.outerWidth;
        windowHeight = $(window).height();
    }

    function loadHandler(){
        slider( $('#sliderHome'), windowWidth );
    }


    // isMobile.any ? body.addClass('is-mobile') : body.addClass('is-desktop');

    // Submenu Anchors
    submenu( $('#submenu'), windowHeight );
    submenu( $('#submenuWrapper'), windowHeight, true );

    // Responsive Header
    header.on('click', '#burger', function(e){
        
        e.preventDefault();
        $(this).toggleClass('on');
        $('#nav').toggleClass('on');
        body.toggleClass('menu-open');

    }).on('mouseenter', 'a', function(){

        if( $(this).parents('.sub-menu').length ) return;

        if( $(this).parents('#header').find('.sub-menu').length ){
            $(this).parents('#header').find('.sub-menu').removeClass('on');
        }

        if( $(this).siblings('.sub-menu').length ){
            $(this).siblings('.sub-menu').addClass('on');
        }

    }).on('mouseleave', '#nav', function(){

        if( $(this).find('.sub-menu').length ){
            $(this).find('.sub-menu').removeClass('on');
        }

    });

    // ScrollReveal
    window.sr = ScrollReveal({reset: true});
    // initScrollReval('.isAnimated');
    sr.reveal('.isAnimated');
    sr.reveal('.content-brand .baseline', { duration: 1500, origin: 'right', scale: 1, distance: '60px' });
    sr.reveal('.exergue', { easing: 'ease-in-out', duration: 500, origin: 'left', scale: 0.9, distance: '60px' });
    sr.reveal('.list-brands >li:nth-child(2n+0)', { easing: 'ease-in-out', duration: 600, origin: 'left', scale: 0.5, distance: '30px' });
    sr.reveal('.list-brands >li:nth-child(2n+1)', { easing: 'ease-in-out', duration: 600, origin: 'right', scale: 0.5, distance: '30px' });
    
    /*
    sr.reveal('.content');
    sr.reveal('.exergue', { easing: 'ease-in-out',duration: 500,origin: 'left',scale: 0.9,distance: '60px' });
    sr.reveal('.push .before', { easing: 'ease-in-out',duration: 500,rotate: { x: 0, y: 0, z: 0 },scale:1, opacity:0,distance: '80px', beforeReveal: function (domEl) {showpush($(".spritepush"));} });
    sr.reveal('.push', { duration: 800,origin: 'bottom',scale: 1,distance: '60px' });//scrollreveal exerguer
    //scrollreveal sur la liste des marques et services
    sr.reveal('.item-marque:nth-child(2n+0)', { easing: 'ease-in-out',duration: 600,origin: 'left',scale: 0.5,distance: '30px' });
    sr.reveal('.item-marque:nth-child(2n+1)', { easing: 'ease-in-out',duration: 600,origin: 'right',scale: 0.5,distance: '30px' });
    if($("body").hasClass('home')){
    sr.reveal('.actu', { beforeReveal: function (domEl) {showpush($(".spritepush"));} });
    }
    */

    // Since script is loaded asynchronously, load event isn't always fired !!!
    document.readyState === 'complete' ? loadHandler() : $(window).on('load', loadHandler);

    $(window).on('resize', throttle(function(){
        requestAnimFrame(resizeHandler);
    }, 60));

    $(document).on('scroll', throttle(function(){
        scrollTop = $(document).scrollTop();
        
        detectScrollDir();

        scrollTop > 50 ? header.addClass('small') : header.removeClass('small');

        if( scrollTop > 200 ){
            scrollDir < 1 ? header.addClass('off') : header.removeClass('off');
        }else{
            header.removeClass('off');
        }
    }, 60));

});
