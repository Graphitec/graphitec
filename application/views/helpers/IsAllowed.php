<?php

/**
 * View Helper IsAllowrd
 *
 * @category   ZT
 * @package    Views
 * @subpackage Helpers
 * @author     Raniery Ribeiro <raniery.rrr@gmail.com.br>
 */
class Zend_View_Helper_IsAllowed extends Zend_View_Helper_Abstract
    {

    /**
     *
     * @var Acl_Model_Params
     */
    protected $_mdlParams;

    /**
     * Verifica se tem permissão de acordo com os parâmetros na 'acl avançada'
     * @param array $params
     * @param bool $forceParams
     * @param bool $isAcl
     * @param bool $onlyUserParam
     * @param array $request
     * @return bool
     */
    public function IsAllowed($params, $forceParams = true, $isAcl = true, $onlyUserParam = false, $request = [])
    {
        $this->_mdlParams = new Acl_Model_Params();

        // Pegando a 'MCA' de acordo com o request ou com os dados passados
        $dbMca = Acl_Model_DbTable_Actions::getMcaByRequest($request);

        $controllerId = $dbMca->controller_id;

        if ($forceParams) {
            $controlParams = array();
            $actions = $this->_mdlParams->getParamsByController($controllerId);

            foreach ($actions as $action)
                $controlParams = array_merge($controlParams, $action['params']);

            foreach ($params as $paramName => $paramValue) {
                $param_exists = false;
                foreach ($controlParams as $cparam)
                    $param_exists = ($cparam['name'] == $paramName) ? true : false;

                if (!$param_exists) {
                    // Criando o parâmetro no banco
                    $this->_mdlParams->createParam(
                            [
                                'acl_action_id' => $dbMca->action_id,
                                'name' => $paramName,
                                'description' => 'create by ' . __CLASS__
                            ]
                    );
                }
            }
        }

        /*
         * Rota
         */
        if ($isAcl) {
            $params['module'] = 'acl';
            $params['controller'] = 'params';
            $params['action'] = 'list';
            $params['control'] = $controllerId;
        } else {
            $params['module'] = $dbMca->module_name;
            $params['controller'] = $dbMca->controller_name;
            $params['action'] = $dbMca->action_name;
        }

        $control = Zend_Controller_Front::getInstance()->getPlugin('Acl_Plugin_Control');
        $control->setUserRole(Zend_Auth::getInstance()->getIdentity()->role_name);
        $access = $control->checkAccess($params['module'] . ':' . $params['controller'] . ':' . $params['action']);

        if ($onlyUserParam) {
            $request = new Zend_Controller_Request_Http();
            $request->setParams($params);
            $access = $control->isParamAllowed($request);
            return $access;
        } else {
            if ($access) {
                $request = new Zend_Controller_Request_Http();
                $request->setParams($params);
                $access = $control->isParamAllowed($request);
            }
        }

        return $access;
    }

    }
