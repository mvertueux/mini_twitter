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
        $form = $this->createForm(UserSearchType::class);
        $form->handleRequest($request);

        $users = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $query = $form->get('search')->getData();
            $users = $userRepository->searchByName($query);
        } else {
            $users = $userRepository->findAll();
        }

        return $this->render('user/list.html.twig', [
            'form' => $form->createView(),
            'users' => $users,
        ]);
    }
}
