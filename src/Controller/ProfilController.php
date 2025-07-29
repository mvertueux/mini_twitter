<?php

namespace App\Controller;

use App\Form\ProfilType;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Repository\LikeRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ProfilController extends AbstractController
{

    #[Route('/profil', name: 'app_profil')]
    #[IsGranted('ROLE_USER')]
    public function index(CommentaireRepository $commentaireRepository, LikeRepository $likeRepository, Request $request, EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        // Création du formulaire d'avatar
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatarFile = $form->get('avatar')->getData();

            if ($avatarFile) {
                $newFilename = uniqid() . '.' . $avatarFile->guessExtension();
                try {
                    $avatarFile->move(
                        $this->getParameter('avatars_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', "Erreur lors de l'envoi du fichier.");
                }
                $user->setAvatar($newFilename);
            }
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', "Photo de profil mise à jour !");
            return $this->redirectToRoute('app_profil');
        }
        $tweets = $user->getTweets();
        $likeTweets = $likeRepository->findTweetsLikedByUser($user);
        $commentTweets = $commentaireRepository->findCommentsMakeByUser($user);
        return $this->render('profil/index.html.twig', [
            "user" => $user,
            $user = $this->getUser(),
            "tweets" => $tweets,
            'likeTweets' => $likeTweets,
            "form" => $form->createView(),
            'commentTweets' => $commentTweets,
        ]);
    }

    // #[Route('/profil/modifier', name: 'app_profil_update', methods: ["POST"])]
    // public function updateProfil(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $user = $this->getUser();
    //     $username = $request->request->get("username");
    //     $description = $request->request->get("description");
    //     if ($username && $description) {
    //         $user->setUsername($username);
    //         $user->setDescription($description);
    //         $entityManager->persist($user);
    //         $entityManager->flush();
    //     }
    //     return $this->redirectToRoute('app_profil');
    // }
}
