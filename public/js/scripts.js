jQuery(document).ready(function($){
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
            $(formId).submit();
        }
    });
});