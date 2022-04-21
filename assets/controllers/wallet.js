$('#add_wallet_name').on('change', function () {
    if ($('#add_wallet_name').val() == 'Kucoin') {
        $('#passPhraseID').show();
    } else {
        $('#passPhraseID').hide();
    }
})

// on click increase/decrease max height and set/unset overflow for expended div
let expended = false;
$('.customButton').on('click', function () {
    // extend div
    if (expended) {
        expended = false;
        $('#table' + this.id).css({ "max-height": "200px", "overflow-y": "hidden" });
        $('#rotateSVG' + this.id).css("transform", "rotate(0deg)");
    } else {
        // collapse div
        expended = true;
        $('#table' + this.id).css({ "overflow-y": "none", "max-height": $(document).height() + "px" });
        $('#rotateSVG' + this.id).css("transform", "rotate(-90deg)");
    }
})

// on hover collapse button background color and rotate arrow
$('.customButton').on('mouseenter', function () {
    // Rotate on mouse enter with expanded check
    expended ? $('#rotateSVG' + this.id).css({ "transform": "rotate(0deg)" }) : $('#rotateSVG' + this.id).css({ "transform": "rotate(-90deg)" });
    // Background color on mouse enter
    $('#' + this.id).css("background-color", "#404040");
}).on('mouseleave', function () {
    // Reset rotate on mouse enter with expanded check
    expended ? $('#rotateSVG' + this.id).css({
        "transform": "rotate(-90deg)",
        "background-color": "transparent"
    }) : $('#rotateSVG' + this.id).css({
        "transform": "rotate(0deg)",
        "background-color": "transparent"
    });
    // Default background color on mouse leave
    $('#' + this.id).css("background-color", "transparent");
});


$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});
