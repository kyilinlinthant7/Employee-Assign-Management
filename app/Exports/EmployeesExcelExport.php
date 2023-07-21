<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

/**
 * Class EmployeesExcelExport
 * @author Kyi Lin Lin Thant
 * @create 21/06/2023
 */
class EmployeesExcelExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle, WithEvents, ShouldQueue
{
    use Exportable;

    private $employees;
    private $currentPage;

    public function __construct($employees, $currentPage)
    {
        $this->employees = $employees;
        $this->currentPage = $currentPage;
    }

    /**
     * @author Kyi Lin Lin Thant
     * @create 21/06/2023
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->employees;
    }

    public function map($employee): array
    {
        // map the columns want to export
        return [
            $employee->employee_id,
            $employee->name,
            $employee->email,
            Carbon::parse($employee->date_of_birth)->format('d-m-Y'),
            $employee->career_part,
            $employee->level,
            $employee->phone,
        ];
    }

    /**
     * @author Kyi Lin Lin Thant
     * @create 21/06/2023
     * @return array
     */
    public function headings(): array
    {
        return [
            'Employee ID',
            'Name',
            'Email',
            'Date of Birth',
            'Career Part',
            'Level',
            'Phone',
        ];
    }

    /**
     * @author Kyi Lin Lin Thant
     * @create 21/06/2023
     * @return array
     */
    public function title(): string
    {
        return 'Employee List - Page ' . $this->currentPage;
    }

    /**
     * @author Kyi Lin Lin Thant
     * @create 21/06/2023
     * @return array
     */
    public function registerEvents(): array
    {
        // retrieve current pagination
        $currentPage = $this->currentPage;
        
        return [
            AfterSheet::class => function(AfterSheet $event) use ($currentPage) {

                // set the sheet title using the current page
                $event->sheet->setTitle('Employee List - Page ' . $currentPage);

                // resize cell width
                $event->sheet->getColumnDimension('D')->setWidth(15);
                for ($column = 'B'; $column <= 'G'; $column++) {
                    $event->sheet->getColumnDimension($column)->setAutoSize(false);
                    if ($column !== 'D') {
                        $event->sheet->getColumnDimension($column)->setWidth(25);
                    }
                }

                // resize cell height
                $event->sheet->getRowDimension(1)->setRowHeight(30);
                $event->sheet->getRowDimension(2)->setRowHeight(20);

                // set the alignment of the cells
                $event->sheet->getStyle('A:G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('A:G')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // add borders to the merged cell A1 (A1 to G1)
                $borderStyleMerged = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => Color::COLOR_BLACK],
                        ],
                    ],
                ];

                $event->sheet->getStyle('A1:G1')->applyFromArray($borderStyleMerged);

                // add borders to the individual cells A1 to G1
                $borderStyleIndividual = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => Color::COLOR_BLACK],
                        ],
                    ],
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '454545'],
                        'size' => 14,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '00FFFF'],
                    ],
                ];

                $range = 'A1:G1';
                $event->sheet->getStyle($range)->applyFromArray($borderStyleIndividual);

                // get data to modify values
                $worksheet = $event->sheet->getDelegate();

                // modify values starting from row 2 (A2 to G2)
                for ($row = 2; $row <= $worksheet->getHighestRow(); $row++) {
                    $careerPart = $worksheet->getCell('E' . $row)->getValue();
                    $level = $worksheet->getCell('F' . $row)->getValue();

                    if ($careerPart >= 1 && $careerPart <= 4) {
                        $careerPartMessage = $this->getCareerPartMessage($careerPart);
                        $worksheet->setCellValue('E' . $row, $careerPartMessage);
                    }

                    if ($level >= 1 && $level <= 4) {
                        $levelMessage = $this->getLevelMessage($level);
                        $worksheet->setCellValue('F' . $row, $levelMessage);
                    }
                }
            },
        ];
    }

    /**
     * @author Kyi Lin Lin Thant
     * @create 23/06/2023
     * @return array
     */
    private function getCareerPartMessage($value)
    {
        $messages = [
            1 => 'Front-end Developer',
            2 => 'Back-end Developer',
            3 => 'Full-stack Developer',
            4 => 'Mobile Developer',
        ];

        return $messages[$value] ?? '';
    }

    /**
     * @author Kyi Lin Lin Thant
     * @create 23/06/2023
     * @return array
     */
    private function getLevelMessage($value)
    {
        $messages = [
            1 => 'Beginner',
            2 => 'Junior Engineer',
            3 => 'Engineer',
            4 => 'Senior Engineer',
        ];

        return $messages[$value] ?? '';
    }
}