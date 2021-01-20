<?php

/**
 * Class Utils class for methods for page generation
 */
class Utils
{
    /**
     * @param $records_limit int limit set by the user for the row per pages
     * @param $get_page int the number of the page to show
     * @return array containing $limit set the limit of the row per page, $page get the number of the page,
     * $paginationStart the index of the value that start the pagination
     */
    public static function initPagination($records_limit, $get_page){
        // If the users choose a record limit we set it a session variable for the entire site
        if(isset($records_limit)){
            $_SESSION['records-limit'] = $records_limit;
        }

        // Set the limit to show in each page
        $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 5;

        // Get the number of the page
        $page = (isset($get_page) && is_numeric($get_page) ) ? $get_page : 1;
        // Get the start of the pages
        $paginationStart = ($page - 1) * $limit;

        return array(
            "limit" => $limit,
            "page" => $page,
            "paginationStart" => $paginationStart);
    }

    /**
     * @param $str String input to filter
     * @return false String if successful or false (boolean instance)
     */
    public static function filterString($str) {
        return $str == null ? false : filter_var($str, FILTER_SANITIZE_SPECIAL_CHARS,
            array('flags' => FILTER_FLAG_STRIP_LOW | FILTER_FLAG_ENCODE_HIGH));
    }
}
