<?php

namespace App\Http\Controllers\Admintractor;

use App\Enums\StatusGlobal;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\MotorbikeController;
use App\Models\CategoryMotorbike;
use Illuminate\Http\Request;
use Session;
use Str;

class AdminMotorbikeCategoryController extends Controller
{

    public function __construct()
    {
        Session::put("ModuleActive", "category");
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $nameModule = "motorbike-category";
        $nameModuleVietnamese = "loại xe";
        $status = $request->input('status', 'all'); // Mặc định là "all"
        $search = $request->input("search");

        $num_page = 10;

        // Khởi tạo query builder
        $query = CategoryMotorbike::query();

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
            'all' => CategoryMotorbike::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->count(),

            'public' => CategoryMotorbike::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "public")->count(),

            'private' => CategoryMotorbike::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "private")->count(),

            'pending' => CategoryMotorbike::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "pending")->count(),

            'draft' => CategoryMotorbike::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "draft")->count(),

            'trash' => CategoryMotorbike::onlyTrashed()->when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->count(),
        ];

        // Phân trang
        $categorys = $query->orderBy("id", "DESC")->paginate($num_page);

        // Điều kiện khi $status là "trash"
        if ($status == "trash" && !$search) {
            $categorys = CategoryMotorbike::onlyTrashed()->orderBy("id", "DESC")->paginate($num_page);
        }else if($status == "trash" && $search){
            $categorys = CategoryMotorbike::onlyTrashed()
            ->where("name", "LIKE", "%{$search}%")
            ->orderBy("id", "DESC")->paginate($num_page);

        }

     

        return view(
            "backend.motorbike_category.index",
            [
                'categorys' => $categorys,
                'counts' => $counts,
                'status' => $status,
                'search' => $search,
                'nameModule' => $nameModule,
                'nameModuleVietnamese' => $nameModuleVietnamese,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view("backend.motorbike_category.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'status' => "required|in:draft,public,pending,private",
        ], [
            // Tùy chỉnh thông báo lỗi
            'name.required' => 'Tên danh mục không được để trống.',
            'name.string' => 'Tên danh mục phải là chuỗi ký tự.',
            'name.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
            'status.required' => 'Trạng thái không được để trống.',
            'status.in' => 'Trạng thái không đúng',
        ]);


        //
        $category = CategoryMotorbike::create(
            [
                "name" => $validatedData['name'],
                "slug" => Str::slug($validatedData['name']),
                "status" => $validatedData['status'],
            ]
        );

        if ($category) {
            return redirect()->back()->with(
                [
                    "code" => "success",
                    "status" => "Thêm mới loại xe thành công",
                ]
            );

        } else {
            return redirect()->back()->withInput()->with(
                [
                    "code" => 'error',
                    "status" => "Thêm mới loại xe thất bại",
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
    public function edit(int $motorbike_category)
    {
        //
        $category = CategoryMotorbike::find($motorbike_category);
        if (!$category) {
            return redirect()->route("motorbike-category.index")->with(
                [
                    "code" => "error",
                    "status" => "Danh mục không tồn tại",
                ]
            );
        }
        // dd($category);
        return view("backend.motorbike_category.edit", compact("category"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $motorbike_category)
    {
        $category = CategoryMotorbike::find($motorbike_category);

        if (!$category) {
            return redirect()->back()->with(
                [
                    "code" => "error",
                    "status" => "Danh mục không tồn tại",
                ]
            );
        }
        //
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'status' => "required|in:draft,public,pending,private",
        ], [
            // Tùy chỉnh thông báo lỗi
            'name.required' => 'Tên danh mục không được để trống.',
            'name.string' => 'Tên danh mục phải là chuỗi ký tự.',
            'name.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
            'status.required' => 'Trạng thái không được để trống.',
            'status.in' => 'Trạng thái không đúng',
        ]);




        $category->name = $validatedData['name'];
        $category->slug = Str::slug($validatedData['name']);
        $category->status = $validatedData['status'];

        if ($category->save()) {
            return redirect()->back()->with(
                [
                    "code" => "success",
                    "status" => "Cập nhật loại xe thành công",
                ]
            );

        } else {
            return redirect()->back()->withInput()->with(
                [
                    "code" => 'error',
                    "status" => "Cập nhật loại xe thất bại",
                ]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $motorbike_category)
    {
        //
        $category = CategoryMotorbike::find($motorbike_category);

        if ($category) {
            $category->delete();
            return response()->json(
                [
                    "code" => "success",
                    "status" => "Xóa tạm thời loại xe thành công",
                ]
            );

        } else {
            return response()->json(
                [
                    "code" => 'error',
                    "status" => "Xóa tạm thời loại xe thất bại",
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
                $category = CategoryMotorbike::find($selected);
                if ($category) {
                    $category->delete();
                } else {
                    $error = 1;
                }
            }
        } elseif ($action == "restore") {
            foreach ($selecteds as $selected) {
                $category = CategoryMotorbike::withTrashed()->find($selected);
                if ($category) {
                    $category->restore();
                } else {
                    $error = 1;
                }
            }

        } elseif ($action == "forceDelete") {
            foreach ($selecteds as $selected) {
                $category = CategoryMotorbike::withTrashed()->find($selected);
                if ($category) {
                    $category->forceDelete();
                } else {
                    $error = 1;
                }
            }
        }else{
            foreach ($selecteds as $selected) {
                $category = CategoryMotorbike::find($selected);
                if ($category) {
                    $category->status = $action;
                    $category->save();
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

    public function restore(int $motorbike_category)
    {
        //

        $category = CategoryMotorbike::withTrashed()->find($motorbike_category);
        // $category = 1;

        if ($category) {
            $category->restore();
            return response()->json(
                [
                    "code" => "success",
                    "status" => "Khôi phục loại xe thành công",
                ]
            );

        } else {
            return response()->json(
                [
                    "code" => 'error',
                    "status" => "Khôi phục loại xe thất bại",
                ]
            );
        }
    }



    public function forceDelete(int $motorbike_category)
    {
        //
        $category = CategoryMotorbike::withTrashed()->find($motorbike_category);
        // $category = 1;

        if ($category) {
            $category->forceDelete();
            return response()->json(
                [
                    "code" => "success",
                    "status" => "Xóa loại xe vĩnh viễn thành công",
                ]
            );

        } else {
            return response()->json(
                [
                    "code" => 'error',
                    "status" => "Xóa loại xe vĩnh viễn thất bại",
                ]
            );
        }
    }
}
