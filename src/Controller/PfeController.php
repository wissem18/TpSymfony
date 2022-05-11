<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\PFE;
use App\Form\PfeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/pfe')]
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
            return $this->redirectToRoute('pfe.detail',[
                'id'=>$pfe->getId()
            ]);
        }
        else{

            return $this->render('pfe/add.html.twig', [
                'form'=>$form->createView()
            ]);
        }
    }
    #[Route('/detail{id?0}', name: 'pfe.detail')]
    public function showDetails(ManagerRegistry $doctrine,$id):Response{
        $repository=$doctrine->getRepository(PFE::class);
        $pfe=$repository->findOneBy(['id'=>$id]);
        if($pfe){
        return $this->render('pfe/detail.html.twig',[
            'pfe'=>$pfe
        ]);
        }
        else{
            $this->addFlash('error','pfe not found');
            return $this->redirectToRoute('pfe.list');
        }
    }
    #[Route('/all', name: 'pfe.list')]
    public function index(ManagerRegistry $doctrine):Response{
        $repository=$doctrine->getRepository(PFE::class);
        $pfes=$repository->findAll();
        return $this->render('pfe/index.html.twig',['pfes'=>$pfes]);
    }
#[Route('/stats', name: 'pfe.stats')]
public function showStats(ManagerRegistry $doctrine):Response{
   // 1ere méthode:
    $repository=$doctrine->getRepository(PFE::class);
    $stats=$repository->numberPfeByEntreprise();
    return $this->render('pfe/stats.html.twig',['stats'=>$stats]);
    //2eme méthode:
//    $repository=$doctrine->getRepository(Entreprise::class);
//    $entreprises=$repository->findAll();
//    return $this->render('pfe/stats.html.twig',['entreprises'=>$entreprises]);

}
}
