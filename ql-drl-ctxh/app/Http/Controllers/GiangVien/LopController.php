<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CoVanHT;
use App\Models\Lop;
use App\Models\SinhVien;
use App\Models\HocKy;
use App\Models\ChucVuSinhVien;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LopDiemExport;
use Illuminate\Support\Str;

class LopController extends Controller
{
    /**
     * Lấy danh sách Lớp Cố vấn và MSSV của giảng viên
     */
    private function getLopCoVanData()
    {
        $maGV = Auth::user()->TenDangNhap;
        $maLops = CoVanHT::where('MaGiangVien', $maGV)->pluck('MaLop');
        $lopCoVanList = Lop::whereIn('MaLop', $maLops)->get();
        //$allMSSV = SinhVien::whereIn('MaLop', $maLops)->pluck('MSSV');

        return compact('maLops', 'lopCoVanList');
    }

    /**
     * Hiển thị trang xem điểm Rèn Luyện
     */
    public function xemDiemDRL(Request $request)
    {
        $data = $this->getLopCoVanData();
        $lopCoVanList = $data['lopCoVanList'];
        $maLops = $data['maLops'];

        $hocKys = HocKy::orderBy('NgayBatDau', 'desc')->get();
        $selectedHocKy = $request->input('hoc_ky', $hocKys->first()->MaHocKy ?? null);
        $selectedLop = $request->input('ma_lop');

        // Query Sinh Viên
        $query = SinhVien::whereIn('MaLop', $maLops);

        // Lọc theo Lớp
        if ($selectedLop) {
            $query->where('MaLop', $selectedLop);
        }

        // 2. Paginate chỉ bảng Sinh Vien (RẤT NHANH)
        $sinhviens = $query->orderBy('MaLop')->orderBy('MSSV')->paginate(70);

        // 3. Load điểm DRL *CHỈ* cho 70 sinh viên trên trang này
        // (Sử dụng quan hệ 'diemRenLuyen' chúng ta đã thêm ở Model)
        if ($sinhviens->count() > 0 && $selectedHocKy) {
            $sinhviens->load(['diemRenLuyen' => function ($q) use ($selectedHocKy) {
                // Chỉ lấy điểm của học kỳ đã chọn
                $q->where('MaHocKy', $selectedHocKy);
            }]);
        }

        return view('giangvien.lop.diem_drl', compact(
            'sinhviens',
            'hocKys',
            'lopCoVanList',
            'selectedHocKy',
            'selectedLop'
        ));
    }

    /**
     * Hiển thị trang xem điểm CTXH
     */
    public function xemDiemCTXH(Request $request)
    {
        $data = $this->getLopCoVanData();
        $lopCoVanList = $data['lopCoVanList'];
        $maLops = $data['maLops'];

        $selectedLop = $request->input('ma_lop');

        // Query Sinh Viên
        $query = SinhVien::whereIn('MaLop', $maLops);

        // Lọc theo Lớp
        if ($selectedLop) {
            $query->where('MaLop', $selectedLop);
        }

        $sinhviens = $query->with('diemctxh') // Tải điểm CTXH
            ->orderBy('MaLop')->orderBy('MSSV')
            ->paginate(70);

        return view('giangvien.lop.diem_ctxh', compact(
            'sinhviens',
            'lopCoVanList',
            'selectedLop'
        ));
    }



    public function indexBanCanSu(Request $request)
    {
        $data = $this->getLopCoVanData();
        $lopCoVanList = $data['lopCoVanList'];

        if ($lopCoVanList->isEmpty()) {
            return view('giangvien.lop.bancansu_empty');
        }

        $hocKys = HocKy::orderBy('NgayBatDau', 'desc')->get();

        // Lấy filter
        $selectedLop = $request->input('ma_lop', $lopCoVanList->first()->MaLop);
        $selectedHocKy = $request->input('hoc_ky', $hocKys->first()->MaHocKy ?? null);
        $search = $request->input('search'); // <--- LẤY TỪ KHÓA TÌM KIẾM

        $sinhviens = collect();
        if ($selectedHocKy) {
            $query = SinhVien::where('MaLop', $selectedLop)
                ->with(['chucVus' => function ($query) use ($selectedHocKy) {
                    $query->where('MaHocKy', $selectedHocKy);
                }]);

            // <--- THÊM LOGIC TÌM KIẾM ---
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('HoTen', 'LIKE', '%' . $search . '%')
                        ->orWhere('MSSV', 'LIKE', '%' . $search . '%');
                });
            }
            // -----------------------------

            $sinhviens = $query->orderBy('HoTen', 'asc')->get();
        }

        $roles = ['Lớp trưởng', 'Bí thư', 'Phó bí thư', 'Khác'];

        return view('giangvien.lop.bancansu', compact(
            'sinhviens',
            'lopCoVanList',
            'hocKys',
            'selectedLop',
            'selectedHocKy',
            'roles',
            'search' // <--- TRUYỀN BIẾN SEARCH RA VIEW
        ));
    }

    // =========================================================
    // === HÀM MỚI: CẬP NHẬT CHỨC VỤ CHO 1 SINH VIÊN
    // =========================================================
    public function updateBanCanSu(Request $request)
    {
        $validated = $request->validate([
            'mssv' => 'required|string|exists:sinhvien,MSSV',
            'ma_lop' => 'required|string|exists:lop,MaLop',
            'ma_hoc_ky' => 'required|string|exists:hocky,MaHocKy',
            'chuc_vu' => 'required|string', // 'none' hoặc một chức vụ
        ]);

        try {
            $hocKy = HocKy::findOrFail($validated['ma_hoc_ky']);

            // Tìm bản ghi hiện có
            $existing = ChucVuSinhVien::where('MSSV', $validated['mssv'])
                ->where('MaHocKy', $validated['ma_hoc_ky'])
                ->first();

            if ($validated['chuc_vu'] == 'none') {
                // Nếu người dùng chọn "Không có", ta xóa bản ghi
                if ($existing) {
                    $existing->delete();
                }
            } else {
                // Nếu chọn chức vụ, ta Cập nhật hoặc Tạo mới
                ChucVuSinhVien::updateOrCreate(
                    [
                        'MSSV' => $validated['mssv'],
                        'MaHocKy' => $validated['ma_hoc_ky'],
                    ],
                    [
                        'MaLop' => $validated['ma_lop'],
                        'ChucVu' => $validated['chuc_vu'],
                        'NgayBatDau' => $hocKy->NgayBatDau,
                        'NgayKetThuc' => $hocKy->NgayKetThuc,
                    ]
                );
            }

            return redirect()->back()->with('success', 'Cập nhật chức vụ cho ' . $validated['mssv'] . ' thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }



    public function indexBaoCao(Request $request)
    {
        $data = $this->getLopCoVanData();
        $lopCoVanList = $data['lopCoVanList'];
        $maLops = $data['maLops'];

        $hocKys = HocKy::orderBy('NgayBatDau', 'desc')->get();

        $sinhviens = collect();

        // Lấy filter
        $selectedLop = $request->input('ma_lop');
        $selectedHocKy = $request->input('hoc_ky');

        // Chỉ truy vấn khi GV đã chọn cả 2
        if ($selectedLop && $selectedHocKy) {
            // Đảm bảo GVCV chỉ xem được lớp của mình
            if (!$maLops->contains($selectedLop)) {
                abort(403, 'Bạn không có quyền xem lớp này.');
            }

            // Tải danh sách SV (đã phân trang) và điểm của họ
            $query = SinhVien::where('MaLop', $selectedLop)
                ->with([
                    'diemRenLuyen' => function ($q) use ($selectedHocKy) {
                        $q->where('MaHocKy', $selectedHocKy);
                    },
                    'diemCtxh' // Tải điểm CTXH tổng
                ]);

            $sinhviens = $query->orderBy('HoTen', 'asc')->paginate(25);
        }

        return view('giangvien.lop.baocao', compact(
            'sinhviens',
            'lopCoVanList',
            'hocKys',
            'selectedLop',
            'selectedHocKy'
        ));
    }

    // =========================================================
    // === HÀM MỚI: XỬ LÝ XUẤT FILE EXCEL
    // =========================================================
    public function exportBaoCao(Request $request)
    {
        $validated = $request->validate([
            'ma_lop' => 'required|string|exists:lop,MaLop',
            'hoc_ky' => 'required|string|exists:hocky,MaHocKy',
        ]);

        $maLop = $validated['ma_lop'];
        $maHocKy = $validated['hoc_ky'];

        // Kiểm tra GVCV có quyền xem lớp này không
        $data = $this->getLopCoVanData();
        if (!$data['maLops']->contains($maLop)) {
            abort(403, 'Bạn không có quyền xuất dữ liệu cho lớp này.');
        }

        // Lấy tên lớp để đặt tên file
        $lop = Lop::find($maLop);
        $fileName = 'BaoCaoDiem_' . Str::slug($lop->TenLop, '_') . '_' . $maHocKy . '.xlsx';

        // Gọi Lớp Export
        // Gọi Lớp Export
        return Excel::download(new LopDiemExport($maLop, $maHocKy), $fileName);
    }
}
