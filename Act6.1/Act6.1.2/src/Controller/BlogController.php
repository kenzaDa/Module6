<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="app_blog")
     */
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }
     /**
     * @Route("/home", name="home")
     */
    public function home(){
        return $this->render('blog/home.html.twig',[
            'title'=> "Bienvenue dans la page home"
        ]);
     }
         /**
     * @Route("/blog/article/{id}", name="blog_show" )
     *
     */
    public function show(string $id){
      
        return $this->render('blog/show.html.twig',[
            'id'=>$id]);
    //   }
    //   elseif ($id==2){return $this->render('blog/show2.html.twig');

    //   }
    //   elseif ($id==3){return $this->render('blog/show3.html.twig');

      }
        
     

    }
