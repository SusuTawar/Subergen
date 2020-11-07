<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $document = new Document();
        $document->user = '1';
        $document->title = 'Surat Jalan Pengambilan Barang';
        $document->slug = 'surat_jalan_pengambilan_barang';
        $document->category = 'surat jalan';
        $document->save();
    }
}
