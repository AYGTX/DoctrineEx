<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    /**
     * @Route("/users", name="users")
     */
    public function getAllUsers(UserRepository $repository): Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users
        ]);
    }
    /**
     * @Route("/add_user/{mail}", name="add_user")
     */
    public function createUser($mail, ValidatorInterface $validator): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setEmail($mail);

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return new Response('Added new user with id ' . $user->getId());
    }
    /**
     * @Route("/update_user/{id}/{mail}", name="update_user")
     */
    public function updateUser($id, $mail, UserRepository $userRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException("user not found");
        }
        $user->setEmail($mail);
        $entityManager->persist($user);
        $entityManager->flush();
        return new Response('Updated user mail with id ' . $user->getId());
    }
        /**
     * @Route("/delete_user/{id}", name="delete_user")
     */
    public function deleteUser($id, UserRepository $userRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException("user not found");
        }
        $entityManager->remove($user);
        $entityManager->flush();

        return new Response('deleted user with successfully');

    }

    /**
     * @Route("/show_user/{id}", name="show_user")
     */
    public function showUser($id, UserRepository $userRepository): Response
    {

        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException("user not found");
        }

        return $this->render('User/userdetails.html.twig', [
            'user' => $user,
        ]);

    }
}
