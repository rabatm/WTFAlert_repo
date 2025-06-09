<!DOCTYPE html>
<html>
<head>
    <title>Nouvelle Alerte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-info {
            background-color: #d1ecf1;
            padding: 15px;
            border-left: 4px solid #bee5eb;
            margin-bottom: 10px;
        }
        .alert-danger {
            background-color: #f8d7da;
            padding: 15px;
            border-left: 4px solid #f5c6cb;
            margin-bottom: 10px;
        }
        .alert-alert {
            background-color: #fff3cd;
            padding: 15px;
            border-left: 4px solid #ffeaa7;
            margin-bottom: 10px;
        }
        .alert-accident {
            background-color: #ffe8d1;
            padding: 15px;
            border-left: 4px solid #fd7e14;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if($alerte->type === 'info')
                <h1>ℹ️ Nouvelle information signalée</h1>
            @elseif($alerte->type === 'danger')
                <h1>⚠️ Nouveau danger signalé</h1>
            @elseif($alerte->type === 'accident')
                <h1>🚗 Nouveau accident signalé</h1>
            @else
                <h1>🚨 Nouvelle alerte signalée</h1>
            @endif
        </div>
        
        <div class="alert-{{ $alerte->type }}">
            <p><strong>Type:</strong> 
                @if($alerte->type === 'info')
                    ℹ️ Information
                @elseif($alerte->type === 'danger')
                    ⚠️ Danger
                @elseif($alerte->type === 'accident')
                    🚗 Accident
                @else
                    🚨 Alerte
                @endif
            </p>
            <p><strong>Titre:</strong> {{ $alerte->titre }}</p>
            <p><strong>Description:</strong> {{ $alerte->description }}</p>
            <p><strong>Localisation:</strong> {{ $alerte->localisation ?? 'Non spécifiée' }}</p>
            <p><strong>Date:</strong> {{ $alerte->created_at->format('d/m/Y à H:i') }}</p>
            
            @if($alerte->latitude && $alerte->longitude)
                <p><strong>Coordonnées GPS:</strong> {{ $alerte->latitude }}, {{ $alerte->longitude }}</p>
            @endif
            
            @if(!$alerte->anonyme && $alerte->habitant)
                <p><strong>Signalé par:</strong> {{ $alerte->habitant->prenom_hb }} {{ $alerte->habitant->nom_hb }}</p>
            @else
                <p><strong>Signalé par:</strong> Utilisateur anonyme</p>
            @endif
            
            @if($alerte->photos && count($alerte->photos) > 0)
                <p><strong>📷 Photos jointes:</strong> {{ count($alerte->photos) }} photo(s) en pièce(s) jointe(s)</p>
                <div style="background-color: #e7f3ff; padding: 10px; border-radius: 5px; margin-top: 10px;">
                    <small>💡 <em>Les photos sont disponibles en pièces jointes de cet email.</em></small>
                </div>
            @endif
        </div>
        
        <div class="footer">
            <p>Cet email a été envoyé automatiquement par le système WTFAlert.</p>
            <p>Merci de ne pas répondre directement à cet email.</p>
        </div>
    </div>
</body>
</html>
