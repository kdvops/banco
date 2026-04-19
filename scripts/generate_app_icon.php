<?php

declare(strict_types=1);

if (!extension_loaded('gd')) {
    fwrite(STDERR, "La extension GD no esta disponible.\n");
    exit(1);
}

$root = dirname(__DIR__);
$pwaDir = $root . DIRECTORY_SEPARATOR . 'pwa';

if (!is_dir($pwaDir) && !mkdir($pwaDir, 0777, true) && !is_dir($pwaDir)) {
    fwrite(STDERR, "No se pudo crear el directorio pwa.\n");
    exit(1);
}

generate_icon(192, $pwaDir . DIRECTORY_SEPARATOR . 'icon-192.png');
generate_icon(512, $pwaDir . DIRECTORY_SEPARATOR . 'icon-512.png');
generate_icon(180, $pwaDir . DIRECTORY_SEPARATOR . 'apple-touch-icon.png');
generate_icon(192, $pwaDir . DIRECTORY_SEPARATOR . 'icon-maskable-192.png', true);
generate_icon(512, $pwaDir . DIRECTORY_SEPARATOR . 'icon-maskable-512.png', true);

echo "Iconos PWA generados correctamente.\n";

function generate_icon(int $size, string $outputPath, bool $maskable = false): void
{
    $image = imagecreatetruecolor($size, $size);
    if ($image === false) {
        throw new RuntimeException('No se pudo crear el lienzo.');
    }

    imagealphablending($image, true);
    imagesavealpha($image, true);
    imageantialias($image, true);

    $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
    imagefill($image, 0, 0, $transparent);

    $radius = (int) round($size * ($maskable ? 0.23 : 0.19));
    draw_gradient_background($image, $size, $radius);
    draw_glow($image, $size, $radius);
    draw_accent_orbs($image, $size);
    draw_back_card($image, $size);
    draw_front_card($image, $size);
    draw_wallet_pocket($image, $size);

    imagepng($image, $outputPath, 9);
    imagedestroy($image);
}

function draw_gradient_background(GdImage $image, int $size, int $radius): void
{
    $top = [23, 51, 89];
    $mid = [17, 34, 59];
    $bottom = [10, 124, 255];

    for ($y = 0; $y < $size; $y++) {
        $t = $y / max(1, $size - 1);
        if ($t < 0.58) {
            $mix = $t / 0.58;
            $rgb = mix_rgb($top, $mid, $mix);
        } else {
            $mix = ($t - 0.58) / 0.42;
            $rgb = mix_rgb($mid, $bottom, $mix);
        }

        $color = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
        imagefilledrectangle($image, 0, $y, $size, $y, $color);
    }

    apply_rounded_mask($image, $size, $radius);
}

function draw_glow(GdImage $image, int $size, int $radius): void
{
    $glow = imagecreatetruecolor($size, $size);
    imagealphablending($glow, true);
    imagesavealpha($glow, true);
    $transparent = imagecolorallocatealpha($glow, 0, 0, 0, 127);
    imagefill($glow, 0, 0, $transparent);

    $cx = (int) round($size * 0.26);
    $cy = (int) round($size * 0.18);
    $max = (int) round($size * 0.18);

    for ($r = $max; $r > 0; $r--) {
        $alpha = (int) round(127 - (10 * ($r / $max)));
        $color = imagecolorallocatealpha($glow, 255, 255, 255, min(127, $alpha));
        imagefilledellipse($glow, $cx, $cy, $r * 2, $r * 2, $color);
    }

    apply_rounded_mask($glow, $size, $radius);
    imagecopy($image, $glow, 0, 0, 0, 0, $size, $size);
    imagedestroy($glow);
}

function draw_accent_orbs(GdImage $image, int $size): void
{
    $gold = imagecolorallocatealpha($image, 255, 209, 102, 8);
    $softBlue = imagecolorallocatealpha($image, 121, 199, 255, 106);

    imagefilledellipse(
        $image,
        (int) round($size * 0.78),
        (int) round($size * 0.22),
        (int) round($size * 0.17),
        (int) round($size * 0.17),
        $gold
    );

    imagefilledellipse(
        $image,
        (int) round($size * 0.27),
        (int) round($size * 0.8),
        (int) round($size * 0.2),
        (int) round($size * 0.2),
        $softBlue
    );
}

function draw_back_card(GdImage $image, int $size): void
{
    $x = (int) round($size * 0.28);
    $y = (int) round($size * 0.28);
    $w = (int) round($size * 0.31);
    $h = (int) round($size * 0.39);
    $r = (int) round($size * 0.045);

    draw_soft_shadow($image, $x, $y, $w, $h, $r, [8, 20, 42], 112, (int) round($size * 0.03));
    draw_rounded_rect_gradient($image, $x, $y, $w, $h, $r, [216, 234, 255], [155, 198, 255]);
}

function draw_front_card(GdImage $image, int $size): void
{
    $x = (int) round($size * 0.31);
    $y = (int) round($size * 0.27);
    $w = (int) round($size * 0.34);
    $h = (int) round($size * 0.42);
    $r = (int) round($size * 0.052);

    draw_soft_shadow($image, $x, $y, $w, $h, $r, [8, 20, 42], 102, (int) round($size * 0.035));
    draw_rounded_rect_gradient($image, $x, $y, $w, $h, $r, [255, 255, 255], [231, 241, 255]);

    $navy = imagecolorallocate($image, 17, 34, 59);
    $lineStrong = imagecolorallocate($image, 140, 188, 255);
    $lineSoft = imagecolorallocate($image, 191, 215, 255);
    $silhouette = imagecolorallocate($image, 219, 233, 255);

    imagefilledellipse(
        $image,
        (int) round($x + $w * 0.28),
        (int) round($y + $h * 0.3),
        (int) round($w * 0.22),
        (int) round($w * 0.22),
        $navy
    );

    imagefilledarc(
        $image,
        (int) round($x + $w * 0.5),
        (int) round($y + $h * 0.88),
        (int) round($w * 0.82),
        (int) round($h * 0.42),
        180,
        360,
        $silhouette,
        IMG_ARC_PIE
    );

    imagesetthickness($image, max(2, (int) round($size * 0.012)));
    imageline(
        $image,
        (int) round($x + $w * 0.18),
        (int) round($y + $h * 0.18),
        (int) round($x + $w * 0.82),
        (int) round($y + $h * 0.18),
        $lineStrong
    );
    imageline(
        $image,
        (int) round($x + $w * 0.18),
        (int) round($y + $h * 0.39),
        (int) round($x + $w * 0.64),
        (int) round($y + $h * 0.39),
        $lineSoft
    );
    imagesetthickness($image, 1);
}

function draw_wallet_pocket(GdImage $image, int $size): void
{
    $x = (int) round($size * 0.49);
    $y = (int) round($size * 0.5);
    $w = (int) round($size * 0.28);
    $h = (int) round($size * 0.19);
    $r = (int) round($size * 0.045);

    draw_soft_shadow($image, $x, $y, $w, $h, $r, [8, 20, 42], 102, (int) round($size * 0.024));
    draw_rounded_rect_gradient($image, $x, $y, $w, $h, $r, [23, 179, 181], [11, 122, 155]);

    $gold = imagecolorallocate($image, 255, 209, 102);
    $goldCore = imagecolorallocate($image, 255, 244, 204);

    imagefilledellipse(
        $image,
        (int) round($x + $w * 0.73),
        (int) round($y + $h * 0.5),
        (int) round($h * 0.4),
        (int) round($h * 0.4),
        $gold
    );
    imagefilledellipse(
        $image,
        (int) round($x + $w * 0.73),
        (int) round($y + $h * 0.5),
        (int) round($h * 0.16),
        (int) round($h * 0.16),
        $goldCore
    );
}

function draw_rounded_rect_gradient(GdImage $image, int $x, int $y, int $w, int $h, int $radius, array $start, array $end): void
{
    $shape = imagecreatetruecolor($w, $h);
    imagealphablending($shape, true);
    imagesavealpha($shape, true);
    $transparent = imagecolorallocatealpha($shape, 0, 0, 0, 127);
    imagefill($shape, 0, 0, $transparent);

    for ($yy = 0; $yy < $h; $yy++) {
        $mix = $yy / max(1, $h - 1);
        $rgb = mix_rgb($start, $end, $mix);
        $color = imagecolorallocate($shape, $rgb[0], $rgb[1], $rgb[2]);
        imagefilledrectangle($shape, 0, $yy, $w, $yy, $color);
    }

    apply_rounded_mask($shape, $w, $radius);
    imagecopy($image, $shape, $x, $y, 0, 0, $w, $h);
    imagedestroy($shape);
}

function draw_soft_shadow(GdImage $image, int $x, int $y, int $w, int $h, int $radius, array $rgb, int $alpha, int $spread): void
{
    $shadow = imagecreatetruecolor($w + ($spread * 2), $h + ($spread * 2));
    imagealphablending($shadow, true);
    imagesavealpha($shadow, true);
    $transparent = imagecolorallocatealpha($shadow, 0, 0, 0, 127);
    imagefill($shadow, 0, 0, $transparent);

    $base = imagecolorallocatealpha($shadow, $rgb[0], $rgb[1], $rgb[2], min(127, max(0, $alpha)));
    fill_rounded_rect($shadow, $spread, $spread, $w, $h, $radius, $base);

    for ($i = 0; $i < $spread; $i++) {
        imagefilter($shadow, IMG_FILTER_GAUSSIAN_BLUR);
    }

    imagecopy($image, $shadow, $x - $spread, $y - $spread + max(1, (int) round($spread * 0.35)), 0, 0, imagesx($shadow), imagesy($shadow));
    imagedestroy($shadow);
}

function apply_rounded_mask(GdImage $image, int $size, int $radius): void
{
    $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
    $corners = [
        [$radius - 1, $radius - 1, 0, 0],
        [$size - $radius, $radius - 1, $size - $radius, 0],
        [$radius - 1, $size - $radius, 0, $size - $radius],
        [$size - $radius, $size - $radius, $size - $radius, $size - $radius],
    ];

    foreach ($corners as [$cx, $cy, $startX, $startY]) {
        for ($x = $startX; $x < $startX + $radius; $x++) {
            for ($y = $startY; $y < $startY + $radius; $y++) {
                $dx = $x - $cx;
                $dy = $y - $cy;

                if (($dx * $dx) + ($dy * $dy) > ($radius * $radius)) {
                    imagesetpixel($image, $x, $y, $transparent);
                }
            }
        }
    }
}

function fill_rounded_rect(GdImage $image, int $x, int $y, int $w, int $h, int $radius, int $color): void
{
    imagefilledrectangle($image, $x + $radius, $y, $x + $w - $radius, $y + $h, $color);
    imagefilledrectangle($image, $x, $y + $radius, $x + $w, $y + $h - $radius, $color);
    imagefilledellipse($image, $x + $radius, $y + $radius, $radius * 2, $radius * 2, $color);
    imagefilledellipse($image, $x + $w - $radius, $y + $radius, $radius * 2, $radius * 2, $color);
    imagefilledellipse($image, $x + $radius, $y + $h - $radius, $radius * 2, $radius * 2, $color);
    imagefilledellipse($image, $x + $w - $radius, $y + $h - $radius, $radius * 2, $radius * 2, $color);
}

function mix_rgb(array $from, array $to, float $mix): array
{
    $mix = max(0.0, min(1.0, $mix));

    return [
        (int) round($from[0] + (($to[0] - $from[0]) * $mix)),
        (int) round($from[1] + (($to[1] - $from[1]) * $mix)),
        (int) round($from[2] + (($to[2] - $from[2]) * $mix)),
    ];
}
