<?php

namespace App\Controller;

use App\Entity\Images;
use App\Form\admin\ImagesType;
use App\Repository\ImagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\JoursRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;



#[Route('/admin/images')]
class ImagesController extends AbstractController
{
    private $jour;

    public function __construct(JoursRepository $joursRepository)
    {
        $this->jour = $joursRepository->findAll();
    }
    
    #[Route('/', name: 'app_images_index', methods: ['GET'])]

    public function index(ImagesRepository $imagesRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('images/index.html.twig', [
            'images' => $imagesRepository->findAll(),
            'jours' => $this->jour,
        ]);
    }

    #[Route('/new', name: 'app_images_new', methods: ['GET', 'POST'])]

    public function new(Request $request, ImagesRepository $imagesRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $image = new Images();
        $form = $this->createForm(ImagesType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer le téléchargement du fichier
            $file = $form->get('images')->getData();
            $fileName = uniqid().'.'.$file->guessExtension();
            $fileSize = $file->getSize(); 

            try {
                $file->move($this->getParameter('images_directory'), $fileName);
            } catch (FileException $e) {
                // Gérer les erreurs de téléchargement du fichier
                // ...

                return $this->redirectToRoute('image_new');
            }

            // Enregistrer les autres données de l'image
            $image->setPath($fileName);
            $image->setSize($fileSize);

            $imagesRepository->save($image, true);

            return $this->redirectToRoute('app_images_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('images/new.html.twig', [
            'form' => $form->createView(),
            'jours' => $this->jour,
        ]);
    }/*

        public function new(Request $request, ImagesRepository $imagesRepository): Response
        {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
            $image = new Images();
            $form = $this->createForm(ImagesType::class, $image);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Gérer le téléchargement du fichier
                $file = $form->get('images')->getData();

                try {
                    // Vérifier si le fichier est bien téléchargé
                    if ($file === null) {
                        throw new \Exception("Aucun fichier n'a été téléchargé.");
                    }

                    // Vérifier les erreurs de téléchargement du fichier
                    if ($file->getError() !== UPLOAD_ERR_OK) {
                        throw new FileException('Une erreur s\'est produite lors du téléchargement du fichier.');
                    }

                    // Générer un nom de fichier unique
                    $fileName = uniqid() . '.' . $file->guessExtension();

                    // Déplacer le fichier vers le répertoire cible
                    $file->move($this->getParameter('images_directory'), $fileName);

                    // Mettre à jour l'objet Images avec les informations du formulaire
                    $image->setPath($fileName);
                    $image->setSize($file->getSize());

                    // Enregistrer la description de l'image
                    $description = $form->get('description')->getData();
                    $image->setDescription($description);

                    // Utiliser le repository pour enregistrer l'image dans la base de données
                    $imagesRepository->save($image);

                } catch (FileException $e) {
                    // Gérer les erreurs de téléchargement du fichier
                    return $this->render('images/new.html.twig', [
                        'form' => $form->createView(),
                        'jours' => $this->jour,
                        'error_message' => 'Une erreur s\'est produite lors du téléchargement du fichier.',
                    ]);
                } catch (\Exception $e) {
                    // Gérer les autres erreurs
                    return $this->render('images/new.html.twig', [
                        'form' => $form->createView(),
                        'jours' => $this->jour,
                        'error_message' => $e->getMessage(),
                    ]);
                }

                return $this->redirectToRoute('app_images_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('images/new.html.twig', [
                'form' => $form->createView(),
                'jours' => $this->jour,
            ]);
        }*/



    #[Route('/{id}/edit', name: 'app_images_edit', methods: ['GET', 'POST'])]
    
    public function edit(Request $request, Images $image, ImagesRepository $imagesRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(ImagesType::class, $image);
        $form->handleRequest($request);

        

        if ($form->isSubmitted() && $form->isValid()) {
            $imagesRepository->save($image, true);

            return $this->redirectToRoute('app_images_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('images/edit.html.twig', [
            'image' => $image,
            'jours' => $this->jour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_images_delete', methods: ['POST'])]
 
    public function delete(Request $request, Images $image, ImagesRepository $imagesRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            // Supprimer le fichier associé à l'image
            $filePath = $this->getParameter('images_directory').'/'.$image->getPath();
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $imagesRepository->remove($image, true);
        }

        return $this->redirectToRoute('app_images_index', [], Response::HTTP_SEE_OTHER);
    }
}
