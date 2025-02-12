<?php

namespace App\Http\Controllers\Admintractor;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Session;

class AdminBranchController extends Controller
{
    public function __construct()
    {
        Session::put("ModuleActive", "branch");
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $nameModule = "branch";
        $nameModuleVietnamese = "chi nhánh";
        $status = $request->input('status', 'all'); // Mặc định là "all"
        $search = $request->input("search");

        $num_page = 10;

        // Khởi tạo query builder
        $query = Branch::query();

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
            'all' => Branch::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->count(),

            'public' => Branch::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "public")->count(),

            'private' => Branch::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "private")->count(),

            'pending' => Branch::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "pending")->count(),

            'draft' => Branch::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "draft")->count(),

            'trash' => Branch::onlyTrashed()->when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->count(),
        ];

        // Phân trang
        $branchs = $query->orderBy("id", "DESC")->paginate($num_page);

        // Điều kiện khi $status là "trash"
        if ($status == "trash" && !$search) {
            $branchs = Branch::onlyTrashed()->orderBy("id", "DESC")->paginate($num_page);
        } else if ($status == "trash" && $search) {
            $branchs = Branch::onlyTrashed()
                ->where("name", "LIKE", "%{$search}%")
                ->orderBy("id", "DESC")->paginate($num_page);

        }

        return view("backend.branch.index", [
            'branchs' => $branchs,
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
        return view("backend.branch.create");

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
                'name' => '<strong>tên chi nhánh</strong>',
                'address' => '<strong>địa chỉ</strong>',
                'phone_number' => '<strong>số điện thoại</strong>',
                'status' => '<strong>trạng thái</strong>',
            ]
        );

        $branch = Branch::create([
            "name" => $validatedData['name'],
            "phone_number" => $validatedData['phone_number'],
            "address" => $validatedData['address'],
            "status" => $validatedData['status'],
        ]);

        if ($branch) {
            return redirect()->back()->with(
                [
                    "code" => "success",
                    "status" => "Thêm mới chi nhánh thành công",
                ]
            );
        } else {
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Thêm mới chi nhánh thất bại",
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
    public function edit(string $branchID)
    {
        //
        $branch = Branch::find($branchID);
        if ($branch) {
            return view("backend.branch.edit", compact("branch"));
        } else {
            return redirect()->route("branch.index")->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tài chi nhánh có id = $branchID",
                ]
            );
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $branchID)
    {
        //
        $branch = Branch::find($branchID);
        if (!$branch) {
            return redirect()->route("branch.index")->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tài chi nhánh có id = $branchID",
                ]
            );
        }

        $validatedData = $request->validate(
            [
                "name" => 'required|string|max:255',
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
                'status' => '<strong>trạng thái</strong>',
            ]
        );


        $branch->name = $validatedData['name'];
        $branch->status = $validatedData['status'];



        if ($branch->save()) {
            return redirect()->back()->with(
                [
                    "code" => "success",
                    "status" => "Cập nhật chi nhánh thành công",
                ]
            );
        } else {
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Cập nhật chi nhánh thất bại",
                ]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $branchID)
    {
        //
        $branch = Branch::find($branchID);

        if ($branch) {
            $branch->delete();
            return response()->json(
                [
                    "code" => "success",
                    "status" => "Xóa chi nhánh thành công",
                ]
            );

        } else {
            return response()->json(
                [
                    "code" => 'error',
                    "status" => "Xóa chi nhánh thất bại",
                ]
            );
        }
    }



    public function restore(int $branchID)
    {
        //

        $branch = Branch::withTrashed()->find($branchID);

        if ($branch) {
            $branch->restore();
            return response()->json(
                [
                    "code" => "success",
                    "status" => "Khôi phục chi nhánh thành công",
                ]
            );

        } else {
            return response()->json(
                [
                    "code" => 'error',
                    "status" => "Khôi phục chi nhánh thất bại",
                ]
            );
        }
    }



    public function forceDelete(int $branchID)
    {
        //
        $branch = Branch::withTrashed()->find($branchID);
        // $branch = 1;

        if ($branch) {
            $branch->forceDelete();
            return response()->json(
                [
                    "code" => "success",
                    "status" => "Xóa chi nhánh vĩnh viễn thành công",
                ]
            );

        } else {
            return response()->json(
                [
                    "code" => 'error',
                    "status" => "Xóa chi nhánh vĩnh viễn thất bại",
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
                $branch = Branch::find($selected);
                if ($branch) {
                    $branch->delete();
                } else {
                    $error = 1;
                }
            }
        } elseif ($action == "restore") {
            foreach ($selecteds as $selected) {
                $branch = Branch::withTrashed()->find($selected);
                if ($branch) {
                    $branch->restore();
                } else {
                    $error = 1;
                }
            }

        } elseif ($action == "forceDelete") {
            foreach ($selecteds as $selected) {
                $branch = Branch::withTrashed()->find($selected);
                if ($branch) {
                    $branch->forceDelete();
                } else {
                    $error = 1;
                }
            }

        } else {
            foreach ($selecteds as $selected) {
                $branch = Branch::find($selected);
                if ($branch) {
                    $branch->status = $action;
                    $branch->save();
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
