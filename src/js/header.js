const $ = require('jquery');

require('gsap/CSSPlugin');
const TweenLite = require('gsap/TweenLite');

window.requestAnimFrame = require('./requestAnimFrame.js');
const throttle = require('./throttle.js');

module.exports = function(htmlAze, body, header, windowWidth) {
    if (!header.length) return;

    const nav = $('#nav');
    let menuX;
    let submenu, submenuList, submenuLeft, submenuWidth, linkCenter;
    const menuBg = $('#menuBg');

    header
        .on('click', '#burger', (e) => {
            e.preventDefault();
            nav.addClass('on');
            htmlAze.addClass('menu-open');
            menuBg.addClass('on');
        })
        .on('mouseenter', 'a', function() {
            if ($(this).parents('.sub-menu').length) return;

            if (header.find('.sub-menu').length) {
                header.find('.sub-menu').removeClass('on');
                header.removeClass('hover');
                if (!htmlAze.hasClass('menu-open')) menuBg.removeClass('on');
            }

            if ($(this).siblings('.sub-menu').length) {
                $(this)
                    .siblings('.sub-menu')
                    .addClass('on');
                header.addClass('hover');
                if (!htmlAze.hasClass('menu-open')) menuBg.addClass('on');
            }
        })
        .on('mouseleave', function() {
            if ($(this).find('.sub-menu').length) {
                $(this)
                    .find('.sub-menu')
                    .removeClass('on');
                header.removeClass('hover');
                if (!htmlAze.hasClass('menu-open')) menuBg.removeClass('on');
                // TweenLite.set($(this).siblings('.sub-menu').children('ul'), {x: 0, delay: 0.3});
            }
        })
        .find('a')
        .each(function() {
            submenu = $(this).parents('.sub-menu');

            if (!submenu.length && $(this).siblings('.sub-menu').length) {
                submenuList = $(this)
                    .siblings('.sub-menu')
                    .children('ul');
                submenuLeft = submenuList.offset()
                    ? submenuList.offset().left
                    : 0;
                submenuWidth = submenuList.width();
                linkCenter = $(this).offset().left + $(this).width() / 2;

                menuX =
                    linkCenter + submenuWidth / 2 > windowWidth - 15
                        ? windowWidth - submenuLeft - submenuWidth - 15
                        : linkCenter - submenuLeft - submenuWidth / 2;

                TweenLite.set(submenuList, { x: menuX });
            }
        });

    header.on('click', '#main-navigation-cross', e => {
        e.preventDefault();
        nav.removeClass('on');
        htmlAze.removeClass('menu-open');
        menuBg.removeClass('on');
    });

    $(window).on(
        'resize',
        throttle(() => {
            requestAnimFrame(() => {
                TweenLite.set(header.find('.sub-menu').children('ul'), {
                    x: 0,
                });
            });
        }, 60)
    );
};
