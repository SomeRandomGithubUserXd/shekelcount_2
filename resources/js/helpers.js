window.priceFormat = function (number) {
    return Intl.NumberFormat('ru-RU', {style: 'currency', currency: 'RUB'}).format(number);
}

window.getEntryForm = function (entry = null, categoryReplacement = null) {
    return {
        entry_id: entry ? entry.id : null,
        category: entry ? entry.category : categoryReplacement,
        sum: entry ? entry.sum : "",
        description: entry ? entry.description : "",
        performed_at: entry ? entry.performed_at : new Date()
    };
}

window.scrollTo = function (elem) {
    elem.scrollIntoView({behavior: "smooth"})
}

window.isMobile = function () {
    const windowWidth = window.screen.width < window.outerWidth
        ? window.screen.width
        : window.outerWidth
    return windowWidth < 768
}
