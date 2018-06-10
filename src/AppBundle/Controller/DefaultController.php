<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Service\TwitchAPI;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, TwitchAPI $TwitchAPI)
    {
        // $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepository->findAll();
        
        return $this->render('AppBundle::default/index.html.twig', [
            'users' => $users
        ]);
    }
}
