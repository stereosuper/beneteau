var $ = require('jquery');

require('gsap/CSSPlugin');
var TweenLite = require('gsap/TweenLite');


module.exports = function( accordion ){
    if( !accordion.length ) return;

    var accordionBtn = accordion.find('.eolia_results_category_table');

    if( accordionBtn.length < 2 ) return;


    accordionBtn.hide(0).eq(0).show(0);


    accordion.on('click', '.eolia_results_category_title', function(){

        $(this).siblings().fadeToggle(300).parents('.eolia_results_category').siblings().find('.eolia_results_category_table').fadeOut(300);

    }).on('click', '.eolia_results_category_title a', function(e){

        e.preventDefault();

    });
}
