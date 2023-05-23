<?php

namespace App\Controller;

use App\Entity\Formules;
use App\Form\admin\FormulesType;
use App\Repository\FormulesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\JoursRepository;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/admin/formules')]
class FormulesController extends AbstractController
{
    private $jour;

    public function __construct(JoursRepository $joursRepository)
    {
        $this->jour = $joursRepository->findAll();
    }
    #[Route('/', name: 'app_formules_index', methods: ['GET'])]
    public function index(FormulesRepository $formulesRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('carte/formules/index.html.twig', [
            'formules' => $formulesRepository->findAll(),
            'jours' => $this->jour,
        ]);
    }

    #[Route('/new', name: 'app_formules_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FormulesRepository $formulesRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $formule = new Formules();
        $form = $this->createForm(FormulesType::class, $formule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formulesRepository->save($formule, true);

            return $this->redirectToRoute('app_formules_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('carte/formules/new.html.twig', [
            'formule' => $formule,
            'jours' => $this->jour,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_formules_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Formules $formule, FormulesRepository $formulesRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(FormulesType::class, $formule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formulesRepository->save($formule, true);

            return $this->redirectToRoute('app_formules_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('carte/formules/edit.html.twig', [
            'jours' => $this->jour,
            'formule' => $formule,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_formules_delete', methods: ['POST'])]
    public function delete(Request $request, Formules $formule, FormulesRepository $formulesRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->isCsrfTokenValid('delete'.$formule->getId(), $request->request->get('_token'))) {
            $formulesRepository->remove($formule, true);
        }

        return $this->redirectToRoute('app_formules_index', [], Response::HTTP_SEE_OTHER);
    }
}
