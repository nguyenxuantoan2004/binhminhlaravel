<?php

namespace App\Http\Controllers\Admintractor;

use App\Http\Controllers\Controller;
use App\Models\CategoryMotorbike;
use App\Models\Motorbike;
use App\Models\MotorbikeImage;
use App\Models\Supplier;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Session;
use Storage;
use Str;

class AdminMotorbikeController extends Controller
{
    public function __construct()
    {
        Session::put("ModuleActive", "motorbike");
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $nameModule = "motorbike";
        $nameModuleVietnamese = "xe";
        $status = $request->input('status', 'all'); // Mặc định là "all"
        $search = $request->input("search");

        $num_page = 10;

        // Khởi tạo query builder
        $query = Motorbike::query();

        // Lọc theo trạng thái (nếu không phải "all")
        if ($status != 'all') {
            $query->where("status", "=", $status);
        }



        // Tìm kiếm theo tên
        if ($search) {
            // status = "private" and (name like "%$search%" or license_plate like "%$search%")
            $query->where(function (Builder $query) use ($search) {
                $query->whereLike('name', "%" . $search . "%");
            });
        }


        // Tính tổng số lượng các trạng thái với điều kiện hiện tại
        $counts = [
            'all' => Motorbike::when($search, function ($q) use ($search) {

                $q->where(function (Builder $query) use ($search) {
                    $query->whereLike('name', "%" . $search . "%");
                });
            })->count(),

            'public' => Motorbike::when($search, function ($q) use ($search) {
                $q->where(function (Builder $query) use ($search) {
                    $query->whereLike('name', "%" . $search . "%");
                });
            })->where("status", "public")->count(),

            'private' => Motorbike::when($search, function ($q) use ($search) {
                $q->where(function (Builder $query) use ($search) {
                    $query->whereLike('name', "%" . $search . "%");
                });
            })->where("status", "private")->count(),

            'pending' => Motorbike::when($search, function ($q) use ($search) {
                $q->where(function (Builder $query) use ($search) {
                    $query->whereLike('name', "%" . $search . "%");
                });
            })->where("status", "pending")->count(),

            'draft' => Motorbike::when($search, function ($q) use ($search) {
                $q->where(function (Builder $query) use ($search) {
                    $query->whereLike('name', "%" . $search . "%");
                });

            })->where("status", "draft")->count(),

            'maintenance' => Motorbike::when($search, function ($q) use ($search) {
                $q->where(function (Builder $query) use ($search) {
                    $query->whereLike('name', "%" . $search . "%");
                });

            })->where("status", "maintenance")->count(),

            'trash' => Motorbike::onlyTrashed()->when($search, function ($q) use ($search) {
                $q->where(function (Builder $query) use ($search) {
                    $query->whereLike('name', "%" . $search . "%");
                });
            })->count(),
        ];


        // Phân trang
        $motorbikes = $query->orderBy("id", "DESC")->paginate($num_page);

        // Điều kiện khi $status là "trash"
        if ($status == "trash" && !$search) {
            $motorbikes = Motorbike::onlyTrashed()->orderBy("id", "DESC")->paginate($num_page);
        } else if ($status == "trash" && $search) {
            $motorbikes = Motorbike::onlyTrashed()

                ->where(function (Builder $query) use ($search) {
                    $query->whereLike('name', "%" . $search . "%");
                })->orderBy("id", "DESC")->paginate($num_page);


        }
        // $sql = $query->toSql(); // Lấy câu lệnh SQL thô
        // dd($sql);
        return view("backend.motorbike.index", [
            'motorbikes' => $motorbikes,
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
        $categories = CategoryMotorbike::where("status", "public")->get();
        $suppliers = Supplier::where("status", "public")->get();

        return view("backend.motorbike.create", compact("categories", "suppliers"));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // "license_plate" => ['required', "regex:/^[0-9]{2}[A-Z]{1}-[0-9]{5}$/"],
        //         "frame_number" => ['required', "regex:/^[A-HJ-NPR-Z0-9]{17}$/"],
        $validatedData = $request->validate(
            [
                "name" => 'required|string|max:255',
                "quantity" => 'required|integer',
                "manufacture_year" => 'required',
                "color" => 'required|string|max:255',
                "vehicle_condition" => 'required|string|max:255',
                'status' => "required|in:draft,public,pending,private",
                "rental_price" => 'required|string',
                "images" => 'required',
                "category_motorbike_id" => 'required',
                "supplier_id" => 'required',

            ],
            [
                'required' => 'Trường :attribute là bắt buộc.',
                'string' => 'Trường :attribute phải là một chuỗi ký tự.',
                'max' => 'Trường :attribute không được vượt quá :max ký tự.',
                'regex' => 'Trường :attribute không đúng định dạng.',
                'email' => 'Trường :attribute phải là một địa chỉ email hợp lệ.',
                'in' => 'Trường :attribute phải là một trạng thái (nháp, công khai, không công khai, chờ duyệt) hợp lệ.',
                'integer' => 'Trường :attribute phải là một số.',
            ],
            [
                "name" => "<strong>tên xe</strong>",
                "quantity" => "<strong>số lượng</strong>",
                "manufacture_year" => "<strong>năm sản xuất</strong>",
                "color" => "<strong>màu sắc</strong>",
                "vehicle_condition" => "<strong>tình trạng xe</strong>",
                'status' => "<strong>trạng thái</strong>",
                "rental_price" => "<strong>giá thuê</strong>",
                "images" => "<strong>hình ảnh</strong>",
                "category_motorbike_id" => "<strong>loại xe</strong>",
                "supplier_id" => "<strong>nhà cung cấp</strong>",
            ]
        );

        $motorbike = Motorbike::create([
            "name" => $validatedData['name'],
            "slug" => Str::slug($validatedData['name']),
            "quantity" => $validatedData['quantity'],
            "manufacture_year" => $validatedData['manufacture_year'],
            "color" => $validatedData['color'],
            "vehicle_condition" => $validatedData['vehicle_condition'],
            "status" => $validatedData['status'],
            "rental_price" => $validatedData['rental_price'],
            "category_motorbike_id" => $validatedData['category_motorbike_id'],
            "supplier_id" => $validatedData['supplier_id'],
        ]);

        $motorbikeID = Motorbike::orderBy('id', 'desc')->first()->id;

        $inputPin = $request->input('pin', 0);

        $images = $request->file('images');

        $i = 0;

        // dd($images);

        foreach ($images as $image) {
            // Tạo tên tệp tùy chỉnh, ví dụ: timestamp + tên gốc
            $customName = time() . '_' . $image->getClientOriginalName();

            // Lưu tệp với tên tùy chỉnh vào thư mục
            Storage::disk('public')->putFileAs('images/motorbike', $image, $customName);
            // $image->storeAs('public/images', $customName);

            // Lưu tên tệp vào cơ sở dữ liệu
            $pin = ($i == $inputPin) ? 1 : 0;


            $motorbikeImage = MotorbikeImage::create([
                "file_name" => $customName,
                "motorbike_id" => $motorbikeID,
                "pin" => $pin,
            ]);
            $i++;
        }

        if ($motorbike) {
            return redirect()->back()->with(
                [
                    "code" => "success",
                    "status" => "Thêm mới xe thành công",
                ]
            );
        } else {
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Thêm mới xe thất bại",
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
    public function edit(string $motorbikeID)
    {
        //
        $categories = CategoryMotorbike::where("status", "public")->get();
        $suppliers = Supplier::where("status", "public")->get();
        // $images = MotorbikeImage::where("motorbike_id", $motorbikeID)->get();
        $images = Motorbike::find($motorbikeID)->MotorbikeImage()->get();
        $count_images = Motorbike::find($motorbikeID)->MotorbikeImage()->count();
        $motorbike = Motorbike::find($motorbikeID);
        // dd($images);
        if ($motorbike) {
            return view("backend.motorbike.edit", compact("motorbike", "categories", "suppliers", "images", "count_images"));
        } else {
            return redirect()->route("motorbike.index")->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tài chức vụ có id = $motorbikeID",
                ]
            );
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $motorbikeID)
    {
        //
        $motorbike = Motorbike::find($motorbikeID);
        if (!$motorbike) {
            return redirect()->route("motorbike.index")->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tài xe có id = $motorbikeID",
                ]
            );
        }

        $validatedData = $request->validate(
            [
                "name" => 'required|string|max:255',
                "quantity" => 'required|integer',
                "manufacture_year" => 'required',
                "color" => 'required|string|max:255',
                "vehicle_condition" => 'required|string|max:255',
                'status' => "required|in:draft,public,pending,private",
                "rental_price" => 'required|string',
                "category_motorbike_id" => 'required',
                "supplier_id" => 'required',

            ],
            [
                'required' => 'Trường :attribute là bắt buộc.',
                'string' => 'Trường :attribute phải là một chuỗi ký tự.',
                'max' => 'Trường :attribute không được vượt quá :max ký tự.',
                'regex' => 'Trường :attribute không đúng định dạng.',
                'email' => 'Trường :attribute phải là một địa chỉ email hợp lệ.',
                'in' => 'Trường :attribute phải là một trạng thái (nháp, công khai, không công khai, chờ duyệt) hợp lệ.',
                'integer' => 'Trường :attribute phải là một số.',
            ],
            [
                "name" => "<strong>tên xe</strong>",
                "license_plate" => "<strong>biển số xe</strong>",
                "frame_number" => "<strong>số khung</strong>",
                "manufacture_year" => "<strong>năm sản xuất</strong>",
                "color" => "<strong>màu sắc</strong>",
                "vehicle_condition" => "<strong>tình trạng xe</strong>",
                'status' => "<strong>trạng thái</strong>",
                "rental_price" => "<strong>giá thuê</strong>",
                "images" => "<strong>hình ảnh</strong>",
                "category_motorbike_id" => "<strong>loại xe</strong>",
                "supplier_id" => "<strong>nhà cung cấp</strong>",
            ]
        );

        //================
        // Kiểm tra hình ảnh tải lên và giữ lại
        //================
        // dd($request->all());

        $keep_images = $request->input('keep_images', []);
        $images = $request->file('images');

        if (empty($images) && empty($keep_images)) {
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Bạn phải giữ lại ít nhất một hình ảnh hoặc tải lên một hình ảnh mới",
                ]
            );
        }

        //================
        // Kiểm tra hình ảnh cũ, giữ và xóa hình ảnh cũ
        //================

        if (is_array($keep_images)) {
            // Lấy danh sách xe không nằm trong mảng $keep_images
            $images_old = $motorbike->MotorbikeImage()->whereNotIn('id', $keep_images)->get();
            // dd($images_old);
            foreach ($images_old as $image_old) {
                Storage::disk('public')->delete('images/motorbike/' . $image_old->file_name);
            }
            $motorbike->MotorbikeImage()->whereNotIn('id', $keep_images)->delete();
        }

        //================
        // Cập nhật thông tin xe
        //================


        $motorbike->update([
            "name" => $validatedData['name'],
            "quantity" => $validatedData['quantity'],
            "manufacture_year" => $validatedData['manufacture_year'],
            "color" => $validatedData['color'],
            "vehicle_condition" => $validatedData['vehicle_condition'],
            "status" => $validatedData['status'],
            "rental_price" => $validatedData['rental_price'],
            "category_motorbike_id" => $validatedData['category_motorbike_id'],
            "supplier_id" => $validatedData['supplier_id'],
        ]);


        //================
        // Kiểm tra xem có hình ảnh mới được tải lên không và lưu vào thư mục
        //================

        if (is_array($images) && !empty($images)) {
            foreach ($images as $image) {
                // Tạo tên tệp tùy chỉnh, ví dụ: timestamp + tên gốc
                $customName = time() . '_' . $image->getClientOriginalName();

                // Lưu tệp với tên tùy chỉnh vào thư mục
                Storage::disk('public')->putFileAs('images/motorbike', $image, $customName);
                // $image->storeAs('public/images', $customName);

                // Lưu tên tệp vào cơ sở dữ liệu
                MotorbikeImage::create([
                    "file_name" => $customName,
                    "motorbike_id" => $motorbikeID,
                    "pin" => 0,
                ]);
            }
        }

        //==============
        //Cập nhật PIN
        //==============
        $inputPin = $request->input('pin', 0);
        // $pin_images_new là danh sách tất cả hình ảnh của xe sau khi cập nhật
        $pin_images_new = $motorbike->MotorbikeImage()->get();
        $i = 0;
        $check_pin = false;
        foreach ($pin_images_new as $pin_image_new) {
            // Nếu $i == $inputPin thì pin = 1, ngược lại pin = 0
            $pin = ($i == $inputPin) ? 1 : 0;
            $pin_image_new->update([
                "pin" => $pin,
            ]);
            $check_pin = true;
            $pin_image_new->save();
            $i++;
        }

        if (!$check_pin) {
            $pin_images_new = $motorbike->MotorbikeImage()->first();
            $pin_images_new->update([
                "pin" => 1,
            ]);
            $pin_images_new->save();
        }



        if ($motorbike) {
            return redirect()->back()->with(
                [
                    "code" => "success",
                    "status" => "Cập nhật xe thành công",
                ]
            );
        } else {
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Cập nhật xe thất bại",
                ]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $motorbikeID)
    {
        //
        $motorbike = Motorbike::find($motorbikeID);

        if ($motorbike) {
            $motorbike->delete();
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



    public function restore(int $motorbikeID)
    {
        //

        $motorbike = Motorbike::withTrashed()->find($motorbikeID);

        if ($motorbike) {
            $motorbike->restore();
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



    public function forceDelete(int $motorbikeID)
    {
        //
        $motorbike = Motorbike::withTrashed()->find($motorbikeID);
        // $motorbike = 1;

        if ($motorbike) {
            $motorbike->forceDelete();
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
                $motorbike = Motorbike::find($selected);
                if ($motorbike) {
                    $motorbike->delete();
                } else {
                    $error = 1;
                }
            }
        } elseif ($action == "restore") {
            foreach ($selecteds as $selected) {
                $motorbike = Motorbike::withTrashed()->find($selected);
                if ($motorbike) {
                    $motorbike->restore();
                } else {
                    $error = 1;
                }
            }

        } elseif ($action == "forceDelete") {
            foreach ($selecteds as $selected) {
                $motorbike = Motorbike::withTrashed()->find($selected);
                if ($motorbike) {
                    $motorbike->forceDelete();
                } else {
                    $error = 1;
                }
            }

        } else {
            foreach ($selecteds as $selected) {
                $motorbike = Motorbike::find($selected);
                if ($motorbike) {
                    $motorbike->status = $action;
                    $motorbike->save();
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
