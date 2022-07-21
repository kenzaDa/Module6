<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use App\Repository\ArticleRepository;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;




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
     * @GET("/articles", name="article_list")
     */
    public function listAction(ArticleRepository $articlesRepo): Response
    {

    $articles = $articlesRepo->findAll();
    $serializer = new Serializer(array(new DateTimeNormalizer('d.m.Y'), new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
    $data = $serializer->serialize($articles, 'json');
       // On instancie la réponse
       $response = new Response($data);

       // On ajoute l'entête HTTP
       $response->headers->set('Content-Type', 'application/json');
   
       // On envoie la réponse
       return $response;
  }


/**
     * @Get(
     *     path = "/article/{id}",
     *     name = "app_article_show",
     *     requirements = {"id"="\d+"}
     * )
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

