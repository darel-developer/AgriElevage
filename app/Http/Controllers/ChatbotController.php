<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function show()
    {
        return view('chatbot');
    }

    public function message(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $userMessage = strtolower($request->message);

        // Réponse IA simulée (à remplacer par une vraie IA si besoin)
        if (str_contains($userMessage, 'bovin')) {
            $reply = "Pour les bovins, pensez à vérifier la vaccination et l'alimentation régulièrement.";
        } elseif (str_contains($userMessage, 'ovin')) {
            $reply = "Les ovins nécessitent un suivi particulier lors de la période d'agnelage.";
        } elseif (str_contains($userMessage, 'bonjour') || str_contains($userMessage, 'salut')) {
            $reply = "Bonjour ! Comment puis-je vous aider aujourd'hui ?";
        } else {
            $reply = "Je suis un assistant IA. Posez-moi une question sur l'élevage ou la gestion de vos animaux.";
        }

        return response()->json(['reply' => $reply]);
    }
}
