$(document).ready(function () {
    if (window.location.hash == '#filters') {
        $('#filters-form').show();
    }

    $('#filters-button').click(function () {
        $('#filters-form').slideToggle();
        return false;
    });

    $('#select-all').change(function () {
        $('.select-item').prop('checked', $(this).is(':checked'));
    });
});
