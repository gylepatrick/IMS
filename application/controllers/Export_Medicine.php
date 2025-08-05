<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class Export_Medicine extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Medicine_model');
        $this->load->model('Medicine_model');
        $this->load->helper('url'); 
        $this->load->library('session');
    }

    public function generate_excel_subsidiary($item_code = null) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        $sheet->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(PageSetup::PAPERSIZE_A4)
            ->setFitToHeight(0); 
    
        $settings = $this->db->get('settings')->row();
        $school_name = $settings->school_name;
        $school_address = $settings->address;
        $checked_by = $settings->checked_by;
        $approved_by = $settings->approved_by;
        $logoFile = trim($settings->school_logo);
        $logo_path = FCPATH . '/' . $logoFile;
    
        $prepared_by = $this->session->userdata('full_name');
        $item_code = $this->input->get('item_code');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
    
        if (file_exists($logo_path)) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('School Logo');
            $drawing->setDescription('School Logo');
            $drawing->setPath($logo_path);
            $drawing->setHeight(100);
            $drawing->setCoordinates('A1');
            $drawing->setWorksheet($sheet);
        }
    
        $sheet->setCellValue('B2', $school_name);
        $sheet->setCellValue('B3', $school_address);
        $sheet->mergeCells('B2:Q2');
        $sheet->mergeCells('B3:Q3');
        $sheet->getStyle('B2:B2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('B2:B3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    
        // Report title
        $sheet->setCellValue('A6', 'SUBSIDIARY LEDGER REPORT');
        $sheet->mergeCells('A6:D6');
        $sheet->getStyle('A6')->getFont()->setBold(true)->setSize(14);
    
        $date_range_text = "From " . (!empty($start_date) ? date('F j, Y', strtotime($start_date)) : 'N/A') . 
                           " to " . (!empty($end_date) ? date('F j, Y', strtotime($end_date)) : 'N/A');
        $sheet->setCellValue('A7', $date_range_text);
    
        if ($item_code) {
            $sheet->setCellValue('A8', "Item Code: $item_code");
            $sheet->getStyle('A8')->getFont()->setBold(true)->setSize(12);
            $sheet->mergeCells('A8:D8');
        } else {
            $sheet->setCellValue('A8', 'All Items');
            $sheet->getStyle('A8')->getFont()->setBold(true)->setSize(12);
            $sheet->mergeCells('A8:D8');
        }
    
        $offices = $this->Medicine_model->get_inventory_medicine($item_code, $start_date, $end_date);
    
        $headers = [
            'Date Purchased', 'Date Entered', 'Transaction', 'Item Code', 'Description', 'Brand',
            'Supplier', 'Requesting Office', 'Location', 'Unit', 'Quantity', 'Unit Cost', 'Amount',
            'Balance Qty', 'Balance Amt', 'Stock Status', 'Entering Personnel'
        ];
    
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '9', $header);
            $col++;
        }
    
        $sheet->mergeCells('N9:O9');
        $sheet->setCellValue('N9', 'Balance');
        $sheet->getStyle('A9:Q9')->getFont()->setBold(true);
        $sheet->setCellValue('N10', 'Qty.');
        $sheet->setCellValue('O10', 'Amt.');
    
        $grouped_data = [];
        foreach ($offices as $t) {
            $grouped_data[$t->item_code][] = $t;
        }
    
        $row = 11;
        foreach ($grouped_data as $item_code => $transactions) {
            $balances = [];
    
            foreach ($transactions as $t) {
                $unitCost = ($t->quantity > 0) ? number_format($t->total / $t->quantity, 2) : 0;
    
                if (!isset($balances[$t->item_code][$t->batch_number])) {
                    $balances[$t->item_code][$t->batch_number] = ['quantity' => 0, 'total' => 0];
                }
    
                if ($t->type == 'Purchase' || $t->type == 'Donation') {
                    $balances[$t->item_code][$t->batch_number]['quantity'] += $t->quantity;
                    $balances[$t->item_code][$t->batch_number]['total'] += $t->total;
                } elseif ($t->type == 'Issuance') {
                    $balances[$t->item_code][$t->batch_number]['quantity'] -= $t->quantity;
                    $balances[$t->item_code][$t->batch_number]['total'] -= $t->total;
                }
    
                $currentBalance = $balances[$t->item_code][$t->batch_number]['quantity'];
                $currentTotal = $balances[$t->item_code][$t->batch_number]['total'];
                $unitCost = ($t->quantity > 0) ? round($t->total / $t->quantity, 2) : 0;
                $status = ($currentBalance <= 0) ? 'Out of Stock' : 'In Stock';
                $enterDate = ($t->type == "Issuance" || $t->type == "Disposal") ? '' : $t->purchased_date;
    
                $sheet->setCellValue('A' . $row, $enterDate);
                $sheet->setCellValue('B' . $row, $t->entered_date);
                $sheet->setCellValue('C' . $row, $t->type);
                $sheet->setCellValue('D' . $row, $t->item_code);
                $sheet->setCellValue('E' . $row, $t->discription);
                $sheet->setCellValue('F' . $row, $t->brand);
                $sheet->setCellValue('G' . $row, $t->supplier);
                $sheet->setCellValue('H' . $row, $t->requesting_office);
                $sheet->setCellValue('I' . $row, $t->location);
                $sheet->setCellValue('J' . $row, $t->unit);
                $sheet->setCellValue('K' . $row, $t->quantity);
                $sheet->setCellValue('L' . $row, number_format($unitCost, 2));
                $sheet->setCellValue('M' . $row, number_format($t->total, 2));
                $sheet->setCellValue('N' . $row, $currentBalance);
                $sheet->setCellValue('O' . $row, number_format($currentTotal, 2));
                $sheet->setCellValue('P' . $row, $status);
                $sheet->setCellValue('Q' . $row, $t->entered_by);
    
                $sheet->getStyle('K' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('L' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('M' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('N' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('O' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    
                if ($status == 'In Stock') {
                    $sheet->getStyle('P' . $row)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('90EE90');
                } elseif ($status == 'Out of Stock') {
                    $sheet->getStyle('P' . $row)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('FFCCCB');
                }
    
                $row++;
            }
        }
    
        $row += 5;
    
        $sheet->setCellValue("B$row", "Prepared by:");
        $sheet->setCellValue("F$row", "Checked by:");
        $sheet->setCellValue("J$row", "Approved by:");
    
        $row += 2;
        $sheet->setCellValue("B$row", $prepared_by);
        $sheet->setCellValue("F$row", $checked_by);
        $sheet->setCellValue("J$row", $approved_by);
    
        $last_data_row = $row - 6; 
        $sheet->getStyle("A9:Q$last_data_row")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
    
        foreach (range('A', 'Q') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    
       $filename = 'SUBSIDIARY_LEDGER_MEDICAL_SUPPLIES_' . date('Ymd_His') . '.xlsx';
       header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
       header("Content-Disposition: attachment; filename=\"$filename\"");
       header('Cache-Control: max-age=0');
    
       $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output'); 
       exit;
    }


    public function generate_excel_acquisition($item_code = null)
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->getPageSetup()
        ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
        ->setPaperSize(PageSetup::PAPERSIZE_A4)
        ->setFitToHeight(0);

    
    $settings = $this->db->get('settings')->row();
    $school_name = $settings->school_name;
    $school_address = $settings->address;
    $checked_by = $settings->checked_by;
    $approved_by = $settings->approved_by; 
    $logoFile = trim($settings->school_logo);
    $logo_path = FCPATH . '/' . $logoFile;

    
    $prepared_by = $this->session->userdata('full_name');
    $item_code = $this->input->get('item_code');
    $start_date = $this->input->get('start_date');
    $end_date = $this->input->get('end_date');

    
    if (file_exists($logo_path)) {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('School Logo');
        $drawing->setDescription('School Logo');
        $drawing->setPath($logo_path);
        $drawing->setHeight(100);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);
    }

    $sheet->setCellValue('B2', $school_name);
    $sheet->setCellValue('B3', $school_address);
    $sheet->mergeCells('B2:Q2');
    $sheet->mergeCells('B3:Q3');
    $sheet->getStyle('B2')->getFont()->setBold(true)->setSize(12);
    $sheet->getStyle('B2:B3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

   
    $sheet->setCellValue('A6', 'ACQUSITION, DONATION, ISSUANCE AND DISPOSAL REPORT  ');
    $sheet->mergeCells('A6:D6');
    $sheet->getStyle('A6')->getFont()->setBold(true)->setSize(14);

    $date_range_text = "From " . (!empty($start_date) ? date('F j, Y', strtotime($start_date)) : 'N/A') .
                       " to " . (!empty($end_date) ? date('F j, Y', strtotime($end_date)) : 'N/A');
    $sheet->setCellValue('A7', $date_range_text);

    $item_text = $item_code ? "Item Code: $item_code" : 'All Items';
    $sheet->setCellValue('A8', $item_text);
    $sheet->mergeCells('A8:D8');
    $sheet->getStyle('A8')->getFont()->setBold(true)->setSize(12);

    // Headers
    $headers = [
        'Date Purchased', 'Date Entered', 'Transaction', 'Item Code', 'Description', 'Brand',
        'Supplier', 'Requesting Office', 'Location', 'Unit', 'Quantity', 'Unit Cost', 'Amount'
    ];
    $sheet->getStyle('A9:M9')->getFont()->setBold(true);
    $sheet->getStyle('A9:M9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '9', $header);
        $col++;
    }

    

    // Fetch transactions
    $offices = $this->Medicine_model->get_inventory_medicine($item_code, $start_date, $end_date);

    // Group data
    $grouped_data = [];
    foreach ($offices as $t) {
        $grouped_data[$t->item_code][] = $t;
    }

    $row = 11;
    foreach ($grouped_data as $item_code => $transactions) {
        $balances = [];

        foreach ($transactions as $t) {
            $unitCost = ($t->quantity > 0) ? number_format($t->total / $t->quantity, 2) : 0;

            if (!isset($balances[$t->item_code][$t->batch_number])) {
                $balances[$t->item_code][$t->batch_number] = ['quantity' => 0, 'total' => 0];
            }

            if ($t->type == 'Purchase' || $t->type == 'Donation') {
                $balances[$t->item_code][$t->batch_number]['quantity'] += $t->quantity;
                $balances[$t->item_code][$t->batch_number]['total'] += $t->total;
            } elseif ($t->type == 'Issuance') {
                $balances[$t->item_code][$t->batch_number]['quantity'] -= $t->quantity;
                $balances[$t->item_code][$t->batch_number]['total'] -= $t->total;
            }

            $currentBalance = $balances[$t->item_code][$t->batch_number]['quantity'];
            $currentTotal = $balances[$t->item_code][$t->batch_number]['total'];
            $unitCost = ($t->quantity > 0) ? round($t->total / $t->quantity, 2) : 0;
            $enterDate = ($t->type == "Issuance" || $t->type == "Disposal") ? '' : $t->purchased_date;

            $sheet->setCellValue("A$row", $enterDate);
            $sheet->setCellValue("B$row", $t->entered_date);
            $sheet->setCellValue("C$row", $t->type);
            $sheet->setCellValue("D$row", $t->item_code);
            $sheet->setCellValue("E$row", $t->discription); // Consider fixing typo in DB: 'description'
            $sheet->setCellValue("F$row", $t->brand);
            $sheet->setCellValue("G$row", $t->supplier);
            $sheet->setCellValue("H$row", $t->requesting_office);
            $sheet->setCellValue("I$row", $t->location);
            $sheet->setCellValue("J$row", $t->unit);
            $sheet->setCellValue("K$row", $t->quantity);
            $sheet->setCellValue("L$row", number_format($unitCost, 2));
            $sheet->setCellValue("M$row", number_format($t->total, 2));

            $sheet->getStyle("K$row:M$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $row++;
        }
    }

    // Leave blank rows before signature
    $row += 5;

    // Signatories
    $sheet->setCellValue("B$row", "Prepared by:");
    $sheet->setCellValue("F$row", "Checked by:");
    $sheet->setCellValue("J$row", "Approved by:");

    $row += 2;
    $sheet->setCellValue("B$row", $prepared_by);
    $sheet->setCellValue("F$row", $checked_by);
    $sheet->setCellValue("J$row", $approved_by);

    // Apply border to data section
    $last_data_row = $row - 7;
    $sheet->getStyle("A9:M$last_data_row")->applyFromArray([
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ]);

    // Auto-size columns
    foreach (range('A', 'M') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Output to browser
    $filename = 'ACQUISITION_REPORT_MEDICAL_SUPPLIES_' . date('Ymd_His') . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
    
}
?>
