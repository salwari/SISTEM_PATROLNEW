<?php

namespace App\Console\Commands;

use App\Support\Arsip;
use Illuminate\Console\Command;

class ArsipOtomatis extends Command
{
    protected $signature = 'arsip:otomatis';

    protected $description = 'Pindahkan data yang melewati batas hari ke arsip';

    public function handle(): int
    {
        foreach (Arsip::otomatis() as $tipe => $jumlah) {
            $this->info("{$tipe}: {$jumlah} data dipindahkan ke arsip");
        }

        return self::SUCCESS;
    }
}
