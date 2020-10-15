<?php

class Utils
{
    public static function initPagination($records_limit, $get_page){
        var_dump($records_limit);

        var_dump($_SESSION['records-limit']);
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
}
