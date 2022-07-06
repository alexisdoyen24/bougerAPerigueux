<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ModifyEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('fixed', CheckboxType::class, [
                'label' => 'Evènement fixe',
                'required' => false,
            ])
            ->add('eventDate', DateType::class, [
                'label' => "Date de l'évènement"
            ])
            ->add('duration', TextType::class, [
                'label' => 'Durée ( en heure )'
            ])
            ->add('price', TextType::class, [
                'label' => 'prix ( en € )'
            ])
            ->add('enterpriseName', TextType::class, [
                'label' => "Nom de l'entreprise",
                'required' => false
            ])
            ->add('description')
            ->add('locality', TextType::class, [
                'label' => 'Localité'
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'data_class' => null,
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' =>' Le format n\'est pas pris en charge'
                    ])
                ]
            ])
            ->add('link', TextType::class, [
                'label' => "lien de l'évènement",
                'required' => false,
            ])
            ->add('type', EntityType::class, [
                'class' => Type::class,
                // 'choice_label' => 'name'
            ])
            ->add('Envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
