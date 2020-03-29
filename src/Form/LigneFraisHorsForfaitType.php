<?php

namespace App\Form;

use App\Entity\LigneFraisHorsForfait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class LigneFraisHorsForfaitType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //Récupération de la date
        $date = new \DateTime();
        $mois = $date->format('m');
        $annee = $date->format('Y');
        $builder
            ->add('date', DateType::class, [
                'months' => array($mois),
                'years' => array($annee),
                'format' => 'dd-MMMM-yyyy'
            ])
            ->add('libelle')
            ->add('montant')
            //->add('fkVisiteur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LigneFraisHorsForfait::class,
            'allow_extra_fields' => true
        ]);
    }
}
