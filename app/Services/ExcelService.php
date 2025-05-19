<?php
namespace App\Services;

use App\Models\Import;
use App\Models\Row;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Carbon;
use App\Events\RowCreated;

class ExcelService
{
    public function handleUpload(UploadedFile $file): Import
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $uniqueName = Str::uuid() . '.' . $extension;

        $file->storeAs(Import::IMPORT_PATH, $uniqueName);

        return Import::create([
            'original_file_name' => $originalName,
            'file_name' => $uniqueName,
            'status' => Import::IMPORT_STATUS_PENDING,
        ]);
    }

    public function processImport(Import $import): void
    {
        $import->update([
            'status' => Import::IMPORT_STATUS_IN_PROGRESS,
            'started_at' => now(),
        ]);

        $rows = $this->loadFile($import->file_name);
        $processed = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            if ($index === 0 && ($row[2] ?? '') === 'date') continue;

            if ($this->validateRow($row, $index + 1, $errors)) {
                $this->processRow($import->id, $row, $index + 1);
                Redis::set("import:{$import->id}:progress", ++$processed);
            }
        }

        if (!empty($errors)) {
            Storage::put(Import::IMPORT_RESULT_PATH . "/result_{$import->id}.txt", implode(PHP_EOL, $errors));
        }

        $import->update([
            'total_rows' => $processed,
            'status' => Import::IMPORT_STATUS_COMPLETED,
            'finished_at' => now(),
        ]);
    }

    protected function loadFile(string $fileName): array
    {
        $filePath = Storage::path(Import::IMPORT_PATH . '/' . $fileName);
        $spreadsheet = IOFactory::load($filePath);

        return $spreadsheet->getActiveSheet()->toArray();
    }

    public function validateRow(array $row, int $lineNumber, array &$errors): bool
    {
        $date = $row[2] ?? null;

        if (empty($date)) {
            $errors[] = "{$lineNumber} - Дата пуста";
            return false;
        }

        try {
            Carbon::createFromFormat('d.m.Y', $date);
            return true;
        } catch (\Exception) {
            $errors[] = "{$lineNumber} - Неверный формат даты: {$date}";
            return false;
        }
    }

    public function processRow(int $importId, array $row, int $lineNumber): void
    {
        $createdRow = Row::create([
            'import_id' => $importId,
            'excel_id' => $row[0],
            'name' => $row[1],
            'date' => Carbon::createFromFormat('d.m.Y', $row[2]),
            'line_number' => $lineNumber,
        ]);

        broadcast(new RowCreated($createdRow));
    }
}
