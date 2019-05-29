

let $ = require('jquery');

global.jQuery = $;
let Cookies = require('js-cookie');

let imagesLoaded = require('imagesloaded');
// provide jQuery argument
imagesLoaded.makeJQueryPlugin($);

require('featherlight/release/featherlight.min.js');
require('featherlight/release/featherlight.gallery.min.js');

$(function() {
    window.requestAnimFrame = require('./requestAnimFrame.js');
    let throttle = require('./throttle.js');

    let slider = require('./slider.js');
    let submenu = require('./submenu.js');
    let initScrollReveal = require('./initScrollReveal.js');
    let animHeader = require('./header.js');
    let sticky = require('./sticky.js');
    let brandSlider = require('./brandSlider.js');
    let accordion = require('./accordion.js');
    let initVideo = require('./initVideo.js');

    let htmlAze = $('html');
    let body = $('body');
    let header = $('#header');
    let sidebar = $('#sidebar');
    // window.outerWidth returns the window width including the scroll, but it's not working with $(window).outerWidth
    let windowWidth = window.outerWidth,
        windowHeight = $(window).height();
    let scrollTop, lastScrollTop, scrollDir;

    function detectScrollDir() {
        if (scrollTop > lastScrollTop) {
            scrollDir = -1;
        } else if (scrollTop < lastScrollTop) {
            scrollDir = 1;
        } else {
            scrollDir = 0;
        }
        lastScrollTop = scrollTop;
    }

    // Exécute les actions lorsqu'on scrolle la page qui masquent le menu
    function DoScroll(scrollDir) {
        scrollTop > 50 ? header.addClass('small') : header.removeClass('small');

        if (
            !body.hasClass('page-template-tpl-brands') &&
            !body.hasClass('single-brand')
        ) {
            if (scrollTop > 200) {
                if (scrollDir < 1) {
                    header.addClass('off');
                    if (sidebar.length) sidebar.addClass('js-show-logo');
                } else {
                    header.removeClass('off');
                    if (sidebar.length) sidebar.removeClass('js-show-logo');
                }
            } else {
                header.removeClass('off');
                if (sidebar.length) sidebar.removeClass('js-show-logo');
            }
        }
    }

    // Au chargement de la page on fait comme si on venait de scroller
    function InitScroll() {
        scrollTop = $(document).scrollTop();
        scrollDir = -1;
        DoScroll(scrollDir);
    }

    function resizeHandler() {
        windowWidth = window.outerWidth;
        windowHeight = $(window).height();
    }

    function loadHandler() {
        // Header
        animHeader(htmlAze, body, header, windowWidth);

        // Slider home
        slider($('#sliderHome'), windowWidth);
    }

    initScrollReveal(body);

    // Sticky
    if ($('.content').length) {
        $('.content').imagesLoaded(() => {
            sticky($('#blockSticky'), 130, {
                minimumWidth: 960
            });
        });
    }

    // Submenu (in pages) Anchors
    submenu($('#submenu'), windowHeight);
    // submenu( $('#submenuWrapper'), windowHeight, true );

    // Prevent popins from opening on mobile
    $.featherlight.defaults.beforeOpen = function(e) {
        if (
            windowWidth <= 580 &&
            $(e.currentTarget).length &&
            $(e.currentTarget).data('url')
        ) {
            window.location = $(e.currentTarget).data('url');
        }
    };

    // Single brand slider
    brandSlider($('#sliderBrand'));

    // Cookies
    body.on('click', '#btnCookies', function(e) {
        e.preventDefault();
        Cookies.set('beneteau-cookies', true, { expires: 30, path: '/' });
        $(this)
            .parents('#cookies')
            .addClass('off');
    });

    // Accordion
    accordion($('.eolia_results'));

    // Videos
    initVideo($('.js-video'));

    // Since script is loaded asynchronously, load event isn't always fired !!!
    document.readyState === 'complete'
        ? loadHandler()
        : $(window).on('load', loadHandler);

    if (!window.ActiveXObject && 'ActiveXObject' in window)
        body.addClass('ie11');

    $(window).on(
        'resize',
        throttle(function() {
            requestAnimFrame(resizeHandler);
        }, 60)
    );

    $(document).on(
        'scroll',
        throttle(function() {
            scrollTop = $(document).scrollTop();
            detectScrollDir();
            DoScroll(scrollDir);
        }, 60)
    );
    InitScroll();
});
