<?php

namespace DarkN3ss\GoogleAnalytics;

use pocketmine\Server;
use pocketmine\scheduler\AsyncTask;

class TrackerAsyncTask extends AsyncTask {

    private $utmUrl;
    private $error;

    public function __construct($utmUrl) {
        $this->utmUrl = $utmUrl;
    }

    public function onRun() {
        try {
            $this->curlRequest($this->utmUrl);
        } catch(Exception $e) {
            $this->error = $e;
        }
    }

    public function onCompletion(Server $server) {
        $server->getPluginManager()->getPlugin("GoogleAnalytics")->googleCallBack($this->error ?? "");
    }

    function curlRequest($url): string {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        $curlResponse = curl_exec($ch);
        curl_close($ch);
        return $curlResponse;
    }

}
