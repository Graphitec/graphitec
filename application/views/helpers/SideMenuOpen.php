<?php

/**
 * Metronic open menu view Helper
 *
 * @category   ZT
 * @package    Views
 * @subpackage Helpers
 * @author     Renato Mendes Figueiredo <contato@renatomefi.com.br>
 */
class Zend_View_Helper_SideMenuOpen extends Zend_View_Helper_Abstract
{
    public function SideMenuOpen($open = null)
    {
        if (null !== $open) {
            switch ($open) {
                case true:
                    $this->view->layout()->sideMenuOpen = true;
                    break;
                case false:
                    $this->view->layout()->sideMenuOpen = false;
                    break;
            }
        }

        if ($open == null && $this->view->layout()->sideMenuOpen == true) {
            echo 'page-sidebar-closed';
        }
    }

}