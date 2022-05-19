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

    /* Helper function */
    function download_file(fileURL, fileName) {
        // for non-IE
        if (!window.ActiveXObject) {
            var save = document.createElement('a');
            save.href = fileURL;
            save.target = '_blank';
            var filename = fileURL.substring(fileURL.lastIndexOf('/')+1);
            save.download = fileName || filename;
            if ( navigator.userAgent.toLowerCase().match(/(ipad|iphone|safari)/) && navigator.userAgent.search("Chrome") < 0) {
                    document.location = save.href; 
                // window event not working here
                }else{
                    var evt = new MouseEvent('click', {
                        'view': window,
                        'bubbles': true,
                        'cancelable': false
                    });
                    save.dispatchEvent(evt);
                    (window.URL || window.webkitURL).revokeObjectURL(save.href);
                }	
        }
        // for IE < 11
        else if ( !! window.ActiveXObject && document.execCommand)     {
            var _window = window.open(fileURL, '_blank');
            _window.document.close();
            _window.document.execCommand('SaveAs', true, fileName || fileURL)
            _window.close();
        }
    }

    $('#menu-toggle').on('click', function(){
        $('#mobile-menu-items').toggle('fast');
    });
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#menu-toggle').length) {
            var mobile_menu = $('#mobile-menu-items');
            if (mobile_menu.css('display') == 'block') {
                mobile_menu.toggle('fast');
            }
        }        
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

    // Search Icon
    $('.search-placeholder').on('click', '.search-icon', function(){
        $('#filter-wrap').slideToggle(400);
        $(this).addClass('hidden');
    });
    // Order Receipt
    $('.order-receipt').on('click', function() {
        console.log('order receipt');
        var order_id = $(this).data('id');
        if (order_id) {
            $.get({
                url : '/order-receipt/'+order_id,
                beforeSend:function() {
                    console.log('before send');
                },
                success:function(data) {
                    var data = JSON.parse(data);
                    console.log(data);
                    download_file(data.url, data.fine_name);
                }
            });
        }
        
    });
});