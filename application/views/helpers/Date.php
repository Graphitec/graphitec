<?php

/**
 * View Helper Date
 *
 * @category   ZT
 * @package    Views
 * @subpackage Helpers
 * @author     Renato Mendes Figueiredo <contato@renatomefi.com.br>
 */
class Zend_View_Helper_Date extends Zend_View_Helper_Abstract
{

    public function date($date, $output = Zend_Date::DATETIME_SHORT, $input = Zend_Date::TIMESTAMP)
    {
        if (!$date)
            return null;

        $zendDate = new Zend_Date($date, $input);

        return $zendDate->get($output);
    }

}