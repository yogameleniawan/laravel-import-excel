<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use App\Jobs\ImportFinishedJob;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::latest();

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->toJson();
        }

        return view('users.index');
    }

    public function import(Request $request)
    {
        $file = $request->excel;

        try {
            (new UsersImport())->import($file)->chain([
                new ImportFinishedJob()
            ]);

            return response()->json(['message' => 'Data sedang diimport', 'status' => 'success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'status' => 'error'], 500);
        }
    }
}
