<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Form\ModifyEventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddEventController extends AbstractController
{
    #[Route('/ajouter-un-evenement', name: 'app_addEvent')]
    #[IsGranted('ROLE_USER')]
    public function addEvent(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $event->setDate(new \DateTime('now')); 
        $eventForm = $this->createForm(EventType::class, $event);
        $eventForm->handleRequest($request);
        $user = $this->getUser();

        if ($eventForm->isSubmitted() &&   $eventForm->isValid()) {

            $event->setUser($user);
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
            $this->addFlash('success', "L'évènement a été créé");
            return $this->redirectToRoute('app_home');
        }  
        return $this->render('add_event/addEvent.html.twig', [
             'eventForm' => $eventForm->createView(),
        ]);      
    }

    #[Route('/modifier-un-evenement/{id<\d+>}', name: 'app_editEvent')]
    #[IsGranted('ROLE_USER')]
    public function editEvent(Request $request, EntityManagerInterface $entityManager, Event $event): Response
    {
        $eventForm = $this->createForm(ModifyEventType::class, $event);
        $eventForm->handleRequest($request);
        // dd($user);

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
            $this->addFlash('success', "L'évènement a été modifié");
            return $this->redirectToRoute('app_home');
        }  
        return $this->render('add_event/editEvent.html.twig', [
            'eventForm' => $eventForm->createView(),
            'event' => $event
        ]);      
    }

    #[Route('/supprimer-un-evenement/{id<\d+>}', name: 'app_deleteEvent')]
    #[IsGranted('ROLE_USER')]
    public function deleteEvent(Request $request, EntityManagerInterface $entityManager, Event $event, EventRepository $eventRepository): Response
    {
        $csrfToken = $request->request->get('token');
        if($this->isCsrfTokenValid('delete-event', $csrfToken)){
            $eventRepository->remove($event, true);
            $this->addFlash('success', "L'évènement a été supprimé");
            return $this->redirectToRoute('app_home');
        }
    }   
}
