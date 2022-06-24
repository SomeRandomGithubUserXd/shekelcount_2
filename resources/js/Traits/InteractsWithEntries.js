const priceFormat = function (number) {
    return Intl.NumberFormat('ru-RU', {style: 'currency', currency: 'RUB'}).format(number);
}

const getEntryForm = function (entry = null, categoryReplacement = null) {
    return {
        entry_id: entry ? entry.id : null,
        category: entry ? entry.category : categoryReplacement,
        sum: entry ? entry.sum : "",
        description: entry ? entry.description : "",
        performed_at: entry ? entry.performed_at : new Date()
    };
}

export {priceFormat, getEntryForm}
