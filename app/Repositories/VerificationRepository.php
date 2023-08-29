<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use YogaMeleniawan\JobBatchingWithRealtimeProgress\Interfaces\RealtimeJobBatchInterface;

class VerificationRepository implements RealtimeJobBatchInterface
{
    public function get_all(): Collection
    {
        $sql = "SELECT * FROM users";

        return collect(DB::select($sql));
    }

    public function save($data): void
    {
        DB::beginTransaction();

        try {
            DB::table('users')
                ->where('id', $data->id)
                ->update([
                    'is_verification' => true,
                ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
