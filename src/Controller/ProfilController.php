<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Repository\LikeRepository;
use App\Repository\TweetRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ProfilController extends AbstractController
{
    #[Route('/user/{id}/delete-banniere', name: 'user_delete_banniere', methods: ['POST'])]
    public function deleteBanierre(User $user, EntityManagerInterface $em, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete_banniere' . $user->getId(), $request->request->get('_token'))) {
            $bannerPath = $this->getParameter('banners_directory') . '/' . $user->getProfilBanierre();
            if (file_exists($bannerPath)) {
                @unlink($bannerPath);
            }
            $user->setProfilBanierre(null);
            $em->flush();
            $this->addFlash('success', 'Bannière supprimée !');
        }
        return $this->redirectToRoute('app_profil', ['id' => $user->getId()]);
    }

    #[Route('/profil', name: 'app_profil')]
    #[IsGranted('ROLE_USER')]
    public function index(TweetRepository $tweetRepository, CommentaireRepository $commentaireRepository, LikeRepository $likeRepository, Request $request, EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        // Création du formulaire d'avatar
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatarFile = $form->get('avatar')->getData();
            $bannerFile = $form->get('profilBanierre')->getData();

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
            if ($bannerFile) {
                $newFilename = uniqid() . '.' . $bannerFile->guessExtension();
                try {
                    $bannerFile->move(
                        $this->getParameter('banners_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', "Erreur lors de l'envoi du fichier.");
                }
                $user->setProfilBanierre($newFilename);
            }
            if ($this->isCsrfTokenValid('delete_banniere' . $user->getId(), $request->request->get('_token'))) {
            $bannerPath = $this->getParameter('banners_directory') . '/' . $user->getProfilBanierre();
            if (file_exists($bannerPath)) {
                @unlink($bannerPath);
            }
            $user->setProfilBanierre(null);
            $this->addFlash('success', 'Bannière supprimée !');
            }
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', "Profil mise à jour !");
            return $this->redirectToRoute('app_profil');
        }

        $tweets = $tweetRepository->findByUserOrderedByIdDesc($user);
        $likeTweets = $likeRepository->findTweetsLikedByUser($user);
        $commentTweets = $commentaireRepository->findByUserOrderedByIdDesc($user);
        return $this->render('profil/index.html.twig', [
            "user" => $user,
            "tweets" => $tweets,
            'likeTweets' => $likeTweets,
            "form" => $form->createView(),
            'commentTweets' => $commentTweets,
        ]);

        // // Commentaires par tweet par utilisateur
        // $commentaires = $user->getComment();
        // $commentTweets = $commentaireRepository->findByUserOrderedByIdDesc($user);
        // return $this->render('profil/index.html.twig', [
        //     "user" => $user,
        //     $user = $this->getUser(),
        //     "commentaires" => $commentaires,
        //     'commentTweets' => $commentTweets,
        //     "form" => $form->createView(),
        // ]);
    }
}
