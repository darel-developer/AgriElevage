<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
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
        $total = Animal::count();
        $types = Animal::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type');

        if ($request->ajax() || $request->query('poll')) {

            $alerts = [];

            /** 1. Mises-bas prévues dans 7 jours **/
            $today = Carbon::today();
            $in7 = Carbon::today()->addDays(7);

            $upcoming = \App\Models\Breeding::with('female')
                ->whereBetween('date_mise_bas', [$today, $in7])
                ->orderBy('date_mise_bas')
                ->get();

            foreach ($upcoming as $b) {
                $alerts[] = [
                    'type' => 'mise_bas',
                    'message' => 'Mise-bas prévue : ' . ($b->female->nom ?? '—'),
                    'date' => $b->date_mise_bas,
                    'priority' => 'high'
                ];
            }

            /** 2. Animaux sans poids **/
            $noWeight = Animal::whereNull('poids')->limit(6)->get();

            foreach ($noWeight as $a) {
                $alerts[] = [
                    'type' => 'poids_manquant',
                    'message' => "Poids manquant pour : {$a->nom} ({$a->type})",
                    'date' => null,
                    'priority' => 'medium'
                ];
            }

            /** 3. Animaux vendus récemment **/
            $soldRecent = Animal::where('vendu', 1)
                ->where('updated_at', '>=', Carbon::now()->subDays(7))
                ->limit(6)
                ->get();

            foreach ($soldRecent as $a) {
                $alerts[] = [
                    'type' => 'vendu',
                    'message' => "Vendu récemment : {$a->nom} ({$a->type})",
                    'date' => optional($a->updated_at)->toDateString(),
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


    public function suiviIndividuel()
    {
        $types = Animal::select('type')->distinct()->pluck('type');
        return view('suivi-individuel', compact('types'));
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Animal::query();

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('search')) {
                $query->where('nom', 'like', "%{$request->search}%");
            }

            $animaux = $query->get()->map(function ($a) {
                return [
                    'id' => $a->id,
                    'nom' => $a->nom,
                    'type' => ucfirst($a->type),
                    'sexe' => ucfirst($a->sexe),
                    'categorie' => $a->categorie,
                    'identifiant' => $a->identifiant ?? 'Information inconnue',
                    'age' => $this->getAge($a->date_naissance),
                    'statut' => $a->statut ?? 'En bonne santé',
                    'vendu' => (bool) $a->vendu
                ];
            });

            return response()->json($animaux);
        }

        $types = Animal::select('type')->distinct()->get();

        return view('animals-list', compact('types'));
    }


    public function details($id)
    {
        $a = Animal::findOrFail($id);

        return response()->json([
            'id' => $a->id,
            'nom' => $a->nom,
            'type' => ucfirst($a->type),
            'sexe' => ucfirst($a->sexe),
            'race' => $a->race ?? 'Information inconnue',
            'date_naissance' => $a->date_naissance,
            'identifiant' => $a->identifiant ?? 'Information inconnue',
            'mere' => $a->mere ?? 'Information inconnue',
            'pere' => $a->pere ?? 'Information inconnue',
            'grands_parents' => $a->grands_parents ?? 'Information inconnue',
            'historique' => $a->historique ?? '',
            'vendu' => (bool) $a->vendu,
            'poids' => $a->poids
        ]);
    }


    private function getAge($date)
    {
        if (!$date) {
            return '-';
        }

        $dt = Carbon::parse($date);
        $now = Carbon::now();

        $years = $dt->diffInYears($now);
        if ($years > 0) {
            return $years . ' an' . ($years > 1 ? 's' : '');
        }

        $months = $dt->diffInMonths($now);
        if ($months > 0) {
            return $months . ' mois';
        }

        $days = $dt->diffInDays($now);
        return $days . ' jour' . ($days > 1 ? 's' : '');
    }


    public function update(Request $request, $id)
    {
        $animal = Animal::findOrFail($id);

        $request->validate([
            'nom' => 'sometimes|required|string|max:255',
            'sexe' => 'sometimes|required|in:male,femelle',
            'type' => 'sometimes|required|in:poule,lapin',
            'categorie' => 'sometimes|required|string|max:255',
            'date_naissance' => 'sometimes|required|date',

            'identifiant' => 'sometimes|nullable|string|max:255',
            'race' => 'sometimes|nullable|string|max:255',
            'mere' => 'sometimes|nullable|string|max:255',
            'pere' => 'sometimes|nullable|string|max:255',
            'grands_parents' => 'sometimes|nullable|string|max:1000',
            'historique' => 'sometimes|nullable|string|max:2000',
            'vendu' => 'sometimes|nullable|boolean',
            'poids' => 'sometimes|nullable|numeric'
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

	/**
	 * Export animals as CSV (streamed).
	 */
	public function exportCsv(Request $request)
	{
		$filename = 'animals_' . date('Ymd_His') . '.csv';
		$columns = ['id','nom','sexe','type','categorie','date_naissance','identifiant','race','mere','pere','grands_parents','historique','poids','vendu','created_at','updated_at'];

		$callback = function() use ($columns) {
			$handle = fopen('php://output', 'w');
			fputcsv($handle, $columns);
			\App\Models\Animal::chunk(200, function($animals) use ($handle) {
				foreach ($animals as $a) {
					$row = [
						$a->id,
						$a->nom,
						$a->sexe,
						$a->type,
						$a->categorie,
						$a->date_naissance,
						$a->identifiant,
						$a->race,
						$a->mere,
						$a->pere,
						$a->grands_parents,
						$a->historique,
						$a->poids,
						$a->vendu ? 1 : 0,
						$a->created_at,
						$a->updated_at
					];
					fputcsv($handle, $row);
				}
			});
			fclose($handle);
		};

		return response()->stream($callback, 200, [
			"Content-Type" => "text/csv",
			"Content-Disposition" => "attachment; filename={$filename}"
		]);
	}

	/**
	 * Import animals from an uploaded CSV file.
	 * Expected header: nom,sexe,type,categorie,date_naissance,...
	 */
	public function importCsv(Request $request)
	{
		if (!$request->hasFile('csv')) {
			return response()->json(['success' => false, 'message' => 'Fichier manquant'], 422);
		}
		$file = $request->file('csv');
		if (!$file->isValid()) {
			return response()->json(['success' => false, 'message' => 'Fichier invalide'], 422);
		}

		$handle = fopen($file->getRealPath(), 'r');
		$header = fgetcsv($handle);
		if (!$header || !is_array($header)) {
			fclose($handle);
			return response()->json(['success' => false, 'message' => 'En-tête CSV invalide'], 422);
		}

		$created = 0;
		$errors = [];
		while (($data = fgetcsv($handle)) !== false) {
			// ignore empty lines
			if (count($data) === 0 || (count($data) === 1 && $data[0] === null)) {
				continue;
			}
			$row = @array_combine($header, $data);
			if (!$row) {
				$errors[] = "Ligne ignorée : format invalide.";
				continue;
			}
			// Champs requis minimaux
			if (empty($row['nom']) || empty($row['sexe']) || empty($row['type']) || empty($row['categorie']) || empty($row['date_naissance'])) {
				$errors[] = "Ligne ignorée (champs requis manquants) : " . json_encode($row);
				continue;
			}
			try {
				\App\Models\Animal::create([
					'nom' => $row['nom'],
					'sexe' => $row['sexe'],
					'type' => $row['type'],
					'categorie' => $row['categorie'],
					'date_naissance' => $row['date_naissance'],
					'identifiant' => $row['identifiant'] ?? null,
					'race' => $row['race'] ?? null,
					'mere' => $row['mere'] ?? null,
					'pere' => $row['pere'] ?? null,
					'grands_parents' => $row['grands_parents'] ?? null,
					'historique' => $row['historique'] ?? null,
					'poids' => isset($row['poids']) && $row['poids'] !== '' ? (float)$row['poids'] : null,
					'vendu' => !empty($row['vendu']) ? 1 : 0,
				]);
				$created++;
			} catch (\Exception $e) {
				$errors[] = "Erreur ligne : " . $e->getMessage();
			}
		}
		fclose($handle);

		return response()->json(['success' => true, 'created' => $created, 'errors' => $errors]);
	}
}
