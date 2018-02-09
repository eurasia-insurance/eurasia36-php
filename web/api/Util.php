<?php

/**
 *
 */
class Util
{

    /**
     * @param string $lang
     * @return string
     */
    public static function passParameters($lang = '') {

        $params = '';

        if(isset($_GET) && !empty($_GET)) {

            if($lang != '' && isset($_GET['lang'])) {
                unset($_GET['lang']);
            }

            $params = http_build_query($_GET);

            if($lang != '') {
                $params = "?lang=$lang&amp;$params";
            } else {
                $params = "?$params";
            }
        }


        return $params;
    }

    public static function minifyHtml($html) {

        $search = array(
            '/(\n|^)(\x20+|\t)/',
            '/(\n|^)\/\/(.*?)(\n|$)/',
            '/\n/',
            '/\<\!--.*?-->/',
            '/(\x20+|\t)/', # Delete multispace (Without \n)
            '/\>\s+\</', # strip whitespaces between tags
            '/(\"|\')\s+\>/', # strip whitespaces between quotation ("') and end tags
            '/=\s+(\"|\')/'); # strip whitespaces between = "'

        $replace = array(
            "\n",
            "\n",
            " ",
            "",
            " ",
            "><",
            "$1>",
            "=$1");

        $html = preg_replace($search, $replace, $html);

        return $html;
    }
}