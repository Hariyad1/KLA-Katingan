<?php

return array(
    'fontDir' => storage_path('fonts/'),
    'fontCache' => storage_path('fonts/'),
    'defaultFont' => 'sans-serif',
    'defaultMediaType' => 'screen',
    'dpi' => 96,
    'defaultPaperSize' => 'a4',
    'defaultPaperOrientation' => 'portrait',
    'isRemoteEnabled' => true,
    'isHtml5ParserEnabled' => true,
    'isFontSubsettingEnabled' => true,
    'debugKeepTemp' => false,
    'debugCss' => false,
    'debugLayout' => false,
    'debugLayoutLines' => false,
    'debugLayoutBlocks' => false,
    'debugLayoutInline' => false,
    'debugLayoutPaddingBox' => false,

    'chroot' => [
        base_path(),
        public_path(),
        storage_path(),
    ],

    'tempDir' => storage_path('app/temp'),
    'logOutputFile' => storage_path('logs/dompdf.html'),
    'isPhpEnabled' => true,
); 