<?php

namespace App\Form;

use App\Entity\LettreMotiv;
use App\Entity\Poste;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MotivationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('wordFilename', FileType::class,[
                'label' => 'Lettre de motivation (.docx)',
                'required' => true,
//                'constraints' => [
//                    new File([
//                        'mimeTypes' => [
//                            'application/msword',
//                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
//                        ],
//                        'mimeTypesMessage' => 'Veuillez charger un fichier Word (.docx) !',
//                    ])
//                ],
            ])
            ->add('NomEntreprise',TextType::class,[
                "label" => "Nom de l'entreprise | \${nom_entreprise}",
                "required" => true,
                "attr" => [
                    "class" => "typeahead"
                ]
            ])
            ->add('adresse',TextType::class,[
                "label" => "Adresse de l'entreprise | \${adresse_entreprise}",
                "required" => true,
                "attr" => [
                    "visibility" => "hidden"
                ]
            ])
            ->add('villeCodeP',TextType::class,[
                "label" => "Code Postal et Ville de l'entreprise",
                "required" => true,
                "attr" => [
                    "id" => "test"
                ]
            ])
            ->add('NomPoste', EntityType::class, [
                'label' => "Nom du poste | \${poste}",
                'class' => Poste::class,
                "expanded" => true,
                "multiple" => false,
            ])
            ->add('save', SubmitType::class,[
                "label" => "Envoyer",
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LettreMotiv::class,
        ]);
    }
}
