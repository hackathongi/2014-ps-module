<?php

if (!defined('_PS_VERSION_'))
    exit;

class MyModule extends Module {

    public $id_lang;
    public $iso_lang;

    public function __construct() {
        $this->name = 'mymodule';
        $this->tab = 'advertising_marketing';
        $this->version = '1.0';
        $this->author = 'Hackathon Girona';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.5');
        $this->dependencies = array('blockcart');

        parent::__construct();

        $this->displayName = $this->l('My module');
        $this->description = $this->l('Description of my module.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('MYMODULE_NAME'))
            $this->warning = $this->l('No name provided');

        if (self::isInstalled($this->name)) {
            $this->id_lang = (int) Configuration::get('PS_LANG_DEFAULT');
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
        }
    }

    public function install() {
        return (parent::install() AND $this->registerHook('newOrder'));
    }

}
