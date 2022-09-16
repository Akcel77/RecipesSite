<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
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
     * This function display all Recipes
     *
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/recette', name: 'recipe', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {

        $recipes = $paginator->paginate(
            $this->entityManager->getRepository(Recipe::class)->findAll(),  /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );


        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes,

        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/recette/nouveau', name: 'recipe.new', methods: ['GET', 'POST'])]
    public function new(Request $request) : Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $recipe = $form->getData();

            $this->entityManager->persist($recipe);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre recette a été ajoutée à la liste.');

            return $this->redirectToRoute('recipe');
        }

        return $this->render('recipe/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/recette/edit/{id}', name: 'recipe.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,Recipe $recipe) : Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $recipe = $form->getData();

            $this->entityManager->persist($recipe);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre recette a bien été modifiée.');

            return $this->redirectToRoute('recipe');
        }

        return $this->render('recipe/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/recette/delete/{id}', name: 'recipe.delete', methods: [ 'GET','POST'])]
    public function delete(Recipe $recipe) : Response
    {
        if (!$recipe){
            $this->addFlash('success', 'Votre recette n\'a pas été trouvée.');
            return $this->redirectToRoute('recipe');
        }
        $this->entityManager->remove($recipe);
        $this->entityManager->flush();
        $this->addFlash('success', 'Votre recette a bien été supprimée.');

        return $this->redirectToRoute('recipe');

    }
}
