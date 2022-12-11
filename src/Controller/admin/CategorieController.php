<?php

namespace App\Controller\admin;

use App\Repository\ArticleRepository;
use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategorieRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{


    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route ("/admin/categories", name="admin_categories_list")
     */

    public function categories_list(CategorieRepository $categorieRepository)
    {
        $categories = $categorieRepository->findAll();
        return $this->render('admin/categorie/list.html.twig',[
            'categories' =>$categories

        ]);
    }



    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route ("/admin/categorie/{id}", name="admin_categorie_show")
     */

    public function categorie_show($id, CategorieRepository $categorieRepository)
    {
        $categorie = $categorieRepository->find($id);
        if (is_null($categorie)){
            throw new NotFoundHttpException();
        }
        return $this->render("admin/categorie/categorie_show.html.twig", [
            'categorie' => $categorie
        ]);


    }



    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route ("/admin/insert/categorie", name="admin_categorie_insert")
     */
    public function insertCategorie(Request $request, EntityManagerInterface $entityManager)
    {

        $categorie = new Categorie();
        $categorieForm = $this->createForm(CategorieType::class, $categorie);

        $categorieForm->handleRequest($request);

        if ($categorieForm->isSubmitted() && $categorieForm->isValid()) {
            $this->addFlash(
                'success',
                'Votre catégorie ' . $categorie->getTitle() . ' a bien été crée !'
            );
            $entityManager->persist($categorie);
            $entityManager->flush();
            return $this->redirectToRoute('admin_categories_list');

        }
        return $this->render('admin/categorie/form.html.twig', [
            'categorieForm' => $categorieForm->createView()
        ]);
    }

        /**
         * @IsGranted("ROLE_ADMIN")
         * @Route ("/admin/update/categorie/{id}",name="admin_categorie_update")
         */
        public function updateCategorie($id, CategorieRepository  $categorieRepository, EntityManagerInterface $entityManager, Request $request)
        {
            $categorie = $categorieRepository->find($id);

            $categorieForm = $this->createForm(CategorieType::class, $categorie);

            $categorieForm->handleRequest($request);

            if ($categorieForm->isSubmitted() && $categorieForm->isValid()) {
                $this->addFlash(
                    'success',
                    'Votre catégorie ' . $categorie->getTitle() . ' a bien été modifiée !'
                );
                $entityManager->persist($categorie);
                $entityManager->flush();
                return $this->redirectToRoute('admin_categories_list');


            }
            return $this->render('admin/categorie/form.html.twig', [
                'categorieForm' => $categorieForm->createView()
            ]);
        }
        /**
         * @IsGranted("ROLE_ADMIN")
         * @Route ("/admin/categorie/delete/{id}",name="admin_categorie_delete")
         */
        public function deleteCategorie($id, CategorieRepository  $catagorieRepository, EntityManagerInterface $entityManager)
    {
        $categorie = $catagorieRepository->find($id);
        if ($categorie)
        {
            $entityManager->remove($categorie);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Votre catégorie '. $categorie->getTitle().' a bien été supprimée !'
            );

        }
        return $this->redirectToRoute('admin_categories_list');
    }

}