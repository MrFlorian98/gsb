<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(Security $security, AuthenticationUtils $authenticationUtils)
    {
        if ($security->getUser()){
            return $this->render('default/index.html.twig', [
                'controller_name' => 'DefaultController',
            ]);

        //Sinon renvoi à la page de connexion
        } else {
            // Récupérer l'erreur s'il y en a une
            $error = $authenticationUtils->getLastAuthenticationError();
            return $this->render('security/login.html.twig', [
                'error' => $error,
            ]);
        }
    }
}
