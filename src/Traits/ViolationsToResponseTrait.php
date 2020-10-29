<?php

namespace App\Traits;

use Symfony\Component\Validator\ConstraintViolationListInterface;

trait ViolationsToResponseTrait
{
    public function violationToResponse(ConstraintViolationListInterface $violationList): array
    {
        $violations = [];
        foreach ($violationList as $violation) {
            if (!key_exists($violation->getPropertyPath(), $violations)) {
                $violations[$violation->getPropertyPath()] = [];
            }
            $violations[$violation->getPropertyPath()][] = $violation->getMessage();
        }
        return $violations;
    }
}
