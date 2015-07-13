<?php

/**
 * Google Analytics View Helper
 *
 * @category   ZT
 * @package    Views
 * @subpackage Helpers
 * @author     Renato Mendes Figueiredo <contato@renatomefi.com.br>
 */
class Zend_View_Helper_GoogleAnalytics extends Zend_View_Helper_Abstract
{

    public function googleAnalytics($layout = null)
    {
        $config = Zend_Registry::get('configs')->layout->{$layout};

        if (!$config)
            throw new Zend_Exception('Layout not found in layout.ini');

        $trackingCode = $config->ga->tracking->code;
        $domain = $config->ga->tracking->domain;

        return $this->view->partial('googleAnalytics.phtml', ['code' => $trackingCode, 'domain' => $domain]);
    }

}