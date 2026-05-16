<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StaffDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    /**
     * Fetch active staff for local matching in Flutter.
     */
    public function syncDown()
    {
        $staff = StaffDetail::where('status', 'Active')
            ->select('id', 'staff_code', 'full_name', 'face_embedding', 'designation')
            ->get();

        return response()->json($staff);
    }

    /**
     * Register new staff member.
     */
    public function register(Request $request)
    {
        $request->validate([
            'staff_code' => 'required|unique:staff_details,staff_code',
            'full_name' => 'required|string',
            'phone' => 'required|string',
            'designation' => 'required|string',
            'joining_date' => 'required|date',
            'face_embedding' => 'required|array',
            'basic_salary' => 'required|numeric',
            'shift_id' => 'required|exists:shifts,id',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'nullable|min:6',
        ]);

        return DB::transaction(function () use ($request) {
            $userId = null;

            if ($request->email && $request->password) {
                $user = User::create([
                    'name' => $request->full_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'Staff',
                ]);
                $userId = $user->id;
            }

            $staff = StaffDetail::create([
                'user_id' => $userId,
                'shift_id' => $request->shift_id,
                'staff_code' => $request->staff_code,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'designation' => $request->designation,
                'joining_date' => $request->joining_date,
                'face_embedding' => $request->face_embedding,
                'basic_salary' => $request->basic_salary,
                'monthly_allowances' => $request->monthly_allowances ?? 0,
                'status' => 'Active',
            ]);

            return response()->json([
                'message' => 'Staff registered successfully',
                'staff' => $staff
            ], 201);
        });
    }
}
