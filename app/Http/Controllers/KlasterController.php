<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class KlasterController extends Controller
{
    private function getKlasterSettings($prefix, $klaster)
    {
        return Setting::where('type', 'statis')
            ->where(function($query) use ($prefix, $klaster) {
                $query->where('url', "{$prefix}/klaster-{$klaster}")
                    ->orWhere('url', str_replace('-', '_', "{$prefix}/klaster-{$klaster}"));
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function klaster1()
    {
        $settings = $this->getKlasterSettings('pemenuhan-hak-anak', '1');
        return view('pemenuhan-hak-anak.klaster1', [
            'settings' => $settings,
            'hasAdditionalContent' => $settings->isNotEmpty()
        ]);
    }

    public function klaster2()
    {
        $settings = $this->getKlasterSettings('pemenuhan-hak-anak', '2');
        return view('pemenuhan-hak-anak.klaster2', [
            'settings' => $settings,
            'hasAdditionalContent' => $settings->isNotEmpty()
        ]);
    }

    public function klaster3()
    {
        $settings = $this->getKlasterSettings('pemenuhan-hak-anak', '3');
        return view('pemenuhan-hak-anak.klaster3', [
            'settings' => $settings,
            'hasAdditionalContent' => $settings->isNotEmpty()
        ]);
    }

    public function klaster4()
    {
        $settings = $this->getKlasterSettings('pemenuhan-hak-anak', '4');
        return view('pemenuhan-hak-anak.klaster4', [
            'settings' => $settings,
            'hasAdditionalContent' => $settings->isNotEmpty()
        ]);
    }

    public function klaster5()
    {
        $settings = $this->getKlasterSettings('perlindungan-khusus-anak', '5');
        return view('perlindungan-khusus-anak.klaster5', [
            'settings' => $settings,
            'hasAdditionalContent' => $settings->isNotEmpty()
        ]);
    }
} 