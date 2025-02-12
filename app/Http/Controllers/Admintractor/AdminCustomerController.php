<?php

namespace App\Http\Controllers\Admintractor;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Rules\UniqueInTwoTables;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Session;

class AdminCustomerController extends Controller
{
    function __construct()
    {
        Session::put("ModuleActive", "customer");
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $nameModule = "customer";
        $nameModuleVietnamese = "khách hàng";

        $status = $request->input('status', 'all'); // Mặc định là "all"
        $search = $request->input("search");

        $num_page = 10;

        // Khởi tạo query builder
        $query = Customer::query();

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
            'all' => Customer::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->count(),

            'active' => Customer::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "active")->count(),

            'temporary_lock' => Customer::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "temporary_lock")->count(),

            'permanently_locked' => Customer::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "permanently_locked")->count(),



            'trash' => Customer::onlyTrashed()->when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->count(),
        ];

        // Phân trang
        $customers = $query->orderBy("id", "DESC")->paginate($num_page);

        // Điều kiện khi $status là "trash"
        if ($status == "trash" && !$search) {
            $customers = Customer::onlyTrashed()->orderBy("id", "DESC")->paginate($num_page);
        } else if ($status == "trash" && $search) {
            $customers = Customer::onlyTrashed()
                ->where("name", "LIKE", "%{$search}%")
                ->orderBy("id", "DESC")->paginate($num_page);

        }

        return view("backend.customer.index", [
            'customers' => $customers,
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
        return view("backend.customer.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // dd($request->input());

        $validatedData = $request->validate(
            [
                "name" => 'required|string|max:255',
                'email' =>  ['required','email', new UniqueInTwoTables()],
                "id_card_number" => 'required|digits:12',
                'driving_license_number' => "nullable|integer",
                "address" => 'required|string',
                'phone_number' => ['required', 'regex:/^(032|033|034|035|036|037|038|039|096|097|098|086|083|084|085|081|082|088|091|094|070|079|077|076|078|090|093|089|056|058|092|059|099)[0-9]{7}$/'],
                'password' => "required|max:200|min:8",
                'status' => "required|in:active,temporary_lock,permanently_locked",
            ],
            [
                'required' => 'Trường :attribute là bắt buộc.',
                'string' => 'Trường :attribute phải là một chuỗi ký tự.',
                'max' => 'Trường :attribute không được vượt quá :max ký tự.',
                'min' => 'Trường :attribute không được bé hơn :min ký tự.',
                'regex' => 'Trường :attribute không đúng định dạng.',
                'email' => 'Trường :attribute phải là một địa chỉ email hợp lệ.',
                'in' => 'Trường :attribute phải là một trạng thái (nháp, công khai, không công khai, chờ duyệt) hợp lệ.',
                'unique' => 'Trường :attribute đã tồn tại trong hệ thống.',
                'digits' => 'Trường :attribute phải có :digits chữ số.'
            ],
            [
                'name' => '<strong>họ và tên</strong>',
                'email' => '<strong>địa chỉ email</strong>',
                'id_card_number' => '<strong>căn cước công dân</strong>',
                'driving_license_number' => '<strong>số bằng lái xe</strong>',
                'address' => '<strong>địa chỉ</strong>',
                'phone_number' => '<strong>số điện thoại</strong>',
                'password' => "<strong>mật khẩu</strong>",
                'status' => '<strong>trạng thái</strong>',
            ]
        );

        $customer = Customer::create([
            "name" => $validatedData['name'],
            "email" => $validatedData['email'],
            "id_card_number" => $validatedData['id_card_number'],
            "driving_license_number" => $validatedData['driving_license_number'],
            "address" => $validatedData['address'],
            "phone_number" => $validatedData['phone_number'],
            "password" => bcrypt($validatedData['password']),
            "status" => $validatedData['status'],
        ]);

        if ($customer) {
            return redirect()->back()->with(
                [
                    "code" => "success",
                    "status" => "Thêm mới khách hàng thành công",
                ]
            );
        } else {
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Thêm mới khách hàng thất bại",
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
    public function edit(string $customerID)
    {
        //
        $customer = Customer::find($customerID);
        if ($customer) {
            return view("backend.customer.edit", compact("customer"));
        } else {
            return redirect()->route("customer.index")->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tài khách hàng có id = $customerID",
                ]
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $customerID)
    {
        //
        $customer = Customer::find($customerID);

        // dd($customer->id, $customerID);
        if (!$customer) {
            return redirect()->route("customer.index")->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tài nhà cung cấp có id = $customerID",
                ]
            );
        }

        $validatedData = $request->validate(
            [
                "name" => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('customers')->ignore($customer->id),
                ],
                "id_card_number" => 'required|digits:12',
                'driving_license_number' => "nullable|integer",
                "address" => 'required|string',
                'phone_number' => ['required', 'regex:/^(032|033|034|035|036|037|038|039|096|097|098|086|083|084|085|081|082|088|091|094|070|079|077|076|078|090|093|089|056|058|092|059|099)[0-9]{7}$/'],
                'password' => "required|max:200|min:8",
                'status' => "required|in:active,temporary_lock,permanently_locked",
            ],
            [
                'required' => 'Trường :attribute là bắt buộc.',
                'string' => 'Trường :attribute phải là một chuỗi ký tự.',
                'max' => 'Trường :attribute không được vượt quá :max ký tự.',
                'min' => 'Trường :attribute không được bé hơn :min ký tự.',
                'regex' => 'Trường :attribute không đúng định dạng.',
                'email' => 'Trường :attribute phải là một địa chỉ email hợp lệ.',
                'in' => 'Trường :attribute phải là một trạng thái (nháp, công khai, không công khai, chờ duyệt) hợp lệ.',
                'unique' => 'Trường :attribute đã tồn tại trong hệ thống.',
                'digits' => 'Trường :attribute phải có :digits chữ số.'
            ],
            [
                'name' => '<strong>họ và tên</strong>',
                'email' => '<strong>địa chỉ email</strong>',
                'id_card_number' => '<strong>căn cước công dân</strong>',
                'driving_license_number' => '<strong>số bằng lái xe</strong>',
                'address' => '<strong>địa chỉ</strong>',
                'phone_number' => '<strong>số điện thoại</strong>',
                'password' => "<strong>mật khẩu</strong>",
                'status' => '<strong>trạng thái</strong>',
            ]
        );

        $customer->name = $validatedData['name'];
        $customer->email = $validatedData['email'];
        $customer->id_card_number = $validatedData['id_card_number'];
        $customer->driving_license_number = $validatedData['driving_license_number'];
        $customer->address = $validatedData['address'];
        $customer->phone_number = $validatedData['phone_number'];
        $customer->password = Hash::make($validatedData['password']);
        $customer->status = $validatedData['status'];

        if ($customer->save()) {
            return redirect()->back()->with(
                [
                    "code" => "success",
                    "status" => "Cập nhật khách hàng thành công",
                ]
            );
        } else {
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Cập nhật khách hàng thất bại",
                ]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $customerID)
    {
        //
        $customer = Customer::find($customerID);

        if ($customer) {
            $customer->delete();
            return response()->json(
                [
                    "code" => "success",
                    "status" => "Xóa khách hàng thành công",
                ]
            );

        } else {
            return response()->json(
                [
                    "code" => 'error',
                    "status" => "Xóa khách hàng thất bại",
                ]
            );
        }
    }

    public function restore(int $customerID)
    {
        //

        $customer = Customer::withTrashed()->find($customerID);

        if ($customer) {
            $customer->restore();
            return response()->json(
                [
                    "code" => "success",
                    "status" => "Khôi phục khách hàng thành công",
                ]
            );

        } else {
            return response()->json(
                [
                    "code" => 'error',
                    "status" => "Khôi phục khách hàng thất bại",
                ]
            );
        }
    }



    public function forceDelete(int $customerID)
    {
        //
        $customer = Customer::withTrashed()->find($customerID);
        // $Customer = 1;

        if ($customer) {
            $customer->forceDelete();
            return response()->json(
                [
                    "code" => "success",
                    "status" => "Xóa khách hàng vĩnh viễn thành công",
                ]
            );

        } else {
            return response()->json(
                [
                    "code" => 'error',
                    "status" => "Xóa khách hàng vĩnh viễn thất bại",
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
                $customer = Customer::find($selected);
                if ($customer) {
                    $customer->delete();
                } else {
                    $error = 1;
                }
            }
        } elseif ($action == "restore") {
            foreach ($selecteds as $selected) {
                $customer = Customer::withTrashed()->find($selected);
                if ($customer) {
                    $customer->restore();
                } else {
                    $error = 1;
                }
            }

        } elseif ($action == "forceDelete") {
            foreach ($selecteds as $selected) {
                $customer = Customer::withTrashed()->find($selected);
                if ($customer) {
                    $customer->forceDelete();
                } else {
                    $error = 1;
                }
            }

        }else{
            foreach ($selecteds as $selected) {
                $customer = Customer::find($selected);
                if ($customer) {
                    $customer->status = $action;
                    $customer->save();
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
