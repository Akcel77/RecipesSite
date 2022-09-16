<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{
    private $entityManager;

    /**
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * This function display all ingredients
     *
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'ingredient', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {

        $ingredients = $paginator->paginate(
            $this->entityManager->getRepository(Ingredient::class)->findAll(),  /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );


        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,

        ]);
    }

    #[Route('/ingredient/nouveau', name: 'ingredient.new', methods: ['GET', 'POST'])]
    public function new(Request $request) : Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $ingredient = $form->getData();

            $this->entityManager->persist($ingredient);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre ingredient a été ajouté à la liste.');

            return $this->redirectToRoute('ingredient');
        }

        return $this->render('ingredient/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/ingredient/edit/{id}', name: 'ingredient.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,Ingredient $ingredient) : Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $ingredient = $form->getData();

            $this->entityManager->persist($ingredient);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre ingredient a bien été modifié.');

            return $this->redirectToRoute('ingredient');
        }

        return $this->render('ingredient/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/ingredient/delete/{id}', name: 'ingredient.delete', methods: [ 'GET','POST'])]
    public function delete(Ingredient $ingredient) : Response
    {

        if (!$ingredient){
            $this->addFlash('success', 'Votre ingredient n\'a pas été trouvé.');
            return $this->redirectToRoute('ingredient');
        }
        $this->entityManager->remove($ingredient);
        $this->entityManager->flush();
        $this->addFlash('success', 'Votre ingredient a bien été supprimé.');

        return $this->redirectToRoute('ingredient');

    }
}
