@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Liste des Habitants et leurs Foyers</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Foyers</th>
            </tr>
        </thead>
        <tbody>
            @foreach($habitants as $habitant)
                <tr>
                    <td>{{ $habitant->nom }}</td>
                    <td>{{ $habitant->prenom }}</td>
                    <td>{{ $habitant->email }}</td>
                    <td>{{ $habitant->telephone }}</td>
                    <td>
                        @if($habitant->foyers->count())
                            <ul>
                                @foreach($habitant->foyers as $foyer)
                                    <li>{{ $foyer->id }} - {{ $foyer->nom ?? 'N/A' }} (type: {{ $foyer->pivot->type_habitant }})</li>
                                @endforeach
                            </ul>
                        @else
                            Aucun foyer
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
