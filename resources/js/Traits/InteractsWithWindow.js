const scrollTo = function (id) {
    document.querySelector(`#${id}`).scrollIntoView({behavior: "smooth"})
}

const isMobile = function () {
    const windowWidth = window.screen.width < window.outerWidth
        ? window.screen.width
        : window.outerWidth
    return windowWidth < 768
}

export {scrollTo, isMobile}
