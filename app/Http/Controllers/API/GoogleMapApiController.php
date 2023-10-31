<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LookupBussinessStatus;
use App\Models\Place;
use Exception;
use GuzzleHttp\Client;
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

    private function getSearchUrl(string $type, $enityKey = null)
    {
        switch ($type) {
            case "textSearch":
                $urlType = "textsearch/json";
                break;
            case "placePhoto":
                $urlType = "photo";
                break;
            case "placeDetail":
                $urlType = "details/json";
                break;
            case "findPlaceByText":
                $urlType = "findplacefromtext/json";
                break;
            case "nearbySearch":
            default:
                $urlType = "nearbysearch/json";
                break;
        }

        return $this->basePlaceUrl . $urlType . "?key=$this->apiKey&";
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
            "nextPage" => 'nullable|string',
            "lang" => 'nullable|string|in:en,th',
            "isOpen" => 'nullable|string|in:true,false',
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

        if (array_key_exists('nextPage', $validatedData)) {
            $paramsBuild["pagetoken"] = $validatedData['nextPage'];
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

        $paramsBuild = array_merge($this->apiFielable, $paramsBuild);

        $paramsKeys = array_keys($paramsBuild);
        $params =  implode('&', array_map(function ($index, $value) {
            return "$index" . "=" . "$value";
        }, $paramsKeys, $paramsBuild));
        $searchUrl = $this->getSearchUrl("nearbySearch") .  $params;
        $data = Cache::remember($params, now()->addHours(12), function () use ($searchUrl) {
            $googleRes = Http::get($searchUrl);
            if ($googleRes->getStatusCode() == 200) {
                $bussinessStatuses = LookupBussinessStatus::get();
                $placesRes = json_decode($googleRes->body(), true);
                if (count($placesRes["results"]) > 0) {
                    foreach ($placesRes["results"] as $key => $placeRes) {
                        $place = Place::wherePlaceId($placeRes['place_id'])->first();
                        if (is_null($place)) {
                            $place = new Place;
                            $place->place_id = $placeRes['place_id'];
                        }
                        $place->google_rating = isset($placeRes['rating']) ? $placeRes['rating'] : null;
                        $place->name = $placeRes['name'];
                        $place->vicinity = isset($placeRes['vicinity']) ? $placeRes['vicinity'] : null;
                        if (isset($placeRes['photos']) && count($placeRes['photos']) > 0) {
                            $place->photo_references = array_map(function ($photo) {
                                return $photo['photo_reference'];
                            }, $placeRes['photos']);
                        }
                        $place->opening_hours = isset($placeRes['opening_hours']) ? $placeRes['opening_hours'] : null;
                        $place->location = $placeRes['geometry']['location'];
                        $place->business_status_id = isset($placeRes['opening_hours'])
                            ? $bussinessStatuses->where('systemname', $placeRes['business_status'])->first()->id
                            : null;
                        $place->save();
                        Log::info('Place' . $place->place_id);
                    }
                }

                return $placesRes;
            }
            return;
        });
        if ($data) {
            return response()->json([
                "message" => "place for query retrived",
                "data" => isset($data["results"]) ? $data['results'] : [],
                "next_page" => isset($data["next_page_token"]) ? $data["next_page_token"] : null,
            ], 200);
        }
        Cache::forget($params);
        throw ValidationException::withMessages(["Request" => "Invalid request."]);
    }

    public function getPlaceDetail($place)
    {
        $searchUrl = $this->getSearchUrl("placeDetail") . "place_id=$place" . "&region=$this->lang" . "&language=$this->lang";
        $data = Cache::remember($place, now()->addHours(1), function () use ($searchUrl, $place) {
            $googleRes = Http::get($searchUrl);
            if ($googleRes->getStatusCode() == 200) {
                $bussinessStatuses = LookupBussinessStatus::get();
                $placeRes = json_decode($googleRes->body(), true);
                $newPlace = Place::wherePlaceId($place)->first();
                $newPlace->google_rating = isset($placeRes['rating']) ? $placeRes['rating'] : null;
                // $newPlace->name = isset($placeRes['name']) ? $placeRes['name'] : null;
                $newPlace->vicinity = isset($placeRes['vicinity']) ? $placeRes['vicinity'] : null;
                if (isset($placeRes['photos']) && count($placeRes['photos']) > 0) {
                    $newPlace->photo_references = array_map(function ($photo) {
                        return $photo['photo_reference'];
                    }, $placeRes['photos']);
                }
                $newPlace->opening_hours = isset($placeRes['opening_hours']) ? $placeRes['opening_hours'] : null;
                if ($newPlace->opening_hours) {
                    $newPlace->periods = isset($placeRes['opening_hours']['periods']) ? $placeRes['opening_hours']['periods'] : null;
                    $newPlace->weekday_text = isset($placeRes['opening_hours']['weekday_text']) ? $placeRes['opening_hours']['weekday_text'] : null;
                }
                $newPlace->formatted_address = isset($placeRes['formatted_address']) ? $placeRes['formatted_address'] : null;
                $newPlace->url = isset($placeRes['url']) ? $placeRes['url'] : null;
                // $newPlace->location = $placeRes['geometry']['location'];
                $newPlace->business_status_id = isset($placeRes['opening_hours'])
                    ? $bussinessStatuses->where('systemname', $placeRes['business_status'])->first()->id
                    : null;

                $newPlace->save();
                Log::info('updated Place:' . $newPlace->place_id);
                return $placeRes;
            }
            return;
        });
        if ($data) {
            return response()->json([
                "message" => "place for query retrived",
                "data" => isset($data) ? $data : [],
            ], 200);
        }
        Cache::forget($place);
        throw ValidationException::withMessages(["Request" => "Invalid request."]);
    }

    public function getPlacePhoto(String $photoReference, Request $request)
    {
        $valdatedDate = $request->validate([
            'width' => 'required|integer',
            'height' => 'required|integer',
        ]);

        $searchUrl = $this->getSearchUrl("placePhoto") . "photo_reference=$photoReference" . "&maxheight=" . $valdatedDate['width'] . "&maxwidth=" . $valdatedDate['height'];
        $cacheKey = $photoReference . "&" . $valdatedDate['width'] . "&" . $valdatedDate['height'];
        $response = Cache::remember(
            $cacheKey,
            now()->addHours(1),
            function () use ($searchUrl) {
                $client = new Client();
                $response = $client->get($searchUrl);
                return $response;
            }
        );
        if ($response->getStatusCode() == 200) {
            $contentType = $response->getHeader('Content-Type')[0];
            $photo = $response->getBody();
            return response($photo, 200, ['Content-Type' => $contentType]);
        }
        Cache::forget($cacheKey);
        throw ValidationException::withMessages(["Request" => "Invalid request."]);
    }
}
