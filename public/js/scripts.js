$(function($){
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
            save.download = fileName;
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

    function show_loading() {
        $('body').append('<div class="spinner-loading">Loading...</div>');
    }
    function hide_loading() {
        $('body').find('.spinner-loading').remove();
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
        show_loading();
        var order_id = $(this).data('id');
        var req_url = '/order-receipt/'+order_id;
        download_file(req_url);
        hide_loading();
    });

    // ANALYSIS
    $('.critical-items .item').each(function(){
        let canvas_id = $(this).data('canvas_id');
        let item_name = $(this).data('item_name');
        let item_percentage = $(this).data('percentage');
        let remaining = $(this).data('remaining');

        let labels = [
            item_name,
            ''
        ];

        let data = {
        labels: labels,
            datasets: [{
                label: 'Critical Item',
                backgroundColor: [
                    '#e02424',
                    'transparent'
                ],
                borderColor: [
                    '#FF7B67',
                    '#FF7B67'
                ],
                borderWidth: 1,
                data: [item_percentage, 100],
            }]
        };

        let config = {
            type: 'doughnut',
            data: data,
            options: {
                cutoutPercentage: 70,
                responsive: false,
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: item_name,
                    fontSize: 20,
                },
                plugins: {
                    doughnutlabel: {
                        labels: [
                            {
                                text: item_percentage+'%',
                                font: {
                                    size: 25,
                                    weight: 'bold'
                                }
                            }
                        ]
                    }
                }
            }
        };

        let criticalChart = new Chart(
            document.getElementById(canvas_id),
            config
        );
    });
});