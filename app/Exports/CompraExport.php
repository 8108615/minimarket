<?php

namespace App\Exports;

use App\Models\Compra;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CompraExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    private $rowNumber = 0;

    public function collection()
    {
        // Traemos las compras con sus relaciones cargadas
        return Compra::with(['user', 'proveedor', 'detalles.producto'])->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Nro',
            'Proveedor',
            'Usuario',
            'Producto',
            'Cantidad',
            'Precio Unitario',
            'Fecha de Compra',
            'Total',
            'Estado'
        ];
    }

    public function map($compra): array
    {
        $this->rowNumber++;
        $filas = [];

        // Si la compra tiene detalles (productos), iteramos sobre cada uno para mostrarlos en filas o en formato legible
        if ($compra->detalles->count() > 0) {
            foreach ($compra->detalles as $index => $detalle) {
                $filas[] = [
                    $index === 0 ? $this->rowNumber : '', // El Nro solo en la primera línea de la compra
                    $index === 0 ? ($compra->proveedor->nombre ?? 'Sin proveedor') : '',
                    $index === 0 ? ($compra->user->name ?? 'N/D') : '',
                    $detalle->producto->nombre ?? 'Producto eliminado',
                    $detalle->cantidad,
                    $detalle->precio_compra,
                    $index === 0 ? $compra->created_at->format('d/m/Y H:i') : '',
                    $index === 0 ? $compra->total : '',
                    $index === 0 ? $compra->estado : '',
                ];
            }
        } else {
            // Por si acaso no tuviera detalles
            $filas[] = [
                $this->rowNumber,
                $compra->proveedor->nombre ?? 'Sin proveedor',
                $compra->user->name ?? 'N/D',
                'Sin productos',
                '-',
                '-',
                $compra->created_at->format('d/m/Y H:i'),
                $compra->total,
                $compra->estado,
            ];
        }

        return $filas;
    }
}