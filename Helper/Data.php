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

use Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public const XML_PATH_ENABLED = 'conversionpro/general_settings/enabled';
    public const XML_PATH_HOST = 'conversionpro/general_settings/host';
    public const XML_PATH_SITE_KEY = 'conversionpro/general_settings/sitekey';
    public const XML_PATH_NAV_TO_SEARCH = 'conversionpro/nav_to_search_settings/nav_to_search';
    public const XML_PATH_NAV_TO_SEARCH_ENABLE_BLACKLIST = 'conversionpro/nav_to_search_settings/nav_to_search_enable_blacklist';
    public const XML_PATH_NAV_TO_SEARCH_BLACKLIST = 'conversionpro/nav_to_search_settings/nav_to_search_blacklist';
    public const XML_PATH_SCRIPT_PATH = 'conversionpro/advanced/scripts_path';
    public const XML_PATH_CLIENT_CONFIG_PATH = 'conversionpro/advanced/client_config_path';
    public const XML_PATH_CLIENT_CONFIG_FILENAME = 'conversionpro/advanced/client_config_js_filename';
    public const XML_PATH_JS_FRAMEWORK = 'conversionpro/advanced/js_framework';
    public const XML_PATH_ADD_DIV = 'conversionpro/advanced/adddiv';
    public const XML_PATH_ADD_SCRIPTS = 'conversionpro/advanced/addscripts';
    public const XML_PATH_SCRIPTS_LOCATION = 'conversionpro/advanced/scriptslocation';
    public const XML_PATH_HIDE_CONTENT = 'conversionpro/advanced/hidecontent';
    public const XML_PATH_ANALYTICS_HOST = 'conversionpro/general_settings/analytics';

    public const ANGULAR_SETTING_PREFIX = 'angular_';

    public const PRODUCT_LIST_CONTAINER_ID = 'celUITDiv';

    public function isCategoryIdBlacklisted($categoryId, $storeId = null): bool
    {
        return $this->getNavToSearchEnableBlacklist($storeId)
            && in_array($categoryId, $this->getNavToSearchBlacklist($storeId));
    }

    /**
     * @return string
     */
    public function getProductListContainerId(): string
    {
        return self::PRODUCT_LIST_CONTAINER_ID;
    }

    /**
     * @param int $store
     * @return bool
     */
    public function isAngular($store = null): bool
    {
        $value = $this->scopeConfig->getValue(
            self::XML_PATH_JS_FRAMEWORK,
            ScopeInterface::SCOPE_STORE,
            $store
        );

        return ($value == 'angular') ? true : false;
    }

    /**
     * @return array
     */
    public function getExternalJsUrls(): array
    {
        $protocol = $this->_getRequest()->isSecure() ? 'https' : 'http';
        $jqueryUrl = 'jquery';
        $clientConfigUrl = $protocol . '://' . implode('/', [
            $this->getHost(),
            $this->getClientConfigPath(),
            $this->getSiteKey(),
            'output',
            $this->getClientConfigFilename()
        ]);

        return [$jqueryUrl, $clientConfigUrl];
    }

    /**
     * @return array
     */
    public function getAngularScriptsArray(): array
    {
        $protocol = $this->_getRequest()->isSecure() ? 'https' : 'http';
        $angular = $protocol . '://'
            . 'ajax.googleapis.com/ajax/libs/angularjs/1.6.3/angular.min.js';
        $angularRoute = $protocol . '://'
            . 'ajax.googleapis.com/ajax/libs/angularjs/1.6.2/angular-route.min.js';
        $clientConfigUrl = $protocol . '://' . implode('/', [
            $this->getHost(),
            $this->getClientConfigPath(),
            $this->getSiteKey(),
            'output',
            $this->getClientConfigFilename()
        ]);

        return [$angular, $angularRoute, $clientConfigUrl];
    }

    /**
     * @param int $store
     * @return bool
     */
    public function isEnabledOnFrontend($store = null): bool
    {
        return $this->isModuleOutputEnabled() && $this->isEnabled($store);
    }

    /**
     * @param int $store
     * @return bool
     */
    public function isEnabled($store = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param int $store
     * @return string
     */
    public function getHost($store = null): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_HOST,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param int $store
     * @return string
     */
    public function getSiteKey($store = null): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SITE_KEY,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param int $store
     * @return bool
     */
    public function getAddDiv($store = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ADD_DIV,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param int $store
     * @return bool
     */
    public function getAddScripts($store = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ADD_SCRIPTS,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param int $store
     * @return string
     */
    public function getScriptsLocation($store = null): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SCRIPTS_LOCATION,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param int $store
     * @return bool
     */
    public function getHideContent($store = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_HIDE_CONTENT,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param int $store
     * @return bool
     */
    public function getNavToSearch($store = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_NAV_TO_SEARCH,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param int $store
     * @return bool
     */
    public function getNavToSearchEnableBlacklist($store = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_NAV_TO_SEARCH_ENABLE_BLACKLIST,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param int $store
     * @return array
     */
    public function getNavToSearchBlacklist($store = null): array
    {
        $value = $this->scopeConfig->getValue(
            self::XML_PATH_NAV_TO_SEARCH_BLACKLIST,
            ScopeInterface::SCOPE_STORE,
            $store
        );
        $value = empty($value) ? [] : explode(',', $value);
        return $value;
    }

    /**
     * @param int $store
     * @return string
     */
    public function getScriptPath($store = null): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SCRIPT_PATH,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param int $store
     * @return string
     */
    public function getClientConfigPath($store = null): string
    {
        $path = $this->prepXmlPath(self::XML_PATH_CLIENT_CONFIG_PATH, $store);
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param int $store
     * @return string
     */
    public function getClientConfigFilename($store = null): string
    {
        $path = $this->prepXmlPath(self::XML_PATH_CLIENT_CONFIG_FILENAME, $store);
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param string $path
     * @param int $store
     * @return string
     */
    protected function prepXmlPath($path, $store = null): string
    {
        if ($this->isAngular($store)) {
            $path = explode('/', $path);
            $path[2] = self::ANGULAR_SETTING_PREFIX . $path[2];
            $path = implode('/', $path);
        }

        return $path;
    }

    /**
     * @return string
     */
    public function getJqueryFilename(): string
    {
        return 'jquery.1.7.Celebros.min.js';
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        return $storeManager->getStore()->getBaseUrl();
    }
}
