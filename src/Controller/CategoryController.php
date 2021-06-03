<?php

namespace App\Controller;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categories", name="categories")
     */
    public function index(CategoryRepository $repository): Response
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repository->findAll();
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories'=>$categories
        ]);
    }

       /**
     * @Route("/addCategory/{cat}", name="addCategory")
     */
    public function createCategory($cat, ValidatorInterface $validator): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $categ = new Category();
        $categ->setNom($cat);

        $errors = $validator->validate($categ);
        if (count($errors) > 0) return new Response((string) $errors, 400);

        $entityManager->persist($categ);
        $entityManager->flush();

        return new Response('Added new category with id '.$categ ->getId());

    }
        /**
     * @Route("/updateCategory/{id}/{titre}", name="updateCategory")
     */
    public function updateCategorie($id, $titre, CategoryRepository $categoryRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $categ = $categoryRepository->find($id);

        if (!$categ) {
            throw $this->createNotFoundException("category not found");
        }

        $categ->setNom($titre);

        $entityManager->persist($categ);

        $entityManager->flush();

        return new Response('Updated category with id ' . $categ->getId());

    }

    /**
     * @Route("/deleteCategory/{id}", name="deleteCategory")
     */
    public function deleteCategorie($id, CategoryRepository $categoryRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $categ = $categoryRepository->find($id);

        if (!$categ) {
            throw $this->createNotFoundException("category not found");
        }

        $entityManager->remove($categ);

        $entityManager->flush();

        return new Response('deleted category successfully');

    }

    /**
     * @Route("/showCategory/{id}", name="showCategory")
     */
    public function showCategory($id, CategoryRepository $categoryRepository): Response
    {

        $categ = $categoryRepository->find($id);

        if (!$categ) {
            throw $this->createNotFoundException("category not found");
        }

        return $this->render('category/categoryDetails.html.twig', [
            'category' => $categ,
        ]);

    }

}

