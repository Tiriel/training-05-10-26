<?php

namespace App\Form;

use App\Entity\Skill;
use App\Entity\Tag;
use App\Entity\VolunteerProfile;
use App\Form\DataTransformer\SkillToFormTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VolunteerProfileType extends AbstractType
{
    public function __construct(
        private readonly SkillToFormTransformer $skillToFormTransformer,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('skills', EntityType::class, [
                'class' => Skill::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
            ->add('interests', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
            ]);
        $builder->get('skills')->addModelTransformer($this->skillToFormTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VolunteerProfile::class,
        ]);
    }
}
