<?php

namespace App\Form;

use App\Entity\Projet;
use App\Entity\Tache;
use App\Entity\Utilisateur;
use App\Enum\Priorite;
use App\Enum\Statut;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TacheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('statut', EnumType::class, [
                'class' => Statut::class,
                'choice_label' => fn (Statut $s) => $s->name,
            ])
            ->add('priorite', EnumType::class, [
                'class' => Priorite::class,
                'choice_label' => fn (Priorite $p) => $p->name,
            ])
            ->add('dateEcheance', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'required' => false,
                'label' => "Date d'échéance",
            ])
            ->add('projet', EntityType::class, [
                'class' => Projet::class,
                'choice_label' => 'nom',
            ])
            ->add('affecteA', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'nomComplet',
                'required' => false,
                'placeholder' => 'Non affectée',
                'label' => 'Affectée à',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Tache::class]);
    }
}
