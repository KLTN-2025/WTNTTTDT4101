<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class DemoUsersSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		User::updateOrCreate(
			['email' => 'student@example.com'],
			[
				'name' => 'Student Demo',
				'password' => Hash::make('password'),
				'role' => 'student',
				'email_verified_at' => now(),
				'remember_token' => Str::random(10),
			]
		);

		User::updateOrCreate(
			['email' => 'teacher@example.com'],
			[
				'name' => 'Teacher Demo',
				'password' => Hash::make('password'),
				'role' => 'teacher',
				'email_verified_at' => now(),
				'remember_token' => Str::random(10),
			]
		);

		User::updateOrCreate(
			['email' => 'admin@example.com'],
			[
				'name' => 'Admin Demo',
				'password' => Hash::make('password'),
				'role' => 'admin',
				'email_verified_at' => now(),
				'remember_token' => Str::random(10),
			]
		);
	}
}
