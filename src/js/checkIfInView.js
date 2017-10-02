var $ = require('jquery-slim');


var eltTop, isInView;
var windowScroll, windowBottom;


var check = function(elt){
    windowScroll = $(window).scrollTop();
    windowBottom = windowScroll + $(window).height();

    isInView = elt.data('bottom') >= windowScroll && elt.data('top') <= windowBottom ? true : false;
    return isInView;
}

var init = function(elt){
    eltTop = elt.offset().top;
    elt.data({'top': eltTop, 'bottom': eltTop + elt.outerHeight()});
}


module.exports = {
    check: check,
    init: init
}
