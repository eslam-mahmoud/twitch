<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;
use AppBundle\Service\TwitchAPI;

class WebhookController extends Controller
{
    /**
     * Each user have a subscribe url with his ID so if we get data then he is streaming else he is not 
     * @Route("/webhook/subscribeUser/{userId}", name="webhookSubscribeUser")
     */
    public function subscribeUserction(Request $request, TwitchAPI $TwitchAPI, $userId)
    {
        //get the user
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->find($userId);

        //get the payload
        $params = $request->request->all();
        //set user streamming status
        if (isset($params['data']) && count($params['data']) > 0) {
            $user->setStreaming(true);
        } else {
            $user->setStreaming(false);
        }
        //update the user 
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
    
        //return response ok to avoid twitch retry send data
        $response = new Response(
            '',
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
        return $response;
    }
}
