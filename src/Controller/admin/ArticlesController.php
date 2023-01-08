<?php

namespace App\Controller\admin;

use App\Entity\Articles;
use App\Entity\Images;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ArticlesController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/articles", name="articles_list", methods={"GET"})
     */
    public function articles_list(ArticlesRepository $articlesRepository): Response
    {
       

        $articles = $articlesRepository->findAll();

        return $this->render('admin/articles/list.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/artcile/insert", name="article_insert", methods={"GET", "POST"})
     */
    public function insert_article(Request $request): Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            foreach ($images as $image) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                $image->move(
                    $this->getParameter('upload_directory'),
                    $fichier
                );

                $img = new Images();
                $img->setName($fichier);
                $article->addImage($img);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('articles_list');
        }

        return $this->renderForm('admin/articles/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/article/{id}", name="article_show", methods={"GET"})
     */
    public function show(Articles $article): Response
    {
        return $this->render('admin/articles/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/article/{id}/edit", name="articles_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Articles $article): Response
    {
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $images = $form->get('images')->getData();
            foreach ($images as $image) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                $image->move(
                    $this->getParameter('upload_directory'),
                    $fichier
                );

                $img = new Images();
                $img->setName($fichier);
                $article->addImage($img);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('articles_list');
        }

        return $this->renderForm('admin/articles/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/article/delete/{id}", name="articles_delete", methods={"POST"})
     */
    public function delete(Request $request, Articles $article, ArticlesRepository $articlesRepository): Response
    {
        $images = $article->getImages();

        if($images){
            foreach($images as $image){
                $nomImage = $this->getParameter('upload_directory'). '/'. $image->getName();
                if(file_exists($nomImage)){
                    unlink($nomImage);
            }
        }
    }


        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('articles_list');
    }

    /**
     * @Route("/admin/supprime/image/{id}", name="articles_delete_image", methods={"DELETE"})
     */

    public function deleteImage(Images $image, Request $request){
        $data = json_decode($request->getContent(), true);
        
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
            $nom = $image->getName();
            unlink($this->getParameter('upload_directory').'/'. $nom);

            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            return new JsonResponse(['success' => 1]);
        }else{
            return new JsonResponse(['error' => 'Token not valid'], 400);
        }
        
    }
}
