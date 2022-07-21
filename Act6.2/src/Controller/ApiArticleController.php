<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
public function getArticle(Article $articles, ArticleRepository $articlesRepo,$id)
{
    $articles = $articlesRepo
    ->find($id);
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
 * @POST("/article", name="ajout")
 */
public function addArticle(Request $request, SerializerInterface $serializer,EntityManagerInterface $em)
{
    $jsonRecu=$request->getContent();
    $article=$serializer->deserialize($jsonRecu, Article::class,'json');
    $em->persist($article);
    $em->flush();
    return $this->json($article,201,[]);
}
    
    }

