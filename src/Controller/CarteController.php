<?php
namespace App\Controller;

use App\Repository\MenusRepository;
use App\Repository\PlatsRepository;
use App\Repository\TypePlatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\JoursRepository;

class CarteController extends AbstractController
{
    private $jour;

    public function __construct(JoursRepository $joursRepository)
    {
        $this->jour = $joursRepository->findAll();
    }
    #[Route('/carte', name: 'app_carte')]
    public function index(PlatsRepository $platsRepository, TypePlatsRepository $typePlatsRepository, MenusRepository $menusRepository): Response
    {  
        $plats = $platsRepository->findBy([], ['type' => 'ASC']);
        $typeplats = $typePlatsRepository->findAll();
    
        return $this->render('carte/index.html.twig', [
            'controller_name' => 'CarteController',
            'plats' => $plats,
            'typeplats' => $typeplats,
            'menuses' => $menusRepository->findAll(),
            'jours' => $this->jour,
        ]);
    }
    

    #[Route('/admin/carte/edit', name: 'app_carte_indexedit')]
    public function indexedit(PlatsRepository $platsRepository, TypePlatsRepository $typePlatsRepository, MenusRepository $menusRepository): Response
    {  
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $plats = $platsRepository->findBy([], ['type' => 'ASC']);
        
        $typeplats = $typePlatsRepository->findAll();
    
        return $this->render('carte/indexedit.html.twig', [
            'controller_name' => 'CarteController',
            'plats' => $plats,
            'typeplats' => $typeplats,
            'menuses' => $menusRepository->findAll(),
            'jours' => $this->jour,
        ]);
    }
}


