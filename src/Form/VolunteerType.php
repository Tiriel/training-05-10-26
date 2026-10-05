<?php

namespace App\Form;

use App\Entity\Conference;
use App\Entity\Project;
use App\Entity\User;
use App\Entity\Volunteer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VolunteerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startAt', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('endAt', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('forUser', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
            ])
        ;

        if ($options['conference'] instanceof Conference) {
            $builder
                ->add('conference', EntityType::class, [
                    'class' => Conference::class,
                    'choice_label' => 'name',
                ]);
        }
        if ($options['project'] instanceof Project) {
            $builder
                ->add('project', EntityType::class, [
                    'class' => Project::class,
                    'choice_label' => 'name',
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => Volunteer::class,
                'conference' => null,
                'project' => null,
            ])
            ->setAllowedTypes('conference', [Conference::class, 'null'])
            ->setAllowedTypes('project', [Project::class, 'null'])
        ;
    }
}
