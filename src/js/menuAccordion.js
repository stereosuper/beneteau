require('gsap');

const forEach = (arr, callback) => {
    let i = 0;
    const { length } = arr;
    while (i < length) {
        callback(arr[i], i);
        i += 1;
    }
};

const menuAccordionHandler = () => {
    const accordionsContent = document.querySelectorAll('.js-sub-menu-wrapper');

    if (!accordionsContent.length) return;

    forEach(accordionsContent, accordionContent => {
        const parent = accordionContent.parentElement;
        const [title] = parent.getElementsByClassName('js-accordion-button');

        if(!title) return;

        title.addEventListener(
            'click',
            () => {
                const alreadyActivated = parent.classList.contains('activated');
                const submenu = accordionContent.querySelector('.js-sub-menu');
                const maxHeight = submenu.getBoundingClientRect().height;

                if (alreadyActivated) {
                    parent.classList.remove('activated');
                    TweenMax.to(accordionContent, 0.3, {
                        maxHeight: 0,
                        opacity: 0,
                        ease: Power4.easeOut,
                    });
                } else {
                    TweenMax.to(accordionContent, 0.3, {
                        maxHeight,
                        opacity: 1,
                        ease: Power4.easeOut,
                    });
                    parent.classList.add('activated');
                }

                // setTimeout(() => {
                //     const offset = title.getBoundingClientRect().top + window.scrollY;
                //     TweenMax.to(window, 0.5, {
                //         scrollTo: {
                //             y: offset,
                //             offsetY: globalStyles.lineHeight,
                //         },
                //         ease: easing.easeFade,
                //     });
                // }, 600);
            },
            false
        );
    });
};

module.exports = menuAccordionHandler;
