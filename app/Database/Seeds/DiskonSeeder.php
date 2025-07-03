<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DiskonSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        for ($i = 0; $i < 10; $i++) {
            $data = [
                'tanggal' => date('Y-m-d', strtotime("+$i days")),
                'nominal'    => $faker->randomElement([50000, 100000, 150000, 200000]),
                'created_at' => date("Y-m-d H:i:s"),
            ]; 
            $this->db->table('diskon')->insert($data);
        }
    }
}