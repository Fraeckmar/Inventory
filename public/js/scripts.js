jQuery(document).ready(function($){
    // Repeater Scripts
    $('.repeater').repeater({
        show: function () {
            $(this).slideDown();
        },
        hide: function (deleteElement) {
            if(confirm('Are you sute to delete this item?')) {
                $(this).slideUp(deleteElement);
            }
        },
        ready: function (setIndexes) {
        }
    });

    $('#menu-toggle').on('click', function(){
        $('#mobile-menu-items').toggle('fast');
    });
    // Delete Item
    $('.delete-item').on('click', function(e){
        e.preventDefault();
        var formId = $(this).data('form');
        var label = $(this).data('label');
        var confirmed = confirm(label);
        if(confirmed){
            $(formId).trigger('submit');
        }
    });
});