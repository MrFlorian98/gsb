<?php

namespace App\Controller;

use App\Entity\Visiteur;
use App\Form\VisiteurType;
use App\Repository\VisiteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/visiteur")
 */
class VisiteurController extends AbstractController
{
    /**
     * @Route("/", name="visiteur_index", methods={"GET"})
     */
    public function index(VisiteurRepository $visiteurRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMINISTRATEUR', null, 'Seuls les administrateurs ont accès à cette page !');
        return $this->render('visiteur/index.html.twig', [
            'visiteurs' => $visiteurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="visiteur_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMINISTRATEUR', null, 'Seuls les administrateurs ont accès à cette page !');
        $visiteur = new Visiteur();
        $form = $this->createForm(VisiteurType::class, $visiteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($visiteur, $visiteur->getPassword());
            $visiteur->setPassword($hash);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($visiteur);
            $entityManager->flush();

            return $this->redirectToRoute('visiteur_index');
        }

        return $this->render('visiteur/new.html.twig', [
            'visiteur' => $visiteur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="visiteur_show", methods={"GET"})
     */
    public function show(Visiteur $visiteur): Response
    {
        //Récupérer l'id de l'utilisateur connecté
        $currentUser = $this->getUser();
        $currentId = $currentUser->getId();
        //Si l'id de l'utilisateur connecté ne correspond pas à l'id de l'utilisateur qu'il veut consulter alors on renvoi l'erreur d'accès (sauf si c'est l'administrateur)
        if($currentId != $visiteur->getId()){
            $this->denyAccessUnlessGranted('ROLE_ADMINISTRATEUR', null, 'Vous ne pouvez pas consulter un autre profil que le votre (petit mâlin) !');
        }
        $this->denyAccessUnlessGranted('ROLE_ADMINISTRATEUR', null, 'Seuls les administrateurs ont accès à cette page !');
        return $this->render('visiteur/show.html.twig', [
            'visiteur' => $visiteur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="visiteur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Visiteur $visiteur, UserPasswordEncoderInterface $encoder): Response
    {
        //Récupérer l'id de l'utilisateur connecté
        $currentUser = $this->getUser();
        $currentId = $currentUser->getId();
        //Si l'id de l'utilisateur connecté ne correspond pas à l'id de l'utilisateur qu'il veut modifier alors on renvoi l'erreur d'accès (sauf si c'est l'administrateur)
        if($currentId != $visiteur->getId()){
            $this->denyAccessUnlessGranted('ROLE_ADMINISTRATEUR', null, 'Vous ne pouvez pas modifier un autre profil que le votre (petit mâlin) !');
        }
        $form = $this->createForm(VisiteurType::class, $visiteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($visiteur, $visiteur->getPassword());
            $visiteur->setPassword($hash);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('accueil');
        }

        return $this->render('visiteur/edit.html.twig', [
            'visiteur' => $visiteur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="visiteur_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Visiteur $visiteur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$visiteur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($visiteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('visiteur_index');
    }
}
