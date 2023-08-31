<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\BeforeImport;
use App\Events\StatusJobEvent;

class UsersImport implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue, WithEvents
{
    use Importable;

    public $total_rows = 0;
    public $progress = 0;
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $value) {
            $this->progress += 1;

            $pending = $this->total_rows - $this->progress;

            $percent = ($this->progress / $this->total_rows) * 100;

            User::create([
                'name' => $value['name'],
                'email' => $value['email'],
                'password' => $value['password'],
            ]);

            event(new StatusJobEvent(
                finished: false,
                progress: (int) $percent,
                pending: $pending,
                total: $this->total_rows,
                data: $value
            ));
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                $this->total_rows = (int)$event->getReader()->getTotalRows()["Sheet1"];
            },
        ];
    }
}
