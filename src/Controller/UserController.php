<?php

namespace App\Controller;

use App\Form\UserSearchType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function list(Request $request, UserRepository $userRepository)
    {
        $formUser = $this->createForm(UserSearchType::class);
        $formUser->handleRequest($request);

        $users = [];

        if ($formUser->isSubmitted() && $formUser->isValid()) {
            $query = $formUser->get('search')->getData();
            $users = $userRepository->searchByName($query);
            // Si un seul résultat : rediriger vers le profil
            if (count($users) === 1) {
                return $this->redirectToRoute('profil_index', [
                    'id' => $users[0]->getId(),
                ]);
            }
        } else {
            $users = $userRepository->findAll();
        }

        return $this->render('user/list.html.twig', [
            'form' => $formUser->createView(),
            'users' => $users,
        ]);
    }
}
