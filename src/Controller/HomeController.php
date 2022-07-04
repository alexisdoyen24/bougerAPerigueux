<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Type;
use App\Form\EventType;
use App\Form\RegistrationFormType;
use App\Repository\EventRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EventRepository $eventRepository): Response
    {
        $showLastEvents = $eventRepository->findBy(array(), array('date'=>'desc'), 3, null);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'events' => $showLastEvents
        ]);
    }

    #[Route('/sorties-festives', name: 'app_party')]
    public function party(EventRepository $eventRepository): Response
    {
        $partiesEvent = $eventRepository->findAll();
        return $this->render('home/party.html.twig', [
            'controller_name' => 'HomeController',
            'parties' => $partiesEvent
        ]);
    }

    #[Route('/sorties-gratuites', name: 'app_free')]
    public function free(EventRepository $eventRepository): Response
    {
        // $showEvents = $eventRepository->findAll();
        $showFree = $eventRepository->findBy(['price'=>0]);
        return $this->render('home/free.html.twig', [
            'controller_name' => 'HomeController',
            // 'events' => $showEvents
            'events' => $showFree
        ]);
    }

    #[Route('/jeune-public', name: 'app_kids')]
    public function kids(EventRepository $eventRepository): Response
    {
        // $showKids = $type->getId(5);
        $kidsEvent = $eventRepository->findAll();

        return $this->render('home/kids.html.twig', [
            'controller_name' => 'HomeController',
            'kids' => $kidsEvent
        ]);
    }

    #[Route('/sorties-sportives', name: 'app_sport')]
    public function sport(EventRepository $eventRepository): Response
    {
        $sportsEvent = $eventRepository->findAll();
        return $this->render('home/sport.html.twig', [
            'controller_name' => 'HomeController',
            'sports' => $sportsEvent
        ]);
    }



}


