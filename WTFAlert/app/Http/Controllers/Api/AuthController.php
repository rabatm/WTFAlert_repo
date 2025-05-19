<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Habitant;
use App\Models\Foyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom_hb' => 'required|string|max:255',
            'prenom_hb' => 'required|string|max:255',
            'mail' => 'required|string|email|max:255|unique:habitants',
            'motdepasse' => 'required|string|min:8',
            'telephone_mobile' => 'nullable|string',
            'foyer' => 'required|array',
            'foyer.nom' => 'required|string',
            'foyer.adresse' => 'required|string',
            'foyer.code_postal' => 'required|numeric',
            'foyer.ville' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Créer le foyer
        $foyer = Foyer::create([
            'nom' => $request->foyer['nom'],
            'adresse' => $request->foyer['adresse'],
            'code_postal' => $request->foyer['code_postal'],
            'ville' => $request->foyer['ville'],
            'numero_voie' => $request->foyer['numero_voie'] ?? null,
            'complement_dadresse' => $request->foyer['complement_dadresse'] ?? null,
        ]);

        // Créer l'habitant
        $habitant = Habitant::create([
            'foyer_id' => $foyer->id,
            'nom_hb' => $request->nom_hb,
            'prenom_hb' => $request->prenom_hb,
            'mail' => $request->mail,
            'motdepasse' => Hash::make($request->motdepasse),
            'telephone_mobile' => $request->telephone_mobile,
        ]);

        return response()->json([
            'message' => 'Utilisateur enregistré avec succès',
            'user' => $habitant
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mail' => 'required|email',
            'motdepasse' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $habitant = Habitant::where('mail', $request->mail)->first();

        if (! $habitant || ! Hash::check($request->motdepasse, $habitant->motdepasse)) {
            return response()->json([
                'error' => 'Email ou mot de passe incorrect'
            ], 401);
        }

        // Supprimez tous les tokens existants si nécessaire
        // $habitant->tokens()->delete();

        $token = $habitant->createToken('auth_token')->plainTextToken;
        
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $habitant
        ]);
    }

    // Remplacez la méthode logout
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'Déconnexion réussie']);
    }

    public function me()
    {
        $user = auth()->user();
        $user->load('foyer');
        
        return response()->json($user);
    }
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['mail' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('mail')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Lien de réinitialisation envoyé par email'])
            : response()->json(['error' => 'Erreur lors de l\'envoi du lien'], 400);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'mail' => 'required|email',
            'motdepasse' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('mail', 'motdepasse', 'motdepasse_confirmation', 'token'),
            function ($user, $password) {
                $user->motdepasse = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Mot de passe réinitialisé avec succès'])
            : response()->json(['error' => 'Erreur lors de la réinitialisation'], 400);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
