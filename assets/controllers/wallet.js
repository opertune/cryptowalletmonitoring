$('#add_wallet_name').on('change', function () {
    if ($('#add_wallet_name').val() == 'Kucoin') {
        $('#passPhraseID').show();
    } else {
        $('#passPhraseID').hide();
    }
})