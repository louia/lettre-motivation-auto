<?php

namespace App\Validator;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class WordValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\Word */

        /* @var $value UploadedFile */

        if (null === $value || '' === $value) {
            return;
        }
dump($value->getClientMimeType());
        if ('application/vnd.openxmlformats-officedocument.wordprocessingml.document' != $value->getClientMimeType()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value->getClientOriginalName())
                ->addViolation();
        }
    }
}
