<?php

namespace AppBundle\Services\Handlers;

use Symfony\Component\Form\Form;

class Validator
{

    public function isValidate(Form $form)
    {
        return $form->isValid();
    }

    public function notValidResponse(Form $form) {
        return ['status' => false , 'errors' => $this->getErrorMessages($form)];
    }

    /**
     * @param Form $form
     *
     * @return array
     */
    private function getErrorMessages(Form $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }

        }

        return $errors;
    }
}