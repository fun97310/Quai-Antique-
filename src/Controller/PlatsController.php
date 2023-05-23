<?php

namespace App\Controller;

use App\Entity\Plats;
use App\Form\admin\PlatsType;
use App\Repository\PlatsRepository;
use App\Repository\TypePlatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\JoursRepository;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/plats')]
class PlatsController extends AbstractController
{
    private $jour;

    public function __construct(JoursRepository $joursRepository)
    {
        $this->jour = $joursRepository->findAll();
    }
    #[Route('/', name: 'app_plats_index', methods: ['GET'])]
    public function index(PlatsRepository $platsRepository, TypePlatsRepository $typePlatsRepository): Response
    {   

        $plats = $platsRepository->findBy([], ['type' => 'ASC']);
        $typeplats = $typePlatsRepository->findAll();

        return $this->render('carte/plats/index.html.twig', [
            'plats' => $plats,
            'jours' => $this->jour,
            'typeplats' => $typeplats
        ]);


    }

    #[Route('/new', name: 'app_plats_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlatsRepository $platsRepository): Response
    {
        $plat = new Plats();
        $form = $this->createForm(PlatsType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $platsRepository->save($plat, true);

            return $this->redirectToRoute('app_carte_indexedit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('carte/plats/new.html.twig', [
            'jours' => $this->jour,
            'plat' => $plat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_plats_show', methods: ['GET'])]
    public function show(Plats $plat): Response
    {
        return $this->render('carte/plats/show.html.twig', [
            'jours' => $this->jour,
            'plat' => $plat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_plats_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Plats $plat, PlatsRepository $platsRepository): Response
    {
        $form = $this->createForm(PlatsType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $platsRepository->save($plat, true);

            return $this->redirectToRoute('app_carte_indexedit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('carte/plats/edit.html.twig', [
            'jours' => $this->jour,
            'plat' => $plat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_plats_delete', methods: ['POST'])]
    public function delete(Request $request, Plats $plat, PlatsRepository $platsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plat->getId(), $request->request->get('_token'))) {
            $platsRepository->remove($plat, true);
        }

        return $this->redirectToRoute('app_carte_indexedit', [], Response::HTTP_SEE_OTHER);
    }
}
