const $ = require('jquery');
const createFocusTrap = require('focus-trap');

require('gsap/CSSPlugin');

module.exports = function(htmlAze, header) {
    if (!header.length) return;

    const nav = $('#nav');
    //const menuBg = $('#menuBg');
    const mainMenus = $('#main-menus');
    const focusTrap = createFocusTrap('#main-menus');
    const lang = $('#lang');
    const accordionBtn = header.find('.js-accordion-button');

    header
        .on('click', '#burger', (e) => {
            focusTrap.activate();
            e.preventDefault();
            nav.addClass('on');
            htmlAze.addClass('menu-open');
            $('#main-navigation-cross').focus();
            mainMenus.attr('role', 'dialog').attr('aria-label', 'navigation');
            $('#access, #main, #footer, #logo').attr('aria-hidden', true);
            accordionBtn.attr('role', 'button');
            mainMenus.attr('aria-hidden', false).find('a, button').attr('tabindex', 0);
        })
        .on('click keyup', '.js-menu-btn', function(e) {
            if( e.keyCode !== undefined && e.keyCode !== 13 && e.keyCode !== 32 ) return;

            $(this).toggleClass('on').parent().find('.menu-content').toggleClass('on').parent().siblings().find('.menu-content').removeClass('on').parent().find('.js-menu-btn').removeClass('on');
            if( $(this).hasClass('on') ){
                $(this).attr('aria-expanded', true);
                header.addClass('submenu-open');
            }else{
                $(this).attr('aria-expanded', false);
                header.removeClass('submenu-open');
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
            focusTrap.deactivate();
            e.preventDefault();
            nav.removeClass('on');
            htmlAze.removeClass('menu-open');
            $('#access, #main, #footer, #logo').attr('aria-hidden', false);
            $('#burger').focus();
            accordionBtn.attr('role', '');
            mainMenus.attr('aria-hidden', true).find('a, button').attr('tabindex', -1);
        });

    $('body').on('click', function(e){
        if( (!header.find(e.target).length || !e.target.id === 'header') && header.hasClass('submenu-open') ){
            header.removeClass('submenu-open').find('.js-menu-btn').removeClass('on');
            header.find('.menu-content').removeClass('on');
        }
    });

    if( $(window).width() > 1100 ){
        accordionBtn.removeAttr('aria-expanded').removeAttr('tabindex');
    }else{
        mainMenus.attr('aria-hidden', true).find('a, button').attr('tabindex', -1);
        header.find('.js-menu-btn').attr('role', '').attr('tabindex', -1).attr('aria-expanded', true);
    }

    if( lang.length ){
        lang.find('[lang="FR"]').attr('title', 'FR - Version française');
        lang.find('[lang="EN"]').attr('title', 'EN - English version');
    }
};
