<?php

namespace App\Controller\api;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use JMS\Serializer\SerializationContext;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;




/**
 * @package App\Controller
 * /**
 * @Rest\Route("/api/article")
 */

class ApiArticleController extends AbstractController
{
    
  /**
     ** @Rest\Get("/")
     */
    public function listAction(ArticleRepository $articlesRepo)
    {
    $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
    $articles = $articlesRepo->findAll();
    $serializer = new Serializer(array(new DateTimeNormalizer('d.m.Y'), new GetSetMethodNormalizer($classMetadataFactory)), array('json' => new JsonEncoder()));
    $data = $serializer->serialize($articles, 'json',['groups'=>'articles']);
       // On instancie la réponse
    $response = new Response($data);

       // On ajoute l'entête HTTP
    $response->headers->set('Content-Type', 'application/json');
    //  $response=$this->json($articles,200,[],['groups'=>'articles']);
       // On envoie la réponse
       return $response;
  }


/**
     *@Get(
     *     path = "/{id}",
     *     name = "app_article_show",
     *     requirements = {"id"="\d+"}
     * )
     */
public function getArticle(Article $articles, ArticleRepository $articlesRepo,$id)
{   
    $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
    $articles = $articlesRepo
    ->find($id);
    $serializer = new Serializer(array(new DateTimeNormalizer('d.m.Y'), new GetSetMethodNormalizer($classMetadataFactory)), array('json' => new JsonEncoder()));
    $data = $serializer->serialize($articles, 'json',['groups'=>'articles']);
       // On instancie la réponse
       $response = new Response($data);

       // On ajoute l'entête HTTP
       $response->headers->set('Content-Type', 'application/json');
   
       // On envoie la réponse
       return $response;
}

/**
 * @POST("/", name="ajout")
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

     *@Put("/{id?}", name="edit")

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
    public function lastArticles(ArticleRepository $articlesRepo): Response
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
 *@DELETE("/{id}", name="supprime")
 */
public function removeArticle(ArticleRepository $articleRepository,EntityManagerInterface $em,$id):Response
{
  $article = $articleRepository->find($id);
  if(!$article){
      return $this->json(["error message" => "article introuvable"],404);
  }
  $em->remove($article);
  $em->flush();
  return $this->json(["message" => "article supprime avec succès"]);
}}

