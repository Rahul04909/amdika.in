<?php
require_once '../../admin/includes/auth.php';
require_once '../../database/db_config.php';
require_once '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// 1. Get Date Filters
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-01');
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d');

$from_date_esc = $conn->real_escape_string($from_date);
$to_date_esc = $conn->real_escape_string($to_date);

// 2. Fetch Sales Data (Chronological order for reports)
$sql = "SELECT 
            o.order_no,
            o.created_at,
            o.address_details,
            u.name AS user_name,
            oi.product_name,
            oi.quantity,
            oi.price AS sales_price,
            oi.gst_percent,
            oi.gst_amount,
            oi.total_line_amount,
            p.mrp AS product_mrp
        FROM order_items oi
        JOIN orders o ON oi.order_id = o.id
        LEFT JOIN users u ON o.user_id = u.id
        LEFT JOIN products p ON oi.product_id = p.id
        WHERE o.payment_status = 'paid'
          AND DATE(o.created_at) >= '$from_date_esc'
          AND DATE(o.created_at) <= '$to_date_esc'
        ORDER BY o.created_at ASC";
$result = $conn->query($sql);

// 3. Initialize PhpSpreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Sales Report');

// Enable grid lines explicitly
$sheet->setShowGridlines(true);

// 4. Set Document Header / Title Block
$sheet->setCellValue('A1', 'AMADIKA PREMIUM LEATHER - SALES REPORT');
$sheet->mergeCells('A1:J1');
$sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true)->getColor()->setRGB('111827');
$sheet->getRowDimension(1)->setRowHeight(35);
$sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

// Date Range info
$date_info = "Date Range: " . date('d M Y', strtotime($from_date)) . " to " . date('d M Y', strtotime($to_date));
$sheet->setCellValue('A2', $date_info);
$sheet->mergeCells('A2:J2');
$sheet->getStyle('A2')->getFont()->setSize(11)->setItalic(true)->getColor()->setRGB('4B5563');
$sheet->getRowDimension(2)->setRowHeight(20);

// Generation Timestamp
$gen_info = "Generated on: " . date('d M Y, h:i A');
$sheet->setCellValue('A3', $gen_info);
$sheet->mergeCells('A3:J3');
$sheet->getStyle('A3')->getFont()->setSize(9)->getColor()->setRGB('6B7280');
$sheet->getRowDimension(3)->setRowHeight(18);

// Empty row spacer in Row 4

// 5. Set Table Headers (Row 5)
$headers = [
    'A5' => 'Date',
    'B5' => 'Invoice No.',
    'C5' => 'Customer Name',
    'D5' => 'Product Name',
    'E5' => 'Qty',
    'F5' => 'MRP (unit)',
    'G5' => 'Sales Price (unit)',
    'H5' => 'Discount',
    'I5' => 'GST',
    'J5' => 'Total'
];

foreach ($headers as $cell => $title) {
    $sheet->setCellValue($cell, $title);
}

// Table Header Styling
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
        'size' => 11,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '2D3436'], // Dark Slate
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ]
];
$sheet->getStyle('A5:J5')->applyFromArray($headerStyle);
$sheet->getStyle('A5:J5')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM)->getColor()->setRGB('D4A017'); // Gold Accent border
$sheet->getRowDimension(5)->setRowHeight(30);

// 6. Populate Data rows (starting at Row 6)
$startRow = 6;
$currentRow = $startRow;

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $addr = json_decode($row['address_details'], true);
        $cust_name = $addr['name'] ?? $row['user_name'] ?? 'Guest';
        $mrp = ($row['product_mrp'] > 0) ? $row['product_mrp'] : $row['sales_price'];
        $sales_price = $row['sales_price'];
        $qty = $row['quantity'];
        $gst = $row['gst_amount'];
        $date = date('Y-m-d', strtotime($row['created_at']));
        
        $sheet->setCellValue('A' . $currentRow, $date);
        $sheet->setCellValue('B' . $currentRow, $row['order_no']);
        $sheet->setCellValue('C' . $currentRow, $cust_name);
        $sheet->setCellValue('D' . $currentRow, $row['product_name']);
        $sheet->setCellValue('E' . $currentRow, $qty);
        $sheet->setCellValue('F' . $currentRow, $mrp);
        $sheet->setCellValue('G' . $currentRow, $sales_price);
        
        // Excel Formulas for dynamic calculation
        // Discount: = (MRP - Sales Price) * Qty
        $sheet->setCellValue('H' . $currentRow, "=(F{$currentRow}-G{$currentRow})*E{$currentRow}");
        
        $sheet->setCellValue('I' . $currentRow, $gst);
        
        // Total: = (Sales Price * Qty) + GST
        $sheet->setCellValue('J' . $currentRow, "=(G{$currentRow}*E{$currentRow})+I{$currentRow}");
        
        // Row Height
        $sheet->getRowDimension($currentRow)->setRowHeight(22);
        
        $currentRow++;
    }
}

$lastDataRow = $currentRow - 1;
$totalRow = $currentRow; // Grand Totals row

// 7. Write Totals Row
if ($lastDataRow >= $startRow) {
    // Grand Total Labels
    $sheet->setCellValue('A' . $totalRow, 'Grand Total');
    $sheet->mergeCells("A{$totalRow}:D{$totalRow}");
    $sheet->getStyle("A{$totalRow}:D{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    
    // Sum Formulas
    $sheet->setCellValue('E' . $totalRow, "=SUM(E{$startRow}:E{$lastDataRow})");
    $sheet->setCellValue('F' . $totalRow, "=SUMPRODUCT(F{$startRow}:F{$lastDataRow},E{$startRow}:E{$lastDataRow})"); // Total MRP value
    $sheet->setCellValue('G' . $totalRow, "=SUMPRODUCT(G{$startRow}:G{$lastDataRow},E{$startRow}:E{$lastDataRow})"); // Total Sales value
    $sheet->setCellValue('H' . $totalRow, "=SUM(H{$startRow}:H{$lastDataRow})");
    $sheet->setCellValue('I' . $totalRow, "=SUM(I{$startRow}:I{$lastDataRow})");
    $sheet->setCellValue('J' . $totalRow, "=SUM(J{$startRow}:J{$lastDataRow})");
    
    $sheet->getRowDimension($totalRow)->setRowHeight(26);
    
    // Totals Row Styling (Bold text, top line border, bottom double accounting border)
    $totalStyle = [
        'font' => ['bold' => true],
        'borders' => [
            'top' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => '2D3436'],
            ],
            'bottom' => [
                'borderStyle' => Border::BORDER_DOUBLE,
                'color' => ['rgb' => '2D3436'],
            ]
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'F8F9FA'],
        ]
    ];
    $sheet->getStyle("A{$totalRow}:J{$totalRow}")->applyFromArray($totalStyle);
} else {
    // No data available
    $sheet->setCellValue('A' . $startRow, 'No sales data found for the selected date range.');
    $sheet->mergeCells("A{$startRow}:J{$startRow}");
    $sheet->getStyle('A' . $startRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $totalRow = $startRow;
}

// 8. General Formatting & Alignments
// Align Date, Invoice No, and Qty to Center
$sheet->getStyle('A6:B' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('E6:E' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Align Currencies to Right
$sheet->getStyle('F6:J' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

// Currency Formatting (INR Currency Format)
$sheet->getStyle('F6:J' . $totalRow)->getNumberFormat()->setFormatCode('"₹"#,##0.00');

// Apply Global Font Family (Segoe UI)
$sheet->getStyle('A1:J' . $totalRow)->getFont()->setName('Segoe UI');

// Apply Outer/Inner Borders to Data Block
if ($lastDataRow >= $startRow) {
    $sheet->getStyle("A{$startRow}:J{$lastDataRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E5E7EB');
}

// Auto-fit Columns width
foreach (range('A', 'J') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// 9. Output Spreadsheet as Download File
$filename = 'Sales_Report_' . date('Ymd', strtotime($from_date)) . '_to_' . date('Ymd', strtotime($to_date)) . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1'); // for compatibility with IE over SSL

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
