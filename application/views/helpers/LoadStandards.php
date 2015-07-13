<?php

/**
 * View Helper LoadStandards
 *
 * Classe helper LoadStandards para cabeçalho do layout.
 *
 * @category   ZT
 * @package    Views
 * @subpackage Helpers
 * @author     Renato Mendes Figueiredo <contato@renatomefi.com.br>
 */
class Zend_View_Helper_LoadStandards extends Zend_View_Helper_Abstract
{

    /**
     * Carrega as informações do layout.ini para completar o cabeçalho do layout
     *
     * @param string $layout
     */
    public function loadStandards($layout)
    {
        $this->view->addScriptPath(APPLICATION_PATH . '/views/scripts/');

        $config = Zend_Registry::get('configs')->layout;
        $config = $config->{$layout};

        if (!is_object($config)) throw new Zend_exception($layout);

        $this->view->layout()->projectName = $config->layout->headTitleShort;

        $this->view->doctype($config->layout->doctype);

        $this->view->setEncoding('UTF-8');
        $this->view->headMeta()->setHttpEquiv('Content-Type', 'text/html; charset=' . $config->layout->charset);
        $this->view->headMeta('width=device-width, initial-scale=1.0', 'viewport');

        if ($config->layout->meta->layoutName == true)
            $this->view->headMeta()->setName('Layout', $layout);
        // Título da página
        $this->view->headTitle()->setSeparator(' | ');

        $mSet = false;

        foreach ($this->view->headTitle() as $t) {

            if ($t == $config->layout->headTitleShort) {
                $mSet = true;
            }

            if ($this->view->layout()->title) {
                $this->view->layout()->sTitle = $this->view->translate($t);
            } else {
                $this->view->layout()->title = $this->view->translate($t);
            }
        }

        if (!$mSet)
            $this->view->headTitle($config->layout->headTitleShort, Zend_View_Helper_Placeholder_Container_Abstract::PREPEND);

        // Adicionar o HeadTitle default caso exista apenas um registrado
        if (count($this->view->headTitle()) == 1)
            $this->view->headTitle($config->layout->headTitle);

        if ($this->view->docType()->getDoctype() == Zend_View_Helper_Doctype::XHTML1_RDFA) {
            foreach ($this->view->FacebookOpenGraphTags($config)->getTags() as $k => $v) {
                if ($v == '') continue;
                $this->view->headMeta()->setProperty($k, $v);
            }
        }

        $this->view->headTitle()->setTranslator(Zend_Registry::get('Zend_Translate'));
        $this->view->headTitle()->enableTranslation();

        $lang = $config->layout->lang->iana;
        $this->view->layout()->htmlLang = $lang;
        $this->view->layout()->htmlAttr = 'lang=' . $lang;
    }

}