<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agentUser = User::create([
            'username' => 'Support Agent 01',
            'email'    => 'agent@support.com',
            'password' => Hash::make('agent123'),
            'role'     => 'agent',
        ]);

        Agent::create([
            'name'   => 'Support Agent',
            'email'  => $agentUser->email,
            'mobile' => '0771234567',
        ]);


    }
}
