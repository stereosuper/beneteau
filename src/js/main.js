'use strict';

var $ = require('jquery');
global.jQuery = $;

require('featherlight/release/featherlight.min.js');
require('featherlight/release/featherlight.gallery.min.js');


$(function(){

    window.requestAnimFrame = require('./requestAnimFrame.js');
    var throttle = require('./throttle.js');
    
    var slider = require('./slider.js');
    var submenu = require('./submenu.js');
    var initScrollReveal = require('./initScrollReveal.js');
    var animHeader = require('./header.js');
    var sticky = require('./sticky.js');
    var brandSlider = require('./brandSlider.js');

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
        // Header
        animHeader( body, header, windowWidth );

        // Slider home
        slider( $('#sliderHome'), windowWidth );
    }


    // isMobile.any ? body.addClass('is-mobile') : body.addClass('is-desktop');
    initScrollReveal();

    // Sticky
    sticky($('#blockSticky'), 130, {
        minimumWidth: 960
    });

    // Submenu (in pages) Anchors
    submenu( $('#submenu'), windowHeight );
    //submenu( $('#submenuWrapper'), windowHeight, true );

    // Prevent popins from opening on mobile
    $.featherlight.defaults.beforeOpen = function(){
        if( windowWidth <= 580 ) return false;
    };

    // Single brand slider
    brandSlider( $('#sliderBrand') );
    

    // Since script is loaded asynchronously, load event isn't always fired !!!
    document.readyState === 'complete' ? loadHandler() : $(window).on('load', loadHandler);

    $(window).on('resize', throttle(function(){
        requestAnimFrame(resizeHandler);
    }, 60));

    $(document).on('scroll', throttle(function(){
        scrollTop = $(document).scrollTop();
        
        detectScrollDir();

        scrollTop > 50 ? header.addClass('small') : header.removeClass('small');

        if( !body.hasClass('page-template-tpl-brands') && !body.hasClass('single-brand') ){
            if( scrollTop > 200 ){
                scrollDir < 1 ? header.addClass('off') : header.removeClass('off');
            }else{
                header.removeClass('off');
            }
        }
    }, 60));

});
