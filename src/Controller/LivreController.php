<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/livres')]
class LivreController extends AbstractController
{
    #[Route('/', name: 'livres_index', methods: ['GET'])]
    public function index(LivreRepository $livreRepository): Response
    {
        $livres = $livreRepository->findAll();

        return $this->render('livre/index.html.twig', [
            'livres' => $livres,
        ]);
    }

    #[Route('/{id}', name: 'livres_show', methods: ['GET'])]
    public function show(Livre $livre): Response
    {
        return $this->render('livre/show.html.twig', [
            'livre' => $livre,
        ]);
    }

    #[Route('/{id}/reserve', name: 'livres_reserve', methods: ['POST'])]
    public function reserve(Livre $livre): Response
    {
        if (!$livre->isDisponibilite()) {
            $this->addFlash('danger', 'Ce livre est déjà emprunté.');
            return $this->redirectToRoute('livres_index');
        }

        $livre->setDisponibilite(false);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('success', 'Livre réservé avec succès !');
        return $this->redirectToRoute('livres_index');
    }
}