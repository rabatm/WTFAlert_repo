<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            padding: 20px 0;
            text-align: center;
            border-bottom: 3px solid #f0f0f0;
            margin-bottom: 20px;
        }
        .logo {
            max-height: 60px;
        }
        .alert-title {
            font-size: 22px;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 4px;
            color: white;
            text-align: center;
        }
        .alert-info .alert-title {
            background-color: #3498db;
        }
        .alert-warning .alert-title {
            background-color: #f39c12;
        }
        .alert-danger .alert-title {
            background-color: #e74c3c;
        }
        .content {
            padding: 0 20px;
        }
        .detail-block {
            background-color: #f9f9f9;
            border-left: 4px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 0 4px 4px 0;
        }
        .detail-label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .detail-value {
            margin-bottom: 0;
        }
        .photos-section {
            margin-top: 25px;
        }
        .photo-container {
            margin-bottom: 15px;
        }
        .photo-container img {
            max-width: 100%;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- Vous pouvez ajouter un logo ici -->
        <h1>WTFAlert</h1>
    </div>

    <div class="alert-{{ $alerte->type === 'info' ? 'info' : ($alerte->type === 'alert' ? 'warning' : ($alerte->type === 'accident' ? 'warning' : 'danger')) }}">
        <div class="alert-title">
            {{ $alerte->type === 'info' ? 'Information' : ($alerte->type === 'alert' ? 'Avertissement' : ($alerte->type === 'accident' ? 'Accident' : 'Alerte urgente')) }}
        </div>

        <div class="content">
            <p>Une nouvelle alerte a été signalée dans votre secteur.</p>
            
            <div class="detail-block">
                <div class="detail-label">Titre</div>
                <p class="detail-value">{{ $alerte->titre }}</p>
            </div>
            
            <div class="detail-block">
                <div class="detail-label">Description</div>
                <p class="detail-value">{{ $alerte->description }}</p>
            </div>
            
            @if($alerte->localisation)
            <div class="detail-block">
                <div class="detail-label">Localisation</div>
                <p class="detail-value">{{ $alerte->localisation }}</p>
            </div>
            @endif

            @if($photos && $photos->count())
            <div class="photos-section">
                <h3>Photos de l'alerte en PJ</h3>
            </div>
            @endif
            <p>Merci de rester vigilant et d'agir en conséquence.</p>
        </div>
    </div>

    <div class="footer">
        <p>Cet email a été envoyé automatiquement par le système WTFAlert.</p>
        <p>© {{ date('Y') }} WTFAlert - Tous droits réservés</p>
    </div>
</body>
</html>