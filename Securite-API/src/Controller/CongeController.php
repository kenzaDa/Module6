<?php

namespace App\Controller;

use App\Entity\Conge;
use App\Form\CongeType;
use App\Repository\CongeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Column\TextColumn;
use Symfony\Component\Routing\Annotation\Route;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Symfony\Component\HttpFoundation\Response;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;





class CongeController extends AbstractController
{
 /**
     * @Route("/conge", name="app_conge")
     */
    public function conge(Request $request, EntityManagerInterface $manager, CongeRepository $congeRepository ,DataTableFactory $dataTableFactory)
    {
       
        $conge = new Conge();
        $user = $this->getUser();
        $form = $this->createForm(CongeType::class, $conge);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $conge->setUser($user);
            $conge->setStatut('attente de validation');
            $manager->persist($conge);
            $manager->flush();
        }
       

        $conge = $congeRepository ->findBy(array('user' => $user));
        $table = $dataTableFactory->create()
        ->add('DateDebut', DateTimeColumn::class,['label' => 'Date de début', 'className' => 'bold','format' => 'Y-m-d'])
        ->add('nbJours', TextColumn::class,['label' => 'Nombre de jours', 'className' => 'bold'])
        ->add('Statut', TextColumn::class,['label' => 'Nombre de jours', 'className' => 'bold'])
        
        ->createAdapter(ORMAdapter::class, [
            'entity' => Conge::class,

        ])
        ->handleRequest($request);

    if ($table->isCallback()) {
        return $table->getResponse();
    }

    
        return $this->render('conge/conge.html.twig', ['form' => $form->createView(), 'conge' => $conge,
    "user" => $user->getLastname(),'datatable' => $table]);
    }
    /** 
     * @Route("/admin/conge", name="admin_conge")
     */
    public function afficheConge(Request $request, DataTableFactory $dataTableFactory)
    {
        $table = $dataTableFactory->create()
        ->add('user', TextColumn::class, ['label' => 'Utilisateur','field' => 'user.firstname'])
        ->add('DateDebut', DateTimeColumn::class, ['label' => 'Date Début','format' => 'd-m-Y'])
        ->add('nbJours', TextColumn::class, ['label' => 'Nombre de jour'])
        // ->add('Statut', TextColumn::class, ['label' => 'Statut'])
        ->add('id', TextColumn::class, [
            'label' => 'actions',
            'render' => function($value){
                return sprintf("<button onclick='accept(%s)' class='btn btn-success'>accepter</button> <button onclick='refuse(%s)' class='btn btn-danger'>refuser</button>",$value,$value);
            }  
        ])        ->createAdapter(ORMAdapter::class, [
         'entity' => Conge::class,

        ])     
        ->handleRequest($request);
if ($table->isCallback()) {
    return $table->getResponse();
}
return $this->render('admin/adminConge.html.twig', ['datatable' => $table]);
    }
    //  /** 
    //  * @Route("/status/conge", name="status_conge")
    //  */
    // public function status (Request $request , Conge $Statut)  {
    //     $id = $request->id;
       
    //     $Statut->find($id)->value('status');
       
    //     if($Statut === 'refuser'){
    //      $status = ' Congé Refusé';
    //     } elseif($Statut === 'accepter'){
    //      $status = 'Congé accepté';
    //    }
    //    Conge::where('id', $id)->update(['status'=> $status]);
       
    //    return $message=json('success');
    //    }

           /**
         * @Route("/manageHoliday/accept", name="app_manage_holiday_accept")
         */
        public function manageHolidayAccept(CongeRepository $congeRepository,EntityManagerInterface $em)
        {
            $data = json_decode(file_get_contents("php://input"), true);
            $conge = $congeRepository->find($data['id']);
            $status = $conge->setStatut('valide');
            $em->flush();
                return $this->json(['data' =>$data, 'conge' => $status]);
            
        }
 
        /**
         * @Route("/manageHoliday/refuse", name="app_manage_holiday")
         */
        public function manageHolidayRefuse(CongeRepository $congeRepository,EntityManagerInterface $em)
        {
            $data = json_decode(file_get_contents("php://input"), true);
            $conge = $congeRepository->find($data['id']);
            $status = $conge->setStatut('refuse');
            $em->flush();
                return $this->json(['data' =>$data, 'conge' => $status]);
            
        }
}






