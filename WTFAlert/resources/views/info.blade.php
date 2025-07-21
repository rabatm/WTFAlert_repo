<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; }
        .card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .secteur-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .secteur-card {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            background: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>{{ $title }}</h1>
            <p>{{ $description }}</p>
            <div class="secteur-grid">
                @foreach($mairies as $mairie)
                    <div class="secteur-card">
                        <h2>{{ $mairie['name'] }}</h2>
                        <p><strong>Adresse:</strong> {{ $mairie['address'] }}</p>
                        <p><strong>Téléphone:</strong> {{ $mairie['phone'] }}</p>
                        <p><strong>Email:</strong> {{ $mairie['email'] }}</p>
                        <p><strong>Site Web:</strong> <a href="{{ $mairie['website'] }}" target="_blank">{{ $mairie['website'] }}</a></p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>
