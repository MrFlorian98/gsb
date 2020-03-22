<?php

namespace App\Controller;

use App\Entity\LigneFraisForfait;
use App\Form\LigneFraisForfaitType;
use App\Repository\LigneFraisForfaitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ligne_frais_forfait")
 */
class LigneFraisForfaitController extends AbstractController
{
    /**
     * @Route("/", name="ligne_frais_forfait_index", methods={"GET"})
     */
    public function index(LigneFraisForfaitRepository $ligneFraisForfaitRepository): Response
    {
        return $this->render('ligne_frais_forfait/index.html.twig', [
            'ligne_frais_forfaits' => $ligneFraisForfaitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ligne_frais_forfait_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ligneFraisForfait = new LigneFraisForfait();
        $form = $this->createForm(LigneFraisForfaitType::class, $ligneFraisForfait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ligneFraisForfait);
            $entityManager->flush();

            return $this->redirectToRoute('ligne_frais_forfait_index');
        }

        return $this->render('ligne_frais_forfait/new.html.twig', [
            'ligne_frais_forfait' => $ligneFraisForfait,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_frais_forfait_show", methods={"GET"})
     */
    public function show(LigneFraisForfait $ligneFraisForfait): Response
    {
        return $this->render('ligne_frais_forfait/show.html.twig', [
            'ligne_frais_forfait' => $ligneFraisForfait,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ligne_frais_forfait_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, LigneFraisForfait $ligneFraisForfait): Response
    {
        $form = $this->createForm(LigneFraisForfaitType::class, $ligneFraisForfait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ligne_frais_forfait_index');
        }

        return $this->render('ligne_frais_forfait/edit.html.twig', [
            'ligne_frais_forfait' => $ligneFraisForfait,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_frais_forfait_delete", methods={"DELETE"})
     */
    public function delete(Request $request, LigneFraisForfait $ligneFraisForfait): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ligneFraisForfait->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ligneFraisForfait);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ligne_frais_forfait_index');
    }
}
