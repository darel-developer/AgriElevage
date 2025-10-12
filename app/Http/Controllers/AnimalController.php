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
        ]);

        Animal::create($request->all());

        return response()->json(['success' => true]);
    }

    public function dashboard(Request $request)
    {
        $total = \App\Models\Animal::count();
        $types = \App\Models\Animal::selectRaw('type, COUNT(*) as count')->groupBy('type')->pluck('count', 'type');

        if ($request->ajax() || $request->query('poll')) {
            return response()->json([
                'total' => $total,
                'poule' => $types['poule'] ?? 0,
                'lapin' => $types['lapin'] ?? 0,
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
                    'categorie' => $a->categorie, // <-- Ajouté
                    'identifiant' => $a->identifiant ?? '', // <-- Si tu veux l'afficher
                    'age' => $this->getAge($a->date_naissance),
                    'statut' => $a->statut ?? 'En bonne santé'
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
            'race' => $a->race ?? '-',
            'date_naissance' => $a->date_naissance,
            'identifiant' => $a->identifiant ?? '-',
            'mere' => $a->mere ?? '-',
            'pere' => $a->pere ?? '-',
            'grands_parents' => $a->grands_parents ?? '-',
            'historique' => $a->historique ?? '',
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
            'nom' => 'required|string|max:255',
            'sexe' => 'required|in:male,femelle',
            'type' => 'required|in:poule,lapin',
            'categorie' => 'required|string|max:255',
            'date_naissance' => 'required|date',
        ]);
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
