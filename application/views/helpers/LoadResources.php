<?php

/**
 * View Helper LoadResources
 *
 * Classe para carregar os javascripts/css das páginas configurados no arquivo .ini
 * e caso ativado deve utilizar o serviço minify nos mesmos.
 *
 * @category   ZT
 * @package    Views
 * @subpackage Helpers
 * @author     Renato Mendes Figueiredo <contato@renatomefi.com.br>
 */
class Zend_View_Helper_LoadResources extends Zend_View_Helper_Abstract
{

    private $_layout;
    private $_config_layout;
    private $_js_path;
    private $_css_path;
    private $_js_sources = array();
    private $_css_sources = array();

    /**
     * @return the $_js_sources
     */
    public function getJs_sources()
    {
        return $this->_js_sources;
    }

    /**
     * @return the $_css_sources
     */
    public function getCss_sources()
    {
        return $this->_css_sources;
    }

    /**
     * @param field_type $_js_sources
     */
    public function setJs_sources($_js_sources)
    {
        $this->_js_sources = $_js_sources;
    }

    /**
     * @param field_type $_css_sources
     */
    public function setCss_sources($_css_sources)
    {
        $this->_css_sources = $_css_sources;
    }

    /**
     * @return the $_js_path
     */
    public function getJs_path()
    {
        return $this->_js_path;
    }

    /**
     * @return the $_css_path
     */
    public function getCss_path()
    {
        return $this->_css_path;
    }

    /**
     * @param field_type $_js_path
     */
    public function setJs_path($_js_path)
    {
        $this->_js_path = $_js_path;
    }

    /**
     * @param field_type $_css_path
     */
    public function setCss_path($_css_path)
    {
        $this->_css_path = $_css_path;
    }

    /**
     * @return the $_layout
     */
    public function getLayout()
    {
        return $this->_layout;
    }

    /**
     * Nome do Layout
     * @param String $_layout
     */
    public function setLayout($_layout)
    {
        $this->_layout = $_layout;
    }

    public function loadResources($layout)
    {
        $this->_layout = $layout;

        $this->autoConfig();

        $this->loadJS();
        $this->loadCSS();
        $this->loadFavicon();
    }

    /**
     * Esta função irá buscar automaticamente as configurações
     * para poder configurar a classe corretamente para uso.
     *
     * Antes de executar esta função é necessário setar o layout,
     * a mesma precisa saber qual sessão utilizar o arquivo .ini
     */
    public function autoConfig()
    {
        /* Parsear os arquivos de configuração */
        $this->_config_layout = Zend_Registry::get('configs')->layout->{$this->_layout};

        $this->_css_path = $this->_config_layout->resources->css->path;
        $this->_js_path = $this->_config_layout->resources->js->path;

        /* Setar os arquivos JS provenientes do .ini para um array */
        $this->_js_sources['global'] = array();
        if ((bool)$this->_config_layout->resources->js->global->reset === false
            && !empty($this->_config_layout->resources->js->global->files)
            && count($this->_config_layout->resources->js->global->files) > 0) {
            foreach ($this->_config_layout->resources->js->global->files as $filejs) {
                $this->_js_sources['global'][] = $filejs;
            }
        }

        $this->_js_sources['local'] = array();
        if ((bool)$this->_config_layout->resources->js->reset === false
            && count($this->_config_layout->resources->js->files) > 0) {
            foreach ($this->_config_layout->resources->js->files as $filejs) {
                $this->_js_sources['local'][] = $filejs;
            }
        }

        /* Setar os arquivos CSS provenientes do .ini para um array */
        $this->_css_sources['global'] = array();
        if ((bool)$this->_config_layout->resources->css->global->reset === false
            && !empty($this->_config_layout->resources->css->global->files)
            && count($this->_config_layout->resources->css->global->files) > 0) {
            foreach ($this->_config_layout->resources->css->global->files as $cssFile) {
                $this->_css_sources['global'][] = $cssFile;
            }
        }

        $this->_css_sources['local'] = array();
        if ((bool)$this->_config_layout->resources->css->reset === false
            && count($this->_config_layout->resources->css->files) > 0) {
            foreach ($this->_config_layout->resources->css->files as $cssFile) {
                $this->_css_sources['local'][] = $cssFile;
            }
        }
    }

    /**
     * Carrega os javascripts no headscript helper
     */
    public function loadJS()
    {

        foreach ($this->view->headScript() as $value) {
            if (array_key_exists('source', $value) && !is_null($value->source)) {
                $this->_js_sources['script'][] = $value;
            } else {
                $this->_js_sources['view'][] = $value->attributes['src'];
            }
        }

        // Reset Headscript
        $this->view->headScript()->exchangeArray([]);

        // Adicionando os arquivos diretamente no layout
        $firstSet = false;
        if (count($this->_js_sources) > 0) {

            if (count($this->_js_sources['global']) > 0) {
                foreach ($this->_js_sources['global'] as $jsFile) {
                    if (false === $firstSet) {
                        $this->_addJS($jsFile, true);
                        $firstSet = true;
                    } else {
                        $this->_addJS($jsFile);
                    }
                }
            }

            if (count($this->_js_sources['local']) > 0) {
                foreach ($this->_js_sources['local'] as $jsFile)
                    $this->_addJS($jsFile);
            }

            if (array_key_exists('view', $this->_js_sources) && count($this->_js_sources['view']) > 0) {
                foreach ($this->_js_sources['view'] as $jsFile)
                    $this->_addJS($jsFile);
            }

            if (array_key_exists('script', $this->_js_sources) && count($this->_js_sources['script']) > 0) {
                foreach ($this->_js_sources['script'] as $jsScript)
                    $this->view->headScript()->appendScript($jsScript->source, $jsScript->type, $jsScript->attributes);
            }

        }
    }

    /**
     * Filter
     *
     * @param $f
     * @return string
     */
    protected function _filterPath($f)
    {
        $publicPath = realpath(APPLICATION_PATH . '/../public');
        $realPath = realpath($publicPath . $f);

        return str_replace($publicPath, null, $realPath);
    }

    protected function _addJS($file, $reset = false)
    {
        $prefix = $this->_js_path . '/';

        $attr = [];
        if (preg_match('/\<[a-zA-Z0-9].*\>$/', $file, $mt)) {
            $attr['conditional'] = substr($mt[0], 1, -1);
            $file = str_replace('.' . $mt[0], '', $file);
        }

        if (!preg_match('/^(http|https):\/\/.*/', $file))
            $file = $this->_filterPath($prefix . $file);

        if (!$file) return;

        if (true === $reset) {
            $this->view->headScript()->setFile($file, $type = 'text/javascript', $attr);
        } else {
            $this->view->headScript()->appendFile($file, $type = 'text/javascript', $attr);
        }
    }

    /**
     * Carrega os javascripts no headlink helper
     */
    public function loadCSS()
    {
        foreach ($this->view->headLink() as $value) {
            $this->_css_sources['view'][] = $value->href;
        }

        // Adicionando os arquivos diretamente no layout
        $firstSet = false;
        if (count($this->_css_sources['global']) > 0) {
            foreach ($this->_css_sources['global'] as $cssFile) {
                if (false === $firstSet) {
                    $this->view->headLink()->setStylesheet($this->_css_path . '/' . $cssFile);
                    $firstSet = true;
                } else {
                    $this->view->headLink()->appendStylesheet($this->_css_path . '/' . $cssFile);
                }
            }
        }

        if (count($this->_css_sources['local']) > 0) {
            foreach ($this->_css_sources['local'] as $cssFile) {
                $this->view->headLink()->appendStylesheet($this->_css_path . '/' . $cssFile);
            }
        }

        if (array_key_exists('view', $this->_css_sources) && count($this->_css_sources['view']) > 0) {
            foreach ($this->_css_sources['view'] as $cssFile)
                $this->view->headLink()->appendStylesheet($this->_css_path . '/' . $cssFile);
        }
    }

    /**
     * Carrega os favicon no headscript helper
     */
    public function loadFavicon()
    {
        if ((bool)$this->_config_layout->resources->favicon->hide === true) return;

        $faviconOpt = $this->_config_layout->resources->favicon;

        if ($faviconOpt) {
            $favicon = $faviconOpt->path . '/' . $faviconOpt->file;

            $this->view->headLink()->headLink(array(
                'rel' => 'icon',
                'type' => 'image/png',
                'href' => $favicon), 'PREPEND');
        }
    }

}