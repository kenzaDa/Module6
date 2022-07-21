<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use App\Repository\ArticleRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;



class ApiArticleController extends AbstractController
{
    /**
     * @Route("/api/article", name="app_api_article")
     */
    public function index(): Response
    {
        return $this->render('api_article/index.html.twig', [
            'controller_name' => 'ApiArticleController',
        ]);
    }
    
  /**
     * @Route("/articles", name="article_list")
     */
    public function listAction(ArticleRepository $articlesRepo)
    {
        $articles = $articlesRepo->findAll();
      // On spécifie qu'on utilise l'encodeur JSON
    $encoders = [new JsonEncoder()];

    // On instancie le "normaliseur" pour convertir la collection en tableau
    $normalizers = [new ObjectNormalizer()];

    // On instancie le convertisseur
    $serializer = new Serializer($normalizers, $encoders);

    // On convertit en json
    $jsonContent = $serializer->serialize($articles, 'json', [
        'circular_reference_handler' => function ($object) {
            return $object->getId();
        }
    ]);

    // On instancie la réponse
    $response = new Response($jsonContent);

    // On ajoute l'entête HTTP
    $response->headers->set('Content-Type', 'application/json');

    // On envoie la réponse
    return $response;
}
/**
 * @Route("/article/{id}", name="article", methods={"GET"})
 */
public function getArticle(Article $article)
{
    $encoders = [new JsonEncoder()];
    $normalizers = [new ObjectNormalizer()];
    $serializer = new Serializer($normalizers, $encoders);
    $jsonContent = $serializer->serialize($article, 'json', [
        'circular_reference_handler' => function ($object) {
            return $object->getId();
        }
    ]);
    $response = new Response($jsonContent);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
}


    }

