var $ = require('jquery');

var ScrollReveal = require('scrollreveal');
require('gsap/CSSPlugin');
var TweenLite = require('gsap/TweenLite');


module.exports = function(){
    window.sr = ScrollReveal({ reset: true });


    function aze(){
        TweenLite.set($('.push-container > a'), {className:'-=isDeployed'});
        $('.push-container > a').each(function(index){
            TweenLite.set($(this), {className:'+=isDeployed', delay: 0.2+0.1*index});
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
    
    sr.reveal('.push-wrapper', { duration: 800, origin: 'bottom', scale: 1, distance: '60px' });
    
    sr.reveal('.gallery >li', { duration: 500, origin: 'bottom', scale: 1, distance: '60px' }, 150);
    
    sr.reveal('.push-banner', { easing: 'ease-in-out', duration: 500, rotate: { x: 0, y: 0, z: 0 }, scale:1, opacity:0, distance: '80px', beforeReveal: function (domEl) {aze()} });
    
    sr.reveal('.home-news', { beforeReveal: aze});
}