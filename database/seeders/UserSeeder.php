
<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Pelayan',
            'email' => 'pelayan@gmail.com',
            'role' => 'pelayan',
            'password' => Hash::make('pelayan1234'),
        ]);

        User::create([
            'name' => 'Kasir',
            'email' => 'kasir@gmail.com',
            'role' => 'kasir',
            'password' => Hash::make('kasir1234'),
        ]);

        User::create([
            'name' => 'Koki',
            'email' => 'koki@gmail.com',
            'role' => 'koki',
            'password' => Hash::make('koki1234'),
        ]);
    }
}
