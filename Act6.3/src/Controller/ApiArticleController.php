<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
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
     *     path = "/api/article/{id}",
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
 * @POST("/api/article", name="ajout")
 */
public function addArticle(Request $request, SerializerInterface $serializer,EntityManagerInterface $em)
{
    $jsonRecu=$request->getContent();
    $article=$serializer->deserialize($jsonRecu, Article::class,'json');
    $em->persist($article);
    $em->flush();
    return $this->json($article,201,[]);
}

  /**

     * @Put("/api/article/{id?}", name="edit")

     */

    public function editArticle(?Article $article, Request $request)

    {

            $donnees = json_decode($request->getContent());

            $code = 200;

            if(!$article){

                $article = new Article();

                $code = 201;

            }

            $article->setTitre($donnees->titre);

            $article->setContenu($donnees->contenu);

            $article->setAuteur($donnees->auteur);

            $article->setDateDePublication (new \DateTime((string)$donnees->dateDePublication));

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($article);

            $entityManager->flush();

           return $this->json($article,$code,[]);

    }
  /**
     * @GET("/api/lastarticle", name="article_list_three")
     */
    public function last3Articles(ArticleRepository $articlesRepo): Response
    {

    $articles = $articlesRepo->apiFindAll();
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
 * @DELETE("/api/article/{id}", name="supprime")
 */
public function removeArticle(Article $article ,EntityManagerInterface $em)
{ if (!$article) {
    return $this->json(["error message" => "article not found"],404);
  } else {
    $em ->remove($article);
    $em->flush();
    return new Response('ok');
  }
    

    }}

