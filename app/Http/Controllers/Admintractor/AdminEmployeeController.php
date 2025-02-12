<?php

namespace App\Http\Controllers\Admintractor;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\Position;
use App\Rules\UniqueInTwoTables;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Session;

class AdminEmployeeController extends Controller
{
    function __construct()
    {
        Session::put("ModuleActive", "employee");
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $nameModule = "employee";
        $nameModuleVietnamese = "nhân viên";

        $status = $request->input('status', 'all'); // Mặc định là "all"
        $search = $request->input("search");

        $num_page = 10;

        // Khởi tạo query builder
        $query = Employee::query();

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
            'all' => Employee::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->count(),

            'active' => Employee::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "active")->count(),

            'temporary_lock' => Employee::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "temporary_lock")->count(),

            'permanently_locked' => Employee::when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->where("status", "permanently_locked")->count(),



            'trash' => Employee::onlyTrashed()->when($search, function ($q) use ($search) {
                $q->where("name", "LIKE", "%{$search}%");
            })->count(),
        ];

        // Phân trang
        $employees = $query->orderBy("id", "DESC")->paginate($num_page);

        // Điều kiện khi $status là "trash"
        if ($status == "trash" && !$search) {
            $employees = Employee::onlyTrashed()->orderBy("id", "DESC")->paginate($num_page);
        } else if ($status == "trash" && $search) {
            $employees = Employee::onlyTrashed()
                ->where("name", "LIKE", "%{$search}%")
                ->orderBy("id", "DESC")->paginate($num_page);

        }

        return view("backend.employee.index", [
            'employees' => $employees,
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
        $positions = Position::where("status", "public")->get();
        return view("backend.employee.create", compact("branches", "positions"));
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
                'salary' => "required|integer",
                "address" => 'required|string',
                'phone_number' => ['required', 'regex:/^(032|033|034|035|036|037|038|039|096|097|098|086|083|084|085|081|082|088|091|094|070|079|077|076|078|090|093|089|056|058|092|059|099)[0-9]{7}$/'],
                'password' => "required|max:200|min:8",
                'status' => "required|in:active,temporary_lock,permanently_locked",
                'branch_id' => "required",
                'position_id' => "required",
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
                'salary' => '<strong>lương</strong>',
                'address' => '<strong>địa chỉ</strong>',
                'phone_number' => '<strong>số điện thoại</strong>',
                'password' => "<strong>mật khẩu</strong>",
                'status' => '<strong>trạng thái</strong>',
                'branch_id' => '<strong>chi nhánh</strong>',
                'position_id' => '<strong>chức vụ</strong>',
            ]
        );

        $employee = Employee::create([
            "name" => $validatedData['name'],
            "email" => $validatedData['email'],
            "phone_number" => $validatedData['phone_number'],
            "id_card_number" => $validatedData['id_card_number'],
            "salary" => $validatedData['salary'],
            "address" => $validatedData['address'],
            "password" => bcrypt($validatedData['password']),
            "status" => $validatedData['status'],
            "branch_id" => $validatedData['branch_id'],
            "position_id" => $validatedData['position_id'],
        ]);

        if ($employee) {
            return redirect()->back()->with(
                [
                    "code" => "success",
                    "status" => "Thêm mới nhân viên thành công",
                ]
            );
        } else {
            return redirect()->back()->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Thêm mới nhân viên thất bại",
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
    public function edit(string $employeeID)
    {
        //
       
        $employee = Employee::find($employeeID);
        if ($employee) {
            $branches = Branch::where("status", "public")->get();
            $positions = Position::where("status", "public")->get();
            return view("backend.employee.edit", compact("employee", "branches", "positions"));
        } else {
            return redirect()->route("employee.index")->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tài khách hàng có id = $employeeID",
                ]
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $employeeID)
    {
        //
        $employee = Employee::find($employeeID);

        // dd($employee->id, $employeeID);
        if (!$employee) {
            return redirect()->route("employee.index")->withInput()->with(
                [
                    "code" => "error",
                    "status" => "Không tồn tài nhà cung cấp có id = $employeeID",
                ]
            );
        }

        $validatedData = $request->validate(
            [
                "name" => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('employees')->ignore($employee->id),
                ],
                "id_card_number" => 'required|digits:12',
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
                'address' => '<strong>địa chỉ</strong>',
                'phone_number' => '<strong>số điện thoại</strong>',
                'password' => "<strong>mật khẩu</strong>",
                'status' => '<strong>trạng thái</strong>',
            ]
        );

        $employee->name = $validatedData['name'];
        $employee->email = $validatedData['email'];
        $employee->id_card_number = $validatedData['id_card_number'];
        $employee->address = $validatedData['address'];
        $employee->phone_number = $validatedData['phone_number'];
        $employee->password = Hash::make($validatedData['password']);
        $employee->status = $validatedData['status'];

        if ($employee->save()) {
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
    public function destroy(string $employeeID)
    {
        //
        $employee = Employee::find($employeeID);

        if ($employee) {
            $employee->delete();
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

    public function restore(int $employeeID)
    {
        //

        $employee = Employee::withTrashed()->find($employeeID);

        if ($employee) {
            $employee->restore();
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



    public function forceDelete(int $employeeID)
    {
        //
        $employee = Employee::withTrashed()->find($employeeID);
        // $employee = 1;

        if ($employee) {
            $employee->forceDelete();
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
                $employee = Employee::find($selected);
                if ($employee) {
                    $employee->delete();
                } else {
                    $error = 1;
                }
            }
        } elseif ($action == "restore") {
            foreach ($selecteds as $selected) {
                $employee = Employee::withTrashed()->find($selected);
                if ($employee) {
                    $employee->restore();
                } else {
                    $error = 1;
                }
            }

        } elseif ($action == "forceDelete") {
            foreach ($selecteds as $selected) {
                $employee = Employee::withTrashed()->find($selected);
                if ($employee) {
                    $employee->forceDelete();
                } else {
                    $error = 1;
                }
            }

        }else{
            foreach ($selecteds as $selected) {
                $employee = Employee::find($selected);
                if ($employee) {
                    $employee->status = $action;
                    $employee->save();
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
