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
        // On base l'analyse sur date_mise_bas (analyses des mises bas)
        $breedingQuery = Breeding::where('date_mise_bas', '>=', $dateFrom);
        if ($espece) {
            $breedingQuery->where('espece', $espece);
        }
        $breedings = $breedingQuery->get();

        // Statistiques principales basées sur date_mise_bas
        $totalMisesBas = $breedings->count();
        $totalMisesBasLastPeriod = Breeding::where('date_mise_bas', '>=', Carbon::now()->subDays($periode+30))
            ->where('date_mise_bas', '<', Carbon::now()->subDays($periode))
            ->when($espece, function($q) use ($espece) { $q->where('espece', $espece); })
            ->count();
        $variationMisesBas = $totalMisesBasLastPeriod > 0
            ? round((($totalMisesBas - $totalMisesBasLastPeriod) / $totalMisesBasLastPeriod) * 100, 1)
            : null;

        $tailleMoyennePortee = $breedings->avg('taille_portee') ? round($breedings->avg('taille_portee'), 1) : 0;
        $tailleMoyennePorteeAnnee = Breeding::where('date_mise_bas', '>=', Carbon::now()->subYear())
            ->when($espece, function($q) use ($espece) { $q->where('espece', $espece); })
            ->avg('taille_portee');
        $variationPortee = $tailleMoyennePorteeAnnee ? round($tailleMoyennePortee - $tailleMoyennePorteeAnnee, 1) : null;

        $dureeMoyenneGestation = 0;
        if ($breedings->count()) {
            // si date_croisement et date_mise_bas présentes, calculer la durée
            $dureeMoyenneGestation = round($breedings->avg(function($b) {
                if ($b->date_croisement && $b->date_mise_bas) {
                    return Carbon::parse($b->date_mise_bas)->diffInDays(Carbon::parse($b->date_croisement));
                }
                return 0;
            }));
        }

        $nbGestations = $breedings->count();
        $nbGestationsReussies = $breedings->where('reussite', true)->count();
        $tauxReussite = $nbGestations > 0 ? round(($nbGestationsReussies / $nbGestations) * 100) : 0;

        $nbMorts = $breedings->sum('nb_morts');
        $nbTotalNes = $breedings->sum('taille_portee');
        $tauxMortalite = $nbTotalNes > 0 ? round(($nbMorts / $nbTotalNes) * 100, 1) : 0;

        $nbMortsLastPeriod = Breeding::where('date_mise_bas', '>=', Carbon::now()->subDays($periode+30))
            ->where('date_mise_bas', '<', Carbon::now()->subDays($periode))
            ->when($espece, function($q) use ($espece) { $q->where('espece', $espece); })
            ->sum('nb_morts');
        $nbTotalNesLastPeriod = Breeding::where('date_mise_bas', '>=', Carbon::now()->subDays($periode+30))
            ->where('date_mise_bas', '<', Carbon::now()->subDays($periode))
            ->when($espece, function($q) use ($espece) { $q->where('espece', $espece); })
            ->sum('taille_portee');

        $tauxMortaliteLastPeriod = $nbTotalNesLastPeriod > 0 ? round(($nbMortsLastPeriod / $nbTotalNesLastPeriod) * 100, 1) : 0;
        $variationMortalite = $tauxMortaliteLastPeriod ? round($tauxMortalite - $tauxMortaliteLastPeriod, 1) : null;

        // Graphiques — mises bas par mois (sur date_mise_bas)
        $accouplementsParMois = Breeding::select(
                DB::raw('DATE_FORMAT(date_mise_bas, "%Y-%m") as mois'),
                DB::raw('COUNT(*) as total')
            )
            ->when($espece, function($q) use ($espece) {
                $q->where('espece', $espece);
            })
            ->where('date_mise_bas', '>=', Carbon::now()->subMonths(12))
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        // Taille de portée par espèce (espece colonne)
        $tailleParEspece = Breeding::select('espece', DB::raw('AVG(taille_portee) as moyenne'))
            ->when($espece, function($q) use ($espece) {
                $q->where('espece', $espece);
            })
            ->groupBy('espece')
            ->get();

        // Pour le filtre : récupérer types d'animaux
        $especes = Animal::select('type')->distinct()->pluck('type');

        // Aliases pour correspondre aux noms attendus par la vue (compatibilité)
        $totalAccouplements = $totalMisesBas;
        $variationAccouplements = $variationMisesBas;

        return view('reproductive-analytics', compact(
            'totalMisesBas', 'variationMisesBas',
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
