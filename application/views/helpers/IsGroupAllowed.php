<?php

/**
 * View Helper isGroupAllowed
 *
 * @category   ZT
 * @package    Views
 * @subpackage Helpers
 * @author     Raniery Ribeiro <raniery.rrr@gmail.com.br>
 */
class Zend_View_Helper_IsGroupAllowed extends Zend_View_Helper_Abstract
{

    /**
     *
     * @var Mdluser_Model_Mca
     */
    protected $_mdlMca;

    /**
     *
     * @var User_Model_User 
     */
    protected $_dbTableUser;
    
    /**
     *
     * @var 
     */
    protected $_acl;

    public function IsGroupAllowed($module, $controller, $action, $user_id = null)
    {
        $this->_dbTableUser = new Acl_Model_DbTable_Users();
        
        if(is_null($user_id))
            $user_id = Zend_Auth::getInstance()->getIdentity()->id;

        $user = $this->_dbTableUser->fetchUser($user_id);

        $parsed_request = $module . ':' . $controller . ':' . $action;
        $this->_acl = Zend_Controller_Front::getInstance()->getPlugin('Acl_Plugin_Control')->getAcl();
        if ($this->_acl->has($parsed_request)) {
            return $this->_acl->isAllowed($user->role_name, $parsed_request);
        }
    }

}