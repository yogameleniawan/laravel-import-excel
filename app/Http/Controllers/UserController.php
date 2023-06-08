<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use YogaMeleniawan\JobBatchingWithRealtimeProgress\RealtimeJobBatch;

class UserController extends Controller
{
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(
        private RealtimeJobBatch $realtimeJob,

    )
    {
        //
    }

    public function index(Request $request) {
        if ($request->ajax()) {
            $data = User::latest();

            return DataTables::eloquent($data)
            ->addIndexColumn()
            ->toJson();
        }

        return view('users.index');
    }

    public function verification() {
        $batch = RealtimeJobBatch::execute(
            name: 'User Verification'
        );

        return response()->json(['message' => 'User verification is running in background', 'batch' => $batch], 200);
    }
}
