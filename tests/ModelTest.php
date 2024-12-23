<?php

use PHPUnit\Framework\TestCase;

use Src\Server\Database;
use Src\Models\User;

class ModelTest extends TestCase
{
    public function testUserGet()
    {
        Database::connect();

        $user = User::get(2);

        $this->assertEquals($user['data']->firstname, 'John');
        $this->assertEquals($user['data']->lastname, 'Doe');
    }
    public function testUserWhere()
    {
        Database::connect();

        $user = User::where([
            'role = "C"'
        ]);

        $this->assertEquals($user['data'][0]->lastname, 'Smith');
        $this->assertEquals($user['data'][2]->lastname, 'Davis');
        $this->assertEquals($user['data'][4]->lastname, 'Doe');
    }
}
