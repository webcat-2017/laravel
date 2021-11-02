<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Rules\Password;



trait PasswordValidationRules
{


    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    public $length_password = 5;

    protected function passwordRules()
    {

        return [
            'required',
            'string',
            'confirmed',
            (new Password)
                ->length($this->length_password)
            #->requireUppercase()
            #->requireNumeric()
            #->requireSpecialCharacter(),
        ];
    }
}


