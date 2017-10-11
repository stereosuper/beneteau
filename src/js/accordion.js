var $ = require('jquery');

require('gsap/CSSPlugin');
var TweenLite = require('gsap/TweenLite');


module.exports = function( accordion ){
    if( !accordion.length ) return;

    var accordionBtn = accordion.find('.eolia_results_category_title');
    var accordionContent = accordion.find('.eolia_results_category_table');

    if( accordionBtn.length < 2 ) return;


    accordionContent.hide(0).eq(0).show(0);
    accordionBtn.addClass('accordion-btn').eq(0).addClass('on');

    accordionBtn.on('click', function(){

        $(this).toggleClass('on').siblings().fadeToggle(300).parents('.eolia_results_category').siblings().find('.eolia_results_category_table').fadeOut(300).siblings('.eolia_results_category_title').removeClass('on');

    }).on('click', '.eolia_results_category_title a', function(e){

        e.preventDefault();

    });
}
