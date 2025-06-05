<?php

namespace App\Imports;

use App\Models\Shipment;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ShipmentImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $data = new Shipment();
        $data->shipment_id = $row['shipment_id'];
        $data->total_parcels = $row['total_parcels'];
        $data->weight = $row['weight'];
        $data->dimension_a = $row['dimension_a'];
        $data->dimension_b = $row['dimension_b'];
        $data->dimension_c = $row['dimension_c'];
        $data->start_hub = $row['start_hub'];
        $data->end_hubww = $row['end_hub'];
        $row = $data;
        return $row;
    }
}