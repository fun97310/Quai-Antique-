<?php
namespace App\Controller;

use App\Repository\JoursRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminReservationController extends AbstractController
{
    private $jour;

    public function __construct(JoursRepository $joursRepository)
    {
        $this->jour = $joursRepository->findAll();
    }

    #[Route('/admin/reservation', name: 'app_admin_reservation', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Accès refusé');
        }

        $reservations = $reservationRepository->findBy([], ['date' => 'ASC']);
        $reservationsByDate = [];
        foreach ($reservations as $reservation) {
            $date = $reservation->getDate()->format('Y-m-d');
            if (!isset($reservationsByDate[$date])) {
                $reservationsByDate[$date] = [];
            }
            $reservationsByDate[$date][] = $reservation;
        }

        return $this->render('admin_reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
            'reservationsByDate' => $reservationsByDate,
            'jours' => $this->jour,
        ]);
    }
}

