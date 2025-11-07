<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnimalController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'sexe' => 'required|in:male,femelle',
            'type' => 'required|in:poule,lapin',
            'categorie' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            // champs optionnels
            'identifiant' => 'nullable|string|max:255',
            'race' => 'nullable|string|max:255',
            'mere' => 'nullable|string|max:255',
            'pere' => 'nullable|string|max:255',
            'grands_parents' => 'nullable|string|max:1000',
            'historique' => 'nullable|string|max:2000',
            'vendu' => 'nullable|boolean',
            'poids' => 'nullable|numeric'
        ]);

        Animal::create($request->all());

        return response()->json(['success' => true]);
    }

    public function dashboard(Request $request)
    {
        $total = \App\Models\Animal::count();
        $types = \App\Models\Animal::selectRaw('type, COUNT(*) as count')->groupBy('type')->pluck('count', 'type');

        if ($request->ajax() || $request->query('poll')) {
            // préparer alertes réelles
            $alerts = [];

            // 1) Mises-bas prévues dans les 7 prochains jours
            $today = Carbon::today();
            $in7 = Carbon::today()->addDays(7);
            $upcoming = \App\Models\Breeding::with('female')
                ->whereBetween('date_mise_bas', [$today->toDateString(), $in7->toDateString()])
                ->orderBy('date_mise_bas')
                ->get();
            foreach ($upcoming as $b) {
                $alerts[] = [
                    'type' => 'mise_bas',
                    'message' => 'Mise-bas prévue: ' . ($b->female ? $b->female->nom : '—'),
                    'date' => $b->date_mise_bas,
                    'priority' => 'high'
                ];
            }

            // 2) Animaux sans enregistrement de poids
            $noWeight = \App\Models\Animal::whereNull('poids')->limit(6)->get();
            foreach ($noWeight as $a) {
                $alerts[] = [
                    'type' => 'poids_manquant',
                    'message' => "Poids manquant pour: {$a->nom} ({$a->type})",
                    'date' => null,
                    'priority' => 'medium'
                ];
            }

            // 3) Animaux marqués vendus récemment (7 derniers jours)
            $soldRecent = \App\Models\Animal::where('vendu', 1)
                ->where('updated_at', '>=', Carbon::now()->subDays(7))
                ->limit(6)
                ->get();
            foreach ($soldRecent as $a) {
                $alerts[] = [
                    'type' => 'vendu',
                    'message' => "Vendu récemment: {$a->nom} ({$a->type})",
                    'date' => $a->updated_at ? $a->updated_at->toDateString() : null,
                    'priority' => 'low'
                ];
            }

            return response()->json([
                'total' => $total,
                'poule' => $types['poule'] ?? 0,
                'lapin' => $types['lapin'] ?? 0,
                'alerts' => $alerts
            ]);
        }

        return view('dashboard', [
            'totalAnimaux' => $total,
            'typesCount' => $types
        ]);
    }

    public function suiviIndividuel(Request $request)
    {
        $types = \App\Models\Animal::select('type')->distinct()->pluck('type');
        return view('suivi-individuel', compact('types'));
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = \App\Models\Animal::query();
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('nom', 'like', "%$search%");
            }
            $animaux = $query->get()->map(function($a) {
                return [
                    'id' => $a->id,
                    'nom' => $a->nom,
                    'type' => ucfirst($a->type),
                    'sexe' => ucfirst($a->sexe),
                    'categorie' => $a->categorie,
                    'identifiant' => $a->identifiant ?? 'Information inconnue',
                    'age' => $this->getAge($a->date_naissance),
                    'statut' => $a->statut ?? 'En bonne santé',
                    'vendu' => (bool) ($a->vendu ?? false) // true/false pour l'affichage côté JS
                ];
            });
            return response()->json($animaux);
        }

        $types = \App\Models\Animal::select('type')->distinct()->get();
        return view('animals-list', [
            'types' => $types
        ]);
    }

    public function details($id)
    {
        $a = \App\Models\Animal::findOrFail($id);
        return response()->json([
            'id' => $a->id,
            'nom' => $a->nom,
            'type' => ucfirst($a->type),
            'sexe' => ucfirst($a->sexe),
            'race' => $a->race ?? 'Information inconnue',
            'date_naissance' => $a->date_naissance ?? 'Information inconnue',
            'identifiant' => $a->identifiant ?? 'Information inconnue',
            'mere' => $a->mere ?? 'Information inconnue',
            'pere' => $a->pere ?? 'Information inconnue',
            'grands_parents' => $a->grands_parents ?? 'Information inconnue',
            'historique' => $a->historique ?? '',
            'vendu' => (bool) ($a->vendu ?? false),
            'poids' => $a->poids ?? null
        ]);
    }

    private function getAge($date)
    {
        if (!$date) return '-';
        $dt = Carbon::parse($date);
        $now = Carbon::now();
        $years = $dt->diffInYears($now);
        if ($years > 0) return $years . ' an' . ($years > 1 ? 's' : '');
        $months = $dt->diffInMonths($now);
        if ($months > 0) return $months . ' mois';
        $days = $dt->diffInDays($now);
        return $days . ' jour' . ($days > 1 ? 's' : '');
    }

    public function update(Request $request, $id)
    {
        $animal = Animal::findOrFail($id);
        $request->validate([
            // utilisation de "sometimes" pour permettre les mises à jour partielles (poids/vendu sans renvoyer tous les champs)
            'nom' => 'sometimes|required|string|max:255',
            'sexe' => 'sometimes|required|in:male,femelle',
            'type' => 'sometimes|required|in:poule,lapin',
            'categorie' => 'sometimes|required|string|max:255',
            'date_naissance' => 'sometimes|required|date',
            // champs optionnels pouvant être mis à jour (poids, vendu, identifiant, etc.)
            'identifiant' => 'sometimes|nullable|string|max:255',
            'race' => 'sometimes|nullable|string|max:255',
            'mere' => 'sometimes|nullable|string|max:255',
            'pere' => 'sometimes|nullable|string|max:255',
            'grands_parents' => 'sometimes|nullable|string|max:1000',
            'historique' => 'sometimes|nullable|string|max:2000',
            'vendu' => 'sometimes|nullable|boolean',
            'poids' => 'sometimes|nullable|numeric'
        ]);
        // update accepte maintenant les champs fillable du modèle
        $animal->update($request->all());
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $animal = Animal::findOrFail($id);
        $animal->delete();
        return response()->json(['success' => true]);
    }
}
