<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();
$header = array('size' => 16, 'bold' => true);

// 1. Basic table

$rows = 10;
$cols = 5;
$section->addText('Basic table', $header);

$table = $section->addTable();
for ($r = 1; $r <= 8; $r++) {
    $table->addRow();
    for ($c = 1; $c <= 5; $c++) {
        $table->addCell(1750)->addText("Row {$r}, Cell {$c}");
    }
}

// 2. Advanced table

$section->addTextBreak(1);
$section->addText('Fancy table', $header);

$fancyTableStyleName = 'Fancy Table';
$fancyTableStyle = array('borderSize' => 6, 'borderColor' => '006699', 'cellMargin' => 80, 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER);
$fancyTableFirstRowStyle = array('borderBottomSize' => 18, 'borderBottomColor' => '0000FF', 'bgColor' => '66BBFF');
$fancyTableCellStyle = array('valign' => 'center');
$fancyTableCellBtlrStyle = array('valign' => 'center', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR);
$fancyTableFontStyle = array('bold' => true);
$phpWord->addTableStyle($fancyTableStyleName, $fancyTableStyle, $fancyTableFirstRowStyle);
$table = $section->addTable($fancyTableStyleName);
$table->addRow(900);
$table->addCell(2000, $fancyTableCellStyle)->addText('Row 1', $fancyTableFontStyle);
$table->addCell(2000, $fancyTableCellStyle)->addText('Row 2', $fancyTableFontStyle);
$table->addCell(2000, $fancyTableCellStyle)->addText('Row 3', $fancyTableFontStyle);
$table->addCell(2000, $fancyTableCellStyle)->addText('Row 4', $fancyTableFontStyle);
$table->addCell(500, $fancyTableCellBtlrStyle)->addText('Row 5', $fancyTableFontStyle);
for ($i = 1; $i <= 8; $i++) {
    $table->addRow();
    $table->addCell(2000)->addText("Cell {$i}");
    $table->addCell(2000)->addText("Cell {$i}");
    $table->addCell(2000)->addText("Cell {$i}");
    $table->addCell(2000)->addText("Cell {$i}");
    $text = (0== $i % 2) ? 'X' : '';
    $table->addCell(500)->addText($text);
}

/**
 *  3. colspan (gridSpan) and rowspan (vMerge)
 *  ---------------------
 *  |     |   B    |    |
 *  |  A  |--------|  E |
 *  |     | C |  D |    |
 *  ---------------------
 */

$section->addPageBreak();
$section->addText('Table with colspan and rowspan', $header);

$fancyTableStyle = array('borderSize' => 6, 'borderColor' => '999999');
$cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => 'FFFF00');
$cellRowContinue = array('vMerge' => 'continue');
$cellColSpan = array('gridSpan' => 2, 'valign' => 'center');
$cellHCentered = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER);
$cellVCentered = array('valign' => 'center');

$spanTableStyleName = 'Colspan Rowspan';
$phpWord->addTableStyle($spanTableStyleName, $fancyTableStyle);
$table = $section->addTable($spanTableStyleName);

$table->addRow();

$cell1 = $table->addCell(2000, $cellRowSpan);
$textrun1 = $cell1->addTextRun($cellHCentered);
$textrun1->addText('A');
$textrun1->addFootnote()->addText('Row span');

$cell2 = $table->addCell(4000, $cellColSpan);
$textrun2 = $cell2->addTextRun($cellHCentered);
$textrun2->addText('B');
$textrun2->addFootnote()->addText('Column span');

$table->addCell(2000, $cellRowSpan)->addText('E', null, $cellHCentered);

$table->addRow();
$table->addCell(null, $cellRowContinue);
$table->addCell(2000, $cellVCentered)->addText('C', null, $cellHCentered);
$table->addCell(2000, $cellVCentered)->addText('D', null, $cellHCentered);
$table->addCell(null, $cellRowContinue);

/**
 *  4. colspan (gridSpan) and rowspan (vMerge)
 *  ---------------------
 *  |     |   B    |  1 |
 *  |  A  |        |----|
 *  |     |        |  2 |
 *  |     |---|----|----|
 *  |     | C |  D |  3 |
 *  ---------------------
 * @see https://github.com/PHPOffice/PHPWord/issues/806
 */
$section->addPageBreak();
$section->addText('Table with colspan and rowspan', $header);

$styleTable = ['borderSize' => 6, 'borderColor' => '999999'];
$phpWord->addTableStyle('Colspan Rowspan', $styleTable);
$table = $section->addTable('Colspan Rowspan');

$row = $table->addRow();

$row->addCell(null, ['vMerge' => 'restart'])->addText('A');
$row->addCell(null, ['gridSpan' => 2, 'vMerge' => 'restart',])->addText('B');
$row->addCell()->addText('1');

$row = $table->addRow();
$row->addCell(null, ['vMerge' => 'continue']);
$row->addCell(null, ['vMerge' => 'continue','gridSpan' => 2,]);
$row->addCell()->addText('2');
$row = $table->addRow();
$row->addCell(null, ['vMerge' => 'continue']);
$row->addCell()->addText('C');
$row->addCell()->addText('D');
$row->addCell()->addText('3');

// 5. Nested table

$section->addTextBreak(2);
$section->addText('Nested table in a centered and 50% width table.', $header);

$table = $section->addTable(array('width' => 50 * 50, 'unit' => 'pct', 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER));
$cell = $table->addRow()->addCell();
$cell->addText('This cell contains nested table.');
$innerCell = $cell->addTable(array('alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER))->addRow()->addCell();
$innerCell->addText('Inside nested table');

// 6. Table with different cell margins

$section->addTextBreak(1);
$section->addText('Table with different cell margins', $header);

$phpWord->addTableStyle('TableStyle', [
    'borderSize' => 1,
    'borderColor' => '000000',
    'cellMargin' => 80,
]);
$table = $section->addTable('TableStyle');

$rowHeight= 200;
$row = $table->addRow($rowHeight);
$row->addCell(2000, ['marginTopSize' => 500])->addText('top: 500');

$row = $table->addRow($rowHeight);
$row->addCell(2000, ['marginLeftSize' => 500])->addText('left: 500');

$row = $table->addRow($rowHeight);
$row->addCell(2000, ['marginRightSize' => 500])->addText('right: 500', null, ['alignment' => 'right']);

$row = $table->addRow($rowHeight);
$row->addCell(2000, ['marginBottomSize' => 500, 'valign' => 'bottom'])->addText('bottom: 500');

$row = $table->addRow($rowHeight);
$row->addCell(2000, ['marginTopSize' => 500, 'marginLeftSize' => 500])->addText('top: 500, left: 500');

// 7. Table with margins

$section->addTextBreak(1);

$rows = 10;
$cols = 5;
$section->addText('Table with margins', $header);

$table = $section->addTable([
    'marginTop' => 400,
    'marginBottom' => 400,
    'marginLeft' => 1000,
    'marginRight' => 3000,
]);
for ($r = 1; $r <= 8; $r++) {
    $table->addRow();
    for ($c = 1; $c <= 5; $c++) {
        $table->addCell(1000)->addText("Row {$r}, Cell {$c}");
    }
}


// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
