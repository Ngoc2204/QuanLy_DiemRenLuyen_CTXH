<?php

namespace App\Exports;

use App\Models\SinhVien;
use App\Models\HocKy;
use App\Models\Lop;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LopDiemExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithTitle, WithStyles
{
    protected $maLop;
    protected $maHocKy;
    protected $tenLop;
    protected $tenHocKy;
    protected $stt = 0;

    public function __construct(string $maLop, string $maHocKy)
    {
        $this->maLop = $maLop;
        $this->maHocKy = $maHocKy;
        
        // Lấy tên để làm tiêu đề file
        $lop = Lop::find($maLop);
        $hocKy = HocKy::find($maHocKy);
        $this->tenLop = $lop ? $lop->TenLop : $maLop;
        $this->tenHocKy = $hocKy ? $hocKy->TenHocKy : $maHocKy;
    }

    /**
    * @return \Illuminate\Database\Query\Builder
    */
    public function query()
    {
        // Đây là câu query chính để lấy dữ liệu xuất file
        // Nó sẽ lấy *tất cả* sinh viên, không phân trang
        return SinhVien::where('MaLop', $this->maLop)
            ->leftJoin('diemrenluyen', function($join) {
                $join->on('sinhvien.MSSV', '=', 'diemrenluyen.MSSV')
                     ->where('diemrenluyen.MaHocKy', '=', $this->maHocKy);
            })
            ->leftJoin('diemctxh', 'sinhvien.MSSV', '=', 'diemctxh.MSSV')
            ->select(
                'sinhvien.MSSV', 
                'sinhvien.HoTen', 
                'diemrenluyen.TongDiem as DiemDRL', 
                'diemrenluyen.XepLoai as XepLoaiDRL', 
                'diemctxh.TongDiem as DiemCTXH'
            )
            ->orderBy('sinhvien.HoTen', 'asc');
    }

    /**
    * @return string
    */
    public function title(): string
    {
        // Tên của Sheet trong file Excel
        return 'BangDiem_' . $this->maLop;
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        // Dòng tiêu đề cho file Excel
        return [
            // Thêm 2 dòng tiêu đề phụ
            ['DANH SÁCH ĐIỂM SINH VIÊN'],
            ['Lớp: ' . $this->tenLop, 'Học kỳ: ' . $this->tenHocKy],
            [], // Dòng trống
            // Tiêu đề cột
            [
                'STT',
                'MSSV',
                'Họ Tên',
                'Điểm DRL (HK)',
                'Xếp Loại DRL',
                'Điểm CTXH (Tổng)',
            ]
        ];
    }

    /**
    * @var SinhVien $sinhvien
    * @return array
    */
    public function map($sinhvien): array
    {
        // Map dữ liệu từ query vào từng cột
        $this->stt++;
        return [
            $this->stt,
            $sinhvien->MSSV,
            $sinhvien->HoTen,
            $sinhvien->DiemDRL ?? 0,
            $sinhvien->XepLoaiDRL ?? 'Chưa có',
            $sinhvien->DiemCTXH ?? 0,
        ];
    }

    /**
    * @return array
    */
    public function styles(Worksheet $sheet)
    {
        // Style cho 2 dòng tiêu đề
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:C2');
        $sheet->mergeCells('D2:F2');
        $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        
        // Style cho tiêu đề cột
        $sheet->getStyle('A4:F4')->getFont()->setBold(true);
        $sheet->getStyle('A4:F4')->getFill()
              ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
              ->getStartColor()->setARGB('FFD9D9D9');
        
        return [];
    }
}