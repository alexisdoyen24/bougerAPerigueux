<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddEventController extends AbstractController
{
    #[Route('/ajouter-un-evenement', name: 'app_addEvent')]
    public function addEvent(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $event->setDate(new \DateTime('now'));   
        $eventForm = $this->createForm(EventType::class, $event);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() &&   $eventForm->isValid()) {

            // récupération des données de l'image dans le formulaire
            $imageData = $eventForm->get('image')->getData();
            // création d'un nom unique concaténé de l'image avec la bonne extension
            $imageName = md5(uniqid()).'.'.$imageData->guessExtension();
            // ajout du nouveau nom dans l'instance event
            $event->setImage($imageName);
            //déplacer l'image dans mon dossier upload directory( 1er param lieu et deuxieme le nom)
            $imageData->move($this->getParameter('upload_directory'), $imageName);

            $entityManager->persist($event);
            $entityManager->flush();           
        }  
        return $this->render('add_event/addEvent.html.twig', [
             'eventForm' => $eventForm->createView(),
        ]);      
    }
}
