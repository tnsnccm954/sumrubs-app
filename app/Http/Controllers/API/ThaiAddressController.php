<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Uatthaphon\ThaiAddress\Models\ThailandDistrict;
use Uatthaphon\ThaiAddress\Models\ThailandProvince;
use Uatthaphon\ThaiAddress\Models\ThailandSubDistrict;

class ThaiAddressController extends Controller
{
    public function index($choice, Request $request)
    {
        switch ($choice) {
            case "provinces":
                $result = ThailandProvince::where("name_in_thai", "like", "%" . $request->input('name') . "%")->take(20);
                $message = "Provinces are retrieved";
                break;
            case "districts":
                $result = ThailandDistrict::where("name_in_thai", "like", "%" . $request->input('name') . "%")->take(20);
                $message = "Districts are retrieved";
                break;
            case "subdistricts":
                $result = ThailandSubDistrict::where("name_in_thai", "like", "%" . $request->input('name') . "%")->take(20);
                $message = "Subdistricts are retrieved";
                break;
            default:
                $result = [];
                $message = "Invalid choice";
                break;
        }

        return response()->json([
            'message' => $message,
            'data' => $result
        ], 200);
    }
}
