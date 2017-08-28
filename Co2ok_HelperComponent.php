<?php


class Co2ok_HelperComponent
{
    public function __construct()
    {

    }

    public function RenderImage($uri, $class = null, $id = null)
    {
        $img_html = '<img src="'.plugins_url($uri, __FILE__).'" ';
        if(isset($class))
            $img_html .= 'class="'.$class.'" ';
        if(isset($id))
            $img_html .= 'id="'.$id.'" ';

        return $img_html.'" />';
    }
};