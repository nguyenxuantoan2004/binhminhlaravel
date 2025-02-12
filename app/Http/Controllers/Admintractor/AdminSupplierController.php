<?php

namespace App\Http\Controllers\Admintractor;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Session;

class AdminSupplierController extends Controller
{
    public function __construct()
    {
        Session::put("ModuleActive", "supplier");
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $nameModule = "supplier";
        $nameModuleVietnamese = "nhà cung cấp";
        $status = $request->input('status', 'all'); // Mặc định là "all"
        $search = $request->input("search");

        $num_page = 10;

        // Khởi tạo query builder
        $query = Supplier::query();

        // Lọc theo trạng thái (nếu không phải "all")
        if ($status != 'all') {
            $query->where("status", "=", $status);
        }



        // Tìm kiếm theo tên
        if ($search) {
            $query->where("name", "LIKE", "%" . $search . "%");
        }


        // Tính tổng số lượng các trạng thái với điều kiện hiện tại
        $counts = [
            'all' => Supplier::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->count(),

            'public' => Supplier::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "public")->count(),

            'private' => Supplier::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "private")->count(),

            'pending' => Supplier::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "pending")->count(),

            'draft' => Supplier::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "draft")->count(),

            'trash' => Supplier::onlyTrashed()->when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->count(),
        ];

        // Phân trang
        $suppliers = $query->orderBy("id", "DESC")->paginate($num_page);

        // Điều kiện khi $status là "trash"
        if ($status == "trash" && !$search) {
            $suppliers = Supplier::onlyTrashed()->orderBy("id", "DESC")->paginate($num_page);
        } else if ($status == "trash" && $search) {
            $suppliers = Supplier::onlyTrashed()
                ->where("name", "LIKE", "%{$search}%")
                ->orderBy("id", "DESC")->paginate($num_page);

        }

        return view("backend.supplier.index", [
            'suppliers' => $suppliers,
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
        return view("backend.supplier.create");

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->input());
        //
        $validatedData = $request->validate(
            [
                "name" => 'required|string|max:255',
                "address" => 'required|string',
                'phone_number' => ['required', 'regex:/^(032|033|034|035|036|037|038|039|096|097|098|086|083|084|085|081|082|088|091|094|070|079|077|076|078|090|093|089|056|058|092|059|099)[0-9]{7}$/'],
                'email' => 'required|email',
                'status' => "required|in:draft,public,pending,private",
            ],
            [
                'required' => 'Trường :attribute là bắt buộc.',
                'string' => 'Trường :attribute phải là một chuỗi ký tự.',
                'max' => 'Trường :attribute không được vượt quá :max ký tự.',
                'regex' => 'Trường :attribute không đúng định dạng.',
                'email' => 'Trường :attribute phải là một địa chỉ email hợp lệ.',
                'in' => 'Trường :attribute phải là một trạng thái (nháp, công khai, không công khai, chờ duyệt) hợp lệ.',
            ],
            [
                'name' => '<strong>tên nhà cung cấp</strong>',
                'address' => '<strong>địa chỉ</strong>',
                'phone_number' => '<strong>số điện thoại</strong>',
                'email' => '<strong>địa chỉ email</strong>',
                'status' => '<strong>trạng thái</strong>',
            ]
        );

        $supplier = Supplier::create([
            "name" => $validatedData['name'],
            "address" => $validatedData['address'],
            "phone_number" => $validatedData['phone_number'],
            "email" => $validatedData['email'],
            "status" => $validatedData['status'],
        ]);

        if ($supplier) {
            return redirect()->back()->with(
                [
                    "code" => "success",
                    "status" => "Thêm mới nhà cung cấp thành công",
                ]
            );
        } else {
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Thêm mới nhà cung cấp thất bại",
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
    public function edit(string $supplierID)
    {
        //
        $supplier = Supplier::find($supplierID);
        if ($supplier) {
            return view("backend.supplier.edit", compact("supplier"));
        } else {
            return redirect()->route("supplier.index")->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tài nhà cung cấp có id = $supplierID",
                ]
            );
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $supplierID)
    {
        //
        $supplier = Supplier::find($supplierID);
        if (!$supplier) {
            return redirect()->route("supplier.index")->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tài nhà cung cấp có id = $supplierID",
                ]
            );
        }

        $validatedData = $request->validate(
            [
                "name" => 'required|string|max:255',
                "address" => 'required|string',
                'phone_number' => ['required', 'regex:/^(032|033|034|035|036|037|038|039|096|097|098|086|083|084|085|081|082|088|091|094|070|079|077|076|078|090|093|089|056|058|092|059|099)[0-9]{7}$/'],
                'email' => 'required|email',
                'status' => "required|in:draft,public,pending,private",
            ],
            [
                'required' => 'Trường :attribute là bắt buộc.',
                'string' => 'Trường :attribute phải là một chuỗi ký tự.',
                'max' => 'Trường :attribute không được vượt quá :max ký tự.',
                'regex' => 'Trường :attribute không đúng định dạng.',
                'email' => 'Trường :attribute phải là một địa chỉ email hợp lệ.',
                'in' => 'Trường :attribute phải là một trạng thái (nháp, công khai, không công khai, chờ duyệt) hợp lệ.',
            ],
            [
                'name' => '<strong>họ và tên</strong>',
                'address' => '<strong>địa chỉ</strong>',
                'phone_number' => '<strong>số điện thoại</strong>',
                'email' => '<strong>địa chỉ email</strong>',
                'status' => '<strong>trạng thái</strong>',
            ]
        );


        $supplier->name = $validatedData['name'];
        $supplier->address = $validatedData['address'];
        $supplier->phone_number = $validatedData['phone_number'];
        $supplier->email = $validatedData['email'];
        $supplier->status = $validatedData['status'];



        if ($supplier->save()) {
            return redirect()->back()->with(
                [
                    "code" => "success",
                    "status" => "Cập nhật nhà cung cấp thành công",
                ]
            );
        } else {
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Cập nhật nhà cung cấp thất bại",
                ]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $supplierID)
    {
        //
        $supplier = Supplier::find($supplierID);

        if ($supplier) {
            $supplier->delete();
            return response()->json(
                [
                    "code" => "success",
                    "status" => "Xóa nhà cung cấp thành công",
                ]
            );

        } else {
            return response()->json(
                [
                    "code" => 'error',
                    "status" => "Xóa nhà cung cấp thất bại",
                ]
            );
        }
    }



    public function restore(int $supplierID)
    {
        //

        $supplier = Supplier::withTrashed()->find($supplierID);

        if ($supplier) {
            $supplier->restore();
            return response()->json(
                [
                    "code" => "success",
                    "status" => "Khôi phục nhà cung cấp thành công",
                ]
            );

        } else {
            return response()->json(
                [
                    "code" => 'error',
                    "status" => "Khôi phục nhà cung cấp thất bại",
                ]
            );
        }
    }



    public function forceDelete(int $supplierID)
    {
        //
        $supplier = Supplier::withTrashed()->find($supplierID);
        // $supplier = 1;

        if ($supplier) {
            $supplier->forceDelete();
            return response()->json(
                [
                    "code" => "success",
                    "status" => "Xóa nhà cung cấp vĩnh viễn thành công",
                ]
            );

        } else {
            return response()->json(
                [
                    "code" => 'error',
                    "status" => "Xóa nhà cung cấp vĩnh viễn thất bại",
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
                $supplier = Supplier::find($selected);
                if ($supplier) {
                    $supplier->delete();
                } else {
                    $error = 1;
                }
            }
        } elseif ($action == "restore") {
            foreach ($selecteds as $selected) {
                $supplier = Supplier::withTrashed()->find($selected);
                if ($supplier) {
                    $supplier->restore();
                } else {
                    $error = 1;
                }
            }

        } elseif ($action == "forceDelete") {
            foreach ($selecteds as $selected) {
                $supplier = Supplier::withTrashed()->find($selected);
                if ($supplier) {
                    $supplier->forceDelete();
                } else {
                    $error = 1;
                }
            }

        }else{
            foreach ($selecteds as $selected) {
                $supplier = Supplier::find($selected);
                if ($supplier) {
                    $supplier->status = $action;
                    $supplier->save();
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
