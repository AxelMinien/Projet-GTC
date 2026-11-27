<?php

namespace App\Controller;

use App\Repository\ProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AcceuilleController extends AbstractController
{
    #[Route('/acceuille', name: 'app_acceuille')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(ProjetRepository $repo): Response
    {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN')) {
            $projets = $repo->findAll();
        } else {
            $projets = [];
            foreach ($user->getEstMembres() as $membre) {
                $projet = $membre->getProjet();
                if ($projet !== null) {
                    $projets[$projet->getId()] = $projet; // évite les doublons
                }
            }
            $projets = array_values($projets);
        }

        return $this->render('acceuille/index.html.twig', [
            'projets' => $projets,
            'utilisateur' => $user,
        ]);
    }
}