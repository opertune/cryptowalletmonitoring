$('#add_wallet_name').on('change', function () {
    if ($('#add_wallet_name').val() == 'Kucoin') {
        $('#passPhraseID').show();
    } else {
        $('#apiKey').show();
        $('#secretKey').show();
        $('#passPhraseID').hide();
        $('#add_wallet_passPhrase').prop("required", false);
    }

    if ($('#add_wallet_name').val() == 'Coinbase') {
        $('#coinbaseOauth').show();
        $('#apiKey').hide();
        $('#secretKey').hide();
        $('#add_wallet_apiKey').prop("required", false);
        $('#add_wallet_secretKey').prop("required", false);
    } else {
        $('#coinbaseOauth').hide();
    }
})

// on click increase/decrease max height and set/unset overflow for expended div
var expanded = {}
$('.customButton').each(function(){
    expanded['expanded'+this.id] = false
})

$('.customButton').on('click', function () {
    // extend div
    if (expanded['expanded'+this.id]) {
        expanded['expanded'+this.id] = false;
        $('#table' + this.id).css({ "max-height": "200px", "overflow-y": "hidden" });
        $('#rotateSVG' + this.id).css("transform", "rotate(0deg)");
    } else {
        // collapse div
        expanded['expanded'+this.id] = true;
        $('#table' + this.id).css({ "overflow-y": "none", "max-height": $(document).height() + "px" });
        $('#rotateSVG' + this.id).css("transform", "rotate(-90deg)");
    }
})

// on hover collapse button background color and rotate arrow
$('.customButton').on('mouseenter', function () {
    // Rotate on mouse enter with expanded check
    expanded['expanded'+this.id] ? $('#rotateSVG' + this.id).css({ "transform": "rotate(0deg)" }) : $('#rotateSVG' + this.id).css({ "transform": "rotate(-90deg)" });
    // Background color on mouse enter
    $('#' + this.id).css("background-color", "#404040");
}).on('mouseleave', function () {
    // Reset rotate on mouse enter with expanded check
    expanded['expanded'+this.id] ? $('#rotateSVG' + this.id).css({
        "transform": "rotate(-90deg)",
        "background-color": "transparent"
    }) : $('#rotateSVG' + this.id).css({
        "transform": "rotate(0deg)",
        "background-color": "transparent"
    });
    // Default background color on mouse leave
    $('#' + this.id).css("background-color", "transparent");
});

// Hide expand button when table have less than 3 rows
jQuery(function ($) {
    $('.walletCustomTable').each(function (index) {
        if ($('#t' + index + ' tr').length <= 5) {
            $('#' + index).hide();
        }
    })
})

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});
