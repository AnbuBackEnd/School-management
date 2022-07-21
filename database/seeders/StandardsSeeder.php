<?php

namespace Database\Seeders;
use App\Http\Traits\StudentTrait;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StandardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    use StudentTrait;
    public function run()
    {
        DB::table('standards')->insert([[
            'id' => 1,
            'standard_name' => 'PRE-LKG',
            'encrypt_id' => $this->encryptData(1),
        ],
        [
            'id' => 2,
            'standard_name' => 'LKG',
            'encrypt_id' => $this->encryptData(2),
        ],
        [
            'id' => 3,
            'standard_name' => 'UKG',
            'encrypt_id' => $this->encryptData(3),
        ],
        [
            'id' => 4,
            'standard_name' => 'I',
            'encrypt_id' => $this->encryptData(4),
        ],
        [
            'id' => 5,
            'standard_name' => 'II',
            'encrypt_id' => $this->encryptData(5),
        ],
        [
            'id' => 6,
            'standard_name' => 'III',
            'encrypt_id' => $this->encryptData(6),
        ],
        [
            'id' => 7,
            'standard_name' => 'IV',
            'encrypt_id' => $this->encryptData(7),
        ],
        [
            'id' => 8,
            'standard_name' => 'V',
            'encrypt_id' => $this->encryptData(8),
        ],
        [
            'id' => 9,
            'standard_name' => 'VI',
            'encrypt_id' => $this->encryptData(9),
        ],
        [
            'id' => 10,
            'standard_name' => 'VII',
            'encrypt_id' => $this->encryptData(10),
        ],
        [
            'id' => 11,
            'standard_name' => 'VIII',
            'encrypt_id' => $this->encryptData(11),
        ],
        [
            'id' => 12,
            'standard_name' => 'IX',
            'encrypt_id' => $this->encryptData(12),
        ],
        [
            'id' => 13,
            'standard_name' => 'X',
            'encrypt_id' => $this->encryptData(13),
        ],
        [
            'id' => 14,
            'standard_name' => 'XI',
            'encrypt_id' => $this->encryptData(14),
        ],
        [
            'id' => 15,
            'standard_name' => 'XII',
            'encrypt_id' => $this->encryptData(15),
        ]
        ]);
    }
}
