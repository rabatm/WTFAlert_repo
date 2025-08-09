<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des foyers</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size:12px; }
        h1 { font-size:16px; }
        table { width:100%; border-collapse: collapse; }
        th, td { border:1px solid #ccc; padding:4px; text-align:left; }
        th { background:#f0f0f0; }
    </style>
</head>
<body>
<h1>Liste des foyers</h1>
<table>
    <thead>
    <tr>
        <th>Nom</th>
        @foreach($columns as $col)
            @if($col !== 'nom')
                <th>{{ $col }}</th>
            @endif
        @endforeach
        <th>Habitants</th>
    </tr>
    </thead>
    <tbody>
    @foreach($foyers as $foyer)
        <tr>
            <td>{{ $foyer->nom }}</td>
            @foreach($columns as $col)
                @if($col !== 'nom')
                    <td>
                        @php($value = $foyer->$col ?? '')
                        @if($col === 'secteurs')
                            {{ $foyer->secteurs->pluck('nom')->join(', ') }}
                        @else
                            {{ is_bool($value) ? ($value ? 'Oui' : 'Non') : $value }}
                        @endif
                    </td>
                @endif
            @endforeach
            <td>
                @foreach($foyer->habitants as $h)
                    {{ $h->user?->prenom }} {{ $h->user?->nom }}<br>
                @endforeach
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
