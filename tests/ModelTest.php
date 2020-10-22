<?php

namespace Hbliang\AttributesReplication\Tests;

use Hbliang\AttributesReplication\Tests\Models\Company;
use Hbliang\AttributesReplication\Tests\Models\Phone;
use Hbliang\AttributesReplication\Tests\Models\User;

class ModelTest extends TestCase
{
    public function testSaved()
    {
        $company = $this->toCompany();

        $company->company = 'new';
        $company->save();


        foreach ($company->users as $user) {
            $this->assertEquals($user->company, 'new');
            $this->assertEquals($user->phone_number, 'phone_number');
            $this->assertEquals($user->name, 'name');
            $this->assertEquals($user->phone->company, 'new');
            $this->assertEquals($user->phone->number, 'number');
            $this->assertEquals($user->phone->user_name, 'user_name');

            $user->name = 'ok';
            $user->save();
            
            $this->assertEquals($user->phone->company, 'new');
            $this->assertEquals($user->phone->number, 'number');
            $this->assertEquals($user->phone->user_name, 'ok');
        }
    }

    protected function toCompany()
    {
        $company = Company::create([
            'company' => 'company',
            'phone_number' => 'phone_number',
        ]);

        $phone = Phone::create([
            'user_name' => 'user_name',
            'company' => 'company',
            'number' => 'number',
        ]);

        $user = new User([
            'company' => 'company',
            'phone_number' => 'phone_number',
            'name' => 'name',
        ]);

        $user->phone()->associate($phone);
        $company->users()->save($user);

        return $company;
    }
}
