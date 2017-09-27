var $ = require('jquery');

require('gsap/CSSPlugin');
var TweenLite = require('gsap/TweenLite');


module.exports = function(body, header){
    if( !header.length ) return;

    var nav = $('#nav'), navLeft = nav.offset().left, navWidth = nav.width();
    var menuX;
    var submenu, submenuList, submenuFirstItem;


    header.on('click', '#burger', function(e){

        e.preventDefault();
        $(this).toggleClass('on');
        nav.toggleClass('on');
        body.toggleClass('menu-open');

    }).on('mouseenter', 'a', function(){

        if( $(this).parents('.sub-menu').length ) return;

        if( header.find('.sub-menu').length ){
            header.find('.sub-menu').removeClass('on');
        }

        if( $(this).siblings('.sub-menu').length ){
            $(this).siblings('.sub-menu').addClass('on');
        }

    }).on('mouseleave', '#nav', function(){

        if( $(this).find('.sub-menu').length ){
            $(this).find('.sub-menu').removeClass('on');
            TweenLite.set($(this).siblings('.sub-menu').children('ul'), {x: 0, delay: 0.3});
        }

    })/*.find('a').each(function(){

        submenu = $(this).parents('.sub-menu');

        if( !submenu.length && $(this).siblings('.sub-menu').length ){
            submenuList = $(this).siblings('.sub-menu').children('ul');
            submenuFirstItem = submenuList.children('li').eq(0);

            if( submenuList.children('li').eq(2).length ){
                menuX = - (submenuFirstItem.offset().left + submenuFirstItem.width() - $(this).offset().left);
                
                if(submenuList.children('li').eq(2).offset().left + submenuList.children('li').eq(2).width() + menuX < navLeft + navWidth){
                    TweenLite.set(submenuList, {x: menuX});
                }else{
                    submenuList.find('li').addClass('right');
                }
            }else if( submenuList.children('li').eq(1).length ){
                menuX = $(this).offset().left - submenuFirstItem.offset().left;
                
                if(submenuList.children('li').eq(1).offset().left + submenuList.children('li').eq(1).width() + menuX < navLeft + navWidth){
                    TweenLite.set(submenuList, {x: menuX});
                }else if( submenuList.children('li').eq(1).offset().left + submenuList.children('li').eq(1).width() < navLeft + navWidth ){
                    TweenLite.set(submenuList, {x: submenuList.children('li').eq(1).width()});
                    submenuList.find('li').addClass('right');
                }
            }else if( submenuFirstItem.length ){
                menuX = $(this).offset().left - submenuFirstItem.offset().left;
                
                if(submenuFirstItem.offset().left + submenuFirstItem.width() + menuX < navLeft + navWidth){
                    TweenLite.set(submenuList, {x: menuX});
                }else if( submenuFirstItem.offset().left + submenuFirstItem.width()*2 < navLeft + navWidth ){
                    TweenLite.set(submenuList, {x: submenuFirstItem.width()*2});
                    submenuList.find('li').addClass('right');
                }
            }
        }

    })*/;

    
}