<?php

namespace App\Http\Controllers\Admintractor;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Auth;
use Illuminate\Http\Request;
use Session;
use Storage;
use Str;

class AdminPostController extends Controller
{
    public function __construct()
    {
        Session::put("ModuleActive", "post");
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //

        $nameModule = "post";
        $nameModuleVietnamese = "bài viết";
        $status = $request->input('status', 'all'); // Mặc định là "all"
        $search = $request->input("search");

        $num_page = 10;

        // Khởi tạo query builder
        $query = Post::query();

        // Lọc theo trạng thái (nếu không phải "all")
        if ($status != 'all') {
            $query->where("status", "=", $status);
        }



        // Tìm kiếm theo tên
        if ($search) {
            $query->where("title", "LIKE", "%" . $search . "%");
        }


        // Tính tổng số lượng các trạng thái với điều kiện hiện tại
        $counts = [
            'all' => Post::when($search, function ($q) use ($search) {
                $q->where("title", "LIKE", "%{$search}%");
            })->count(),

            'public' => Post::when($search, function ($q) use ($search) {
                $q->where("title", "LIKE", "%{$search}%");
            })->where("status", "public")->count(),

            'private' => Post::when($search, function ($q) use ($search) {
                $q->where("title", "LIKE", "%{$search}%");
            })->where("status", "private")->count(),

            'pending' => Post::when($search, function ($q) use ($search) {
                $q->where("title", "LIKE", "%{$search}%");
            })->where("status", "pending")->count(),

            'draft' => Post::when($search, function ($q) use ($search) {
                $q->where("title", "LIKE", "%{$search}%");
            })->where("status", "draft")->count(),

            'trash' => Post::onlyTrashed()->when($search, function ($q) use ($search) {
                $q->where("title", "LIKE", "%{$search}%");
            })->count(),
        ];

        // Phân trang
        $posts = $query->orderBy("id", "DESC")->paginate($num_page);

        // Điều kiện khi $status là "trash"
        if ($status == "trash" && !$search) {
            $posts = Post::onlyTrashed()->orderBy("id", "DESC")->paginate($num_page);
        } else if ($status == "trash" && $search) {
            $posts = Post::onlyTrashed()
                ->where("title", "LIKE", "%{$search}%")
                ->orderBy("id", "DESC")->paginate($num_page);
        }

        return view("backend.post.index", [
            'posts' => $posts,
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
        return view("backend.post.create");

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
                "title" => 'required|string|max:255',
                "short_description" => 'required|string',
                "content" => 'required|string',
                "thumbnail" => 'required|image',
                'status' => "required|in:draft,public,pending,private",
            ],
            [
                'required' => 'Trường :attribute là bắt buộc.',
                'string' => 'Trường :attribute phải là một chuỗi ký tự.',
                'max' => 'Trường :attribute không được vượt quá :max ký tự.',
                'in' => 'Trường :attribute phải là một trạng thái (nháp, công khai, không công khai, chờ duyệt) hợp lệ.',
            ],
            [
                "title" => '<strong>tiêu đề</strong>',
                "short_description" => '<strong>mô tả ngắn</strong>',
                "content" => '<strong>nội dung</strong>',
                "thumbnail" => '<strong>hình ảnh</strong>',
                'status' => '<strong>trạng thái</strong>',
            ]
        );

        $thumbnail = $request->file('thumbnail');
        $thumbnailName = Str::slug($request->title) . '-' . uniqid() . '.' . $thumbnail->getClientOriginalExtension();

        // dd($thumbnail);
        Storage::disk('public')->putFileAs('images/posts', $thumbnail, $thumbnailName);
        $post = Post::create([
            "title" => $validatedData['title'],
            "slug" => Str::slug($validatedData['title']),
            "short_description" => $validatedData['short_description'],
            "content" => $validatedData['content'],
            "thumbnail" => $thumbnailName,
            "status" => $validatedData['status'],
            "employee_id" => Auth::guard('web')->user()->id,
        ]);
     
        if ($post) {
            return redirect()->back()->with(
                [
                    "code" => "success",
                    "status" => "Thêm mới bài viết thành công",
                ]
            );
        } else {
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Thêm mới bài viết thất bại",
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
    public function edit(string $postID)
    {
        //
        $post = Post::find($postID);
        if ($post) {
            return view("backend.post.edit", compact("post"));
        } else {
            return redirect()->route("post.index")->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tài chi nhánh có id = $postID",
                ]
            );
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $postID)
    {
        //
        $post = Post::find($postID);
        if (!$post) {
            return redirect()->route("post.index")->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tài chi nhánh có id = $postID",
                ]
            );
        }

        $validatedData = $request->validate(
            [
                "title" => 'required|string|max:255',
                "short_description" => 'required|string',
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
                "title" => '<strong>tiêu đề</strong>',
                "short_description" => '<strong>mô tả ngắn</strong>',
                "content" => '<strong>nội dung</strong>',
                'status' => '<strong>trạng thái</strong>',
            ]
        );

        $thumbnail = $request->file('thumbnail');

        $post->title = $validatedData['title'];
        $post->slug = Str::slug($validatedData['title']);
        $post->short_description = $validatedData['short_description'];
        $post->content = $validatedData['content'];
        if ($thumbnail) {
            Storage::disk('public')->delete('images/posts/' . $post->thumbnail);
            $thumbnailName = Str::slug($request->title) . '-' . uniqid() . '.' . $thumbnail->getClientOriginalExtension();

            // dd($thumbnail);
            Storage::disk('public')->putFileAs('images/posts', $thumbnail, $thumbnailName);
            $post->thumbnail = $thumbnailName;
        }
        $post->status = $validatedData['status'];


        if ($post->save()) {
            return redirect()->back()->with(
                [
                    "code" => "success",
                    "status" => "Cập nhật bài viết thành công",
                ]
            );
        } else {
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Cập nhật bài viết thất bại",
                ]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $postID)
    {
        //
        $post = Post::find($postID);

        if ($post) {
            $post->delete();
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



    public function restore(int $postID)
    {
        //

        $post = Post::withTrashed()->find($postID);

        if ($post) {
            $post->restore();
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



    public function forceDelete(int $postID)
    {
        //
        $post = Post::withTrashed()->find($postID);
        // $post = 1;

        if ($post) {
            $post->forceDelete();
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
                $post = Post::find($selected);
                if ($post) {
                    $post->delete();
                } else {
                    $error = 1;
                }
            }
        } elseif ($action == "restore") {
            foreach ($selecteds as $selected) {
                $post = Post::withTrashed()->find($selected);
                if ($post) {
                    $post->restore();
                } else {
                    $error = 1;
                }
            }

        } elseif ($action == "forceDelete") {
            foreach ($selecteds as $selected) {
                $post = post::withTrashed()->find($selected);
                if ($post) {
                    $post->forceDelete();
                } else {
                    $error = 1;
                }
            }

        } else {
            foreach ($selecteds as $selected) {
                $post = Post::find($selected);
                if ($post) {
                    $post->status = $action;
                    $post->save();
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
