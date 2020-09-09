<?php

namespace App\Form\Type;

use App\Entity\LettreMotiv;
use App\Form\DataTransformer\PosteTransformer;
use App\Validator\Word;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MotivationType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $entityManager;
    private $transformer;

    public function __construct(EntityManagerInterface $entityManager, PosteTransformer $transformer)
    {
        $this->entityManager = $entityManager;
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('wordFilename', FileType::class, [
                'label' => 'Lettre de motivation (.docx)',
                'required' => true,
                'constraints' => [
                    new Word(),
                ],
                'attr' => [
                    'accept' => '.docx',
                ],
            ])
            ->add('NomEntreprise', TextType::class, [
                'label' => "Nom de l'entreprise | \${nom_entreprise}",
                'required' => true,
                'attr' => [
                    'autocomplete' => 'motivation[NomEntreprise]',
                    'class' => 'typeahead',
                ],
            ])
            ->add('adresse', TextType::class, [
                'label' => "Adresse de l'entreprise | \${adresse_entreprise}",
                'required' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                ],
            ])
            ->add('villeCodeP', TextType::class, [
                'label' => "Code Postal et Ville de l'entreprise",
                'required' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                ],
            ])
            ->add('NomPoste', TextType::class, [
                'required' => false,
                'label' => 'Nom du poste | ${poste}',

                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'typeahead',
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer',
            ]);

        $builder->get('NomPoste')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LettreMotiv::class,
        ]);
    }
}
