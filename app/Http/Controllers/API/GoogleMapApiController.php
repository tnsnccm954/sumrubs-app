<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


use Uatthaphon\ThaiAddress\Models\ThailandProvince;
use Uatthaphon\ThaiAddress\Models\ThailandDistrict;
use Uatthaphon\ThaiAddress\Models\ThailandSubdistrict;


class GoogleMapApiController extends Controller
{
    protected $apiKey, $lang;
    //restaurant,bakery,food,cafe,department_store,store,shopping_mall
    protected $apiFielable = [
        "type" => "restaurant|food|bakery|cafe",
        "radius" => 500, // in meter
    ];

    protected $defaultLocation = ["lat" => 13.82, "long" => 100.529];

    protected $basePlaceUrl = "https://maps.googleapis.com/maps/api/place/";

    protected $radiusRules = [100, 300, 500, 1000, 3000, 5000, 10000, 50000];

    protected $typeRule = ["restaurant", "bakery", "food", "cafe"];

    public function __construct()
    {
        $this->apiKey = config("googlemapapi.key");
        $this->lang = config("app.locale", "th");
    }

    private function getSearchUrl(string $type)
    {
        switch ($type) {
            case "textSearch":
                $urlType = "textsearch/";
                break;
            case "placePhoto":
                $urlType = "photo/";
                break;
            case "placeDetail":
                $urlType = "details/";
                break;
            case "findPlaceByText":
                $urlType = "findplacefromtext/";
                break;
            case "nearbySearch":
            default:
                $urlType = "nearbysearch/";
                break;
        }

        return $this->basePlaceUrl . $urlType . "json?key=$this->apiKey&";
    }


    public function searchPlaceNearby(Request $request)
    {
        $validateRadius = implode(',', $this->radiusRules);
        $validatedData = $request->validate([
            "query" => "nullable|string",
            "provinceId" => "nullable|integer|exists:thailand_provinces,id",
            "districtId" => "nullable|integer|exists:thailand_districts,id",
            "subDistrictId" => "nullable|integer|exists:thailand_sub_districts,id",
            "radius" => "nullable|integer|in:$validateRadius",
            "nextpage" => 'nullable|string',
            "lang" => 'nullable|string|in:en,th',
            "isOpen" => 'nullable|boolean',
        ]);
        $paramsBuild = [];



        if (array_key_exists('subDistrictId', $validatedData)) {
            $location = app(ThailandSubdistrict::class)->find($validatedData['subDistrictId']);
            if ($location) {
                $paramsBuild["location"] = "$location->latitude,$location->longitude";
            }
        } else if (array_key_exists('districtId', $validatedData)) {
            $location = app(ThailandDistrict::class)->find($validatedData['districtId'])->subDistricts()->first();
            if ($location) {
                $paramsBuild["location"] = "$location->latitude,$location->longitude";
            }
        } else if (array_key_exists('provinceId', $validatedData)) {
            $location = app(ThailandProvince::class)->find($validatedData['provinceId'])->districts()->first()->subDistricts()->first();
            if ($location) {
                $paramsBuild["location"] = "$location->latitude,$location->longitude";
            }
        } else {
            $paramsBuild["location"] = $this->defaultLocation['lat'] . "," . $this->defaultLocation['long'];
        }

        if (array_key_exists('radius', $validatedData)) {
            $paramsBuild["radius"] = $validatedData['radius'];
        }

        if (array_key_exists('nextpage', $validatedData)) {
            $paramsBuild["pagetoken"] = $validatedData['nextpage'];
        }

        if (array_key_exists('isOpen', $validatedData)) {
            $paramsBuild["opennow"] = $validatedData['isOpen'];
        }

        if (array_key_exists('lang', $validatedData)) {
            $paramsBuild["language"] = $validatedData['lang'];
        } else {
            $paramsBuild["language"] = $this->lang;
        }

        if (array_key_exists('query', $validatedData)) {
            $paramsBuild["keyword"] = $validatedData['query'];
        }

        $paramsBuild = array_merge($paramsBuild, $this->apiFielable);

        $paramsKeys = array_keys($paramsBuild);
        $params =  implode('&', array_map(function ($index, $value) {
            return "$index" . "=" . "$value";
        }, $paramsKeys, $paramsBuild));

        $searchUrl = $this->getSearchUrl("nearbySearch") .  $params;
        $data = Cache::remember($params, now()->addHours(12), function () use ($searchUrl) {
            $googleRes = Http::get($searchUrl);
            if ($googleRes->ok()) {
                $dataRes = json_decode($googleRes->body(), true);
                return $dataRes;
            }
            return;
        });
        if ($data) {
            return response()->json([
                "message" => "place for query retrived",
                "data" => isset($data["results"]) ? $data['results'] : [],
                "next_page" => isset($data["next_page_token"]) ? isset($data["next_page_token"]) : null,
            ], 200);
        }
        Cache::forget($params);
        throw ValidationException::withMessages(["Request" => "Invalid request."]);
    }

    public function getPlaceDetail()
    {
    }
}
