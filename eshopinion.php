<?php

include(dirname(__FILE__) . "/classes/EShopinionOrderInput.php");
include(dirname(__FILE__) . "/classes/EShopinionClient.php");
include(dirname(__FILE__) . "/classes/EShopinionOrder.php");

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

        $this->displayName = "EShopinion " . $this->l('Module');
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

        if (Configuration::updateValue('MOD_ESHOPINION_TOKEN', Tools::getValue('MOD_ESHOPINION_TOKEN')))
            $message = $this->displayConfirmation($this->l('Your settings have been saved'));
        else
            $message = $this->displayError($this->l('There was an error while saving your settings'));

        return $message;
    }

    private function _displayContent($message) {
        $this->context->smarty->assign(array(
            'message' => $message,
            'module_name' => $this->name,
            'MOD_ESHOPINION_TOKEN' => Configuration::get('MOD_ESHOPINION_TOKEN'),
        ));
    }

    public function hookNewOrder($params) {

        // ORDER INPUT
        // Order
        $order = new EShopinionOrder();
        $order->id = isset($params['order']) && isset($params['order']->id) ? $params['order']->id : null;
        $order->products = null;
        $order->date = isset($params['order']) && isset($params['order']->date_add) ? $params['order']->date_add : null;

        // Client 
        $client = new EShopinionClient();
        $client->email = isset($params['customer']) && isset($params['customer']->email) ? $params['customer']->email : null;
        $client->name = isset($params['customer']) && isset($params['customer']->firstname) ? $params['customer']->firstname : null;
        $client->surname = isset($params['customer']) && isset($params['customer']->lastname) ? $params['customer']->lastname : null;
        $client->language = $this->iso_lang;

        $orderInput = new EShopinionOrderInput();
        $orderInput->token = Configuration::get('MOD_ESHOPINION_TOKEN');
        $orderInput->order = $order;
        $orderInput->client = $client;


        $url = "http:/api-test.eshopinion.com/orders";

        // CURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderInput));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($orderInput))));


        // Result
        $result = curl_exec($ch);


        return true;
    }

}
