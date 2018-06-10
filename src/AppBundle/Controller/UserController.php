<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\User;
use AppBundle\Service\TwitchAPI;

class UserController extends Controller
{
    /**
     * @Route("/user/profile", name="userProfile")
     */
    public function profileAction(Request $request, TwitchAPI $TwitchAPI)
    {
        $user = $this->getUser();
        // print_r($user);
        $twitchForm = $this->createFormBuilder($user)
            ->add('twitchId', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Save twitch ID'))
            ->getForm();

        $twitchForm->handleRequest($request);

        if ($twitchForm->isSubmitted() && $twitchForm->isValid()) {
            // $twitchForm->getData() holds the submitted values
            // but, the original `$user` variable has also been updated
            // $user = $twitchForm->getData();

            //save the user to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }

        $isUserLive = $TwitchAPI->isUserLive($user->getTwitchId());

        return $this->render('AppBundle::user/profile.html.twig', [
            'twitchForm' => $twitchForm->createView(),
            'isUserLive' => $isUserLive
        ]);
    }
}
