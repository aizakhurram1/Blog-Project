<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ImportUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function handle(): void
    {
        $fileContent = Storage::disk('public')->get($this->path);
        $rows = array_map('str_getcsv', explode("\n", trim($fileContent)));

        $header = array_map('trim', array_shift($rows));

        foreach ($rows as $row) {
            if (empty($row) || count($row) < 2) {
                continue;
            }

            $data = array_combine($header, array_map('trim', $row));

            if (! filter_var($data['email'] ?? null, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            User::updateOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'] ?? 'Unnamed']
            );
        }
    }
}
