<?php

namespace App\Http\Controllers\Admintractor;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;
use Session;

class AdminPositionController extends Controller
{
    public function __construct()
    {
        Session::put("ModuleActive", "position");
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $nameModule = "position";
        $nameModuleVietnamese = "chức vụ";
        $status = $request->input('status', 'all'); // Mặc định là "all"
        $search = $request->input("search");

        $num_page = 10;

        // Khởi tạo query builder
        $query = Position::query();

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
            'all' => Position::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->count(),

            'public' => Position::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "public")->count(),

            'private' => Position::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "private")->count(),

            'pending' => Position::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "pending")->count(),

            'draft' => Position::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "draft")->count(),

            'trash' => Position::onlyTrashed()->when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->count(),
        ];

        // Phân trang
        $positions = $query->orderBy("id", "DESC")->paginate($num_page);

        // Điều kiện khi $status là "trash"
        if ($status == "trash" && !$search) {
            $positions = Position::onlyTrashed()->orderBy("id", "DESC")->paginate($num_page);
        } else if ($status == "trash" && $search) {
            $positions = Position::onlyTrashed()
                ->where("name", "LIKE", "%{$search}%")
                ->orderBy("id", "DESC")->paginate($num_page);

        }

        return view("backend.position.index", [
            'positions' => $positions,
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
        return view("backend.position.create");

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
                'name' => '<strong>tên chức vụ</strong>',
                'status' => '<strong>trạng thái</strong>',
            ]
        );

        $position = Position::create([
            "name" => $validatedData['name'],
            "status" => $validatedData['status'],
        ]);

        if ($position) {
            return redirect()->back()->with(
                [
                    "code" => "success",
                    "status" => "Thêm mới chức vụ thành công",
                ]
            );
        } else {
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Thêm mới chức vụ thất bại",
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
    public function edit(string $positionID)
    {
        //
        $position = Position::find($positionID);
        if ($position) {
            return view("backend.position.edit", compact("position"));
        } else {
            return redirect()->route("position.index")->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tài chức vụ có id = $positionID",
                ]
            );
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $positionID)
    {
        //
        $position = Position::find($positionID);
        if (!$position) {
            return redirect()->route("position.index")->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tài chức vụ có id = $positionID",
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


        $position->name = $validatedData['name'];
        $position->status = $validatedData['status'];



        if ($position->save()) {
            return redirect()->back()->with(
                [
                    "code" => "success",
                    "status" => "Cập nhật chức vụ thành công",
                ]
            );
        } else {
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Cập nhật chức vụ thất bại",
                ]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $positionID)
    {
        //
        $position = Position::find($positionID);

        if ($position) {
            $position->delete();
            return response()->json(
                [
                    "code" => "success",
                    "status" => "Xóa chức vụ thành công",
                ]
            );

        } else {
            return response()->json(
                [
                    "code" => 'error',
                    "status" => "Xóa chức vụ thất bại",
                ]
            );
        }
    }



    public function restore(int $positionID)
    {
        //

        $position = Position::withTrashed()->find($positionID);

        if ($position) {
            $position->restore();
            return response()->json(
                [
                    "code" => "success",
                    "status" => "Khôi phục chức vụ thành công",
                ]
            );

        } else {
            return response()->json(
                [
                    "code" => 'error',
                    "status" => "Khôi phục chức vụ thất bại",
                ]
            );
        }
    }



    public function forceDelete(int $positionID)
    {
        //
        $position = Position::withTrashed()->find($positionID);
        // $position = 1;

        if ($position) {
            $position->forceDelete();
            return response()->json(
                [
                    "code" => "success",
                    "status" => "Xóa chức vụ vĩnh viễn thành công",
                ]
            );

        } else {
            return response()->json(
                [
                    "code" => 'error',
                    "status" => "Xóa chức vụ vĩnh viễn thất bại",
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
                $position = Position::find($selected);
                if ($position) {
                    $position->delete();
                } else {
                    $error = 1;
                }
            }
        } elseif ($action == "restore") {
            foreach ($selecteds as $selected) {
                $position = Position::withTrashed()->find($selected);
                if ($position) {
                    $position->restore();
                } else {
                    $error = 1;
                }
            }

        } elseif ($action == "forceDelete") {
            foreach ($selecteds as $selected) {
                $position = Position::withTrashed()->find($selected);
                if ($position) {
                    $position->forceDelete();
                } else {
                    $error = 1;
                }
            }

        }else{
            foreach ($selecteds as $selected) {
                $position = Position::find($selected);
                if ($position) {
                    $position->status = $action;
                    $position->save();
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
