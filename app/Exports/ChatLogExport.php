<?php

namespace App\Exports;

use App\Models\ChatLog;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ChatLogExport implements FromQuery, ShouldAutoSize, WithColumnWidths, WithStyles, WithMapping, WithHeadings, WithEvents
{
    protected $batch;
    protected $builder;
    protected $nData;

    public function __construct(?string $batch, $builder = null)
    {
        $this->batch = $batch;
        $this->builder = $builder;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        if($this->builder){
            $this->nData = $this->builder->count();
            return $this->builder;
        }
        else if ($this->batch) {
            $builder = ChatLog::where('batch', $this->batch)
            ->select(['ip', 'question', 'answer', 'created_at']);
            $this->nData = $builder->count();
            return $builder;
            // return ChatLog::where('batch', $this->batch)
                // ->select(['ip', 'question', 'answer', 'created_at']);
        } else {

            return ChatLog::query();
        }
    }

    public function map($data): array
    {
        return [
            $data->ip,
            $data->question,
            $data->answer,
            Carbon::parse($data->created_at)
        ];
    }

    public function headings(): array
    {
        return [
            'IP',
            'Question',
            'Answer',
            'Time'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'B' => 45,
            'C' => 45,

        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle(1)->getFont()->setBold(true);
        $sheet->getStyle('B')->applyFromArray(
            [
                'alignment' => [
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ]
        );
        $sheet->getStyle('C')->applyFromArray(
            [
                'alignment' => [
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ]
        );
       $sheet->getRowDimension(10)->setRowHeight(-1);
    }

    public function registerEvents(): array
    {
        $cellRange      = 'A1:D10';

        return [
            AfterSheet::class    => function (AfterSheet $event) use ($cellRange) {
                for($i=2;$i<=($this->nData+1);$i++){
                    $event->sheet->getRowDimension($i)->setRowHeight(45);
                }
                // $event->sheet->getRowDimension(10)->setRowHeight(-1);
                // $event->sheet->getStyle($cellRange)->applyFromArray([
                //     'borders' => [
                //         'allBorders' => [
                //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                //             'color' => ['argb' => '000000'],
                //         ],
                //     ],
                // ])->getAlignment()->setWrapText(true);
            },
        ];
    }
}
