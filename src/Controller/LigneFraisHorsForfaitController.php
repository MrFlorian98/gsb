<?php

namespace App\Controller;

use App\Entity\LigneFraisHorsForfait;
use App\Form\LigneFraisHorsForfaitType;
use App\Repository\LigneFraisHorsForfaitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ligne_frais_hors_forfait")
 */
class LigneFraisHorsForfaitController extends AbstractController
{
    /**
     * @Route("/", name="ligne_frais_hors_forfait_index", methods={"GET"})
     */
    public function index(LigneFraisHorsForfaitRepository $ligneFraisHorsForfaitRepository): Response
    {
        return $this->render('ligne_frais_hors_forfait/index.html.twig', [
            'ligne_frais_hors_forfaits' => $ligneFraisHorsForfaitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ligne_frais_hors_forfait_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ligneFraisHorsForfait = new LigneFraisHorsForfait();
        $form = $this->createForm(LigneFraisHorsForfaitType::class, $ligneFraisHorsForfait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ligneFraisHorsForfait);
            $entityManager->flush();

            return $this->redirectToRoute('ligne_frais_hors_forfait_index');
        }

        return $this->render('ligne_frais_hors_forfait/new.html.twig', [
            'ligne_frais_hors_forfait' => $ligneFraisHorsForfait,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_frais_hors_forfait_show", methods={"GET"})
     */
    public function show(LigneFraisHorsForfait $ligneFraisHorsForfait): Response
    {
        return $this->render('ligne_frais_hors_forfait/show.html.twig', [
            'ligne_frais_hors_forfait' => $ligneFraisHorsForfait,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ligne_frais_hors_forfait_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, LigneFraisHorsForfait $ligneFraisHorsForfait): Response
    {
        $form = $this->createForm(LigneFraisHorsForfaitType::class, $ligneFraisHorsForfait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ligne_frais_hors_forfait_index');
        }

        return $this->render('ligne_frais_hors_forfait/edit.html.twig', [
            'ligne_frais_hors_forfait' => $ligneFraisHorsForfait,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_frais_hors_forfait_delete", methods={"DELETE"})
     */
    public function delete(Request $request, LigneFraisHorsForfait $ligneFraisHorsForfait): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ligneFraisHorsForfait->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ligneFraisHorsForfait);
            $entityManager->flush();
        }

        return $this->redirectToRoute('saisie_fiche_frais');
    }
}
