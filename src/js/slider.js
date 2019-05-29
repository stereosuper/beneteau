const $ = require('jquery');

require('gsap/CSSPlugin');
const TweenLite = require('gsap/TweenLite');

const Hammer = require('hammerjs');

window.requestAnimFrame = require('./requestAnimFrame.js');
const throttle = require('./throttle.js');

const checkIfInView = require('./checkIfInView.js');

module.exports = function(slider, windowWidth) {
    if (!slider.length) return;

    const slidesImg = slider.find('.js-slide-img');
    const slidesTxt = slider.find('.js-slide-txt');

    if (slidesImg.length < 2) return;

    let activeSlideImg = slider.find('.js-slide-img.first-on'),
        newActiveSlideImg;
    let activeSlideTxt = slider.find('.js-slide-txt.first-on'),
        newActiveSlideTxt;

    const sliderNav = slider.find('.js-slider-nav');

    let done = true;

    let hammertime = new Hammer(slider.get(0)),
        swipeBtn;

    function slide(index, button) {
        done = false;

        if (index > -1) {
            newActiveSlideImg = slidesImg.eq(index);
            newActiveSlideTxt = slidesTxt.eq(index);
        } else {
            newActiveSlideImg = activeSlideImg.next('.js-slide-img').length
                ? activeSlideImg.next('.js-slide-img')
                : slidesImg.eq(0);
            newActiveSlideTxt = activeSlideTxt.next('.js-slide-txt').length
                ? activeSlideTxt.next('.js-slide-txt')
                : slidesTxt.eq(0);
        }

        if (!button) {
            button = sliderNav
                .find('.on')
                .parent()
                .next().length
                ? sliderNav
                      .find('.on')
                      .parent()
                      .next()
                      .find('button')
                : sliderNav
                      .find('li')
                      .eq(0)
                      .find('button');
        }

        TweenLite.fromTo(
            activeSlideTxt.find('.title'),
            0.5,
            { opacity: 1, x: 0 },
            { opacity: 0, x: '50px', overwrite: true }
        );
        TweenLite.fromTo(
            activeSlideTxt.find('.txt'),
            0.5,
            { opacity: 1, x: 0 },
            { opacity: 0, x: '50px', delay: 0.1, overwrite: true }
        );
        TweenLite.fromTo(
            activeSlideTxt.find('.button'),
            0.5,
            { opacity: 1, x: 0 },
            { opacity: 0, x: '50px', delay: 0.2, overwrite: true }
        );

        TweenLite.fromTo(
            activeSlideImg,
            0.7,
            { opacity: 1 },
            { opacity: 0, delay: 0.5, overwrite: true }
        );

        activeSlideImg.removeClass('on');
        activeSlideTxt.removeClass('on');

        newActiveSlideImg.addClass('on');
        TweenLite.fromTo(
            newActiveSlideImg,
            0.7,
            { opacity: 0 },
            { opacity: 1, overwrite: true }
        );

        newActiveSlideTxt.addClass('on');
        TweenLite.fromTo(
            newActiveSlideTxt.find('.title'),
            0.5,
            { opacity: 0, x: '-50px' },
            { opacity: 1, x: 0, delay: 0.5, overwrite: true }
        );
        TweenLite.fromTo(
            newActiveSlideTxt.find('.txt'),
            0.5,
            { opacity: 0, x: '-50px' },
            { opacity: 1, x: 0, delay: 0.6, overwrite: true }
        );
        TweenLite.fromTo(
            newActiveSlideTxt.find('.button'),
            0.5,
            { opacity: 0, x: '-50px' },
            {
                opacity: 1,
                x: 0,
                delay: 0.7,
                onComplete() {
                    done = true;
                },
                overwrite: true,
            }
        );

        activeSlideImg = slider.find('.js-slide-img.on');
        activeSlideTxt = slider.find('.js-slide-txt.on');

        windowWidth > 780
            ? slider.attr('style', '')
            : slider.height(activeSlideTxt.height());

        button
            .addClass('on')
            .parent()
            .siblings()
            .find('button')
            .removeClass('on');

        setSliderTimeout();
    }

    function setSliderTimeout() {
        TweenLite.killDelayedCallsTo(slide);

        if (!checkIfInView.check(slider)) return;

        TweenLite.delayedCall(8, slide);
    }

    function handleAction(btn) {
        if (!done) return;

        TweenLite.killDelayedCallsTo(slide);
        slide(btn.parent().index(), btn);
    }

    activeSlideImg
        .removeClass('first-on')
        .addClass('on')
        .css('opacity', 1);

    activeSlideTxt
        .removeClass('first-on')
        .addClass('on')
        .find('.title');
    TweenLite.set(
        [
            activeSlideTxt.find('.title'),
            activeSlideTxt.find('.txt'),
            activeSlideTxt.find('.button'),
        ],
        { opacity: 1 }
    );

    windowWidth > 780
        ? slider.attr('style', '')
        : slider.height(activeSlideTxt.height());

    checkIfInView.init(slider);
    setSliderTimeout();

    slider.on('click', 'button', function(e) {
        e.preventDefault();
        if (!$(this).hasClass('on')) {
            handleAction($(this));
        }
    });

    hammertime
        .on('swipeleft', () => {
            swipeBtn = sliderNav
                .find('.on')
                .parent()
                .next().length
                ? sliderNav
                      .find('.on')
                      .parent()
                      .next()
                      .find('button')
                : sliderNav
                      .find('li')
                      .eq(0)
                      .find('button');
            handleAction(swipeBtn);
        })
        .on('swiperight', () => {
            swipeBtn = sliderNav
                .find('.on')
                .parent()
                .prev().length
                ? sliderNav
                      .find('.on')
                      .parent()
                      .prev()
                      .find('button')
                : sliderNav
                      .find('li')
                      .last()
                      .find('button');
            handleAction(swipeBtn);
        });

    $(window)
        .on('focusout', () => {
            TweenLite.killDelayedCallsTo(slide);
        })
        .on('focusin', setSliderTimeout)
        .on(
            'resize',
            throttle(() => {
                requestAnimFrame(() => {
                    windowWidth = window.outerWidth;
                    windowWidth > 780
                        ? slider.attr('style', '')
                        : slider.height(activeSlideTxt.height());
                    setSliderTimeout();
                });
            }, 60)
        );

    $(document).on(
        'scroll',
        throttle(() => {
            requestAnimFrame(setSliderTimeout);
        }, 10)
    );
};
