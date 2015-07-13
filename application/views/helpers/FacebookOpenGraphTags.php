<?php
/**
 * Helper para o plugin de FacebookOpenGraphTags
 * 
 * @category   ZT
 * @package    Views
 * @subpackage Helpers
 * @author     Renato Mendes Figueiredo <contato@renatomefi.com.br>
 */
class Zend_View_Helper_FacebookOpenGraphTags extends Zend_View_Helper_Abstract
{
    
    protected $_tagsAvailable = array(
        'og:title'      => 'Título do site',
        'og:type'       => 'website',
        'og:locale'     => 'pt_BR',
    	'og:image'      => '',
        'og:description'=> 'Descrição do site',
        'og:url'        => '',
        'og:site_name'  => 'Nome do site',
    	'fb:admins'     => 'renatomefidf',
    	'fb:app_id'     => ''
    );
    
    protected $_tags = array();
    
    public function FacebookOpenGraphTags($layoutConfig = null)
    {
    	if ($layoutConfig instanceof Zend_Config) {
    		$this->_tagsAvailable['og:title'] = $layoutConfig->layout->headTitle;
    		$this->_tagsAvailable['og:site_name'] = $layoutConfig->layout->headTitle;
    		$this->_tagsAvailable['og:description'] = $layoutConfig->layout->description;
    	}
    	
        return $this;
    }
    
    public function getTags()
    {
        $ogtKeys = array_keys($this->_tags);
        $ogtAvailableKeys = array_keys($this->_tagsAvailable);
        
        $ogtComplete = array_diff($ogtAvailableKeys, $ogtKeys);
        
        foreach ($ogtComplete as $k) {
            $this->_tags[$k] = $this->_tagsAvailable[$k];
            if ($k == 'og:image') $this->_tags[$k] = $this->view->baseUrl() . '/resources/img/logo.png';
            if ($k == 'og:url') $this->_tags[$k] = $this->view->baseUrl() . $_SERVER['REQUEST_URI'];
            if ($k == 'og:title') {
                foreach ($this->view->headTitle() as $value) {
                    $title[] = $value;
                }
                $this->_tags[$k] = implode(' | ', $title);
            }
        }
        
        return $this->_tags;
    }
    
    public function setImage($uri, $baseUrl = true)
    {
        $uri = ($baseUrl) ? $this->view->baseUrl() . $uri : $uri;
        $this->_tags['og:image'] = $uri;
    }
    
    public function setType($type)
    {
        $this->_tags['og:type'] = $type;
    }
    
    public function setDescription($description, $htmlEntityDecode = false)
    {
        $filter = new Zend_Filter_StripTags();
        
        if ($htmlEntityDecode)
            $description = html_entity_decode($description);

        $description = $filter->filter($description);
        
        $this->_tags['og:description'] = $description;
    }

    public function setUrl($uri, $removeGetTags = false)
    {
        if ($removeGetTags === true) $uri = strstr($uri, '?', true);
        $this->_tags['og:url'] = $this->view->baseUrl() . $uri;
    }
    
}