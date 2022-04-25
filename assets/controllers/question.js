jQuery(function () {
    const select = $('#selectExchange');
    let firstExchange = select.val().toLowerCase();
    select.on('change', function () {
        $('#' + firstExchange).attr('hidden', true);
        $('#' + select.val().toLowerCase()).attr('hidden', false);
        firstExchange = select.val().toLowerCase();
    })
})