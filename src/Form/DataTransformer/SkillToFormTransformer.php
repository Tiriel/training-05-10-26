<?php

namespace App\Form\DataTransformer;

use App\Entity\Skill;
use App\Repository\SkillRepository;
use Symfony\Component\Form\DataTransformerInterface;

class SkillToFormTransformer implements DataTransformerInterface
{
    public function __construct(
        private readonly SkillRepository $repository,
    ) {}

    /**
     * @inheritDoc
     */
    public function transform(mixed $value): mixed
    {
        if ($value instanceof Skill) {
            return $value->getId();
        }

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform(mixed $value): mixed
    {
        $entity = $this->repository->find($value);

        if ($entity instanceof Skill) {
            return  $entity;
        }

        return $value;
    }
}
