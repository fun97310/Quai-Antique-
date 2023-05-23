<?php

namespace App\Controller;

use App\Entity\HeureMatins;
use App\Entity\HeureSoirs;
use App\Entity\Jours;
use App\Form\HeureMatinsType;
use App\Form\admin\HeureSoirsType;
use App\Form\admin\JoursType;
use App\Repository\HeureMatinsRepository;
use App\Repository\HeureSoirsRepository;
use App\Repository\JoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class HoraireController extends AbstractController
{
    private $jour;

    public function __construct(JoursRepository $joursRepository)
    {
        $this->jour = $joursRepository->findAll();
    }

    #[Route('/admin/horaire', name: 'app_horaire')]
    public function index(JoursRepository $joursRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('horaire/index.html.twig', [
            'controller_name' => 'HoraireController',
            'jours' => $this->jour,
        ]);
    }
    
   

    #[Route('/admin/{id}/editMatin', name: 'app_horaire_editMatin', methods: ['GET', 'POST'])]
    public function editMatin(Request $request, HeureMatins $heureMatin, HeureMatinsRepository $heureMatinsRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(HeureMatinsType::class, $heureMatin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $heureMatinsRepository->save($heureMatin, true);

            return $this->redirectToRoute('app_horaire', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('horaire/heure_matins/edit.html.twig', [
            'heure_matin' => $heureMatin,
            'jours' => $this->jour,
            'form' => $form,
        ]);
    }

    

    #[Route('/admin/{id}/editSoir', name: 'app_horaire_editSoir', methods: ['GET', 'POST'])]
    public function editSoir(Request $request, HeureSoirs $heureSoir, HeureSoirsRepository $heureSoirsRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(HeureSoirsType::class, $heureSoir);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $heureSoirsRepository->save($heureSoir, true);

            return $this->redirectToRoute('app_horaire', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('horaire/heure_soirs/edit.html.twig', [
            'heure_soir' => $heureSoir,
            'jours' => $this->jour,
            'form' => $form,
        ]);
    }

    #[Route('/admin/{id}/editCapacite', name: 'app_horaire_editCapacite', methods: ['GET', 'POST'])]
    public function editCapacite(Request $request, Jours $jour, JoursRepository $joursRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(JoursType::class, $jour);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Les données du formulaire sont valides, mettez à jour la capacité du jour
            $joursRepository->save($jour, true);
    
            return $this->redirectToRoute('app_horaire', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('horaire/capacite_jour/edit.html.twig', [
            'jour' => $jour,
            'form' => $form->createView(),
            'jours' => $this->jour,
        ]);
    }
    

}



