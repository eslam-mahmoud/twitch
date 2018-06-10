<?php

namespace AppBundle\Service;

use Unirest;

//TODO add logger
class TwitchAPI
{
    private $id;
    private $secret;

    public function __construct($id, $secret)
    {
        $this->id = $id;
        $this->secret = $secret;
    }

    /**
     * @param String $twtichLoginName twitch user login name
     * @return Object twtich stream objects in array of key data of object reply
     */
    public function getStreams($twtichLoginName)
    {
        $headers = array('Client-ID'=>$this->id);
        $query = array('user_login'=>$twtichLoginName);
                
        $response = Unirest\Request::get('https://api.twitch.tv/helix/streams',$headers,$query);

        //{"data":[],"pagination":{}}
        /*
        {
            "data":[
                {
                    "id":"29035753040",
                    "user_id":"57781936",
                    "game_id":"30921",
                    "community_ids":[

                    ],
                    "type":"live",
                    "title":"World Championship - Dignitas vs. NRG (BRACKET RESET -- GRAND FINAL)",
                    "viewer_count":156305,
                    "started_at":"2018-06-10T12:00:04Z",
                    "language":"en",
                    "thumbnail_url":"https://static-cdn.jtvnw.net/previews-ttv/live_user_rocketleague-{width}x{height}.jpg"
                }
            ],
            "pagination":{
                "cursor":"eyJiIjpudWxsLCJhIjp7Ik9mZnNldCI6MX19"
            }
        }
        */
        return $response->body;
    }

    //TODO merge with getStreams 
    /**
     * @param Array $ids twitch users ids
     * @return Object twtich stream objects in array of key data of object reply
     */
    public function getStreamsByIds($ids)
    {
        $headers = array('Client-ID'=>$this->id);
        $query = array('user_id'=>$ids);
                
        $response = Unirest\Request::get('https://api.twitch.tv/helix/streams',$headers,$query);

        //{"data":[],"pagination":{}}
        /*
        {
            "data":[
                {
                    "id":"29035753040",
                    "user_id":"57781936",
                    "game_id":"30921",
                    "community_ids":[

                    ],
                    "type":"live",
                    "title":"World Championship - Dignitas vs. NRG (BRACKET RESET -- GRAND FINAL)",
                    "viewer_count":156305,
                    "started_at":"2018-06-10T12:00:04Z",
                    "language":"en",
                    "thumbnail_url":"https://static-cdn.jtvnw.net/previews-ttv/live_user_rocketleague-{width}x{height}.jpg"
                }
            ],
            "pagination":{
                "cursor":"eyJiIjpudWxsLCJhIjp7Ik9mZnNldCI6MX19"
            }
        }
        */
        return $response->body;
    }

    /**
     * @param String $twtichLoginName user login name
     * @return Boolian true or false if user is live or not
     */
    public function isUserLive($twtichLoginName)
    {
        $streams = $this->getStreams($twtichLoginName);
        foreach ($streams->data as $key => $value) {
            if ($value->type == 'live') {
                return true;
            }
        }
        return false;
    }

    /**
     * @param String $twtichLoginName user login name
     * @return Object twtich user object
     */
    public function getUserByLoginName($twtichLoginName)
    {
        $headers = array('Client-ID'=>$this->id);
        $query = array('login'=>$twtichLoginName);
                
        $response = Unirest\Request::get('https://api.twitch.tv/helix/users',$headers,$query);

        return $response->body;
    }

    /**
     * @param String $twtichLoginName user login name
     * @return int|null twtich user id or null of not found
     */
    public function getIdByLoginName($twtichLoginName)
    {
        $twitchUser = $this->getUserByLoginName($twtichLoginName);
        var_dump($twitchUser);
        foreach ($twitchUser->data as $key => $value) {
            if ($value->id) {
                return $value->id;
            }
        }
        //TODO exception
        return null;
    }

    /**
     * @param Integer $twitchUserId twitch user id
     * @return Boolian true or false on success of failer of subscrioption 
     */
    public function webhooksSubscribeUser($twitchUserId) {
        $headers = array(
            'Client-ID'=>$this->id,
            // 'Content-Type' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded',
        );
        $query = array(
            'hub.callback'=>'http://127.0.0.1:8000/webhooks/subscribeUser',
            'hub.mode'=>'subscribe',
            'hub.topic'=>'https://api.twitch.tv/helix/streams?user_id='.$twitchUserId,
            'hub.lease_seconds' => 864000 //10 days 
        );
    
        $body = Unirest\Request\Body::form($query);
        $response = Unirest\Request::post('https://api.twitch.tv/helix/webhooks/hub',$headers,$body);
        
        if ($response->code < 300 && $response->code >= 200) {
            return true;
        } else {
            //TODO exception
            return false;
        }
    }

    //TODO renew subscrition passed on hub.lease_seconds
}
