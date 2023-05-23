<?php

namespace App\Controller;

use App\Entity\TypePlats;
use App\Form\admin\TypePlatsType;
use App\Repository\TypePlatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\JoursRepository;
use App\Repository\PlatsRepository;

#[Route('/type/plats')]
class TypePlatsController extends AbstractController
{
    private $jour;

    public function __construct(JoursRepository $joursRepository)
    {
        $this->jour = $joursRepository->findAll();
    }
    #[Route('/new', name: 'app_type_plats_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TypePlatsRepository $typePlatsRepository): Response
    {
        $typePlat = new TypePlats();
        $form = $this->createForm(TypePlatsType::class, $typePlat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typePlatsRepository->save($typePlat, true);

            return $this->redirectToRoute('app_carte_indexedit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('carte/typeplat/new.html.twig', [
            'typeplats' => $typePlat,
            'jours' => $this->jour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_plats_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypePlats $typePlat, TypePlatsRepository $typePlatsRepository): Response
    {
        $form = $this->createForm(TypePlatsType::class, $typePlat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typePlatsRepository->save($typePlat, true);

            return $this->redirectToRoute('app_carte_indexedit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('carte/typeplat/edit.html.twig', [
            'typeplat' => $typePlat,
            'jours' => $this->jour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_plats_delete', methods: ['POST'])]
    public function delete(Request $request, TypePlats $typePlat, TypePlatsRepository $typePlatsRepository, PlatsRepository $platsRepository): Response
    {
        $typeplatName = $typePlat->getName();
        if ($this->isCsrfTokenValid('delete'.$typePlat->getId(), $request->request->get('_token'))) {
            $plats = $platsRepository->findByType($typePlat);            
            foreach ($plats as $plat) {
                $platsRepository->remove($plat);
            }
            
            $typePlatsRepository->remove($typePlat, true);
        }

        return $this->redirectToRoute('app_carte_indexedit', [], Response::HTTP_SEE_OTHER);
    }

}
