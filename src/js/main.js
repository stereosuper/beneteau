'use strict';

var $ = require('jquery');
global.jQuery = $;

require('featherlight/release/featherlight.min.js');
require('featherlight/release/featherlight.gallery.min.js');


$(function(){

    window.requestAnimFrame = require('./requestAnimFrame.js');
    var throttle = require('./throttle.js');
    var slider = require('./slider.js');

    var body = $('body');
    var header = $('#header');
    // window.outerWidth returns the window width including the scroll, but it's not working with $(window).outerWidth
    var windowWidth = window.outerWidth, windowHeight = $(window).height();
    var scrollTop;


    function resizeHandler(){
        windowWidth = window.outerWidth;
        windowHeight = $(window).height();
    }

    function loadHandler(){
        slider( $('#sliderHome'), windowWidth );
    }


    // isMobile.any ? body.addClass('is-mobile') : body.addClass('is-desktop');

    header.on('click', '#burger', function(e){
        
        e.preventDefault();
        $(this).toggleClass('on');
        $('#nav').toggleClass('on');
        body.toggleClass('menu-open');

    });


    // Since script is loaded asynchronously, load event isn't always fired !!!
    document.readyState === 'complete' ? loadHandler() : $(window).on('load', loadHandler);

    $(window).on('resize', throttle(function(){
        requestAnimFrame(resizeHandler);
    }, 60));

    $(document).on('scroll', throttle(function(){
        scrollTop = $(document).scrollTop();

        scrollTop > 50 ? header.addClass('small') : header.removeClass('small');
    }, 60));

});
