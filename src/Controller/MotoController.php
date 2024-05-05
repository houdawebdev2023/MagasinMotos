<?php

namespace App\Controller;

use App\Entity\Moto;
use App\Form\MotoType;
use App\Repository\MotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MotoController extends AbstractController
{
    #[Route(path:'/moto', name: 'app_moto_index')]
    public function index(Request $request, MotoRepository $repository, EntityManagerInterface $em): Response
    {
        // $moto = new Moto();
        // $moto->setMarque('Honda')
        //     ->setNom('CB1000R')
        //     ->setCouleur('Rouge')
        //     ->setAnee(2005)
        //     ->setPrix(10650);
        //     $em->persist($moto);
        //     $em->flush();

        // $moto = new Moto();
        // $moto->setMarque('KAWAZAKI')
        //     ->setNom('PERFORMANCE')
        //     ->setCouleur('Vert')
        //     ->setAnee(2017)
        //     ->setPrix(7350);
        //     $em->persist($moto);
        //     $em->flush();
        
        $motos = $repository->findAll();



        // $motos[1]->setAnnee(2018);
        // $em->flush();

        return $this->render('moto/index.html.twig',[
            'motos'=>$motos
    ]);
    }
    #[Route(path:'/moto/{nom}-{id}', name: 'app_moto_show', requirements : [ 'id'=> '\d+', 'nom'=> '[a-z0-9-]+'])]
    public function show(Request $request, string $nom, int $id, MotoRepository $repository): Response
    {
        $moto = $repository->find($id);
        if($moto->getNom() !== $nom){
            return $this->redirectToRoute('app_moto_show', ['id' => $moto->getId(), 'nom' => $moto->getNom()]);
        }
        return $this->render ('moto/show.html.twig', [
            // 'nom'=> $nom,
            // 'id'=> $id,
            // 'user' => [
            //     "firstname"=>"houda",
            //     "lastname"=>"elmeslouhi"
            'moto' =>$moto
            
            


            
        ]);
    }

    #[Route(path:'/moto/{id}/edit', name: 'app_moto_edit')]
    public function edit(Moto $moto, Request $request, EntityManagerInterface $em): Response
    {
        //dd($moto);
        $form = $this->createForm(MotoType::class,$moto);
        $form->handleRequest($request);
        // dd($moto)
        if ($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success', 'La moto a bien été modifiée');
            return $this->redirectToRoute('app_moto_show', ['id' => $moto->getId(), 'nom' => $moto->getNom()]);
        }
        return $this->render('moto/edit.html.twig', [
            'moto'=> $moto,
            'monForm'=>$form
        ]);
    }

    #[Route(path:'/moto/create', name: 'app_moto_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $moto = new Moto;
        $form = $this->createForm(MotoType::class,$moto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($moto);
            $em->flush();
            $this->addFlash('success', 'La moto'. $moto->getMarque() .  'a bien été créée');
            return $this->redirectToRoute('app_moto_index');
        }
        return $this->render('moto/create.html.twig', [
           'form'=>$form
        ]);
    }

    #[Route(path:'/moto/{id}/delete', name: 'app_moto_delete')]
    public function delete(Moto $moto, EntityManagerInterface $em): Response
    {
        $marque = $moto->getMArque();
        $em->remove($moto);
        $em->flush();
        $this->addFlash('info', 'La moto'. $marque . 'a bien été supprimée');
        return $this->redirectToRoute('app_moto_index');
        
    }


}