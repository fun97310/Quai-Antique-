<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\user\ReservationType;
use App\Repository\JoursRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    private $jour;

    public function __construct(JoursRepository $joursRepository)
    {
        $this->jour = $joursRepository->findAll();
    }
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository, JoursRepository $joursRepository): Response
    {
        return $this->render('admin_reservation/reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
            'jours' => $this->jour,
        ]);
    }

    #[Route('/disponibilite', name: 'disponibilite', methods: ['GET'], format: 'json')]
    public function disponibilite(JoursRepository $joursRepository, ReservationRepository $reservationRepository): JsonResponse
    {
        $datajour = $joursRepository->findAll();
        $reservations = $reservationRepository->findAll();

        $reservationData = []; // Correction : Initialisation de $reservationData

        foreach ($reservations as $object) {
            $reservationData[] = [
                'nmbr_couvert' => $object->getNmbrCouvert(),
                'date' => $object->getDate(),
            ];
        }

        $response = [
            'Reservations' => $reservationData,
            'jours' => [],
        ];

        foreach ($datajour as $jour) {
            $heureOuvertureMatin = $jour->getHMatin()->getHOuverture();
            $heureOuvertureMatinFormatted = $heureOuvertureMatin->format('H:i');
            $heureFermetureMatin = $jour->getHMatin()->getHFermeture();
            $heureFermetureMatinFormatted = $heureFermetureMatin->format('H:i');

            $heureOuvertureSoir = $jour->getHSoir()->getHOuverture();
            $heureOuvertureSoirFormatted = $heureOuvertureSoir->format('H:i');
            $heureFermetureSoir = $jour->getHSoir()->getHFermeture();
            $heureFermetureSoirFormatted = $heureFermetureSoir->format('H:i');

            $response['jours'][] = [
                'jour' => $jour->getJour(),
                'heure_ouverture_matin' => $heureOuvertureMatinFormatted,
                'heure_fermeture_matin' => $heureFermetureMatinFormatted,
                'heure_ouverture_soir' => $heureOuvertureSoirFormatted,
                'heure_fermeture_soir' => $heureFermetureSoirFormatted,
                'hmatin' => [
                    'isclose' => $jour->getHMatin()->isIsClose(),
                ],
                'hsoir' => [
                    'isclose' => $jour->getHSoir()->isIsClose(),
                ],
                'capacite' => $jour->getCapacite(),
            ];
        }

        return new JsonResponse($response);
    }

    //all user
    
    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationRepository $reservationRepository, JoursRepository $joursRepository): Response
    {
        // Crée une nouvelle instance de l'entité Reservation
        $reservation = new Reservation();

        // Crée un formulaire pour la réservation en utilisant ReservationType (défini ailleurs dans le code)
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère le nombre de couverts et la date depuis le formulaire
            $nmbrCouvert = $form->get('nmbr_couvert')->getData();
            $date = $reservation->getDate();
            $jourSemaine = $date->format('N');

            // Utilise le repository JoursRepository pour récupérer l'objet Jours correspondant
            $jour = $joursRepository->find($jourSemaine);

            // Vérifie que l'objet Jours est trouvé
            if ($jour) {
                // Récupère la capacité maximale pour ce jour
                $capaciteMax = $jour->getCapacite();

                // Récupère toutes les réservations pour cette date
                $reservations = $reservationRepository->findBy(['date' => $date]);

                // Calcule la capacité disponible pour cette date en soustrayant le nombre de couverts des réservations existantes
                foreach ($reservations as $existingReservation) {
                    $nmbrcouvert = $existingReservation->getNmbrCouvert();
                    $capaciteMax -= $nmbrcouvert;
                }

                // Soustrait également le nombre de couverts de la réservation actuelle
                $capaciteMax -= $nmbrCouvert;

                // Vérifie si la capacité maximale disponible est suffisante pour la réservation
                if ($capaciteMax >= 0) {
                    // Si oui, met à jour l'heure de la réservation et la sauvegarde en base de données
                    $heure = $form->get('heure')->getData();
                    $reservation->setHeure($heure);
                    $reservationRepository->save($reservation, true);

                    // Redirige l'utilisateur vers la page de détails de la réservation nouvellement créée
                    return $this->redirectToRoute('app_reservation_show', [
                        'id' => $reservation->getId(),
                    ], Response::HTTP_SEE_OTHER);
                } else {
                    // Si la capacité maximale n'est pas suffisante, affiche une page d'erreur
                    $complet = $this->renderView('admin_reservation/reservation/complete.html.twig',[
                        'jours' => $this->jour,
                    ]);
                    return new Response($complet);
                }
            }
        }

        // Si le formulaire n'est pas soumis ou n'est pas valide, affiche le formulaire de réservation
        return $this->render('admin_reservation/reservation/new.html.twig', [
            'reservation' => $reservation,
            'jours' => $this->jour,
            'form' => $form->createView(),
        ]);
    }




    
   
    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation, JoursRepository $joursRepository): Response
    {
        return $this->render('admin_reservation/reservation/show.html.twig', [
            'jours'=> $joursRepository->findAll(),
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository, JoursRepository $joursRepository): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->save($reservation, true);

            return $this->redirectToRoute('app_admin_reservation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_reservation/reservation/edit.html.twig', [
            'reservation' => $reservation,
            'jours' => $this->jour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $reservationRepository->remove($reservation, true);
        }
        
        return $this->redirectToRoute('app_admin_reservation', [], Response::HTTP_SEE_OTHER);

   /*     if ($role === 'ROLE_ADMIN') {
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        } elseif ($role === 'ROLE_USER') {
            return $this->redirectToRoute('app_acceuil', [], Response::HTTP_SEE_OTHER);
        } else {
            // Redirection par défaut vers la page d'accueil
            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }*/
    }


}

   /* public function new(Request $request, ReservationRepository $reservationRepository, JoursRepository $joursRepository, AuthorizationCheckerInterface $authorizationChecker, UsersRepository $usersRepository): Response
    {
        $reservation = new Reservation();
        if ($authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            // L'utilisateur est connecté, récupérer ses informations
            $user = $usersRepository->find($this->getUser());
            $form = $this->createForm(ReservationType::class, $reservation, [
                'user' => $user,
            ]);
        } else {

            $form = $this->createForm(ReservationType::class, $reservation);
        }
        
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $nmbrCouvert = $form->get('nmbr_couvert')->getData();
            $date = $reservation->getDate();
            $jourSemaine = $date->format('N');

            // Utilize your repository or data access method to retrieve the corresponding Jours object
            $jour = $joursRepository->find( $jourSemaine);

            if ($jour) {
                $capaciteMax = $jour->getCapacite();
                $reservations = $reservationRepository->findBy(['date' => $date]);

                foreach ($reservations as $reservation) {
                    // Access the properties of each Reservation instance
                    $nmbrcouvert = $reservation->getNmbrCouvert();
                    $capaciteMax -= $nmbrcouvert;
                }

                $capaciteMax -= $nmbrCouvert;

                if ($capaciteMax >= 0) {
                    $heure = $form->get('heure')->getData();
                    $reservation->setHeure($heure);
                    $reservationRepository->save($reservation, true);

                    return $this->redirectToRoute('app_reservation_show', [
                        'id' => $reservation->getId(),
                    ], Response::HTTP_SEE_OTHER);
                } else {
                    $complet = $this->renderView('admin_reservation/reservation/complete.html.twig');
                    return new Response($complet);
                }
            }
        }

        return $this->render('admin_reservation/reservation/new.html.twig', [
            'reservation' => $reservation,
            'jours' => $this->jour,
            'form' => $form,
        ]);
    }*/
