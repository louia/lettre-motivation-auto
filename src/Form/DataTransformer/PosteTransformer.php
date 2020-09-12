<?php

namespace App\Form\DataTransformer;

use App\Entity\Poste;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;

class PosteTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($value)
    {
        if (null === $value) {
            return '';
        }

//        return $value->getId();
    }

    public function reverseTransform($value)
    {
        if (!$value) {
            return;
        }
        $poste = $value;
        $postes = $this->entityManager->getRepository(Poste::class)->findBy([
                'nom' => $poste,
            ]);

        if (!in_array($poste, $postes)) {
            $item = new Poste();
            $item->setNom($poste);

            $this->entityManager->persist($item);
            $this->entityManager->flush();

            return $item;
        } else {
            $neededObject = $this->entityManager->getRepository(Poste::class)->findOneBy([
                    'nom' => $poste,
                ]);

            return $neededObject;
        }
    }
}
