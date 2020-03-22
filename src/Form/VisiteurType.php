<?php

namespace App\Form;

use App\Entity\Visiteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Security;

class VisiteurType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('ville')
            ->add('cp')
            ->add('dateEmbauche', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('username')
            ->add('password')
        ;

        //Affichage des rôles seulement si c'est l'utilisateur administrateur
        //Les autres ne peuvent pas modifier des rôles
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $user = $this->security->getUser();
            $userRoles = $user->getRoles();
            $form = $event->getForm();
            if($userRoles[0] == 'ROLE_ADMINISTRATEUR'){
                $form->add('roles', ChoiceType::class, [
                    'choices' => [
                        'Administrateur' => 'ROLE_ADMINISTRATEUR',
                        'Visiteur médical' => 'ROLE_VISITEUR_MEDICAL',
                        'Comptable' => 'ROLE_COMPTABLE'
                    ],
                    'multiple' => true
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Visiteur::class,
            'allow_extra_fields' => true
        ]);
    }
}
