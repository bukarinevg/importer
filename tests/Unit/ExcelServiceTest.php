<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ExcelService;

class ExcelServiceTest extends TestCase
{
    public function test_validate_row_with_valid_date()
    {
        $service = new ExcelService();
        $errors = [];
        $row = [1, 'Иванов', '01.01.2024'];

        $result = $service->validateRow($row, 2, $errors);

        $this->assertTrue($result);
        $this->assertEmpty($errors);
    }

    public function test_validate_row_with_invalid_date()
    {
        $service = new ExcelService();
        $errors = [];
        $row = [2, 'Петров', 'invalid_date'];

        $result = $service->validateRow($row, 3, $errors);

        $this->assertFalse($result);
        $this->assertCount(1, $errors);
        $this->assertStringContainsString('Неверный формат даты', $errors[0]);
    }
}
