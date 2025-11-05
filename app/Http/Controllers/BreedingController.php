<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\Breeding;
use Carbon\Carbon;

class BreedingController extends Controller
{
    public function showForm()
    {
        $males = Animal::where('type', 'lapin')->where('sexe', 'male')->get();
        $femelles = Animal::where('type', 'lapin')->where('sexe', 'femelle')->get();
        return view('breeding-planning', compact('males', 'femelles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'male_id' => 'required|exists:animals,id',
            'female_id' => 'required|exists:animals,id',
            'date_mise_bas' => 'required|date',
            'taille_portee' => 'required|integer|min:0',
            'nb_morts' => 'required|integer|min:0',
        ]);

        // Récupère la femelle pour déterminer l'espèce
        $female = Animal::find($request->female_id);

        // Calcul automatique de la réussite
        $taille = (int)$request->taille_portee;
        $morts = (int)$request->nb_morts;
        $reussite = ($taille > 0 && $taille > $morts) ? 1 : 0;

        // date_croisement : si fourni dans la requête on l'utilise, sinon maintenant
        $dateCroisement = $request->input('date_croisement') ? $request->input('date_croisement') : Carbon::now()->toDateString();

        $breeding = Breeding::create([
            'male_id' => $request->male_id,
            'female_id' => $request->female_id,
            'date_mise_bas' => $request->date_mise_bas,
            'taille_portee' => $taille,
            'nb_morts' => $morts,
            'reussite' => $reussite,
            'date_croisement' => $dateCroisement,
            'espece' => $female ? $female->type : null,
        ]);

        // Pour AJAX
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('breeding.form')->with('success', 'Croisement enregistré avec succès !');
    }

    // Pour le calendrier : retourne les mises bas prévues
    public function events()
    {
        $events = Breeding::with('female')->get()->map(function($b) {
            return [
                'date' => $b->date_mise_bas,
                'title' => 'Mise bas de ' . ($b->female ? $b->female->nom : ''),
                'description' => 'Taille portée : ' . ($b->taille_portee ?? '-') . ', Morts-nés : ' . ($b->nb_morts ?? '-'),
            ];
        });
        return response()->json($events);
    }
}
