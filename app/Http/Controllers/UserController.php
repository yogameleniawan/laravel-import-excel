<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request) {
        if ($request->ajax()) {
            $data = User::latest();

            return DataTables::eloquent($data)
            ->addIndexColumn()
            ->toJson();
        }

        return view('users.index');
    }
}
