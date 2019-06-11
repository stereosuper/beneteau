const $ = require('jquery');

require('gsap');

const menuAccordionHandler = () => {
    const accordionsContent = $('.js-sub-menu-wrapper');

    if (!accordionsContent.length) return;

    accordionsContent.each(function(){
        const that = $(this);
        const parent = that.parent();
        const title = parent.find('.js-accordion-button');

        if(!title) return;

        title.on('click keyup', (e) => {
            if( e.keyCode !== undefined && e.keyCode !== 13 ) return;

            const alreadyActivated = parent.hasClass('activated');
            const submenu = that.find('.js-sub-menu');
            const maxHeight = submenu.innerHeight();

            if (alreadyActivated) {
                parent.removeClass('activated');
                TweenMax.to(that, 0.3, {
                    maxHeight: 0,
                    opacity: 0,
                    ease: Power4.easeOut,
                });
                title.attr('aria-expanded', false);
            } else {
                TweenMax.to(that, 0.3, {
                    maxHeight,
                    opacity: 1,
                    ease: Power4.easeOut,
                });
                parent.addClass('activated');
                title.attr('aria-expanded', true);
            }
        });
    });
};

module.exports = menuAccordionHandler;
