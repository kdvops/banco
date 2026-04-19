<?php

$outputDir = __DIR__ . '/../imagen';

if (!is_dir($outputDir) && !mkdir($outputDir, 0777, true) && !is_dir($outputDir)) {
    fwrite(STDERR, "No se pudo crear el directorio de salida.\n");
    exit(1);
}

$entities = [
    [
        'name' => 'Banco BHD',
        'filename' => 'do-banco-bhd.svg',
        'mark' => 'BHD',
        'accent' => '#F58220',
        'accentSoft' => '#FFE1BE',
        'category' => 'Banco multiple',
    ],
    [
        'name' => 'Banco Ademi',
        'filename' => 'do-banco-ademi.svg',
        'mark' => 'ADEMI',
        'accent' => '#C61D5A',
        'accentSoft' => '#FFD6E3',
        'category' => 'Banco multiple',
    ],
    [
        'name' => 'Banreservas',
        'filename' => 'do-banreservas.svg',
        'mark' => 'BANRES',
        'accent' => '#0055A5',
        'accentSoft' => '#D5E8FF',
        'category' => 'Banco multiple',
    ],
    [
        'name' => 'Banco Santa Cruz',
        'filename' => 'do-banco-santa-cruz.svg',
        'mark' => 'SC',
        'accent' => '#00A2D9',
        'accentSoft' => '#D7F3FF',
        'category' => 'Banco multiple',
    ],
    [
        'name' => 'Bancamerica',
        'filename' => 'do-bancamerica.svg',
        'mark' => 'BAM',
        'accent' => '#D72C2C',
        'accentSoft' => '#FFD9D9',
        'category' => 'Banco multiple',
    ],
    [
        'name' => 'Banco Activo Dominicana',
        'filename' => 'do-banco-activo.svg',
        'mark' => 'ACTIVO',
        'accent' => '#0FA37F',
        'accentSoft' => '#D9FFF3',
        'category' => 'Banco multiple',
    ],
    [
        'name' => 'Banco BDI',
        'filename' => 'do-banco-bdi.svg',
        'mark' => 'BDI',
        'accent' => '#1E4AA8',
        'accentSoft' => '#DBE6FF',
        'category' => 'Banco multiple',
    ],
    [
        'name' => 'Banco Caribe',
        'filename' => 'do-banco-caribe.svg',
        'mark' => 'CARIBE',
        'accent' => '#00A6A6',
        'accentSoft' => '#D8FFFF',
        'category' => 'Banco multiple',
    ],
    [
        'name' => 'Banco del Progreso',
        'filename' => 'do-banco-del-progreso.svg',
        'mark' => 'PROGRESO',
        'accent' => '#6B2FB3',
        'accentSoft' => '#E9D7FF',
        'category' => 'Banco multiple',
    ],
    [
        'name' => 'Banco Lafise',
        'filename' => 'do-banco-lafise.svg',
        'mark' => 'LAFISE',
        'accent' => '#D63A31',
        'accentSoft' => '#FFE0DD',
        'category' => 'Banco multiple',
    ],
    [
        'name' => 'Banco Lopez de Haro',
        'filename' => 'do-banco-lopez-de-haro.svg',
        'mark' => 'LDH',
        'accent' => '#5444C8',
        'accentSoft' => '#E4E0FF',
        'category' => 'Banco multiple',
    ],
    [
        'name' => 'Banco Popular Dominicano',
        'filename' => 'do-banco-popular-dominicano.svg',
        'mark' => 'POPULAR',
        'accent' => '#0052B8',
        'accentSoft' => '#D7E5FF',
        'category' => 'Banco multiple',
    ],
    [
        'name' => 'Banco Promerica',
        'filename' => 'do-banco-promerica.svg',
        'mark' => 'PROMERICA',
        'accent' => '#00A859',
        'accentSoft' => '#D8FFE9',
        'category' => 'Banco multiple',
    ],
    [
        'name' => 'Banco Vimenca',
        'filename' => 'do-banco-vimenca.svg',
        'mark' => 'VIMENCA',
        'accent' => '#0093D0',
        'accentSoft' => '#D6F2FF',
        'category' => 'Banco multiple',
    ],
    [
        'name' => 'Banesco',
        'filename' => 'do-banesco.svg',
        'mark' => 'BANESCO',
        'accent' => '#0A8F63',
        'accentSoft' => '#D8FFEF',
        'category' => 'Banco multiple',
    ],
    [
        'name' => 'BellBank',
        'filename' => 'do-bellbank.svg',
        'mark' => 'BELL',
        'accent' => '#3A7BD5',
        'accentSoft' => '#DDEBFF',
        'category' => 'Banco multiple',
    ],
    [
        'name' => 'Citibank',
        'filename' => 'do-citibank.svg',
        'mark' => 'CITI',
        'accent' => '#1A4DB3',
        'accentSoft' => '#DCE7FF',
        'category' => 'Banco multiple',
    ],
    [
        'name' => 'Scotiabank',
        'filename' => 'do-scotiabank.svg',
        'mark' => 'SCOTIA',
        'accent' => '#E31B23',
        'accentSoft' => '#FFE0E2',
        'category' => 'Banco multiple',
    ],
    [
        'name' => 'Asociacion Bonao de Ahorros y Prestamos',
        'filename' => 'do-asociacion-bonao.svg',
        'mark' => 'ABONAP',
        'accent' => '#0B8F75',
        'accentSoft' => '#D6FFF4',
        'category' => 'Asociacion',
    ],
    [
        'name' => 'Asociacion Cibao de Ahorros y Prestamos',
        'filename' => 'do-asociacion-cibao.svg',
        'mark' => 'ACAP',
        'accent' => '#008E6F',
        'accentSoft' => '#D6FFF0',
        'category' => 'Asociacion',
    ],
    [
        'name' => 'Asociacion Duarte de Ahorros y Prestamos',
        'filename' => 'do-asociacion-duarte.svg',
        'mark' => 'ADAP',
        'accent' => '#1D8F5F',
        'accentSoft' => '#DFFFEF',
        'category' => 'Asociacion',
    ],
    [
        'name' => 'Asociacion La Nacional de Ahorros y Prestamos',
        'filename' => 'do-asociacion-la-nacional.svg',
        'mark' => 'ALNAP',
        'accent' => '#0A7D78',
        'accentSoft' => '#D8FFFD',
        'category' => 'Asociacion',
    ],
    [
        'name' => 'Asociacion La Vega Real de Ahorros y Prestamos',
        'filename' => 'do-asociacion-la-vega-real.svg',
        'mark' => 'ALVR',
        'accent' => '#008B86',
        'accentSoft' => '#D6FFFF',
        'category' => 'Asociacion',
    ],
    [
        'name' => 'Asociacion Maguana de Ahorros y Prestamos',
        'filename' => 'do-asociacion-maguana.svg',
        'mark' => 'AMAG',
        'accent' => '#1B9C85',
        'accentSoft' => '#DDFFF7',
        'category' => 'Asociacion',
    ],
    [
        'name' => 'Asociacion Mocana de Ahorros y Prestamos',
        'filename' => 'do-asociacion-mocana.svg',
        'mark' => 'AMOC',
        'accent' => '#198C96',
        'accentSoft' => '#D9FBFF',
        'category' => 'Asociacion',
    ],
    [
        'name' => 'Asociacion Peravia de Ahorros y Prestamos',
        'filename' => 'do-asociacion-peravia.svg',
        'mark' => 'APER',
        'accent' => '#168B7E',
        'accentSoft' => '#D9FFF7',
        'category' => 'Asociacion',
    ],
    [
        'name' => 'Asociacion Popular de Ahorros y Prestamos',
        'filename' => 'do-asociacion-popular.svg',
        'mark' => 'APAP',
        'accent' => '#0072CE',
        'accentSoft' => '#D9ECFF',
        'category' => 'Asociacion',
    ],
    [
        'name' => 'Asociacion Romana de Ahorros y Prestamos',
        'filename' => 'do-asociacion-romana.svg',
        'mark' => 'ARAP',
        'accent' => '#227D89',
        'accentSoft' => '#DFFAFF',
        'category' => 'Asociacion',
    ],
];

foreach ($entities as $entity) {
    $svg = buildSvg($entity['name'], $entity['mark'], $entity['accent'], $entity['accentSoft'], $entity['category']);
    file_put_contents($outputDir . '/' . $entity['filename'], $svg);
}

echo 'Generados ' . count($entities) . " iconos SVG para entidades dominicanas.\n";

function buildSvg(string $name, string $mark, string $accent, string $accentSoft, string $category): string
{
    $safeName = escapeSvg($name);
    $safeMark = escapeSvg($mark);
    $safeCategory = escapeSvg($category);

    return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="256" height="256" viewBox="0 0 256 256" role="img" aria-labelledby="title desc">
  <title id="title">{$safeName}</title>
  <desc id="desc">Icono generado localmente para {$safeName}</desc>
  <defs>
    <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#FFFFFF" />
      <stop offset="100%" stop-color="{$accentSoft}" />
    </linearGradient>
    <linearGradient id="pill" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{$accent}" />
      <stop offset="100%" stop-color="#10233C" />
    </linearGradient>
  </defs>

  <rect width="256" height="256" rx="52" fill="url(#bg)" />
  <circle cx="220" cy="38" r="42" fill="{$accentSoft}" opacity="0.85" />
  <circle cx="38" cy="228" r="44" fill="{$accentSoft}" opacity="0.7" />
  <rect x="28" y="28" width="200" height="200" rx="42" fill="#FFFFFF" opacity="0.92" />
  <rect x="44" y="44" width="168" height="112" rx="30" fill="url(#pill)" />
  <text x="128" y="111" text-anchor="middle" font-family="Segoe UI, Arial, sans-serif" font-size="34" font-weight="800" fill="#FFFFFF" letter-spacing="1">{$safeMark}</text>
  <text x="128" y="185" text-anchor="middle" font-family="Segoe UI, Arial, sans-serif" font-size="20" font-weight="700" fill="#10233C">{$safeCategory}</text>
</svg>
SVG;
}

function escapeSvg(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
}
