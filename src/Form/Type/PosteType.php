<?php

// src/Form/Type/TagType.php

namespace App\Form\Type;

use App\Form\DataTransformer\PosteTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PosteType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addModelTransformer(new CollectionToArrayTransformer(), true)
            ->addModelTransformer(new PosteTransformer($this->entityManager), true);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('required', true);
    }

    public function getParent(): string
    {
        return TextType::class;
    }
}
