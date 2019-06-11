const $ = require('jquery');

require('gsap/CSSPlugin');

module.exports = function(htmlAze, header) {
    if (!header.length) return;

    const nav = $('#nav');
    //const menuBg = $('#menuBg');
    const close = $('#main-navigation-cross');

    header
        .on('click', '#burger', (e) => {
            e.preventDefault();
            nav.addClass('on');
            htmlAze.addClass('menu-open');
            //menuBg.addClass('on');
        })
        .on('click', '.js-menu-btn', function() {
            $(this).toggleClass('on').parent().find('.menu-content').toggleClass('on').parent().siblings().find('.menu-content').removeClass('on').parent().find('.js-menu-btn').removeClass('on');
            if( $(this).hasClass('on') ){
                $(this).attr('aria-expanded', true);
                header.addClass('on');
                close.css('top', $(this).parent().find('.menu-content').offset().top + $(this).parent().find('.menu-content').outerHeight());
            }else{
                $(this).attr('aria-expanded', false);
                header.removeClass('on');
            }
        })
        .on('click', '#main-navigation-cross', function(){
            header.removeClass('on').find('.js-menu-btn').removeClass('on').parent().find('.menu-content').removeClass('on');
        });

    header.on('click', '#main-navigation-cross', e => {
        e.preventDefault();
        nav.removeClass('on');
        htmlAze.removeClass('menu-open');
        //menuBg.removeClass('on');
    });
};
