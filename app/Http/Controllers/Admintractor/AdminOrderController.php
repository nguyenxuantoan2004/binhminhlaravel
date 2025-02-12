<?php

namespace App\Http\Controllers\Admintractor;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Motorbike;
use Auth;
use Illuminate\Http\Request;
use Session;

class AdminOrderController extends Controller
{
    public function __construct()
    {
        Session::put("ModuleActive", "invoice");
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $nameModule = "invoice";
        $nameModuleVietnamese = "đơn đặt xe";
        $status = $request->input('status', 'all'); // Mặc định là "all"
        $search = $request->input("search");

        $num_page = 10;

        // Khởi tạo query builder
        $query = Invoice::query();

        // Lọc theo trạng thái (nếu không phải "all")
        if ($status != 'all') {
            $query->where("status", "=", $status);
        }



        // Tìm kiếm theo tên
        if ($search) {
            $query->where("id", "LIKE", "%" . $search . "%");
        }


        // Tính tổng số lượng các trạng thái với điều kiện hiện tại
        $counts = [
            'all' => Invoice::when($search, function ($q) use ($search) {
                $q->where("id", "LIKE", "%{$search}%");
            })->count(),

            'pending' => Invoice::when($search, function ($q) use ($search) {
                $q->where("id", "LIKE", "%{$search}%");
            })->where("status", "pending")->count(),

            'confirmed' => Invoice::when($search, function ($q) use ($search) {
                $q->where("id", "LIKE", "%{$search}%");
            })->where("status", "confirmed")->count(),

            'delivering' => Invoice::when($search, function ($q) use ($search) {
                $q->where("id", "LIKE", "%{$search}%");
            })->where("status", "delivering")->count(),

            'waiting_for_pickup' => Invoice::when($search, function ($q) use ($search) {
                $q->where("id", "LIKE", "%{$search}%");
            })->where("status", "waiting_for_pickup")->count(),

            'picked_up' => Invoice::when($search, function ($q) use ($search) {
                $q->where("id", "LIKE", "%{$search}%");
            })->where("status", "picked_up")->count(),

            'completed' => Invoice::when($search, function ($q) use ($search) {
                $q->where("id", "LIKE", "%{$search}%");
            })->where("status", "completed")->count(),

            'cancelled' => Invoice::when($search, function ($q) use ($search) {
                $q->where("id", "LIKE", "%{$search}%");
            })->where("status", "cancelled")->count(),

            'trash' => Invoice::onlyTrashed()->when($search, function ($q) use ($search) {
                $q->where("id", "LIKE", "%{$search}%");
            })->count(),
        ];

        // Phân trang
        $invoices = $query->orderBy("id", "DESC")->paginate($num_page);

        // Điều kiện khi $status là "trash"
        if ($status == "trash" && !$search) {
            $invoices = Invoice::onlyTrashed()->orderBy("id", "DESC")->paginate($num_page);
        } else if ($status == "trash" && $search) {
            $invoices = Invoice::onlyTrashed()
                ->where("id", "LIKE", "%{$search}%")
                ->orderBy("id", "DESC")->paginate($num_page);

        }

        return view("backend.invoice.index", [
            'invoices' => $invoices,
            'counts' => $counts,
            'status' => $status,
            'search' => $search,
            'nameModule' => $nameModule,
            'nameModuleVietnamese' => $nameModuleVietnamese,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $branches = Branch::where("status", "public")->get();
        $customers = Customer::where("status", "active")->get();
        $motorbikes = Motorbike::where("status", "public")
            ->where("quantity", ">", 0)->get();

        return view("backend.invoice.create", compact("branches", "customers", "motorbikes"));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        // dd($request->all());
        // Validate dữ liệu đầu vào
        $validatedData = $request->validate(
            [
                "start_date" => "required|date",
                "expected_return_date" => "required|date|after:start_date",
                "total_rental_duration" => "required|string",
                "total_amount" => "required|numeric|min:0",
                "motorbike_receipt_method" => "required|in:store,delivery",
                "motorbike_pickup_location" => "nullable|string|max:255",
                "motorbike_id" => "required|exists:motorbikes,id",
                "customer_id" => "required|exists:customers,id",
                "branch_id" => "required_if:motorbike_receipt_method,store",
                'vat_fee' => 'nullable|numeric|min:0',
                'additional_fees' => 'nullable|numeric|min:0',
                'total_cost' => 'nullable|numeric|min:0',
            ],
            [
                'required' => 'Trường :attribute là bắt buộc.',
                'string' => 'Trường :attribute phải là một chuỗi ký tự.',
                'numeric' => 'Trường :attribute phải là một số.',
                'date' => 'Trường :attribute phải là một ngày hợp lệ.',
                'after_or_equal' => 'Trường :attribute phải là ngày hôm nay hoặc sau.',
                'after' => 'Trường :attribute phải là ngày sau ngày bắt đầu.',
                'max' => 'Trường :attribute không được vượt quá :max ký tự.',
                'min' => 'Trường :attribute phải lớn hơn hoặc bằng :min.',
                'in' => 'Trường :attribute phải là một giá trị hợp lệ.',
                'exists' => 'Trường :attribute không tồn tại.',
                'required_if' => 'Trường :attribute là bắt buộc khi phương thức nhận xe là tại cửa hàng.',

            ],
            [
                'start_date' => '<strong>ngày bắt đầu</strong>',
                'expected_return_date' => '<strong>ngày trả dự kiến</strong>',
                'total_rental_duration' => '<strong>tổng thời gian thuê</strong>',
                'total_amount' => '<strong>tổng tiền</strong>',
                'motorbike_receipt_method' => '<strong>phương thức nhận xe</strong>',
                'motorbike_pickup_location' => '<strong>địa điểm nhận xe</strong>',
                'motorbike_id' => '<strong>xe</strong>',
                'branch_id' => '<strong>đơn hàng</strong>',
                'customer_id' => '<strong>khách hàng</strong>',
                'vat_fee' => '<strong>phí VAT</strong>',
                'additional_fees' => '<strong>phụ phí</strong>',
                'total_cost' => '<strong>tổng chi phí</strong>',
            ]
        );
        $motorbike = Motorbike::find($validatedData["motorbike_id"]);

        if ($motorbike->status == "is_being_borrowed") {
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Xe đã được mượn",
                ]
            );
        }

        // Lấy thông tin email và số điện thoại từ customer_id
        $customer = Customer::findOrFail($validatedData["customer_id"]);
        // dd($customer);

        $email = $customer->email;
        $phone = $customer->phone_number;
        // Lưu thông tin đơn đặt xe
        $invoice = Invoice::create([
            "start_date" => $validatedData["start_date"],
            "expected_return_date" => $validatedData["expected_return_date"],
            "vat_fee" => $validatedData["vat_fee"],
            "phone" => $phone, // Lấy từ customer
            "email" => $email, // Lấy từ customer
            "total_rental_duration" => $validatedData["total_rental_duration"],
            "additional_fees" => $validatedData["additional_fees"],
            "total_cost" => $validatedData["total_cost"],
            "total_amount" => $validatedData["total_amount"],
            "motorbike_receipt_method" => $validatedData["motorbike_receipt_method"],
            "motorbike_pickup_location" => $validatedData["motorbike_pickup_location"],
            "motorbike_id" => $validatedData["motorbike_id"],
            "customer_id" => $validatedData["customer_id"],
            "employee_id" => Auth::guard("web")->user()->id, // Mặc định ID nhân viên
            "branch_id" => $validatedData["branch_id"] ?? 1, // đơn hàng (nếu có)
        ]);

        // Kiểm tra kết quả và trả về thông báo
        if ($invoice) {
            $motorbike = Motorbike::find($validatedData["motorbike_id"]);
            $quantity_new = $motorbike->quantity - 1;
            $motorbike->quantity = $quantity_new;
            if ($motorbike->save()) {
                return redirect()->back()->with(
                    [
                        "code" => "success",
                        "status" => "Đơn đặt xe thành công",
                    ]
                );
            }
        } else {
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Đơn đặt xe thất bại",
                ]
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $invoiceID)
    {
        //

        $invoice = Invoice::find($invoiceID);
        if ($invoice) {
            $branches = Branch::where("status", "public")->get();
            $customers = Customer::where("status", "active")->get();

            $motorbikeId = $invoice->motorbike_id;

            $motorbikes = Motorbike::where("status", "public")
                ->where("quantity", ">", 0)
                ->orWhere('id', $motorbikeId)
                ->get();
            return view("backend.invoice.edit", compact("invoice", "branches", "customers", "motorbikes"));
        } else {
            return redirect()->route("invoice.index")->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tài đơn hàng có id = $invoiceID",
                ]
            );
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $invoiceID)
    {
        // Tìm kiếm hóa đơn theo ID
        $invoice = Invoice::find($invoiceID);

        if (!$invoice) {
            return redirect()->route("invoice.index")->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tại hóa đơn với id = $invoiceID",
                ]
            );
        }
        // dd($request->all());
        // Validate dữ liệu đầu vào
        $validatedData = $request->validate(
            [
                "start_date" => "required|date",
                // "expected_return_date" => "date|after:date",
                "total_rental_duration" => "required|string",
                "total_amount" => "required|numeric|min:0",
                "motorbike_receipt_method" => "required|in:store,delivery",
                "motorbike_pickup_location" => "nullable|string|max:255",
                "motorbike_id" => "required|exists:motorbikes,id",
                "customer_id" => "required|exists:customers,id",
                "branch_id" => "required_if:motorbike_receipt_method,store",
                'vat_fee' => 'nullable|numeric|min:0',
                'additional_fees' => 'nullable|numeric|min:0',
                'total_cost' => 'nullable|numeric|min:0',
                'status' => 'required|in:pending,confirmed,delivering,waiting_for_pickup,picked_up,completed,cancelled,trash',
                'after_rental_status' => 'nullable|string',
            ],
            [
                'required' => 'Trường :attribute là bắt buộc.',
                'string' => 'Trường :attribute phải là một chuỗi ký tự.',
                'numeric' => 'Trường :attribute phải là một số.',
                'date' => 'Trường :attribute phải là một ngày hợp lệ.',
                'after_or_equal' => 'Trường :attribute phải là ngày hôm nay hoặc sau.',
                'after' => 'Trường :attribute phải là ngày sau ngày bắt đầu.',
                'max' => 'Trường :attribute không được vượt quá :max ký tự.',
                'min' => 'Trường :attribute phải lớn hơn hoặc bằng :min.',
                'in' => 'Trường :attribute phải là một giá trị hợp lệ.',
                'exists' => 'Trường :attribute không tồn tại.',
                'required_if' => 'Trường :attribute là bắt buộc khi phương thức nhận xe là tại cửa hàng.',
            ],
            [
                'start_date' => '<strong>ngày bắt đầu</strong>',
                'expected_return_date' => '<strong>ngày trả thực tế</strong>',
                'total_rental_duration' => '<strong>tổng thời gian thuê</strong>',
                'total_amount' => '<strong>tổng tiền</strong>',
                'motorbike_receipt_method' => '<strong>phương thức nhận xe</strong>',
                'motorbike_pickup_location' => '<strong>địa điểm nhận xe</strong>',
                'motorbike_id' => '<strong>xe</strong>',
                'branch_id' => '<strong>đơn hàng</strong>',
                'customer_id' => '<strong>khách hàng</strong>',
                'vat_fee' => '<strong>phí VAT</strong>',
                'additional_fees' => '<strong>phụ phí</strong>',
                'total_cost' => '<strong>tổng chi phí</strong>',
                'status' => '<strong>trạng thái</strong>',
                'after_rental_status' => '<strong>trạng thái khi trả xe</strong>',
            ]
        );

        $motorbikeID = $invoice->motorbike_id;

        $motorbike = Motorbike::find($validatedData["motorbike_id"]);

        if ($motorbike->id != $motorbikeID && $motorbike->quantity <= 0) {
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Xe đang chọn đã hết hàng.",
                ]
            );
        }

        // Lấy thông tin khách hàng từ customer_id
        $customer = Customer::findOrFail($validatedData["customer_id"]);
        $email = $customer->email;
        $phone = $customer->phone_number;

        // Cập nhật thông tin hóa đơn
        $invoice->start_date = $validatedData["start_date"];

        if($validatedData["status"] == "completed"){
            $invoice->actual_return_date = $validatedData["expected_return_date"];
        }

        $invoice->vat_fee = $validatedData["vat_fee"];
        $invoice->total_rental_duration = $validatedData["total_rental_duration"];
        $invoice->additional_fees = $validatedData["additional_fees"];
        $invoice->total_cost = $validatedData["total_cost"];
        $invoice->total_amount = $validatedData["total_amount"];
        $invoice->motorbike_receipt_method = $validatedData["motorbike_receipt_method"];
        $invoice->motorbike_pickup_location = $validatedData["motorbike_pickup_location"];
        $invoice->status = $validatedData["status"];
        $invoice->motorbike_id = $validatedData["motorbike_id"];
        $invoice->customer_id = $validatedData["customer_id"];
        $invoice->employee_id = Auth::guard("web")->user()->id; // Mặc định ID nhân viên
        $invoice->branch_id = $validatedData["branch_id"] ?? 1; // Cập nhật đơn hàng (nếu có)
        $invoice->after_rental_status = $validatedData["after_rental_status"]; // Cập nhật đơn hàng (nếu có)

        
        // Lưu thông tin hóa đơn
        if ($invoice->save()) {

            if ($invoice->status == "cancelled" || $invoice->status == "trash" || $invoice->status == "completed") {
                $motorbike = Motorbike::find($validatedData["motorbike_id"]);
                $quantity_new = $motorbike->quantity + 1;
                $motorbike->quantity = $quantity_new;
                if ($motorbike->save()) {
                    return redirect()->back()->with(
                        [
                            "code" => "success",
                            "status" => "Cập nhật đơn đặt xe thành công",
                        ]
                    );
                }
            }

            return redirect()->back()->with(
                [
                    "code" => "success",
                    "status" => "Cập nhật đơn đặt xe thành công",
                ]
            );

        } else {
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Cập nhật đơn đặt xe thất bại",
                ]
            );
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $invoiceID)
    {
        //
        $invoice = Invoice::find($invoiceID);

        if ($invoice) {
            $invoice->delete();
            return response()->json(
                [
                    "code" => "success",
                    "status" => "Xóa đơn hàng thành công",
                ]
            );

        } else {
            return response()->json(
                [
                    "code" => 'error',
                    "status" => "Xóa đơn hàng thất bại",
                ]
            );
        }
    }



    public function restore(int $invoiceID)
    {
        //

        $invoice = Invoice::withTrashed()->find($invoiceID);

        if ($invoice) {
            $invoice->restore();
            return response()->json(
                [
                    "code" => "success",
                    "status" => "Khôi phục đơn hàng thành công",
                ]
            );

        } else {
            return response()->json(
                [
                    "code" => 'error',
                    "status" => "Khôi phục đơn hàng thất bại",
                ]
            );
        }
    }



    public function forceDelete(int $invoiceID)
    {
        //
        $invoice = Invoice::withTrashed()->find($invoiceID);
        // $invoice = 1;

        if ($invoice) {
            $invoice->forceDelete();
            return response()->json(
                [
                    "code" => "success",
                    "status" => "Xóa đơn hàng vĩnh viễn thành công",
                ]
            );

        } else {
            return response()->json(
                [
                    "code" => 'error',
                    "status" => "Xóa đơn hàng vĩnh viễn thất bại",
                ]
            );
        }
    }

    // hàm chỉnh sửa action 

    public function updateAction(Request $request)
    {
        // $action = 1;
        $action = $request->action;
        $selecteds = $request->selecteds;
        $error = 0;

        if ($action == "delete") {
            foreach ($selecteds as $selected) {
                $invoice = Invoice::find($selected);
                if ($invoice) {
                    $invoice->delete();
                } else {
                    $error = 1;
                }
            }
        } elseif ($action == "restore") {
            foreach ($selecteds as $selected) {
                $invoice = Invoice::withTrashed()->find($selected);
                if ($invoice) {
                    $invoice->restore();
                } else {
                    $error = 1;
                }
            }

        } elseif ($action == "forceDelete") {
            foreach ($selecteds as $selected) {
                $invoice = Invoice::withTrashed()->find($selected);
                if ($invoice) {
                    $invoice->forceDelete();
                } else {
                    $error = 1;
                }
            }

        } else {
            foreach ($selecteds as $selected) {
                $invoice = Invoice::find($selected);
                if ($invoice) {
                    $invoice->status = $action;
                    $invoice->save();
                } else {
                    $error = 1;
                }
            }
        }

        if ($error == 0) {
            return response()->json(
                [
                    "code" => "success",
                    "status" => "Thực hiện thay đổi hàng loạt thành công",
                ]
            );
        } else {
            return response()->json(
                [
                    "code" => "error",
                    "status" => "Thực hiện thay đổi hàng loạt thất bại",
                ]
            );
        }

    }
}
