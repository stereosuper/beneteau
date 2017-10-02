var $ = require('jquery');

require('gsap/CSSPlugin');
var TweenLite = require('gsap/TweenLite');

window.requestAnimFrame = require('./requestAnimFrame.js');
var throttle = require('./throttle.js');

var checkIfInView = require('./checkIfInView.js');


module.exports = function( container ){
    if( !container.length ) return;

    var imgs = container.find('img');

    if( imgs.length < 10 ) return;

    var imgsSrc = [], index = 0, indexSrc = 10, indexArray = [];


    function shuffle( a ){
        for( let i = a.length; i; i-- ){
            let j = Math.floor( Math.random() * i );
            [a[i - 1], a[j]] = [a[j], a[i - 1]];
        }
    }

    function updateImg(){
        TweenLite.to(imgs.eq(indexArray[index]), 0.25, {opacity: 0, scale: 0.7, onComplete: function(){
            imgs.eq(indexArray[index]).attr('src', imgsSrc[indexSrc]);
            TweenLite.to(imgs.eq(indexArray[index]), 0.25, {opacity: 1, scale: 1});
            
            index = index + 10 > imgs.length ? 0 : index + 1;
            indexSrc = indexSrc + 1 === imgsSrc.length ? 0 : indexSrc + 1;

            if( indexSrc === 0) shuffle(indexArray);
            
            setUpdateTimeout(0.6);
        }});
    }

    function setUpdateTimeout(delay){
        TweenLite.killDelayedCallsTo( updateImg );

        if( checkIfInView.check(container) ){
            TweenLite.delayedCall( delay, updateImg );
        }
    }


    imgs.each(function(i){
        imgsSrc[i] = $(this).attr('src');
        if( !$(this).parent().hasClass('hidden') ) indexArray[i] = i;
    });

    checkIfInView.init(container);

    shuffle(indexArray);
    updateImg();


    $(window).on('focusout', function(){
        TweenLite.killDelayedCallsTo( updateImg );
    }).on('focusin', updateImg).on('resize', throttle(function(){
        requestAnimFrame(function(){
            setUpdateTimeout(0);
        });
    }, 60));

    $(document).on('scroll', throttle(function(){
        requestAnimFrame(function(){
            setUpdateTimeout(0);
        });
    }, 10));
}