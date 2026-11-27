<?php
namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(
        Request $request,
        UtilisateurRepository $repo,
        EntityManagerInterface $em,
        Security $security
    ): Response {
        $utilisateur = null;
        $erreur = null;
        $email = null;

        if ($request->isMethod('POST')) {
            $email = trim((string) $request->request->get('email'));
            $motDePasse = (string) $request->request->get('password');

            $user = $repo->findOneBy(['email' => $email]);

            if (!$user) {
                $erreur = "Cet utilisateur n'existe pas.";
            } else {
                $stocke = (string) $user->getPassword();

                if (password_get_info($stocke)['algo'] === null) {
                    if ($stocke !== '' && hash_equals($stocke, $motDePasse)) {
                        $user->setMotDePasse(password_hash($motDePasse, PASSWORD_DEFAULT));
                        $em->flush();

                        $security->login($user);
                        return $this->redirectToRoute('app_acceuille');
                    } else {
                        $erreur = "Mot de passe incorrect.";
                    }
                } elseif (password_verify($motDePasse, $stocke)) {
                    $security->login($user);
                    return $this->redirectToRoute('app_acceuille');
                } else {
                    $erreur = "Mot de passe incorrect.";
                }
            }
        }

        return $this->render('login/index.html.twig', [
            'utilisateur' => $utilisateur,
            'erreur' => $erreur,
            'email' => $email,
        ]);
    }
}
