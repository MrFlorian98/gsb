<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Entity\LigneFraisForfait;
use App\Entity\LigneFraisHorsForfait;
use App\Form\FicheFraisType;
use App\Form\LigneFraisHorsForfaitType;
use App\Form\SaisieLigneFraisForfaitType;
use App\Repository\FicheFraisRepository;
use App\Repository\FraisForfaitRepository;
use App\Repository\LigneFraisForfaitRepository;
use App\Repository\LigneFraisHorsForfaitRepository;
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
     * @Route("/saisie_fiche", name="saisie_fiche_frais", methods={"GET","POST"})
     */
    public function saisieFiche(Request $request, FicheFraisRepository $ficheFraisRepository, FraisForfaitRepository $fraisForfaitRepository, LigneFraisForfaitRepository $ligneFraisForfaitRepository, LigneFraisHorsForfaitRepository $ligneFraisHorsForfaitRepository): Response
    {
        //Récupérer mois actuel
        $date = new \DateTime();
        $mois = $date->format("m-Y");
        //Indice boucle
        $i = 1;
        //Récupérer les quantités de chaque frais forfait de l'utilisateur en cours pour sa fiche de frais en cours
        $ficheFraisUser = $ficheFraisRepository->findBy(array('fkVisiteur' => $this->getUser()));
        $nbEtp = $ligneFraisForfaitRepository->countFrais($this->getUser(), 1);
        $nbKm = $ligneFraisForfaitRepository->countFrais($this->getUser(), 2);
        $nbNui = $ligneFraisForfaitRepository->countFrais($this->getUser(), 3);
        $nbRep = $ligneFraisForfaitRepository->countFrais($this->getUser(), 4);
        //Récupérer les élements hors forfaits
        $lignesHorsForfaits = $ligneFraisHorsForfaitRepository->findBy(array('fkVisiteur' => $this->getUser()));

        //Création du formulaire de saisie de ligne de frais forfait
        $form = $this->createForm(SaisieLigneFraisForfaitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            //Récupérer les données du formulaire
            $donnees = $form->getData();
            foreach ($donnees as $quantite) {
                $fraisForfait = $fraisForfaitRepository->findOneById($i);
                $ligne = new LigneFraisForfait();
                $ligne->setMois($mois);
                $ligne->setFkVisiteur($this->getUser());
                $ligne->setFkFraisForfait($fraisForfait);
                $ligne->setQuantite($quantite);
                $entityManager->persist($ligne);
                $entityManager->flush();
                $i++;
            }
            
            //On vérifie s'il existe une fiche de frais pour ce mois pour cet utilisateur
            $ficheFrais = $ficheFraisRepository->findOneBy(array('fkVisiteur' => $this->getUser(), 'mois' => $mois));
            //S'il n'y en a pas on en créer une
            if (!$ficheFrais) {
                $ficheFrais = new FicheFrais();
                $ficheFrais->setFkVisiteur($this->getUser());
                $ficheFrais->setMois($mois);
                $ficheFrais->setNbJustificatifs(0);
                $ficheFrais->setMontantValide(0);
                $ficheFrais->setDateModif($date);
                $entityManager->persist($ficheFrais);
            } else {
                //Sinon on la met à jour
                $ficheFrais->setDateModif($date);
                $entityManager->persist($ficheFrais);
            }
            $entityManager->flush();
            $this->addFlash('ff', 'La ligne de frais forfait a bien été ajoutée à la fiche de frais de ' . $ficheFrais->getFkVisiteur() . ' du ' . $ficheFrais->getMois() . '');
            return $this->redirectToRoute('saisie_fiche_frais');
        }

        //Ajout de la ligne de frais hors forfait
        $ligneFraisHorsForfait = new LigneFraisHorsForfait();
        $formHF = $this->createForm(LigneFraisHorsForfaitType::class, $ligneFraisHorsForfait);
        $formHF->handleRequest($request);
        if ($formHF->isSubmitted() && $formHF->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            //$ligneFraisHorsForfait->setDate($date);
            $ligneFraisHorsForfait->setFkVisiteur($this->getUser());
            $entityManager->persist($ligneFraisHorsForfait);
            //On vérifie s'il existe une fiche de frais pour ce mois pour cet utilisateur
            $ficheFrais = $ficheFraisRepository->findOneBy(array('fkVisiteur' => $this->getUser(), 'mois' => $mois));
            //S'il n'y en a pas on en créer une
            if (!$ficheFrais) {
                $ficheFrais = new FicheFrais();
                $ficheFrais->setFkVisiteur($this->getUser());
                $ficheFrais->setMois($mois);
                $ficheFrais->setNbJustificatifs(0);
                $ficheFrais->setMontantValide(0);
                $ficheFrais->setDateModif($date);
                $entityManager->persist($ficheFrais);
            } else {
                //Sinon on la met à jour
                $ficheFrais->setDateModif($date);
                $entityManager->persist($ficheFrais);
            }
            $entityManager->flush();
            $this->addFlash('hf', 'La ligne de frais hors forfait a bien été ajoutée à la fiche de frais de ' . $ficheFrais->getFkVisiteur() . ' du ' . $ficheFrais->getMois() . '');
            return $this->redirectToRoute('saisie_fiche_frais');
        }

        return $this->render('fiche_frais/saisie_fiche.html.twig', [
            'form' => $form->createView(),
            'formHF' => $formHF->createView(),
            'ficheFraisUser' => $ficheFraisUser,
            'lignesHorsForfaits' => $lignesHorsForfaits,
            'nbEtp' => $nbEtp,
            'nbKm' => $nbKm,
            'nbNui' => $nbNui,
            'nbRep' => $nbRep
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
