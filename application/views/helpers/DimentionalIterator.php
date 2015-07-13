<?php

/**
 * View Helper IsAllowrd
 *
 * @category   ZT
 * @package    Views
 * @subpackage Helpers
 * @author     Raniery Ribeiro <raniery.rrr@gmail.com.br>
 */
class Zend_View_Helper_DimentionalIterator extends Zend_View_Helper_Abstract
{



    public function DimentionalIterator($dimentional, $callback, $callbackBeforeChild, $callbackAfterChild)
    {
        ZT_Array_Parser_Dimentional::iterator($dimentional, $callback, $callbackBeforeChild, $callbackAfterChild);
    }

}