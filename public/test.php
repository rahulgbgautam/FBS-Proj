awdhesh123<?php
die(); 
function neutrinoApiData($domainIP){
      $neutrino_api_userid = 'gulshan.singsys';
      $neutrino_api_key = '2BtXzzP24KlPnNH4NSE23Qnwo6kWap2PVmceyocsssp7cz0s'; 
      // $neutrino_api_userid = getGeneralSetting('neutrino_api_userid'); 
      // $neutrino_api_key = getGeneralSetting('neutrino_api_key'); 
      
      $url = 'https://neutrinoapi.net/ip-blocklist?user-id='.$neutrino_api_userid.'&api-key='.$neutrino_api_key.'&ip='.$domainIP;

      $getContent = file_get_contents($url);
      return $getContent;
    }

    $domainIP = '52.221.172.188'; 
    $neutrinoContent = neutrinoApiData($domainIP);
      $neutrinoContentArr = json_decode($neutrinoContent);

      if($neutrinoContentArr->is-proxy == false
            && $neutrinoContentArr->is-tor  == false
            && $neutrinoContentArr->is-vpn  == false
            && $neutrinoContentArr->is-malware  == false
            && $neutrinoContentArr->is-spyware  == false
            && $neutrinoContentArr->is-dshield  == false
            && $neutrinoContentArr->is-hijacked  == false
            && $neutrinoContentArr->is-spider == false
            && $neutrinoContentArr->is-bot == false
            && $neutrinoContentArr->is-spam-bot == false
            && $neutrinoContentArr->is-exploit-bot == false
          ) {
        echo "LLLLLLL222";
            $scanStatus = true;
          }
die();