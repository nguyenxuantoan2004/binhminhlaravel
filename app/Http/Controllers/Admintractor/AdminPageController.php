<?php

namespace App\Http\Controllers\Admintractor;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Auth;
use Illuminate\Http\Request;
use Session;
use Storage;
use Str;

class AdminPageController extends Controller
{
    public function __construct()
    {
        Session::put("ModuleActive", "page");
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //

        $nameModule = "page";
        $nameModuleVietnamese = "trang";
        $status = $request->input('status', 'all'); // Mặc định là "all"
        $search = $request->input("search");

        $num_page = 10;

        // Khởi tạo query builder
        $query = Page::query();

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
            'all' => Page::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->count(),

            'public' => Page::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "public")->count(),

            'private' => Page::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "private")->count(),

            'pending' => Page::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "pending")->count(),

            'draft' => Page::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "draft")->count(),

            'trash' => Page::onlyTrashed()->when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->count(),
        ];

        // Phân trang
        $pages = $query->orderBy("id", "DESC")->paginate($num_page);

        // Điều kiện khi $status là "trash"
        if ($status == "trash" && !$search) {
            $pages = Page::onlyTrashed()->orderBy("id", "DESC")->paginate($num_page);
        } else if ($status == "trash" && $search) {
            $pages = Page::onlyTrashed()
                ->where("name", "LIKE", "%{$search}%")
                ->orderBy("id", "DESC")->paginate($num_page);
        }

        return view("backend.page.index", [
            'pages' => $pages,
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
        return view("backend.page.create");

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        //
        $validatedData = $request->validate(
            [
                "name" => 'required|string|max:255',
                "content" => 'required|string',
                'status' => "required|in:draft,public,pending,private",
            ],
            [
                'required' => 'Trường :attribute là bắt buộc.',
                'string' => 'Trường :attribute phải là một chuỗi ký tự.',
                'max' => 'Trường :attribute không được vượt quá :max ký tự.',
                'in' => 'Trường :attribute phải là một trạng thái (nháp, công khai, không công khai, chờ duyệt) hợp lệ.',
            ],
            [
                "name" => '<strong>tên trang</strong>',
                "content" => '<strong>nội dung</strong>',
                'status' => '<strong>trạng thái</strong>',
            ]
        );

        $page = Page::create([
            "name" => $validatedData['name'],
            "slug" => Str::slug($validatedData['name']),
            "content" => $validatedData['content'],
            "status" => $validatedData['status'],
            "employee_id" => Auth::guard('web')->user()->id,
        ]);

        if ($page) {
            return redirect()->back()->with(
                [
                    "code" => "success",
                    "status" => "Thêm mới trang thành công",
                ]
            );
        } else {
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Thêm mới trang thất bại",
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
    public function edit(string $pageID)
    {
        //
        $page = Page::find($pageID);
        if ($page) {
            return view("backend.page.edit", compact("page"));
        } else {
            return redirect()->route("page.index")->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tài chi nhánh có id = $pageID",
                ]
            );
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $pageID)
    {
        //
        $page = Page::find($pageID);
        if (!$page) {
            return redirect()->route("page.index")->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tại trang có id = $pageID",
                ]
            );
        }

        $validatedData = $request->validate(
            [
                "name" => 'required|string|max:255',
                "content" => 'required|string',
                'status' => "required|in:draft,public,pending,private",
            ],
            [
                'required' => 'Trường :attribute là bắt buộc.',
                'string' => 'Trường :attribute phải là một chuỗi ký tự.',
                'max' => 'Trường :attribute không được vượt quá :max ký tự.',
                'in' => 'Trường :attribute phải là một trạng thái (nháp, công khai, không công khai, chờ duyệt) hợp lệ.',
            ],
            [
                "name" => '<strong>tên trang</strong>',
                "content" => '<strong>nội dung</strong>',
                'status' => '<strong>trạng thái</strong>',
            ]
        );

        $page->name = $validatedData['name'];
        $page->slug = Str::slug($validatedData['name']);
        $page->content = $validatedData['content'];
        $page->status = $validatedData['status'];
        $page->employee_id = Auth::guard('web')->user()->id;

        if ($page->save()) {
            return redirect()->back()->with(
                [
                    "code" => "success",
                    "status" => "Cập nhật trang thành công",
                ]
            );
        } else {
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Cập nhật trang thất bại",
                ]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $pageID)
    {
        //
        $page = Page::find($pageID);

        if ($page) {
            $page->delete();
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



    public function restore(int $pageID)
    {
        //

        $page = Page::withTrashed()->find($pageID);

        if ($page) {
            $page->restore();
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



    public function forceDelete(int $pageID)
    {
        //
        $page = Page::withTrashed()->find($pageID);
        // $page = 1;

        if ($page) {
            $page->forceDelete();
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
                $page = Page::find($selected);
                if ($page) {
                    $page->delete();
                } else {
                    $error = 1;
                }
            }
        } elseif ($action == "restore") {
            foreach ($selecteds as $selected) {
                $page = Page::withTrashed()->find($selected);
                if ($page) {
                    $page->restore();
                } else {
                    $error = 1;
                }
            }

        } elseif ($action == "forceDelete") {
            foreach ($selecteds as $selected) {
                $page = Page::withTrashed()->find($selected);
                if ($page) {
                    $page->forceDelete();
                } else {
                    $error = 1;
                }
            }

        } else {
            foreach ($selecteds as $selected) {
                $page = Page::find($selected);
                if ($page) {
                    $page->status = $action;
                    $page->save();
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
