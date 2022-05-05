<?php

namespace App\Controller;

use App\Entity\PFE;
use App\Form\PfeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/pfe', name: 'app_pfe')]
class PfeController extends AbstractController
{
    #[Route('/add/{id?0}', name: 'pfe.add')]
    public function addPfe(ManagerRegistry $doctrine,Request $request,PFE $pfe=null): Response
    {
        if(!$pfe){
            $pfe=new PFE();
        }
        $entitymanager=$doctrine->getManager();
        $form=$this->createForm(PfeType::class,$pfe);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $entitymanager->persist($pfe);
            $entitymanager->flush();
            $this->addFlash('success','Pfe added successfuly');
            return $this->redirectToRoute('pfe.list');
        }
        else{
            $this->addFlash('warning',"form n'est pas rempli");
            return $this->render('pfe/add.html.twig', [
                'form'=>$form->createView()
            ]);
        }
    }

    #[Route('/all', name: 'pfe.list')]
    public function index(ManagerRegistry $doctrine):Response{
        $repository=$doctrine->getRepository(PFE::class);
        $pfes=$repository->findAll();
        return $this->render('pfe/index.html.twig',['pfes'=>$pfes]);
    }

}
