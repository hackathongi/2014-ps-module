<?php

if (!defined('_PS_VERSION_'))
    exit;

class EShopinion extends Module {

    public $id_lang;
    public $iso_lang;

    public function __construct() {
        $this->name = 'eshopinion';
        $this->tab = 'advertising_marketing';
        $this->version = '1.0';
        $this->author = 'Hackathon Girona';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.5.6.2');

        parent::__construct();

        $this->displayName = "EShopinion ". $this->l('Module');
        $this->description = $this->l('Adds an EShopinion block');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (self::isInstalled($this->name)) {
            $this->id_lang = (int) Configuration::get('PS_LANG_DEFAULT');
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
        }
    }

    public function install() {
        return (parent::install() AND $this->registerHook('newOrder'));
    }

    public function getContent() {
        $message = '';

        if (Tools::isSubmit('submit_' . $this->name))
            $message = $this->_saveContent();

        $this->_displayContent($message);

        return $this->display(__FILE__, '/views/templates/settings.tpl');
    }

    private function _saveContent() {
        $message = '';

        if (Configuration::updateValue('MOD_ESHOPINION_API_USERNAME', Tools::getValue('MOD_ESHOPINION_API_USERNAME')) &&
                Configuration::updateValue('MOD_ESHOPINION_API_KEY', Tools::getValue('MOD_ESHOPINION_API_KEY')))
            $message = $this->displayConfirmation($this->l('Your settings have been saved'));
        else
            $message = $this->displayError($this->l('There was an error while saving your settings'));

        return $message;
    }

    private function _displayContent($message) {
        $this->context->smarty->assign(array(
            'message' => $message,
            'module_name' => $this->name,
            'MOD_ESHOPINION_API_USERNAME_FIELD_KEY' => $this->l('API Username'),
            'MOD_ESHOPINION_API_USERNAME' => Configuration::get('MOD_ESHOPINION_API_USERNAME'),
            'MOD_ESHOPINION_API_KEY_FIELD_KEY' => $this->l('API Key'),
            'MOD_ESHOPINION_API_KEY' => Configuration::get('MOD_ESHOPINION_API_KEY'),
        ));
    }

    public function hookNewOrder($params) {
        return true;
    }

}
