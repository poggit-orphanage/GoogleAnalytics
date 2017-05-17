<?php

namespace DarkN3ss\GoogleAnalytics;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class GoogleAnalytics extends PluginBase{
    
    /** @var EventListener */
	protected $listener;
        private $configYML;
    
    public function onEnable(){
        $this->getLogger()->info("GoogleAnalytics Starterd");
        
        @mkdir($this->getDataFolder());
        
        $this->configYML = new Config($this->getDataFolder()."config.yml", Config::YAML, array('analyticsServerAccount' => "MO-XXXXXXXX-X", 'analyticsServerDomain' => "website.com"));
        $this->configYML->save();
        define("AnalyticsServerAccount", $this->configYML->get('analyticsServerAccount'));
        define("AnalyticsServerDomain", $this->configYML->get('analyticsServerDomain'));
        
        $this->listener = new EventListener($this);
        $this->getServer()->getPluginManager()->registerEvents($this->listener, $this);
    }

    public function googleCallBack($error)
    {
        if (strlen($error) > 0) $this->getLogger()->info("GoogleAnalytics [ERROR] : " . $error);
    }
    public function onDisable(){
        $this->getLogger()->info("GoogleAnalytics Stopped");
    }
}