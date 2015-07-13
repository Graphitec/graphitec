<?php

/**
 * Page header view
 * Renderiza um cabeçalho para página
 *
 * @category   ZT
 * @package    Views
 * @subpackage Helpers
 * @author Erick Francis <erickfrancis.zip@gmail.com>
 */
class Zend_View_Helper_PageHead extends Zend_View_Helper_Abstract
{

    const VIEW_SCRIPT_DEFAULT = 'page-header-default.phtml';

    /**
     * @var boll
     */
    private $show = true;

    /**
     * @var sting
     */
    private $viewScript;

    /**
     * @var array
     */
    private $options = [];

    /**
     * @return \Zend_View_Helper_PageHead
     */
    public function pageHead()
    {
        return $this;
    }

    /**
     * @return string
     */
    public function html()
    {
        $html = '';

        if ($this->show) {
            $script = $this->getViewScript();
            $html = $this->view->partial(
                    $script, $this->options
            );
        }

        return $html;
    }

    public function setViewScript($script)
    {
        $this->viewScript = $script;
    }

    public function getViewScript()
    {
        return ($this->viewScript == null) ? self::VIEW_SCRIPT_DEFAULT : $this->viewScript;
    }

    /**
     * @param string $option
     * @param mixed $value
     */
    public function addOption($option, $value)
    {
        $this->options[$option] = $value;
    }

    /**
     * @param arrays $options
     */
    public function addOptions($options)
    {
        foreach ($options as $option => $value) {
            $this->addOption($option, $value);
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->html();
    }

    public function hide()
    {
        $this->show = false;
    }

    public function show()
    {
        $this->show = true;
    }

}
