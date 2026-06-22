<?php
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$file = 'D:\\2026\\10. Sensus Ekonomi\\20260608 Rekap Prelist_21.xlsx';
if (!file_exists($file)) {
    echo "Excel file not found at {$file}\n";
    exit(1);
}

$spreadsheet = IOFactory::load($file);
$sheetNames = $spreadsheet->getSheetNames();
echo "Sheets:\n" . implode("\n", $sheetNames) . "\n\n";

$sheet = $spreadsheet->getActiveSheet();
echo "Active sheet: " . $sheet->getTitle() . "\n";

$rows = [];
$highestRow = min(15, $sheet->getHighestRow());
$highestColumn = $sheet->getHighestColumn();
$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

for ($row = 1; $row <= $highestRow; $row++) {
    $rowVal = [];
    for ($col = 1; $col <= $highestColumnIndex; $col++) {
        $rowVal[] = $sheet->getCellByColumnAndRow($col, $row)->getValue();
    }
    $rows[] = $rowVal;
}

echo "First few columns/rows:\n";
foreach ($rows as $rIdx => $row) {
    echo "Row " . ($rIdx + 1) . ": ";
    $truncated = array_slice($row, 0, 45); // Show first 45 columns
    foreach ($truncated as $cIdx => $val) {
        $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cIdx + 1);
        if ($val !== null && $val !== '') {
            echo "{$colLetter}:[{$val}] ";
        }
    }
    echo "\n";
}
