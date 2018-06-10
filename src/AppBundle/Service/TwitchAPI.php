<?php

namespace AppBundle\Service;

use Unirest;

class TwitchAPI
{
    public function getStreams($user_login)
    {
        //TODO USE ID FROM PARAMS
        $headers = array('Client-ID'=>'suhvoekstds1n449e6gjmq3acbfp8f');
        $query = array('user_login'=>$user_login);
                
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

    public function isUserLive($user_login)
    {
        $streams = $this->getStreams($user_login);
        foreach ($streams->data as $key => $value) {
            if ($value->type == 'live') {
                return true;
            }
        }
        return false;
    }
}
