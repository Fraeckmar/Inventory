<?php
namespace App\Helpers\Pdf;

class PDFOptions
{
    public function getAll()
    {
        return [
            'dpi' => 150, 
            'isRemoteEnabled' => true,
            //'defaultFont' => 'sans-serif'
        ];
    }
    public function pdf_sizes()
    {
        return [
            'order' => [
                'size' => 'A6',
                'orient' => 'portrait'
            ],
        ];
    }

    function styles() 
    {
        ob_start();
        ?>
        <style>
            * {
                padding: 0 !important;
                margin: 0 !important;
            }
            *, .h1, .h2, .h3, .h4, .h5 {
                font-family: sans-serif !important;
            }
            p { font-size: 22px; }
            .border { border: 1px solid #000; }
            .border-l { border-left:1px solid #000; }
            .border-r { border-right:1px solid #000; }
            .border-t { border-top:1px solid #000; }
            .border-b { border-bottom:1px solid #000; }
            .no-border { border: none; }
            .p-05 { padding: .15rem!important; }
            .p-1 { padding: .25rem!important; }
            .p-2 { padding: .5rem!important; }
            .p-3 { padding: 1rem!important; }
            .p-4 { padding: 1.5rem!important; }
            .p-5 { padding: 3rem!important; }

            .pl-05 { padding-left: .15rem!important; }
            .pl-1 { padding-left: .25rem!important; }
            .pl-2 { padding-left: .5rem!important; }
            .pl-3 { padding-left: 1rem!important; }
            .pl-4 { padding-left: 1.5rem!important; }
            .pl-5 { padding-left: 3rem!important; }

            .pr-05 { padding-right: .15rem!important; }
            .pr-1 { padding-right: .25rem!important; }
            .pr-2 { padding-right: .5rem!important; }
            .pr-3 { padding-right: 1rem!important; }
            .pr-4 { padding-right: 1.5rem!important; }
            .pr-5 { padding-right: 3rem!important; }

            .pt-05 { padding-top: .15rem!important; }
            .pt-1 { padding-top: .25rem!important; }
            .pt-2 { padding-top: .5rem!important; }
            .pt-3 { padding-top: 1rem!important; }
            .pt-4 { padding-top: 1.5rem!important; }
            .pt-5 { padding-top: 3rem!important; }

            .pb-05 { padding-bottom: .15rem!important; }
            .pb-1 { padding-bottom: .25rem!important; }
            .pb-2 { padding-bottom: .5rem!important; }
            .pb-3 { padding-bottom: 1rem!important; }
            .pb-4 { padding-bottom: 1.5rem!important; }
            .pb-5 { padding-bottom: 3rem!important; }

            .m-1 { margin: .25rem!important; }
            .m-2 { margin: .5rem!important; }
            .m-3 { margin: 1rem!important; }
            .m-4 { margin: 1.5rem!important; }
            .m-5 { margin: 3rem!important; }

            .ml-1 { margin-left: .25rem!important; }
            .ml-2 { margin-left: .5rem!important; }
            .ml-3 { margin-left: 1rem!important; }
            .ml-4 { margin-left: 1.5rem!important; }
            .ml-5 { margin-left: 3rem!important; }

            .mr-1 { margin-right: .25rem!important; }
            .mr-2 { margin-right: .5rem!important; }
            .mr-3 { margin-right: 1rem!important; }
            .mr-4 { margin-right: 1.5rem!important; }
            .mr-5 { margin-right: 3rem!important; }

            .mt-1 { margin-top: .25rem!important; }
            .mt-2 { margin-top: .5rem!important; }
            .mt-3 { margin-top: 1rem!important; }
            .mt-4 { margin-top: 1.5rem!important; }
            .mt-5 { margin-top: 3rem!important; }

            .mb-1 { margin-bottom: .25rem!important; }
            .mb-2 { margin-bottom: .5rem!important; }
            .mb-3 { margin-bottom: 1rem!important; }
            .mb-4 { margin-bottom: 1.5rem!important; }
            .mb-5 { margin-bottom: 3rem!important; }

            .h1 { font-size: 2.5rem }
            .h2 { font-size: 2rem }
            .h3 { font-size: 1.75rem }
            .h4 { font-size: 1.5rem }
            .h5 { font-size: 1.25rem }
            .h6 { font-size: 1rem }

            .bg-black { background-color: #000; }
            .text-white { color: #fff; }
            .center { text-align: center; }
            .bold { font-weight: 500; }
            .bolder { font-weight: 800; }
            .uppercase { text-transform: capitalize; }
            .lowercase { text-transform: lowercase; }

            table {
                table-layout: fixed;
                border-spacing: 0;
            }
        </style>
        <?php
        return ob_get_clean();
    }
}