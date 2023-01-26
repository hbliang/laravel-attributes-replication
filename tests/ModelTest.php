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

        foreach ($company->users as $user) {
            $this->assertEquals($user->name, 'name');
            $this->assertEquals($user->company_name, 'company');
            $this->assertEquals($user->company_link, 'link');
            $this->assertEquals($user->phone_number, 'phone_number');
            $this->assertEquals($user->phone->user_name, 'name');
            $this->assertEquals($user->phone->company_name, 'company');
            $this->assertEquals($user->phone->number, 'phone_number');
        }

        $company->name = 'new';
        $company->link = 'new';
        $company->save();


        foreach ($company->users as $user) {
            $this->assertEquals($user->name, 'name');
            $this->assertEquals($user->company_name, 'new');
            $this->assertEquals($user->company_link, 'link');
            $this->assertEquals($user->phone_number, 'phone_number');
            $this->assertEquals($user->phone->user_name, 'name');
            $this->assertEquals($user->phone->company_name, 'new');
            $this->assertEquals($user->phone->number, 'phone_number');

            $user->name = 'ok';
            $user->save();

            $this->assertEquals($user->name, 'ok');
            $this->assertEquals($user->company_name, 'new');
            $this->assertEquals($user->company_link, 'link');
            $this->assertEquals($user->phone_number, 'phone_number');
            $this->assertEquals($user->phone->user_name, 'ok');
            $this->assertEquals($user->phone->company_name, 'new');
            $this->assertEquals($user->phone->number, 'phone_number');
        }

        foreach ($company->users as $user) {
            $user->name = 'TEST';
            $user->save();
        }


        // TEST filter relation
        $company->link = 'new2';
        $company->save();

        foreach ($company->users as $user) {
            $this->assertEquals($user->company_link, 'new2');
        }
    }


    protected function toCompany()
    {
        $company = Company::create([
            'name' => 'company',
            'phone_number' => 'phone_number',
            'link' => 'link',
        ]);

        $phone = Phone::create([
            'number' => 'number',
        ]);

        $user = new User([
            'name' => 'name',
        ]);

        $user->phone()->associate($phone);
        $company->users()->save($user);

        return $company;
    }
}
