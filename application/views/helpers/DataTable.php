<?php
/**
 * DataTable view Helper
 *
 * @category   ZT
 * @package    Views
 * @subpackage Helpers
 * @author     Renato Mendes Figueiredo <contato@renatomefi.com.br>
 */
class Zend_View_Helper_DataTable extends Zend_View_Helper_Abstract
{
    public function dataTable($name, $columns, $custom = array())
    {
        $dtColumns = array();

        foreach ($columns as $c)
            $dtColumns[] = array('sTitle' => $c);

        $partial = $this->view->partial('dataTable.phtml', array('name' => $name,
            'columns' => $dtColumns, 'custom' => $custom));

        return $partial;
    }
}