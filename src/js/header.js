const $ = require('jquery');

require('gsap/CSSPlugin');

module.exports = function(htmlAze, header) {
    if (!header.length) return;

    const nav = $('#nav');
    const menuBg = $('#menuBg');

    header
        .on('click', '#burger', (e) => {
            e.preventDefault();
            nav.addClass('on');
            htmlAze.addClass('menu-open');
            menuBg.addClass('on');
        })
        .on('click', '.js-menu-btn', function() {
            $(this).toggleClass('on').parent().find('.menu-content').toggleClass('on').parent().siblings().find('.menu-content').removeClass('on').parent().find('.js-menu-btn').removeClass('on');
            $(this).hasClass('on') ? header.addClass('on') : header.removeClass('on');
        });

    header.on('click', '#main-navigation-cross', e => {
        e.preventDefault();
        nav.removeClass('on');
        htmlAze.removeClass('menu-open');
        menuBg.removeClass('on');
    });
};
