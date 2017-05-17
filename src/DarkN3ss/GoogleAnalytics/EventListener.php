<?php

namespace DarkN3ss\GoogleAnalytics;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerDeathEvent;

class EventListener implements Listener{
    /** @var SimpleAuth */
    private $plugin;

    public function __construct(GoogleAnalytics $plugin){
            $this->plugin = $plugin;
    }

    /**
     * @param PlayerJoinEvent $event
     *
     * @priority MONITOR
     */
    public function onPlayerJoin(PlayerJoinEvent $event){
        $this->track($event->getPlayer()->getDisplayName(), $event->getPlayer()->getAddress(),$event->getPlayer()->getDisplayName(),"Spawn",$event->getPlayer()->getDisplayName());
    }

    /**
     * @param PlayerPreLoginEvent $event
     *
     * @priority HIGHEST
     */
    public function onPlayerPreLogin(PlayerPreLoginEvent $event){
            $this->pos[$event->getPlayer()->getDisplayName()][0] = round(microtime(true) * 1000);
            $this->track($event->getPlayer()->getDisplayName(), $event->getPlayer()->getAddress(), $event->getPlayer()->getDisplayName(),"Connecting",$event->getPlayer()->getAddress()); //$visitorId, $visitorIp, $category, $action, $label
    }

    /**
     * @param PlayerRespawnEvent $event
     *
     * @priority MONITOR
     */
    public function onPlayerRespawn(PlayerRespawnEvent $event){
        $this->track($event->getPlayer()->getDisplayName(), $event->getPlayer()->getAddress(),$event->getPlayer()->getDisplayName(),"Respawn", "At Spawn");
    }
    
    public function onPlayerDeath(PlayerDeathEvent $event){
        $this->track($event->getPlayer()->getDisplayName(), $event->getPlayer()->getAddress(),$event->getPlayer()->getDisplayName(),"Died", "Died");
    }

    /**
     * @param PlayerQuitEvent $event
     *
     * @priority MONITOR
     */
    public function onPlayerQuit(PlayerQuitEvent $event){
        $this->pos[$event->getPlayer()->getDisplayName()][1] = "Playtime Unknown";
        if ($this->pos[$event->getPlayer()->getDisplayName()][1] != null){
            $this->pos[$event->getPlayer()->getDisplayName()][2] = (round(microtime(true) * 1000) - $this->pos[$event->getPlayer()->getDisplayName()][0]) / 1000;
            if ($this->pos[$event->getPlayer()->getDisplayName()][2] / 60 / 60 >= 5) {
                $this->pos[$event->getPlayer()->getDisplayName()][1] = "Played more than 5 hours";
            } else if ($this->pos[$event->getPlayer()->getDisplayName()][2] / 60 / 60 >= 4) {
                $this->pos[$event->getPlayer()->getDisplayName()][1] = "Played 4 hours";
            } else if ($this->pos[$event->getPlayer()->getDisplayName()][2] / 60 / 60 >= 3) {
                $this->pos[$event->getPlayer()->getDisplayName()][1] = "Played 3 hours";
            } else if ($this->pos[$event->getPlayer()->getDisplayName()][2] / 60 / 60 >= 2) {
                $this->pos[$event->getPlayer()->getDisplayName()][1] = "Played 2 hours";
            } else if ($this->pos[$event->getPlayer()->getDisplayName()][2] / 60 / 60 >= 1) {
                $this->pos[$event->getPlayer()->getDisplayName()][1] = "Played 1 hour";
            } else if (($this->pos[$event->getPlayer()->getDisplayName()][2] / 60 <= 30) && ($this->pos[$event->getPlayer()->getDisplayName()][2] / 60 > 5)) {
                $this->pos[$event->getPlayer()->getDisplayName()][1] = "Played less than 30 minutes";
            } else {
                $this->pos[$event->getPlayer()->getDisplayName()][1] = "Played less than 5 minutes";
            }
        }
        $deathlevel = $event->getPlayer()->getLevel()->getName();
        $this->track($event->getPlayer()->getDisplayName(), $event->getPlayer()->getAddress(),$event->getPlayer()->getDisplayName(),"Quit", $this->pos[$event->getPlayer()->getDisplayName()][1]);
        $this->track($event->getPlayer()->getDisplayName(), $event->getPlayer()->getAddress(),$event->getPlayer()->getDisplayName(),"Quit World", $deathlevel);
    }

    public function track($username, $visitorIp, $category, $action, $label){
        $this->trackPageAction($username, $visitorIp, $category, $action, $label);
        $this->trackPageView($username, $visitorIp);
    }

    public function trackPageAction($visitorId, $visitorIp, $category, $action, $label)
    {
        $message = AnalyticsServerAccount . " " . $visitorId;	
        $messageAsNumber = md5(strlen($message));
        $md5String = $messageAsNumber;
                while(strlen($md5String) < 32)
                {
                        $md5String = "0" . $md5String;
                }
        $getVisitorId =  ("0x" . substr($md5String,0, 16));
        $event = '(' . $category . '*' . $action . '*' . $label . ')';

        $utmUrl = 'http://www.google-analytics.com/__utm.gif?utmwv=4.4sj&utmn=' .
          rand() . 
          "&utmhn=" . AnalyticsServerDomain . 
          "&utmr=" . "-" . 
          "&utmp=" . 
          "&utmt=" . "event" . 
          "&utme=" . "5" . str_replace(' ', '%20', $event) .
          "&utmac=" . AnalyticsServerAccount . 
          "&utmcc=__utma%3D999.999.999.999.999.1%3B" . 
          "&utmvid=" . $getVisitorId . 
          "&utmdt=" . ("MCPE") . 
          "&utmip=" . $visitorIp;
        //$this->api->chat->broadcast("Tracker Url: " . $utmUrl);  //for debug

        $this->asyncOperation($utmUrl);
    }
	
    public function trackPageView($visitorId, $visitorIp)
    {
        $message = AnalyticsServerAccount . " " . $visitorId;	
        $messageAsNumber = md5(strlen($message));
        $md5String = $messageAsNumber;
                while(strlen($md5String) < 32)
                {
                        $md5String = "0" . $md5String;
                }
        $getVisitorId =  ("0x" . substr($md5String,0, 16));

        $utmUrl = 'http://www.google-analytics.com/__utm.gif?utmwv=4.4sj&utmn=' .
          rand() . 
          "&utmhn=" . AnalyticsServerDomain . 
          "&utmr=" . "-" . 
          "&utmp=" . AnalyticsServerDomain . 
          "&utmac=" . AnalyticsServerAccount . 
          "&utmcc=__utma%3D999.999.999.999.999.1%3B" . 
          "&utmvid=" . $getVisitorId  . 
          "&utmdt=" . ("MCPE") . 
          "&utmip=" . $visitorIp;
        //$this->api->chat->broadcast("Tracker Url: " . $utmUrl);  //for debug

        $this->asyncOperation($utmUrl);
    }
	
    public function asyncOperation($url){
    $trackerTask = new TrackerAsyncTask($url);
    $this->plugin->getServer()->getScheduler()->scheduleAsyncTask($trackerTask);
    }
}