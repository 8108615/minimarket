<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductosExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Producto::with('categoria')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Código',
            'Descripción',
            'Precio Venta',
            'Precio Compra',
            'Stock',
            'Stock Mínimo',
            'Categoría',
            'Estado'
        ];
    }

    public function map($producto): array
    {
        return [
            $producto->id,
            $producto->nombre,
            $producto->codigo,
            $producto->descripcion ?? 'N/A',
            $producto->precio_venta,
            $producto->precio_compra,
            $producto->stock,
            $producto->stock_minimo,
            $producto->categoria->nombre ?? 'Sin categoría',
            $producto->estado,
        ];
    }
}
