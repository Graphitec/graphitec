<?php

/**
 * View Helper Utm2latlong
 *
 * @category   ZT
 * @package    Views
 * @subpackage Helpers
 * @author     Yves Samson C Toupe <styvesamson@gmail.com>
 */
class Zend_View_Helper_MapConvert extends Zend_View_Helper_Abstract {  // Based on http://www.ibm.com/developerworks/java/library/j-coordconvert/

    /**
     * @var array 
     */

    private $_options;

    /**
     * @var ZT_Map_ConvertCoordinates 
     */
    private $_plugin;

    public function mapConvert(array $options = []) {
        $this->_options = $options;

        $this->_plugin = new ZT_Map_ConvertCoordinates();

        return $this;
    }

    /**
     *  Converte latitude e longitude para UTM
     * @param string $latitude
     * @param string $longitude
     * @return string
     */
    public function latLogToUtm($latitude, $longitude) {
        $convert = $this->_plugin->convertLatLngToUtm($latitude, $longitude);
        return implode($convert,' ');
    }

    public function utmToLatLog($UTMEasting, $UTMNorthing, $UTMZone) {
        return $this->_plugin->convertUtmToLatLng($UTMEasting, $UTMNorthing, $UTMZone);
    }

}

?>
