<?php

use PHPUnit\Framework\TestCase;

use Src\Server\Validator;

class ValidatorTest extends TestCase
{
    public function testEmailPasswordSuccess()
    {
        $validator = new Validator(
            [
                'example@example.com',
                '%^*password#?$847'
            ],
            [
                '/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/',
                '/^[\w\-\.#?!@$%^&*]{8,32}$/',
            ],
            [
                'Адрес почты недействителен',
                'Пароль должен состоять из букв латинского алфавита, цифр, символов .#?!@$%^&*- и иметь длину от 8 до 32 символов',
            ]
        );

        $result = $validator->validate();

        $this->assertEquals($result, true);
    }
    public function testEmailPasswordFail()
    {
        $validator = new Validator(
            [
                'example@example.com',
                '123'
            ],
            [
                '/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/',
                '/^[\w\-\.#?!@$%^&*]{8,32}$/',
            ],
            [
                'Адрес почты недействителен',
                'Пароль должен состоять из букв латинского алфавита, цифр, символов .#?!@$%^&*- и иметь длину от 8 до 32 символов',
            ]
        );

        $result = $validator->validate();

        $this->assertEquals($result, true);
        $this->assertEquals($validator->last_message, 'Пароль должен состоять из букв латинского алфавита, цифр, символов .#?!@$%^&*- и иметь длину от 8 до 32 символов');
    }
}
