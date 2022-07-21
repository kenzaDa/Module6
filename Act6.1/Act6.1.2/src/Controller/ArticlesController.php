<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client;
use Symfony\Contracts\HttpClient\HttpClientInterface;
class ArticlesController extends AbstractController


{    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
    /**
     * @Route("/article", name="app_articles")
     */
    public function index(): Response
    {
        $client = new Client(['base_uri' => 'http://127.0.0.1:8000']);
    $response = $client->request('GET', '/articles');
    $body = $response->getBody();
    $articles = json_decode($body);



 
        return $this->render('articles/index.html.twig', [
            'controller_name' => 'ArticlesController',
                'articles' => $articles 
        ]);
    }



     
/**
     * @Route("/addArticle", name="app_add")
     */
    public function addArticle():Response
    {
        $response=$this->client->request('POST','http://127.0.0.1:8000/article',[
                'json' => ['titre' => 'nouveau titre  ',
                            'contenu' => 'nouveau contenu ',
                            'auteur' => 'auteur',
                            'dateDePublication' =>  '2022-07-21 10:18:15'
                ]
            ]
             );
             
      
         
          
            
        return $this->json(["message" => "article ajoute"],201);
        
    }
/**
     * @Route("/ModifArticle", name="app_modif")
     */
    public function ModifArticle()

    {   
        $response=$this->client->request('PUT','http://127.0.0.1:8000/article/23',[
        'json' => ['titre' => ' titre modifie ',
                    'contenu' => ' contenu modifie ',
                    'auteur' => 'auteur',
                    'dateDePublication' =>  '2022-07-21 10:18:15'
        ]
    ]
     );
     $content = $response->toArray();

    
     return $this->json(["message" => "article modifie"],200);
    } 
    
     /**
     * @Route("/delete", name="app_delete")
     */
    public function DeleteArticle()
     {  if (isset($_GET['id'])) {
     $this->client->request('DELETE','http://127.0.0.1:8000/article/'.$_GET['id']);
     }return $this->json(["message" => "article supprime"],200);
     
        
    }
}

