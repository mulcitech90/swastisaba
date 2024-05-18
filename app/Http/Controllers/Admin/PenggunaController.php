<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use DB;

class PenggunaController extends Controller
{
    public function index()
    {
        $dinas = DB::table('dinas')->get();
        $pemda = DB::table('kab_kota')->get();
        $users = User::all();
        return view('admin.master-user.index', compact('users', 'dinas', 'pemda'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        if ($request->role == 'admin') {
            $insert =[
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Default password, ensure to change it later
                'role' => $request->role,
            ];
        }elseif ($request->role == 'pemda') {
            $insert =[
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Default password, ensure to change it later
                'role' => $request->role,
                'id_kab_kota' => $request->pemda,
            ];
        }elseif ($request->role == 'dinas') {
            $insert =[
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Default password, ensure to change it later
                'role' => $request->role,
                'id_kab_kota' => $request->pemda,
                'id_dinas' => $request->dinas,
            ];
        }

        $user = User::create($insert);

        return response()->json(['success' => 'User created successfully.', 'user' => $user], 200);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['user' => $user], 200);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {

        // Attempt to find the user by ID using query builder
        $user = DB::table('users')->where('id', $id)->first();

        // If the user doesn't exist, return a 404 error
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|max:50',
        ]);

        // If validation fails, return the errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Prepare the data for update based on the role
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->role == 'pemda') {
            $data['id_kab_kota'] = $request->pemda;
        } elseif ($request->role == 'dinas') {
            $data['id_dinas'] = $request->dinas;
        }

        if (!empty($request->password)) {
            $data['password'] = $request->password;
        }


        // Update the user with the validated data using query builder
        DB::table('users')
            ->where('id', $id)
            ->update($data);

        // Retrieve the updated user
        $updatedUser = DB::table('users')->where('id', $id)->first();


        return response()->json(['success' => 'User updated successfully.', 'user' => $user], 200);
    }

    /**
     * Remove the specified user from storage.
     */
    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => 'User deleted successfully.'], 200);
    }
}
