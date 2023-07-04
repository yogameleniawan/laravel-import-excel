<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UnverificationRepository;
use App\Repositories\VerificationRepository;
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

        $batch = RealtimeJobBatch::setRepository(new VerificationRepository())
                    ->execute(name: 'User Verification');

        return response()
            ->json([
                'message' => 'User verification is running in background',
                'batch' => $batch
            ], 200);
    }
}
