<?php

namespace App\Helpers;

class Page
{
    static function pagination($pagination, $addional_param='')
    {
        $_from =  $pagination['from'];
        $_to =  $pagination['to'];
        $_total = $pagination['total'];
        $data_count = count($pagination['data']);

        $html = '<div id="pagination" class="sm:flex justify-between items-center">';
            $html .= '<div class="hidden sm:block result mb-4 text-center">'.__('Showing').' '.$_from.' - '.$_to.' of '.$_total.' '.__('results').'</div>';
            $html .= '<div class="page-links text-center self-center h-8">';
                foreach ($pagination['links'] as $page_link) {
                    $_url = $page_link['url'];
                    $_url .= !empty($_url) ? $addional_param : '';
                    $_label = $page_link['label'];
                    $_active_class = $page_link['active'] ? 'bg-blue-600 text-white' : 'bg-white';
                    $html .= '<a href="'.$_url.'" class="link rounded px-4 py-2.5 shadow-lg hover:bg-blue-600 hover:text-white transition duration-300 ease-in-out '.$_active_class.'">'.$_label.'</a>';                    
                }                    
            $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
}