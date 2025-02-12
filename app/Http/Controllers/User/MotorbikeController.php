<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\CategoryMotorbike;
use App\Models\Invoice;
use App\Models\Motorbike;
use Auth;
use Illuminate\Http\Request;
use Session;

class MotorbikeController extends Controller
{
    //
    function index(string $slug = "all")
    {
        $num_page = 10;
        $motorbikes = Motorbike::where("status", "public")->paginate($num_page);
        Session::put("ModuleCategory", $slug);
        $sort = request("sort");
        $categorys = null;
        if ($slug != "all") {
            $categorys = CategoryMotorbike::where("status", "public")->where("slug", $slug)->first();
            if ($categorys) {
                Session::put("ModuleCategory", $categorys->slug);
                Session::put("ModuleCategoryName", $categorys->name);
                // dd($categorys);
                $motorbikes = $categorys->motorbike()->where("status", "public")->paginate($num_page);
                // $motorbikes = CategoryMotorbike::find($categorys->id)->motorbike()->where("status", "public")->paginate($num_page);
            }
        }

        if ($sort) {
            $motorbikesQuery = $categorys
                ? $categorys->motorbike()->where("status", "public")
                : Motorbike::where("status", "public");

            $motorbikes = $motorbikesQuery
                ->when(request('sort'), function ($query, $sort) {
                    switch ($sort) {
                        case "motorbike-by-date-new":
                            return $query->orderBy('created_at', 'desc');
                        case "motorbike-by-date-old":
                            return $query->orderBy('created_at', 'asc');
                        case "motorbike-by-price-desc":
                            return $query->orderBy('rental_price', 'desc');
                        case "motorbike-by-price-asc":
                            return $query->orderBy('rental_price', 'asc');
                    }
                })->paginate($num_page);
        }

        $categorys = CategoryMotorbike::where("status", "public")->get();
        $branchs = Branch::where("status", "public")->get();

        return view("frontend.motorbike", compact("categorys", "motorbikes", "branchs"));
    }

    function detail()
    {
        $motorbikeID = request('id');
        // dd($motorbikeID);
        $branchs = Branch::where("status", "public")->get();

        $motorbike = Motorbike::find($motorbikeID);
        $listMotorbikes = $motorbike->categoryMotorbike->motorbike()->where("status", "public")->get();
        return view("frontend.detail-motorbike", compact("motorbike", "branchs", "listMotorbikes"));
    }

    function order(Request $request)
    {
        $data = $request->all();
        // $start_date = $data["branch_id"];
        $invoice = Invoice::create([
            "start_date" => $data["start_date"],
            "expected_return_date" => $data["expected_return_date"],
            "phone" => $data["phone"],
            "email" => $data["email"],
            "total_rental_duration" => $data["total_rental_duration"],
            "total_amount" => $data["total_amount"],
            "motorbike_receipt_method" => $data["motorbike_receipt_method"],
            "motorbike_pickup_location" => $data["motorbike_pickup_location"],
            "motorbike_id" => $data["motorbike_id"],
            "customer_id" => Auth::guard("customer")->user()->id,
            "employee_id" => 1,
            "branch_id" => $data["branch_id"],
        ]);

        if ($invoice) {
            $motorbike = Motorbike::find($data["motorbike_id"]);
            $quantity_new = $motorbike->quantity - 1;
            $motorbike->quantity = $quantity_new;
            //    ;
            if ($motorbike->save()) {
                return response()->json(
                    [
                        "code" => "success",
                        "status" => "Đơn đặt xe thành công",
                    ]
                );
            }

        } else {
            return response()->json(
                [
                    "code" => "error",
                    "status" => "Đơn đặt xe thất bại",
                ]
            );
        }
        // dd($request->all());
    }



}
