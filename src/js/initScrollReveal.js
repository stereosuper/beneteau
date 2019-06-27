var $ = require('jquery');

var ScrollReveal = require('scrollreveal');
require('gsap/CSSPlugin');
var TweenLite = require('gsap/TweenLite');


module.exports = function( body ){
    if( body.hasClass('no-sr') ) return;

    window.sr = ScrollReveal({ reset: true });

    function count( elt ){
        if( elt.text() != 0 ) return;

        elt.prop('Counter',0).animate({
            Counter: elt.data('number')
        }, {
            duration: 2000,
            easing: 'swing',
            step: function (now) {
                elt.text(Math.ceil(now));
            }
        });
    }


    sr.reveal('.isAnimated', { viewFactor: 0.1 } );
    
    sr.reveal('.content-brand .baseline', { duration: 1500, origin: 'right', scale: 1, distance: '60px' });
    
    sr.reveal('.exergue', { easing: 'ease-in-out', duration: 500, origin: 'left', scale: 0.9, distance: '60px' });
    
    sr.reveal('.intro', { easing: 'ease-in-out', duration: 500, origin: 'left', scale: 0.9, distance: '60px' });
    
    sr.reveal('blockquote', { easing: 'ease-in-out', duration: 500, origin: 'bottom', scale: 0.9, distance: '60px' });
    
    sr.reveal('.highlighted .title', { easing: 'ease-in-out', duration: 500, origin: 'left', scale: 0.9, distance: '60px' });
    
    sr.reveal('.highlighted strong', { easing: 'ease-in-out', duration: 500, origin: 'right', scale: 0.9, distance: '60px' });
    
    sr.reveal('.list-brands >li:nth-child(2n+0)', { easing: 'ease-in-out', duration: 600, origin: 'left', scale: 0.5, distance: '30px' });
    
    sr.reveal('.list-brands >li:nth-child(2n+1)', { easing: 'ease-in-out', duration: 600, origin: 'right', scale: 0.5, distance: '30px' });
    
    sr.reveal('.push-links-item', { duration: 800, origin: 'bottom', scale: 1, distance: '60px' }, 150);

    sr.reveal('.home-group-number', { duration: 800, origin: 'bottom', scale: 1, distance: '60px' , afterReveal: function(elt){
        $(elt).find('.js-number').each(function(){
            count($(this));
        });
    }}, 150);

    sr.reveal('.home-brands-services-list > li', { duration: 800, origin: 'bottom', scale: 1, distance: '60px' }, 150);

    sr.reveal('.home-offers > .container', { duration: 800, origin: 'bottom', scale: 1, distance: '20px' });

    sr.reveal('.home-professions-video', { duration: 800, origin: 'bottom', scale: 1, distance: '0' });
    
    sr.reveal('.gallery >li', { duration: 500, origin: 'bottom', scale: 1, distance: '60px' }, 150);

    sr.reveal('.grid-post-type-title .no-text', { easing: 'ease-in-out', duration: 500, scale: 1, distance: '0px' });
}