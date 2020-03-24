<?php
/**
 * Created by PhpStorm.
 * User: Steven
 * Date: 20/02/2020
 * Time: 11:21
 */

class DownloadService
{
    function PrintCSVHeader( $filename )
    {
        // CSV header
        header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
        header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");

        //session_cache_limiter("must-revalidate");

        header("Content-Type: application/csv-tab-delimited-table");
        header("Content-disposition: attachment; filename=".$filename.".csv");
    }
}