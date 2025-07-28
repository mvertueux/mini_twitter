<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Repository\LikeRepository;
use Symfony\Component\HttpFoundation\Request;

final class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(LikeRepository $likeRepository): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $tweets = $user->getTweets();
        $likeTweets = $likeRepository->findTweetsLikedByUser($user);
        return $this->render('profil/index.html.twig', [
            "user" => $user,
            "tweets" => $tweets,
            'likeTweets' => $likeTweets,
        ]);
    }

    // public function showLikes(LikeRepository $likeRepository): Response
    // {
    //     $user = $this->getUser();
    //     $likeTweets = $likeRepository->findTweetsLikedByUser($user);
    //     return $this->render('profil/index.html.twig', [
    //         'likeTweets' => $likeTweets,
    //     ]);
    // }

    #[Route('/profil/modifier', name: 'app_profil_update', methods:["POST"])]
    public function updateProfil(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $username = $request->request->get("username");
        $description = $request->request->get("description");
        if ($username && $description) {
            $user->setUsername($username);
            $user->setDescription($description);
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_profil');
    }
}
