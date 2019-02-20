<?php
/**
 * Created by PhpStorm.
 * User: teo
 * Date: 05/02/19
 * Time: 15:46
 */

namespace App\Service;


use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ProductValidator
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var ValidatorHelper
     */
    protected $validatorHelper;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator=$validator;
    }

    public function validateProduct(array $data)
    {
        $constraint = new Assert\Collection([
            'fields' => [
                'name' => [
                    new Assert\NotBlank(),
                    new Assert\Type('string'),
                    new Assert\LessThanOrEqual(255)
                ],
                'price' => [
                    new Assert\GreaterThanOrEqual(1),
                    new Assert\LessThanOrEqual(5500),
                    new Assert\Type('int')
                ],
                'category' => [
                    new Assert\Type('int')
                ]
            ],
            'allowExtraFields' => true
        ]);

        return $this->violationsToArray($this->validator->validate($data, $constraint));
    }

    /**
     * Returns an array of violations
     *
     * @param ConstraintViolationListInterface $violations
     *
     * @return array
     */
    public function violationsToArray(ConstraintViolationListInterface $violations) {
        $messages = [];

        foreach ($violations as $violation) {
            $messages[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $messages;
    }

}