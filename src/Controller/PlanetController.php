<?php

namespace App\Controller;

use App\Entity\Planet;
use App\Form\PlanetType;
use App\Entity\Satellite;
use App\Form\SatelliteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlanetController extends AbstractController
{
    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }

    /**
     * @Route("/planet", name="app_planet")
     */
    public function index(Request $request): Response
    {
        $planet = new Planet;

        $add = $this->createForm(PlanetType::class, $planet);
        $add->handleRequest($request);

        if($add->isSubmitted() && $add->isValid()){
            $this->manager->persist($planet);
            $this->manager->flush();

            return $this->redirectToRoute('app_all_planet');
        }

        return $this->render('planet/index.html.twig', [
            'addForm' => $add->createView(),
        ]);
    }

    /**
     * @Route("all/planet", name="app_all_planet")
     */
    public function all(): Response{
        $planets = $this->manager->getRepository(Planet::class)->findAll();

        return $this->render('planet/all.html.twig', [
            'planets' => $planets,
        ]);
    }

    /**
     * @Route("/single/planet/{id}", name="app_single_planet")
     */
    public function single(Planet $id): Response{
        $planets = $this->manager->getRepository(Planet::class)->find($id);

        return $this->render('planet/single.html.twig', [
            'planets' => $planets,
        ]);
    }

    /**
     * @Route("/admin/all/planet", name="app_admin_all_planet")
     */
    public function admin(): Response{
        $planets = $this->manager->getRepository(Planet::class)->findAll();

        // INSTANTIATION DE SATELLITE
        $satellite = new Satellite;
        $satellite = $this->manager->getRepository(Satellite::class)->findAll();

        return $this->render('planet/gestion.html.twig', [
            'planets' => $planets,
            'satellite' => $satellite,
        ]);
    }

    /**
     * @Route("/admin/planet/delete/{id}", name="app_admin_delete_planet")
     */
    public function delete(Planet $planet): Response{

        $this->manager->remove($planet);
        $this->manager->flush();

        return $this->redirectToRoute('app_admin_all_planet');
    }

    // -------------------------------------------------------

    // ----- DELETE DE SATELLITE -----
    /**
     * @Route("/admin/satellite/delete/{id}", name="app_admin_delete_satellite")
     */
    public function satellite(Satellite $satellite): Response{

        $this->manager->remove($satellite);
        $this->manager->flush();

        return $this->redirectToRoute('app_admin_all_satellite');
    }

    // MODIFICATION
    /**
     * @Route("/admin/planet/edit/{id}", name="app_admin_edit_planet")
     */
    public function edit(Planet $planet, Request $request): Response{

        $formEdit = $this->createForm(PlanetType::class, $planet);

        $formEdit->handleRequest($request);

        if($formEdit->isSubmitted() && $formEdit->isValid()){
            $this->manager->persist($planet);
            $this->manager->flush();        
        
            return $this->redirectToRoute('app_admin_all_planet');
        }

        return $this->render('planet/edit.html.twig', [
            'modif' => $formEdit->createView(),
        ]);
    }

    // EDIT DE DANGANDEUX
    /**
     * @Route("/admin/satellite/edit/{id}", name="app_admin_edit_satellite")
     */
    public function editSatel(Satellite $satellite, Request $request): Response{

        $formEdit = $this->createForm(SatelliteType::class, $satellite);

        $formEdit->handleRequest($request);

        if($formEdit->isSubmitted() && $formEdit->isValid()){
            $this->manager->persist($satellite);
            $this->manager->flush();        
        
            return $this->redirectToRoute('app_admin_all_planet');
        }
       

        return $this->render('satellite/editDeux.html.twig', [
            'modif' => $formEdit->createView(),
        ]);       
    }
}