<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Form\FicheFraisType;
use App\Repository\FicheFraisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/fiche_frais")
 */
class FicheFraisController extends AbstractController
{
    /**
     * @Route("/", name="fiche_frais_index", methods={"GET"})
     */
    public function index(FicheFraisRepository $ficheFraisRepository): Response
    {
        return $this->render('fiche_frais/index.html.twig', [
            'fiche_frais' => $ficheFraisRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="fiche_frais_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ficheFrai = new FicheFrais();
        $form = $this->createForm(FicheFraisType::class, $ficheFrai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ficheFrai);
            $entityManager->flush();

            return $this->redirectToRoute('fiche_frais_index');
        }

        return $this->render('fiche_frais/new.html.twig', [
            'fiche_frai' => $ficheFrai,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="fiche_frais_show", methods={"GET"})
     */
    public function show(FicheFrais $ficheFrai): Response
    {
        return $this->render('fiche_frais/show.html.twig', [
            'fiche_frai' => $ficheFrai,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="fiche_frais_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, FicheFrais $ficheFrai): Response
    {
        $form = $this->createForm(FicheFraisType::class, $ficheFrai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('fiche_frais_index');
        }

        return $this->render('fiche_frais/edit.html.twig', [
            'fiche_frai' => $ficheFrai,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="fiche_frais_delete", methods={"DELETE"})
     */
    public function delete(Request $request, FicheFrais $ficheFrai): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ficheFrai->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ficheFrai);
            $entityManager->flush();
        }

        return $this->redirectToRoute('fiche_frais_index');
    }
}
