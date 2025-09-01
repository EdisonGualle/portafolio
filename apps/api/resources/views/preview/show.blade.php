<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8"/>
  <meta name="robots" content="noindex,nofollow"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Vista previa</title>
  <style> body{font-family:ui-sans-serif,system-ui;padding:2rem;max-width:800px;margin:auto} </style>
</head>
<body>
  <h1>Vista previa: {{ class_basename($model) }}</h1>
  @php
    $title = $model->title ?? ($model->name ?? 'Sin título');
    $summary = $model->summary ?? ($model->excerpt ?? null);
  @endphp

  <p><strong>Título:</strong> {{ $title }}</p>
  @if($summary)
    <p><strong>Resumen:</strong> {{ $summary }}</p>
  @endif

  <p style="color:#666;margin-top:1rem;">Vence: {{ $token->expires_at->toDayDateTimeString() }}</p>
  <p style="color:#999;">(Luego tu frontend hará el render completo.)</p>
</body>
</html>
