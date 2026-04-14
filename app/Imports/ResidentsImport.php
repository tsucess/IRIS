<?php

namespace App\Imports;

use App\Models\Street;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ResidentsImport implements ToCollection, WithHeadingRow, SkipsEmptyRows, WithChunkReading
{
    public int $imported = 0;
    public int $skipped  = 0;
    public array $errors  = [];

    public function chunkSize(): int
    {
        return 100;
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            try {
                // Skip if email already exists
                if (User::where('email', $row['email'] ?? '')->exists()) {
                    $this->skipped++;
                    continue;
                }

                // Resolve street by name if provided
                $streetId = null;
                if (! empty($row['street'])) {
                    $street   = Street::firstOrCreate(['name' => $row['street']]);
                    $streetId = $street->id;
                }

                User::create([
                    'firstname' => $row['firstname'] ?? '',
                    'lastname'  => $row['lastname']  ?? '',
                    'email'     => $row['email']     ?? '',
                    'phone'     => $row['phone']     ?? null,
                    'street_id' => $streetId,
                    'role'      => 'user',
                    'id_number' => strtoupper('COMM-'.Str::random(6)),
                    'password'  => Hash::make(Str::random(12)),
                ]);

                $this->imported++;
            } catch (\Throwable $e) {
                $this->errors[] = "Row ".($index + 2).": ".$e->getMessage();
                Log::warning('Resident import row error', ['row' => $index + 2, 'error' => $e->getMessage()]);
            }
        }
    }
}
