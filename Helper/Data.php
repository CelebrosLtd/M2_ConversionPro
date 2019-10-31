<?php
/**
 * Celebros
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish correct extension functionality.
 * If you wish to customize it, please contact Celebros.
 *
 ******************************************************************************
 * @category    Celebros
 * @package     Celebros_ConversionPro
 */
namespace Celebros\ConversionPro\Helper;

use \Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_ENABLED = 'conversionpro/general_settings/enabled';
    const XML_PATH_HOST = 'conversionpro/general_settings/host';
    const XML_PATH_SITE_KEY = 'conversionpro/general_settings/sitekey';
    const XML_PATH_NAV_TO_SEARCH = 'conversionpro/nav_to_search_settings/nav_to_search';
    const XML_PATH_NAV_TO_SEARCH_ENABLE_BLACKLIST = 'conversionpro/nav_to_search_settings/nav_to_search_enable_blacklist';
    const XML_PATH_NAV_TO_SEARCH_BLACKLIST = 'conversionpro/nav_to_search_settings/nav_to_search_blacklist';
    const XML_PATH_SCRIPT_PATH = 'conversionpro/advanced/scripts_path';
    const XML_PATH_CLIENT_CONFIG_PATH = 'conversionpro/advanced/client_config_path';
    const XML_PATH_CLIENT_CONFIG_FILENAME = 'conversionpro/advanced/client_config_js_filename';
    const XML_PATH_JS_FRAMEWORK = 'conversionpro/advanced/js_framework';
    const XML_PATH_ADD_DIV = 'conversionpro/advanced/adddiv';
    const XML_PATH_ADD_SCRIPTS = 'conversionpro/advanced/addscripts';
    const XML_PATH_SCRIPTS_LOCATION = 'conversionpro/advanced/scriptslocation';
    const XML_PATH_HIDE_CONTENT = 'conversionpro/advanced/hidecontent';
    
    const ANGULAR_SETTING_PREFIX = 'angular_';
    
    const PRODUCT_LIST_CONTAINER_ID = 'celUITDiv';
    
    public function isCategoryIdBlacklisted($categoryId, $storeId = null)
    {
        return $this->getNavToSearchEnableBlacklist($storeId)
            && in_array($categoryId, $this->getNavToSearchBlacklist($storeId));
    }
    
    public function getProductListContainerId()
    {
        return self::PRODUCT_LIST_CONTAINER_ID;
    }
    
    public function isAngular($store = null)
    {
        $value = $this->scopeConfig->getValue(
            self::XML_PATH_JS_FRAMEWORK,
            ScopeInterface::SCOPE_STORE,
            $store);
            
        return ($value == 'angular') ? true : false;
    }
    
    public function getExternalJsUrls()
    {
        $protocol = $this->_getRequest()->isSecure() ? 'https' : 'http';
        /*$jqueryUrl = $protocol . '://'
            . implode('/', [$this->getHost(), $this->getScriptPath(), $this->getJqueryFilename()]);*/
        $jqueryUrl = 'jquery';
        $clientConfigUrl = $protocol . '://'
            . implode('/', [$this->getHost(), $this->getClientConfigPath(), $this->getSiteKey(), 'output', $this->getClientConfigFilename()]);
        return [$jqueryUrl, $clientConfigUrl];
    }
    
    public function getAngularScriptsArray()
    {
        $protocol = $this->_getRequest()->isSecure() ? 'https' : 'http';
        $angular = $protocol . '://'
            . 'ajax.googleapis.com/ajax/libs/angularjs/1.6.3/angular.min.js';
        $angularRoute = $protocol . '://'    
            . 'ajax.googleapis.com/ajax/libs/angularjs/1.6.2/angular-route.min.js';
        $clientConfigUrl = $protocol . '://'
            . implode('/', [$this->getHost(), $this->getClientConfigPath(), $this->getSiteKey(), 'output', $this->getClientConfigFilename()]);
            /*. 'uitemplatev3stag.celebros.com/UITemplateAngular/Clients/Demo2/output/CelScripts.js';*/
        return [$angular, $angularRoute, $clientConfigUrl];
    }
    
    public function isEnabledOnFrontend($store = null)
    {
        return $this->isModuleOutputEnabled() && $this->isEnabled($store);
    }
    
    public function isEnabled($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
    
    public function getHost($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_HOST,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
    
    public function getSiteKey($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SITE_KEY,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
    
    public function getAddDiv($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ADD_DIV,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
    
    public function getAddScripts($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ADD_SCRIPTS,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
    
    public function getScriptsLocation($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SCRIPTS_LOCATION,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
    
    public function getHideContent($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_HIDE_CONTENT,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
    
    public function getNavToSearch($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_NAV_TO_SEARCH,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
    
    public function getNavToSearchEnableBlacklist($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_NAV_TO_SEARCH_ENABLE_BLACKLIST,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
    
    public function getNavToSearchBlacklist($store = null)
    {
        $value = $this->scopeConfig->getValue(
            self::XML_PATH_NAV_TO_SEARCH_BLACKLIST,
            ScopeInterface::SCOPE_STORE,
            $store
        );
        $value = empty($value) ? [] : explode(',', $value);
        return $value;
    }
    
    public function getScriptPath($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SCRIPT_PATH,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
    
    public function getClientConfigPath($store = null)
    {
        $path = $this->_prepXmlPath(self::XML_PATH_CLIENT_CONFIG_PATH, $store);
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
    
    public function getClientConfigFilename($store = null)
    {
        $path = $this->_prepXmlPath(self::XML_PATH_CLIENT_CONFIG_FILENAME, $store);
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
    
    protected function _prepXmlPath($path, $store = null)
    {
        if ($this->isAngular($store)) {
            $path = explode('/', $path);
            $path[2] = self::ANGULAR_SETTING_PREFIX . $path[2];
            $path = implode('/', $path);
        }

        return $path;
    }
    
    public function getJqueryFilename()
    {
        return 'jquery.1.7.Celebros.min.js';
    }
    
    public function getBaseUrl()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        return $storeManager->getStore()->getBaseUrl();
    }
}
