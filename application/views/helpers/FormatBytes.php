<?php

/**
 *
 * @category   ZT
 * @package    Views
 * @subpackage Helpers
 * @author     Erick Francis <erickfrancis.zip@gmail.com>
 */
class Zend_View_Helper_FormatBytes extends Zend_View_Helper_Abstract
{

    public function formatBytes($bytes)
    {

        $originalsize = $bytes;

        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $originalsize >= 1024 && sizeof($i) < 4; $i++)
            $originalsize /= 1024;

        return round($originalsize) . ' ' . $units[$i];
    }

}