<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    ////////////////////////////////////////
    ///////////// CREATE ARTICLE ///////////
    ////////////////////////////////////////

    #[Route('/article/creer', name: 'article_create')]
    
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success','Votre article ' . $article->getId() .' a été ajouté');
        }

        return $this->render('article/creer.html.twig', [
            'controller_name' => 'ArticleController',
            'form' => $form,
        ]);
    }

    ////////////////////////////////////////
    ///////////// LIST ARTICLE /////////////
    ////////////////////////////////////////

    #[Route('article/liste', name: 'article_liste')]
    public function show(EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Article::class)->findAll();

        // dd($article);

        return $this->render('article/liste.html.twig', [
            'controller_name' => 'ArticleController',
            'article' => $article,
        ]);
    }

    ////////////////////////////////////////
    ///////////// EDIT ARTICLE /////////////
    ////////////////////////////////////////

    #[Route('/article/modifier/{id}', name: 'article_edit')]
    
    public function update(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'L\'article d\'id '. $id . ' n\'existe pas'
            );
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success','Votre article ' . $article->getId() .' a été modifié');
        }

        return $this->render('article/modifier.html.twig', [
            'controller_name' => 'ArticleController',
            'form' => $form,
        ]);
    }
       

    ////////////////////////////////////////
    ///////////// DELETE ARTICLE ///////////
    ////////////////////////////////////////

    #[Route('/article/supprimer/{id}', name:'supprimer_article')]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article found for id '.$id
            );
        }

        $entityManager->remove($article);
        $entityManager->flush();

        return $this->render('article/supprimer.html.twig', [
            'controller_name' => 'ArticleController',
            'article' => $article,
        ]);
    }  
}