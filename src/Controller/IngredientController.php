<?php

namespace App\Controller;

use App\Form\IngredientFormTypeV2Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;


use App\Entity\Ingredient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Form\IngredientFormType;

class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'app_ingredient')]
    public function index(IngredientRepository $repository): Response
    {
        $ingredients = $repository->findAll();

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    //-------------------------------------------------------------------------------------------

    #[Route('/ingredient/sup100', name: 'app_ingredient_greater_than_100_v1')]
    public function index_only_greater_than_100(IngredientRepository $repository): Response
    {
        $ingredients = $repository->findAll();
        $ingredients100 = [];
        foreach ($ingredients as $ingredient) {
            if ($ingredient->getPrix() > 100) {
                $ingredients100[] = $ingredient;
            }
        }

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients100,
        ]);
    }

    #[Route('/ingredient/sup100v2', name: 'app_ingredient_greater_than_100_v2')]
    public function index_only_greater_than_100_v2(IngredientRepository $repository): Response
    {
        $ingredients = $repository->findAll();
        $collection_ingredients = new ArrayCollection($ingredients);

        $filteredCollection = $collection_ingredients->filter(static fn($element): bool => $element->getPrix() > 100);

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $filteredCollection,
        ]);
    }

    #[Route('/ingredient/sup100v3', name: 'app_ingredient_greater_than_100_v3')]
    public function index_only_greater_than_100_v3(IngredientRepository $repository): Response
    {
        $ingredients = $repository->findAll();
        $collection = new ArrayCollection($ingredients);

        $criteria = Criteria::create()->where(Criteria::expr()->gt("prix", 100));

        $ingredients100 = $collection->matching($criteria);

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients100,
        ]);
    }

    //---------------------------------------------------------------------------------------------

    #[Route('/ingredient/create', name: 'ingredient.create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {

        $ingredient = new Ingredient();


        $form = $this->createFormBuilder($ingredient)
            ->add('nom', TextType::class, [
                'label' => 'Nom de l’ingrédient',
                'attr' => ['class' => 'form-control']
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix (€)',
                'attr' => ['class' => 'form-control']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Créer',
                'attr' => ['class' => 'btn btn-primary mt-3']
            ])
            ->getForm();


        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($ingredient);
            $em->flush();


            return $this->redirectToRoute('app_ingredient');
        }

        return $this->render('ingredient/create.html.twig', [
            'formIngredient' => $form->createView(),
        ]);
    }

    #[Route('/ingredient/create_v2', name: 'ingredient_create_v2')]
    public function create_and_store_v2(Request $request, EntityManagerInterface $em): Response
    {

        $ingredient = new Ingredient();
        $crea_form = $this->createForm(IngredientFormType::class, $ingredient);


        $crea_form->handleRequest($request);

        if ($crea_form->isSubmitted() && $crea_form->isValid()) {

            $em->persist($ingredient);
            $em->flush();

            $this->addFlash('success', 'Votre ingrédient a bien été créé avec succès !');

            return $this->redirectToRoute('app_ingredient');
        }

        return $this->render('ingredient/create_v2.html.twig', [
            'formIngredient' => $crea_form->createView(),
        ]);
    }

    #[Route('/ingredient/create_store_v3', name: 'ingredient_create_store_v3')]
    public function create_and_store_v3(Request $request, EntityManagerInterface $em): Response
    {

        $ingredient = new Ingredient();
        $crea_form = $this->createForm(IngredientFormTypeV2Type::class, $ingredient);


        $crea_form->handleRequest($request);

        if ($crea_form->isSubmitted() && $crea_form->isValid()) {

            $em->persist($ingredient);
            $em->flush();

            $this->addFlash('success', 'Votre ingrédient a bien été créé avec succès !');

            return $this->redirectToRoute('app_ingredient');
        }

        return $this->render('ingredient/create_v3.html.twig', [
            'form' => $crea_form->createView(),
        ]);
    }


    //-------------------------------------------------------------------------------------------------------


    #[Route('/ingredient/edit/{id}', name: 'ingredient.edit', methods: ['GET', 'PUT'])]
    public function edit(
        int $id,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $ingredient = $em->getRepository(Ingredient::class)->find($id);

        if (!$ingredient) {
            throw $this->createNotFoundException("Ingrédient introuvable");
        }

        $form = $this->createForm(IngredientFormTypeV2Type::class, $ingredient, [
            'method' => 'PUT',
            'submit label' => 'Enregistrer les modifications',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Votre ingrédient a bien été modifié !');
            return $this->redirectToRoute('app_ingredient');
        }

        return $this->render('ingredient/create_v3.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('ingredient/{id}', name: 'ingredient.delete', methods: ['DELETE'])]
    public function remove(
        int $id,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $ingredient = $em->getRepository(Ingredient::class)->find($id);

        if (!$ingredient) {
            throw $this->createNotFoundException("Ingrédient introuvable");
        }

        $em->remove($ingredient);
        $em->flush();

        $this->addFlash('success', 'Votre ingrédient a bien été supprimé !');

        return $this->redirectToRoute('app_ingredient');
    }


    //-------------------------------------------------------------------------


    #[Route('ingredient/tomate', name: 'ingredient.tomate')]
    public function search_tomate(IngredientRepository $repository): Response
    {
        $ingredients = $repository->findIngredientTomate();

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('ingredient/tomate_5', name: 'ingredient.tomate_5')]
    public function search_tomate_5(IngredientRepository $repository): Response
    {
        $ingredients = $repository->findIngredientTomate5();

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('ingredient/byPrice/{price}', name: 'ingredient.by_price')]
    public function by_price(IngredientRepository $repository, int $price): Response
    {
        $price = $price / 100;

        $ingredients = $repository->findIngredientByPrice($price);

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('ingredient/byPrice/{price}/byName/{name}', name: 'ingredient.by_price&by_Name')]
    public function by_price_by_name(IngredientRepository $repository, string $price, string $name): Response
    {
        $price = $price / 100;

        $ingredients = $repository->findIngredientByPriceByName($price, $name);

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }
}
