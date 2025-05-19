<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <style>
    @page {
      size: A4 portrait;
      margin: 10mm;
    }

    body {
      margin: 0;
      font-family: sans-serif;
      font-size: 11px;
    }

    .page {
      width: 190mm;
      height: 277mm; /* A4 height (297mm) - 10mm top/bottom margin */
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .container {
      width: 190mm;
      min-height: 100%;
    }

    .card {
      float: left;
      width: 60mm;
      height: 90mm;
      margin-right: 5mm;
      margin-bottom: 5mm;
      border: 1px dashed #ccc;
      box-sizing: border-box;
      padding: 8px;
      text-align: center;
      page-break-inside: avoid;
    }

    .card:nth-child(3n) {
      margin-right: 0;
    }

    .name {
      font-weight: bold;
      font-size: 12px;
      text-transform: uppercase;
      margin-bottom: 4px;
    }

    .info {
      font-size: 10px;
      margin-bottom: 2px;
    }

    .type {
      font-size: 10px;
      font-weight: bold;
      margin-bottom: 6px;
      text-transform: uppercase;
    }

    .card img {
      width: 120px;
      height: 120px;
      margin-top: 6px;
    }

    .clearfix::after {
      content: "";
      display: block;
      clear: both;
    }

    .page-break {
      page-break-after: always;
    }
  </style>
</head>
<body>
  @php $count = 0; @endphp

  <div class="page">
    <div class="container clearfix">
      @foreach($people as $person)
        @php
          $isStudent = get_class($person) === App\Models\Student::class;
          $isGuardian = get_class($person) === App\Models\Guardian::class;
          $isFunctionary = get_class($person) === App\Models\Functionary::class;

          $typeLabel = $isStudent ? 'Aluno' : ($isGuardian ? 'Responsável' : 'Funcionário');
          $registration = $isStudent ? ($person->registration_number ?? null) : null;
          $cpf = !$isStudent ? ($person->cpf ?? null) : null;
          $cpfFormatted = $cpf ? preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf) : null;
        @endphp

        <div class="card">
          <div class="name">{{ $person->name }}</div>

          @if ($registration)
            <div class="info">Matrícula: {{ $registration }}</div>
          @elseif ($cpfFormatted)
            <div class="info">CPF: {{ $cpfFormatted }}</div>
          @endif

          <div class="type">{{ $typeLabel }}</div>
          <img src="data:image/png;base64,{{ $person->qr_base64 }}">
        </div>

        @php
          $count++;
          if ($count % 9 === 0)
            echo '</div></div><div class="page"><div class="container clearfix">';
        @endphp
      @endforeach
    </div>
  </div>
</body>
</html>
