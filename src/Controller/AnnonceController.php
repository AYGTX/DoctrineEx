<?php

namespace App\Controller;
use App\Entity\Annonce;
use App\Entity\Category;
use App\Repository\AnnonceRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonce", name="annonce")
     */
    public function index(): Response
    {

        return $this->render('annonce/index.html.twig', [
            'controller_name' => 'AnnonceController',
        ]);
    }
        /**
     * @Route("/annonces", name="annonces")
     */
    public function getAllAnnounces(AnnonceRepository $repository): Response
    {
        $repository = $this->getDoctrine()->getRepository(Annonce::class);
        $annonces = $repository->findBy(array(),array('createdAt' => 'desc'));
        return $this->render('annonce/index.html.twig', [
            'controller_name' => 'AnnonceController',
            'annonces'=>$annonces
        ]);
    }
    /**
     * @Route("/add_annonce/{id_user}/{id_categorie}", name="add_annonce")
     */
    public function createAnnonce($id_user, $id_categorie, ValidatorInterface $validator, UserRepository $userRepository, CategoryRepository $categorieRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $userRepository->find($id_user);
        $categorie = $categorieRepository->find($id_categorie);

        $annonce = new Annonce();
        $annonce->setTitre('New Annonce');
        $annonce->setDescription('Description...');
        $annonce->setUser($user);
        $annonce->setCategory($categorie);

        $errors = $validator->validate($annonce);
        if (count($errors) > 0) return new Response((string) $errors, 400);

        $entityManager->persist($annonce);
        $entityManager->flush();

        return new Response('Added new announcement with id '.$annonce ->getId());

    }

    /**
     * @Route("/update_annonce/{id}", name="update_annonce")
     */
    public function updateAnnonce($id, AnnonceRepository $annonceRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $annonce = $annonceRepository->find($id);

        if (!$annonce) {
            throw $this->createNotFoundException("annonce not found");
        }

        $annonce->setDescription('annonce modified');
        $annonce->setUpdatedAt(new \DateTime());

        $entityManager->persist($annonce);

        $entityManager->flush();

        return new Response('Updated annonce with id ' . $annonce->getId());

    }

    /**
     * @Route("/delete_annonce/{id}", name="delete_annonce")
     */
    public function deleteAnnonce($id, AnnonceRepository $annonceRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $annonce = $annonceRepository->find($id);

        if (!$annonce) {
            throw $this->createNotFoundException("annonce not found");
        }

        $entityManager->remove($annonce);

        $entityManager->flush();

        return new Response('deleted annonce with id ' . $annonce->getId());

    }

    /**
     * @Route("/show_annonce/{id}", name="show_annonce")
     */
    public function showAnnonce($id, AnnonceRepository $annonceRepository): Response
    {

        $annonce = $annonceRepository->find($id);

        if (!$annonce) {
            throw $this->createNotFoundException("annonce not found");
        }

        return $this->render('annonce/annonce.html.twig', [
            'annonce' => $annonce,
        ]);

    }

    /**
     * @Route("/search_annonce/{term}", name="search_annonce")
     */
    public function searchComment($term, AnnonceRepository $annonceRepository): Response
    {
        return $this->render('annonce/search.html.twig', [
            'search_term' => $term,
            'annonces' => $annonceRepository->search($term)
        ]);
    }

}
