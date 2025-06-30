<!DOCTYPE html>
<html>
<head>
    <title>📋 Nouvelle Demande de Modification - {{ ucfirst(str_replace('_', ' ', $demande->type)) }}</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            line-height: 1.6; 
            color: #333; 
            margin: 0;
            padding: 0;
        }
        .container { 
            max-width: 650px; 
            margin: 0 auto; 
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #ffffff;
        }
        .header { 
            background-color: #4a6fa5; 
            color: white;
            padding: 20px; 
            text-align: center;
            border-radius: 6px 6px 0 0;
            margin: -20px -20px 20px -20px;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #4a6fa5;
            padding: 12px 15px;
            margin: 15px 0;
            border-radius: 0 4px 4px 0;
        }
        .detail-section {
            margin: 20px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 6px;
            border: 1px solid #eaeaea;
        }
        .detail-row {
            display: flex;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #e0e0e0;
        }
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .detail-label {
            font-weight: 600;
            color: #555;
            min-width: 180px;
        }
        .detail-value {
            flex: 1;
        }
        .footer { 
            margin-top: 30px; 
            padding-top: 15px; 
            border-top: 1px solid #eee; 
            font-size: 12px; 
            color: #777;
            text-align: center;
        }
        .button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #4a6fa5;
            color: white !important;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: 500;
            text-align: center;
        }
        .button-container {
            text-align: center;
            margin: 25px 0;
        }
        .status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin: 0; font-weight: 500;">📋 Nouvelle Demande de Modification</h2>
            <div style="margin-top: 10px; font-size: 14px; opacity: 0.9;">
                Référence #{{ $demande->id }} - 
                <span class="status status-pending">En attente</span>
            </div>
        </div>
        
        <div class="info-box">
            Une nouvelle demande de modification a été soumise et nécessite votre attention.
        </div>

        <div class="detail-section">
            <h3 style="margin-top: 0; color: #4a6fa5; border-bottom: 1px solid #eaeaea; padding-bottom: 8px;">
                Détails de la demande
            </h3>
            
            <div class="detail-row">
                <div class="detail-label">Type de demande :</div>
                <div class="detail-value">
                    <strong>{{ ucfirst(str_replace('_', ' ', $demande->type)) }}</strong>
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Date de la demande :</div>
                <div class="detail-value">
                    {{ $demande->created_at->format('d/m/Y à H:i') }}
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Demandeur :</div>
                <div class="detail-value">
                    {{ $demande->user->prenom }} {{ $demande->user->nom }}
                    <div style="color: #666; font-size: 13px; margin-top: 2px;">
                        {{ $demande->user->email }}
                        @if($demande->user->telephone_mobile)
                            • {{ $demande->user->telephone_mobile }}
                        @endif
                    </div>
                </div>
            </div>
            
            @if($demande->foyer)
            <div class="detail-row">
                <div class="detail-label">Foyer concerné :</div>
                <div class="detail-value">
                    <strong>{{ $demande->foyer->nom }}</strong>
                    <div style="color: #666; font-size: 13px; margin-top: 2px;">
                        {{ $demande->foyer->adresse_complete ?? '' }}
                    </div>
                </div>
            </div>
            @endif
            
            @if($demande->habitant)
            <div class="detail-row">
                <div class="detail-label">Habitant concerné :</div>
                <div class="detail-value">
                    {{ $demande->habitant->prenom }} {{ $demande->habitant->nom }}
                    @if($demande->habitant->date_naissance)
                        <div style="color: #666; font-size: 13px; margin-top: 2px;">
                            Né(e) le {{ \Carbon\Carbon::parse($demande->habitant->date_naissance)->format('d/m/Y') }}
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="detail-section">
            <h3 style="margin-top: 0; color: #4a6fa5; border-bottom: 1px solid #eaeaea; padding-bottom: 8px;">
                Message
            </h3>
            <div style="white-space: pre-line; line-height: 1.7;">
                {{ $demande->message }}
            </div>
        </div>

        @if(!empty($demande->donnees))
        <div class="detail-section">
            <h3 style="margin-top: 0; color: #4a6fa5; border-bottom: 1px solid #eaeaea; padding-bottom: 8px;">
                Informations supplémentaires
            </h3>
            <div style="font-family: monospace; background: #f5f5f5; padding: 10px; border-radius: 4px; overflow-x: auto;">
                {{ json_encode($demande->donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
            </div>
        </div>
        @endif

        <div class="button-container">
            <a href="{{ url('/admin/demandes-modification/' . $demande->id) }}" class="button">
                👁️ Voir et traiter cette demande
            </a>
        </div>
        
        <div class="footer">
            <p>Ceci est un message automatique, merci de ne pas y répondre directement.</p>
            <p>© {{ date('Y') }} {{ config('app.name', 'WTFAlert') }}. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
