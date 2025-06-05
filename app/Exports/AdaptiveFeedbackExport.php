<?php

namespace App\Exports;

use App\Models\ChatLog;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class AdaptiveFeedbackExport implements FromCollection
{
  protected $batch;
  public function __construct( $batch)
    {
        $this->batch = $batch;
    }

    public function collection()
    {
      return ChatLog::where('batch', $this->batch)->get();
    }
}
