var $ = require('jquery');

require('gsap/CSSPlugin');
var TweenLite = require('gsap/TweenLite');

// window.requestAnimFrame = require('./requestAnimFrame.js');
// var throttle = require('./throttle.js');

// var checkIfInView = require('./checkIfInView.js');


module.exports = function( container ){
    if( !container.length ) return;

    var imgs = container.find('img');

    if( imgs.length < 10 ) return;

    var imgsSrc = [], index = 0, indexSrc = 0, indexArray = [];


    // function shuffle( a ){
    //     for( let i = a.length; i; i-- ){
    //         let j = Math.floor( Math.random() * i );
    //         [a[i - 1], a[j]] = [a[j], a[i - 1]];
    //     }
    // }

    function updateImg(){
        // we make disappear the image corresponding to "index"
        TweenLite.to(imgs.eq(indexArray[index]), 0.25, {opacity: 0, scale: 0.7, onComplete: function(){
            // we replace it's url whith the url corresponding to "indexSrc"
            imgs.eq(indexArray[index]).attr('src', imgsSrc[indexSrc]);
            TweenLite.to(imgs.eq(indexArray[index]), 0.25, {opacity: 1, scale: 1});
            
            // we increment index of 1
            // if this equals the number of visible imgs
            // we go back to the beginning (so only visible imgs will be changed)
            index = index + 1 === indexArray.length ? 0 : index + 1;

            // we increment indexSrc of 1
            // if this equals the number of total url
            // we go back to the beginning
            indexSrc = indexSrc + 1 === imgsSrc.length ? 0 : indexSrc + 1;

            //if( index === 0 ) shuffle(indexArray);
            
            // we call back the function so it keeps running
            //setUpdateTimeout(0.6);
            TweenLite.delayedCall( 0.6, updateImg );
        }});
    }

    // function setUpdateTimeout( delay ){
    //     TweenLite.killDelayedCallsTo( updateImg );

    //     if( checkIfInView.check(container) ){
    //         TweenLite.delayedCall( delay, updateImg );
    //     }
    // }


    imgs.each(function( i ){
        // array containing all the urls
        imgsSrc[i] = $(this).attr('src');

        // if img visible we stock its index (so we know how many imgs are to display)
        if( !$(this).parent().hasClass('hidden') ) indexArray[i] = i;
    });
    
    // we set the value indexSrc (which will be used to choose the new url) to start by selecting the first non visible img
    indexSrc = indexArray.length;

    //checkIfInView.init(container);

    // we shuffle the array of visible imgs index so order is random
    // shuffle(indexArray);

    // let's start the animation
    updateImg();


    // $(window).on('focusout', function(){
    //     TweenLite.killDelayedCallsTo( updateImg );
    // }).on('focusin', updateImg).on('resize', throttle(function(){
    //     requestAnimFrame(function(){
    //         setUpdateTimeout(0);
    //     });
    // }, 60));

    // $(document).on('scroll', throttle(function(){
    //     requestAnimFrame(function(){
    //         setUpdateTimeout(0);
    //     });
    // }, 10));
}