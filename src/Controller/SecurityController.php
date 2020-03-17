<?php
namespace App\Controller;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function login(Security $security, AuthenticationUtils $authenticationUtils) : Response
    {
        // Récupérer l'erreur s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        if($security->getUser()){
            return $this->redirectToRoute('accueil');
        } else{
            return $this->render('security/login.html.twig', [
                'error' => $error,
            ]);
        } 
    }
    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(){}
}