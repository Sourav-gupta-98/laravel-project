<?php

namespace App\Imports;

use App\Models\products;
use App\Services\UtilityService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ProductsImport implements OnEachRow, WithHeadingRow, WithChunkReading, ShouldQueue
{
    protected $adminId;

    public function __construct($adminId)
    {
        $this->adminId = $adminId;
    }

    public function onRow(Row $row)
    {
        $data = $row->toArray();

        try {
            products::Create([
                'name' => $data['name'],
                'price' => $data['price'] ?? 0,
                'stock' => $data['stock'] ?? 0,
                'description' => $data['description'] ?? 'NO DESCRIPTION',
                'category' => $data['category'] ?? 0,
                'added_by' => $this->adminId,
                'unique_id' => UtilityService::generateUniqueCode()
            ]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }

    }

    public function chunkSize(): int
    {
        return 500;
    }
}
