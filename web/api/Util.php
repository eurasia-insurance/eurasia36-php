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
}