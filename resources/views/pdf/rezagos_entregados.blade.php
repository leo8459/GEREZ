<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formularios de Entrega - Rezagos</title>
    <style>
        /* Márgenes generosos para Epson LQ-590 */
        @page {
            margin-top: 14mm;
            margin-bottom: 14mm;
            margin-left: 150mm;   /* margen lateral amplio */
            margin-right: 150mm;  /* margen lateral amplio */
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }

        .pair {
            border: 1px solid #000;
            padding: 12px 14px;
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .container { width: 100%; }
        .center-text { text-align: center; }
        .small-text { font-size: 12px; line-height: 1.25; }
        .normal-text { font-size: 13px; line-height: 1.25; }
        .special-text { text-align: center; font-size: 11px; }
        .logo img { display: block; }
        .row { display: table; width: 100%; table-layout: fixed; }
        .col { display: table-cell; width: 50%; vertical-align: top; padding: 2px 6px; }
        .barcode { margin: 4px 0 2px; }
        .mh-5 { margin: 6px 0; }
        .divider { border-top: 1px dashed #333; margin: 8px 0; }
        .sign { margin-top: 8px; }
        .copy-title { font-size: 10px; text-align:center; opacity:.85; margin-top: 2px; }
        .meta { font-size: 10px; text-align:right; margin-bottom: 4px; }

        /* Línea de firma estable para matriciales */
        .line {
            display: block;
            border-bottom: 1px solid #000;
            width: 70%;
            height: 10px;
            margin: 0 auto 2px;
        }
    </style>
</head>
<body>
@php
    // Helper para mostrar "—" si viene nulo o vacío
    function show($v){ $t = trim((string)($v ?? '')); return $t === '' ? '—' : e($t); }
@endphp

@foreach ($items as $item)
    {{-- 2 copias por rezago (Cliente / AGBC) --}}
    <div class="pair">
        <div class="meta">
            <strong>Generado:</strong> {{ $fecha }} &nbsp; | &nbsp; <strong>Usuario:</strong> {{ $usuario }}
        </div>

        {{-- Copia 1 - Cliente --}}
        <div class="container">
            <div class="logo">
                {{-- Asegúrate de tener el archivo en public/images/images.png --}}
                <img src="{{ public_path('images/images.png') }}" alt="AGBC" width="100" height="50">
            </div>
            <div class="center-text">
                <h2 class="normal-text" style="margin-top: 0;">FORMULARIO DE ENTREGA</h2>
                <h3 class="normal-text">AGENCIA BOLIVIANA DE CORREOS</h3>
                <div class="copy-title">COPIA CLIENTE</div>
            </div>

            <div class="row mh-5">
                <div class="col">
                    @php
                        // Código de barras como PNG base64 (estable para DomPDF + matricial)
                        $barcodePng = \Milon\Barcode\Facades\DNS1DFacade::getBarcodePNG($item->codigo, 'C128', 1.25, 35);
                    @endphp
                    <p class="barcode">
                        <img src="data:image/png;base64,{{ $barcodePng }}" alt="barcode" />
                    </p>
                    <p class="small-text"><strong>Código Rastreo:</strong> {{ show($item->codigo) }}</p>
                    <p class="small-text"><strong>Destinatario:</strong> {{ show($item->destinatario) }}</p>
                    <p class="small-text"><strong>Teléfono:</strong> {{ show($item->telefono) }}</p>
                    <p class="small-text"><strong>Ciudad:</strong> {{ show($item->ciudad) }}</p>
                    <p class="small-text"><strong>Zona:</strong> {{ show($item->zona) }}</p>
                    <p class="small-text"><strong>Aduana:</strong> {{ show($item->aduana) }}</p>
                </div>
                <div class="col">
                    <p class="small-text"><strong>Tipo:</strong> {{ show($item->tipo) }}</p>
                    <p class="small-text"><strong>Peso:</strong> {{ show($item->peso) }} kg</p>
                    <p class="small-text"><strong>Estado:</strong> {{ show($item->estado) }}</p>
                    <p class="small-text"><strong>Observación:</strong> {{ show($item->observacion) }}</p>
                    <p class="small-text"><strong>Fecha Entrega:</strong> {{ $fecha }}</p>
                </div>
            </div>

            <div class="row sign">
                <div class="col center-text">
                    <span class="line"></span>
                    <p class="special-text">RECIBIDO POR</p>
                    <p class="special-text">{{ show($item->destinatario) }}</p>
                </div>
                <div class="col center-text">
                    <span class="line"></span>
                    <p class="special-text">ENTREGADO POR</p>
                    <p class="special-text">{{ $usuario }}</p>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        {{-- Copia 2 - AGBC --}}
        <div class="container">
            <div class="logo">
                <img src="{{ public_path('images/images.png') }}" alt="AGBC" width="100" height="50">
            </div>
            <div class="center-text">
                <h2 class="normal-text" style="margin-top: 0;">FORMULARIO DE ENTREGA</h2>
                <h3 class="normal-text">AGENCIA BOLIVIANA DE CORREOS</h3>
                <div class="copy-title">COPIA AGBC</div>
            </div>

            <div class="row mh-5">
                <div class="col">
                    @php
                        // Reusar el mismo PNG para consistencia
                        $barcodePng2 = $barcodePng ?? \Milon\Barcode\Facades\DNS1DFacade::getBarcodePNG($item->codigo, 'C128', 1.25, 35);
                    @endphp
                    <p class="barcode">
                        <img src="data:image/png;base64,{{ $barcodePng2 }}" alt="barcode" />
                    </p>
                    <p class="small-text"><strong>Código Rastreo:</strong> {{ show($item->codigo) }}</p>
                    <p class="small-text"><strong>Destinatario:</strong> {{ show($item->destinatario) }}</p>
                    <p class="small-text"><strong>Teléfono:</strong> {{ show($item->telefono) }}</p>
                    <p class="small-text"><strong>Ciudad:</strong> {{ show($item->ciudad) }}</p>
                    <p class="small-text"><strong>Zona:</strong> {{ show($item->zona) }}</p>
                    <p class="small-text"><strong>Aduana:</strong> {{ show($item->aduana) }}</p>
                </div>
                <div class="col">
                    <p class="small-text"><strong>Tipo:</strong> {{ show($item->tipo) }}</p>
                    <p class="small-text"><strong>Peso:</strong> {{ show($item->peso) }} kg</p>
                    <p class="small-text"><strong>Estado:</strong> {{ show($item->estado) }}</p>
                    <p class="small-text"><strong>Observación:</strong> {{ show($item->observacion) }}</p>
                    <p class="small-text"><strong>Fecha Entrega:</strong> {{ $fecha }}</p>
                </div>
            </div>

            <div class="row sign">
                <div class="col center-text">
                    <span class="line"></span>
                    <p class="special-text">RECIBIDO POR</p>
                    <p class="special-text">{{ show($item->destinatario) }}</p>
                </div>
                <div class="col center-text">
                    <span class="line"></span>
                    <p class="special-text">ENTREGADO POR</p>
                    <p class="special-text">{{ $usuario }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Salto entre items para que cada par quede limpio en la LQ --}}
    <div style="page-break-after: always;"></div>
@endforeach
</body>
</html>
    