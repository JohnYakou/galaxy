<?php

namespace App\Controller;

use App\Entity\Satellite;
use App\Form\SatelliteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SatelliteController extends AbstractController
{
    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }

    /**
     * @Route("/satellite", name="app_satellite")
     */
    public function index(Request $request): Response
    {
        $satellite = new Satellite();

        $add = $this->createForm(SatelliteType::class, $satellite);
        $add->handleRequest($request);

        if($add->isSubmitted() && $add->isValid()){
            $this->manager->persist($satellite);
            $this->manager->flush();

            return $this->redirectToRoute('app_all_satellite');
        }

        return $this->render('satellite/index.html.twig', [
            'addForm' => $add->createView(),
        ]);
    }

    /**
     * @Route("/all/satellite/deux", name="app_all_satellite")
     */
    public function all(): Response
    {    
        $satellite = new Satellite;
        $satellite = $this->manager->getRepository(Satellite::class)->findAll();
    
        return $this->render('satellite/allSatellite.html.twig', [
            'satellite' => $satellite,
        ]);
    }

    /**
     * @Route("/single2/danganDeux/{id}", name="app_single2_dangan")
     */
    public function single(Satellite $id): Response{
        $satellite = $this->manager->getRepository(Satellite::class)->find($id);

        return $this->render('satellite/singleDeux.html.twig', [
            'satellite' => $satellite,
        ]);
    }
}