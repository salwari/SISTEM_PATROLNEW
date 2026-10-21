<?php

namespace App\Support;

use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;

class QrPng
{
    public static function make(string $text, int $size = 300, int $margin = 2): string
    {
        $matrix = Encoder::encode($text, ErrorCorrectionLevel::L())->getMatrix();
        $modules = $matrix->getWidth();
        $total = $modules + ($margin * 2);
        $scale = max(1, intdiv($size, $total));
        $pixels = $total * $scale;

        $base = imagecreatetruecolor($pixels, $pixels);
        $white = imagecolorallocate($base, 255, 255, 255);
        $black = imagecolorallocate($base, 0, 0, 0);
        imagefilledrectangle($base, 0, 0, $pixels - 1, $pixels - 1, $white);

        for ($y = 0; $y < $modules; $y++) {
            for ($x = 0; $x < $modules; $x++) {
                if ($matrix->get($x, $y) === 1) {
                    $px = ($x + $margin) * $scale;
                    $py = ($y + $margin) * $scale;
                    imagefilledrectangle($base, $px, $py, $px + $scale - 1, $py + $scale - 1, $black);
                }
            }
        }

        if ($pixels !== $size) {
            $final = imagecreatetruecolor($size, $size);
            imagecopyresized($final, $base, 0, 0, 0, 0, $size, $size, $pixels, $pixels);
            $base = $final;
        }

        ob_start();
        imagepng($base);
        return ob_get_clean();
    }
}