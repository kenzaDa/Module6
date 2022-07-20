<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client;
class ArticlesController extends AbstractController
{
    /**
     * @Route("/articles", name="app_articles")
     */
    public function index(): Response
    {
        $client = new Client(['base_uri' => 'https://jsonplaceholder.typicode.com/']);
    $response = $client->request('GET', '/posts');
    $body = $response->getBody();
    $articles = json_decode($body);


    
    $response = $client->request('GET', '/comments');
    $body = $response->getBody();
    $commentaires = json_decode($body);
 
        return $this->render('articles/index.html.twig', [
            'controller_name' => 'ArticlesController',
                'articles' => $articles ,'commentaires' => $commentaires
        ]);
    }



     
/**
     * @Route("/addArticle", name="app_add")
     */
    public function AddArticle()
    { $client = new Client();
        $response = $client->request('POST', 'https://jsonplaceholder.typicode.com/posts', [
            'form_params' => [
                'userId' => '1',
                'id' => '101',
                'title' => 'titre de cet article ajoute',
                'body' => 'contenu ajouté',
                ]
        ]);
        $body=$response->getBody();
       
        $code=$response->getStatusCode() ;
        $reasonPhrase = $response->getReasonPhrase(); // OK
     
    return $this->render('articles/message.html.twig', [
                    'controller_name' => 'ArticlesController',
                    'code'=>$code ,
                    'body'=>$body,
                    'reasonPhrase'=>$reasonPhrase
            ]);
    } 

    
     /**
     * @Route("/delete", name="app_delete")
     */
    public function DeleteArticle()
     {
        $client = new Client();
        $response =$client->delete('https://jsonplaceholder.typicode.com/posts/1');
        $code=$response->getStatusCode() ;
        $reason = $response->getReasonPhrase(); // OK
            return $this->render('articles/message.html.twig', [
                    'controller_name' => 'ArticlesController',
                    'code'=>$code ,
                    'reasonPhrase'=>$reason,
                    'body'=>''
            ]);
    }
}

