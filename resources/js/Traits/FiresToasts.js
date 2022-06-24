const toast = args => {
    return {
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2500,
        showClass: {
            popup: 'animate__animated animate__fadeInRight'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutRight'
        },
        icon: args['icon'],
        title: args['title']
    }
};

export {toast}
