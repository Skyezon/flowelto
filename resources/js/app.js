require('./bootstrap');

$(() => {
    if($('#notification').length > 0) {
        setTimeout(() => {
            $('.alert').alert('close');
            console.log('Close Notification');
        }, 6000, $('#notification'))
    }
});