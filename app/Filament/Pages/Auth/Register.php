<?php

namespace App\Filament\Pages\Auth;

use App\Enums\UserType;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;

class Register extends BaseRegister
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        TextInput::make('business_name')->required(),
                        TextInput::make('tin'),
                        TextInput::make('website')->rules([
                            'url'
                        ])->required(),
                        TextInput::make('phone')->rules([
                            'phone'
                        ]),
                        TextInput::make('address'),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function handleRegistration(array $data): Model
    {
        $user = $this->getUserModel()::create([
            'name' => data_get($data, 'name'),
            'email' => data_get($data, 'email'),
            'type' => UserType::SUPPLIER->value,
            'password' => data_get($data, 'password')
        ]);

        $user->supplier()->create([
            'name' => data_get($data, 'business_name'),
            'tin' => data_get($data, 'tin'),
            'website' => data_get($data, 'website'),
            'phone' => data_get($data, 'phone'),
            'address' => data_get($data, 'address'),
        ]);

        return $user;
    }

}
