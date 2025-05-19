<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportExcelRequest;
use App\Http\Resources\RowResource;
use App\Models\Row;
use App\Services\ExcelService;
use Illuminate\Http\JsonResponse;

class ImportController extends Controller
{
    public function __construct(
        private readonly ExcelService $excelService,
    )
    {
    }

    public function upload(ImportExcelRequest $request): JsonResponse
    {
        $file = $request->file('file');
        $import = $this->excelService->handleUpload($file);
        $this->excelService->processImport($import);

        return response()->json([
            'message' => 'Файл успешно загружен',
            'import_id' => $import->id,
            'file_name' => $import->file_name,
        ]);
    }

    public function export(): JsonResponse
    {
        $rows = Row::orderBy('date')->get();

        $grouped = $rows
            ->groupBy(fn($row) => $row->date)
            ->map(fn($items, $date) => [
                'date' => $date,
                'items' => RowResource::collection($items)->resolve(),
            ])
            ->values();

        return response()->json($grouped);
    }
}
