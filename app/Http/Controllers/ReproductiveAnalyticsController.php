<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Breeding;
use App\Models\Animal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReproductiveAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->input('periode', 30);
        $espece = $request->input('espece', '');

        $dateFrom = Carbon::now()->subDays($periode);

        // Filtre sur l'espèce si besoin
        $breedingQuery = Breeding::where('date_croisement', '>=', $dateFrom);
        if ($espece) {
            $breedingQuery->whereHas('female', function($q) use ($espece) {
                $q->where('type', $espece);
            });
        }
        $breedings = $breedingQuery->get();

        // Statistiques principales
        $totalAccouplements = $breedings->count();
        $totalAccouplementsLastMonth = Breeding::where('date_croisement', '>=', Carbon::now()->subDays($periode+30))
            ->where('date_croisement', '<', Carbon::now()->subDays($periode))
            ->count();
        $variationAccouplements = $totalAccouplementsLastMonth > 0
            ? round((($totalAccouplements - $totalAccouplementsLastMonth) / $totalAccouplementsLastMonth) * 100, 1)
            : null;

        $tailleMoyennePortee = round($breedings->avg('taille_portee'), 1);
        $tailleMoyennePorteeAnnee = Breeding::where('date_croisement', '>=', Carbon::now()->subYear())->avg('taille_portee');
        $variationPortee = $tailleMoyennePorteeAnnee ? round($tailleMoyennePortee - $tailleMoyennePorteeAnnee, 1) : null;

        $dureeMoyenneGestation = round($breedings->avg(function($b) {
            return Carbon::parse($b->date_mise_bas)->diffInDays(Carbon::parse($b->date_croisement));
        }));

        $nbGestations = $breedings->count();
        $nbGestationsReussies = $breedings->where('reussite', true)->count();
        $tauxReussite = $nbGestations > 0 ? round(($nbGestationsReussies / $nbGestations) * 100) : 0;

        $nbMorts = $breedings->sum('nb_morts');
        $nbTotalNes = $breedings->sum('taille_portee');
        $tauxMortalite = $nbTotalNes > 0 ? round(($nbMorts / $nbTotalNes) * 100, 1) : 0;

        $tauxMortaliteLastMonth = 0;
        $nbMortsLastMonth = Breeding::where('date_croisement', '>=', Carbon::now()->subDays($periode+30))
            ->where('date_croisement', '<', Carbon::now()->subDays($periode))
            ->sum('nb_morts');
        $nbTotalNesLastMonth = Breeding::where('date_croisement', '>=', Carbon::now()->subDays($periode+30))
            ->where('date_croisement', '<', Carbon::now()->subDays($periode))
            ->sum('taille_portee');
        if ($nbTotalNesLastMonth > 0) {
            $tauxMortaliteLastMonth = round(($nbMortsLastMonth / $nbTotalNesLastMonth) * 100, 1);
        }
        $variationMortalite = $tauxMortaliteLastMonth ? round($tauxMortalite - $tauxMortaliteLastMonth, 1) : null;

        // Graphiques
        // Accouplements réussis par mois (12 derniers mois)
        $accouplementsParMois = Breeding::select(
                DB::raw('DATE_FORMAT(date_croisement, "%Y-%m") as mois'),
                DB::raw('COUNT(*) as total')
            )
            ->when($espece, function($q) use ($espece) {
                $q->whereHas('female', function($q2) use ($espece) {
                    $q2->where('type', $espece);
                });
            })
            ->where('date_croisement', '>=', Carbon::now()->subMonths(12))
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        // Taille de portée par espèce
        $tailleParEspece = Breeding::select('espece', DB::raw('AVG(taille_portee) as moyenne'))
            ->join('animals as femelles', 'breedings.female_id', '=', 'femelles.id')
            ->groupBy('espece')
            ->get();

        // Pour le filtre
        $especes = Animal::select('type')->distinct()->pluck('type');

        return view('reproductive-analytics', compact(
            'totalAccouplements', 'variationAccouplements',
            'tailleMoyennePortee', 'variationPortee',
            'dureeMoyenneGestation',
            'tauxReussite',
            'tauxMortalite', 'variationMortalite',
            'accouplementsParMois', 'tailleParEspece',
            'especes', 'espece', 'periode'
        ));
    }
}
