<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function index(Request $request, UtilisateurRepository $repo, EntityManagerInterface $em): Response
    {
        $erreur = null;
        $nom = null;
        $prenom = null;
        $email = null;

        if ($request->isMethod('POST')) {
            $nom = trim((string) $request->request->get('nom'));
            $prenom = trim((string) $request->request->get('prenom'));
            $email = trim((string) $request->request->get('email'));
            $motDePasse = (string) $request->request->get('password');
            $confirmation = (string) $request->request->get('password_confirm');

            if ($nom === '' || $prenom === '' || $email === '' || $motDePasse === '') {
                $erreur = "Tous les champs sont obligatoires.";
            } elseif (strlen($motDePasse) < 6) {
                $erreur = "Le mot de passe doit faire au moins 6 caractères.";
            } elseif ($motDePasse !== $confirmation) {
                $erreur = "Les mots de passe ne sont pas identiques.";
            } elseif ($repo->findOneBy(['email' => $email])) {
                $erreur = "Cet email est déjà utilisé.";
            } else {
                $user = new Utilisateur();
                $user->setNom($nom);
                $user->setPrenom($prenom);
                $user->setEmail($email);
                $user->setRole('ROLE_USER');
                $user->setMotDePasse(password_hash($motDePasse, PASSWORD_DEFAULT));

                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'Compte créé, vous pouvez vous connecter.');
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('inscription/index.html.twig', [
            'erreur' => $erreur,
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
        ]);
    }
}