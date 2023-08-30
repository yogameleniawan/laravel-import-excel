<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use App\Jobs\ImportFinishedJob;
use App\Jobs\UnverificationUserJob;
use App\Models\User;
use App\Repositories\UnverificationRepository;
use App\Repositories\VerificationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use YogaMeleniawan\JobBatchingWithRealtimeProgress\Events\StatusJobEvent;
use YogaMeleniawan\JobBatchingWithRealtimeProgress\RealtimeJobBatch;

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

    public function verification(Request $request)
    {
        if ($request->type == 'job') {
            $batch = RealtimeJobBatch::setRepository(new VerificationRepository())
                ->execute(name: 'User Verification');

            return response()
                ->json([
                    'message' => 'Proses Verifikasi User sedang berjalan',
                    'batch' => $batch
                ], 200);
        } else {
            UnverificationUserJob::dispatch();

            return response()
                ->json([
                    'message' => 'Proses Unverifikasi User sedang berjalan',
                ], 200);
        }
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
