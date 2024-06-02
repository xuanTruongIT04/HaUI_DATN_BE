<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromCollection, WithHeadings, WithEvents, WithStyles
{

    protected $orders;

    public function __construct(Collection $orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders->map(function ($order) {
            return [
                'id' => $order->id,
                'order_code' => $order->code,
                'address_delivery' => $order->address_delivery,
                'payment_method' => $order->payment_method == 0 ? 'Tiền mặt' : 'Chuyển khoản',
                'total_mount' => currencyFormat($order->total_mount),
                'order_date' => $order->order_date,
                'status' => match ($order->status) {
                    0 => 'Đã đặt hàng',
                    1 => 'Đang xử lý',
                    2 => 'Đã thanh toán',
                    3 => 'Đã huỷ',
                },
                'coupon_id' => $order->coupon_id ?? 'Không có',
                'cart_id' => $order->cart_id,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID đơn hàng',
            'Mã đơn hàng',
            'Địa chỉ giao hàng',
            'Phương thức thanh toán',
            'Tổng tiền',
            'Ngày đặt hàng',
            'Trạng thái đơn hàng',
            'ID mã giảm giá',
            'ID giỏ hàng',
            'Ngày tạo',
            'Ngày cập nhật',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A4:K' . ($this->collection()->count() + 5) => ['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM]]],
            'G5:G' . ($this->collection()->count() + 6) => ['font' => ['color' => ['rgb' => 'FF0000']]],
            4 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Freeze the first row
                $sheet->freezePane('A4');

                // Auto size columns
                foreach (range('A', 'K') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Set row height
                $sheet->getRowDimension(1)->setRowHeight(90);

                // Set column width
                $sheet->getColumnDimension('A')->setWidth(10);
                $sheet->getColumnDimension('B')->setWidth(20);
                $sheet->getColumnDimension('C')->setWidth(40);
                $sheet->getColumnDimension('D')->setWidth(30);
                $sheet->getColumnDimension('E')->setWidth(20);
                $sheet->getColumnDimension('F')->setWidth(20);
                $sheet->getColumnDimension('G')->setWidth(20);
                $sheet->getColumnDimension('H')->setWidth(20);
                $sheet->getColumnDimension('I')->setWidth(20);
                $sheet->getColumnDimension('J')->setWidth(20);
                $sheet->getColumnDimension('K')->setWidth(20);

                // Set title and export details
                $sheet->mergeCells('A1:K1');
                $sheet->setCellValue('A1', '[Lotus Thé] - Orders Report')->getStyle('A1')->applyFromArray(['font' => ['bold' => true, 'size' => 20]]);
                $sheet->setCellValue('B2', 'Thời gian xuất file: ' . now()->format('d/m/Y H:i:s'))->getStyle('B2')->applyFromArray(['font' => ['size' => 12]]);
                $sheet->setCellValue('B3', 'Người xuất file: ' . Auth::user()->name)->getStyle('B3')->applyFromArray(['font' => ['size' => 12]]);

                // Add logo
                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('This is my logo');
                $drawing->setPath(public_path('rsrc/dist/img/AdminLotusLogo.jpg'));
                $drawing->setHeight(90);
                $drawing->setCoordinates('A1');

                $drawing->setWorksheet($sheet);
            },
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set cell alignment
                $sheet->getStyle('A1:K1')->getAlignment()->setHorizontal('center');
            },
        ];
    }
}