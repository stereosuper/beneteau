const $ = require('jquery');

require('gsap/CSSPlugin');

module.exports = function(htmlAze, header) {
    if (!header.length) return;

    const nav = $('#nav');
    //const menuBg = $('#menuBg');
    const mainMenus = $('#main-menus');

    header
        .on('click', '#burger', (e) => {
            e.preventDefault();
            nav.addClass('on');
            htmlAze.addClass('menu-open');
            $('#main-navigation-cross').focus();
            mainMenus.attr('role', 'dialog').attr('aria-label', 'navigation');
            $('#access, #main, #footer, #logo').attr('aria-hidden', true);
        })
        .on('click', '.js-menu-btn', function() {
            $(this).toggleClass('on').parent().find('.menu-content').toggleClass('on').parent().siblings().find('.menu-content').removeClass('on').parent().find('.js-menu-btn').removeClass('on');
            if( $(this).hasClass('on') ){
                $(this).attr('aria-expanded', true);
                header.addClass('on');
            }else{
                $(this).attr('aria-expanded', false);
                header.removeClass('on');
            }
        })
        .on('focus', '.js-menu-btn', function(){
            if( header.find('.js-menu-btn.on').length ){
                header.find('.js-menu-btn').removeClass('on').attr('aria-expanded', false).parent().find('.menu-content').removeClass('on');
                header.removeClass('on');
            }
        })
        .on('click', '.nav-cross', function(){
            header.removeClass('on').find('.js-menu-btn.on').focus().removeClass('on').parent().find('.menu-content').removeClass('on');
        })
        .on('click', '#main-navigation-cross', e => {
            e.preventDefault();
            nav.removeClass('on');
            htmlAze.removeClass('menu-open');
            $('#access, #main, #footer, #logo').attr('aria-hidden', false);
            $('#burger').focus();
        });

    if( $(window).width() > 1100 ){
        $('.js-accordion-button').removeAttr('aria-expanded').removeAttr('tabindex');
    }
};
