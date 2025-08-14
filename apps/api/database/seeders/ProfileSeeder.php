<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        Profile::updateOrCreate(
            ['email' => 'ed.gualle@gmail.com'],
            [
                'name' => 'Edison Gualle',
                'role' => 'Desarrollador Full Stack',
                'bio_md' => 'Soy Edison Gualle, desarrollador especializado en Laravel, React y tecnologías modernas.',
                'photo_url' => null, // puedes poner URL o dejar null
                'phone' => null,
                'location' => 'Ecuador',
                'socials_json' => [
                    'github' => 'https://github.com/EdisonGualle',
                    'linkedin' => 'https://www.linkedin.com/in/edisongualle/',
                ],
            ]
        );
    }
}
