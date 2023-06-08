<?php
namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;
use YogaMeleniawan\JobBatchingWithRealtimeProgress\Interfaces\RealtimeJobBatchInterface;

class VerificationRepository implements RealtimeJobBatchInterface {
    public function get_all(): Collection {
        return User::get();
    }

    public function save($data): void {
        User::where('id', $data->id)->update([
            'is_verification' => true,
        ]);
    }
}
