<?php

namespace App\Http\Controllers\Api;

use App\Models\Survice;
use App\Models\Minisurvice;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class HomePageController extends Controller
{
    use ApiResponseTrait;
    use isFavoriteTrait;

    public function index()
    {
        $miniservices = Survice::with(['subsurvice'])->get();
        $survice = Survice::all();
        $popularMiniservices = Reservation::select('minisurvice_id', DB::raw('count(*) as total'))
            ->with('minisurvice')
            ->groupBy('minisurvice_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();
        $topMinisurvice = Minisurvice::orderBy('rating', 'desc')
            ->take(5)
            ->get();
        $minisurvicesEndingSoon = Minisurvice::where('humannumber', '!=', 'reservationnumber')
            ->where(function ($query) {
                $query->where('start_at', '>', Carbon::now())
                    ->where('start_at', '<', Carbon::now()->addDays(10));
            })
            ->orderBy('start_at', 'desc')
            ->take(5)
            ->get();

        $minisurvicesOffer = Minisurvice::where('isOffer', '=', 1)->orderBy('rating', 'desc')
            ->take(5)
            ->get();

        $minisurvicesFamily = Minisurvice::where('isFamily', '=', 1)->orderBy('rating', 'desc')
            ->take(5)
            ->get();

        if (auth()->check()) {
            $popularMiniservices->transform(function ($minisurvice) {
                $minisurvice->isFavorite = $minisurvice->favorites_count > 0;
                return $minisurvice;
            });

            $topMinisurvice->transform(function ($minisurvice) {
                $minisurvice->isFavorite = $this->isFavorite(auth()->user()->id, $minisurvice->id);
                return $minisurvice;
            });

            $minisurvicesEndingSoon->transform(function ($minisurvice) {
                $minisurvice->isFavorite = $this->isFavorite(auth()->user()->id, $minisurvice->id);
                return $minisurvice;
            });

            $minisurvicesOffer->transform(function ($minisurvice) {
                $minisurvice->isFavorite = $this->isFavorite(auth()->user()->id, $minisurvice->id);
                return $minisurvice;
            });

            $minisurvicesFamily->transform(function ($minisurvice) {
                $minisurvice->isFavorite = $this->isFavorite(auth()->user()->id, $minisurvice->id);
                return $minisurvice;
            });
        } else {

            $popularMiniservices->transform(function ($minisurvice) {
                $minisurvice->isFavorite = false;
                return $minisurvice;
            });

            $topMinisurvice->transform(function ($minisurvice) {
                $minisurvice->isFavorite = false;
                return $minisurvice;
            });

            $minisurvicesEndingSoon->transform(function ($minisurvice) {
                $minisurvice->isFavorite = false;
                return $minisurvice;
            });

            $minisurvicesOffer->transform(function ($minisurvice) {
                $minisurvice->isFavorite = false;
                return $minisurvice;
            });

            $minisurvicesFamily->transform(function ($minisurvice) {
                $minisurvice->isFavorite = false;
                return $minisurvice;
            });
        }


        $miniservices->map(function ($minisurvice) {
            $minisurvice->subsurvice->map(function ($subsurvice) use ($minisurvice) {
                $subsurvice->minisurvice = $subsurvice->minisurvice->map(function ($mini) use ($minisurvice) {
                    $mini->isFavorite = auth()->check() ? $this->isFavorite(auth()->user()->id, $mini->id) : false;
                    return $mini;
                })->values();
                return $subsurvice;
            });
        });

        if ($miniservices) {
            $response = [
                'data' => [
                    'survice' => $survice,
                    'popularMiniservices' => $popularMiniservices,
                    'topMinisurvice' => $topMinisurvice,
                    'minisurvicesEndingSoon' => $minisurvicesEndingSoon,
                    'minisurvicesOffer' => $minisurvicesOffer,
                    'minisurvicesFamily' => $minisurvicesFamily,
                    'allsurvice' => $miniservices,
                ],
                'message' => 'جميع البيانات للصفحة الرئيسية',
                'status' => 200,
            ];
            return response()->json($response);
        } else {
            return $this->apiResponse(null, 'لا يوجد بيانات', 201);
        }
    }
}
