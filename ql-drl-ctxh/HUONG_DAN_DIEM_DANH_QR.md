# Hướng Dẫn Sử Dụng Hệ Thống Điểm Danh QR Code Riêng Biệt

## 📋 Tổng Quan

Hệ thống điểm danh đã được cập nhật với tính năng **tách riêng Check-In và Check-Out**, cho phép nhân viên:
- Phát mã QR check-in và check-out **riêng biệt**
- Đặt **thời gian hiệu lực riêng** cho từng mã QR
- Kiểm soát linh hoạt thời gian sinh viên có thể quét mã

---

## 🚀 Quy Trình Điểm Danh

### **Bước 1: Tạo Hoạt Động CTXH**
Truy cập menu **Quản lý Hoạt Động CTXH** → **Tạo mới** hoặc **Chỉnh sửa** hoạt động

### **Bước 2: Phát Mã QR Check-In**

1. Vào chi tiết hoạt động → Nhấp nút **"Phát Mã QR Check-In"** (màu xanh lá)
2. Điền vào form:
   - **Thời gian bắt đầu quét**: Khi nào sinh viên có thể bắt đầu quét check-in
   - **Thời gian hết hạn**: Khi nào hệ thống ngừng nhận check-in
3. Nhấp **"Phát Mã QR Check-In"** để lưu

```
Ví dụ:
- Hoạt động bắt đầu lúc: 13:30 (1:30 PM)
- Mở quét check-in từ: 13:25
- Đóng quét check-in lúc: 13:45
```

### **Bước 3: Phát Mã QR Check-Out**

1. Sau khi sinh viên check-in xong, nhấp nút **"Phát Mã QR Check-Out"** (màu vàng cam)
2. Điền vào form:
   - **Thời gian bắt đầu quét**: Khi nào sinh viên có thể bắt đầu quét check-out
   - **Thời gian hết hạn**: Khi nào hệ thống ngừng nhận check-out
3. Nhấp **"Phát Mã QR Check-Out"** để lưu

```
Ví dụ:
- Hoạt động kết thúc lúc: 14:30 (2:30 PM)
- Mở quét check-out từ: 14:20
- Đóng quét check-out lúc: 14:40
```

### **Bước 4: Thông Báo cho Sinh Viên**

- **Hiển thị mã check-in** khi hoạt động bắt đầu
- Sinh viên quét mã khi vào (Check-In)
- Nhân viên thay đổi mã check-out khi hoạt động gần hết (tùy chọn)
- Sinh viên quét mã khi rời khỏi (Check-Out)

### **Bước 5: Tổng Kết Điểm Danh**

Sau khi hoạt động kết thúc:

1. Nhấp nút **"Tổng kết Điểm danh"** (màu xanh dương)
2. Xác nhận hành động trong modal

**Hệ thống tự động đánh dấu**:
- ✅ **Đã tham gia**: Nếu sinh viên check-in AND check-out đều có
- ❌ **Vắng**: Nếu sinh viên KHÔNG check-in AND KHÔNG check-out
- ⚠️ **Chưa tổng kết**: Nếu chỉ check-in hoặc chỉ check-out (cần review thủ công)

### **Bước 6: Điều Chỉnh Kết Quả Thủ Công (Nếu Cần)**

1. Nhấp **"Ghi nhận/Điều chỉnh Kết quả"** (màu xanh nước biển)
2. Chọn trạng thái cho từng sinh viên:
   - Đã tham gia
   - Vắng
   - Chưa tổng kết
3. Lưu các thay đổi

---

## 📊 Chi Tiết Trạng Thái Điểm Danh

| Trạng Thái | Check-In | Check-Out | Mô Tả |
|-----------|----------|-----------|-------|
| **✅ Đã tham gia** | ✓ | ✓ | Quét đúng cả lúc vào và ra |
| **❌ Vắng** | ✗ | ✗ | Không quét lần nào (hoặc không tới) |
| **⚠️ Chưa tổng kết** | ✓ | ✗ | Chỉ quét vào nhưng quên ra |
| **⚠️ Chưa tổng kết** | ✗ | ✓ | Quét ra nhưng quên quét vào |

---

## 🔄 Làm Mới / Thay Đổi Mã QR

**Nếu cần phát lại mã QR**:
- Nhân viên có thể nhấp nút **"Phát Mã QR Check-In"** hoặc **"Phát Mã QR Check-Out"** lại bất kỳ lúc nào
- Mã cũ sẽ bị **vô hiệu hóa**, chỉ mã mới hoạt động
- Mã mới có thời gian hiệu lực riêng

---

## ⏰ Thời Gian Hiệu Lực - Ví Dụ Thực Tế

### Hoạt Động: Lớp Học Thêm từ 13:30 - 14:30

```
13:20 ─────── Nhân viên phát mã check-in
       │
       ├─ Mã check-in hiệu lực: 13:25 - 13:45
       │  (Sinh viên vào trong khung này)
       │
13:30 ─────── Hoạt động bắt đầu
       │
       │
14:20 ─────── Nhân viên phát mã check-out
       │
       ├─ Mã check-out hiệu lực: 14:20 - 14:40
       │  (Sinh viên ra trong khung này)
       │
14:30 ─────── Hoạt động kết thúc
       │
14:45 ─────── Nhân viên tổng kết
```

---

## 💡 Lợi Ích Của Hệ Thống Mới

✅ **Linh hoạt cao** - Nhân viên có thể điều chỉnh thời gian bất kỳ lúc nào  
✅ **Chính xác hơn** - Phân biệt rõ ràng check-in và check-out  
✅ **Ngăn chặn gian lận** - Mỗi mã chỉ hiệu lực trong khoảng thời gian nhất định  
✅ **Dễ quản lý** - Tổng kết tự động + điều chỉnh thủ công  
✅ **Thân thiện người dùng** - Giao diện clear, hướng dẫn rõ ràng

---

## 🛠️ Các Nút Chức Năng (Trên Chi Tiết Hoạt Động)

| Nút | Màu | Chức Năng |
|-----|-----|----------|
| Phát Mã QR Check-In | 🟢 Xanh lá | Tạo/Làm mới mã check-in với thời gian hiệu lực |
| Phát Mã QR Check-Out | 🟠 Vàng cam | Tạo/Làm mới mã check-out với thời gian hiệu lực |
| Tổng kết Điểm danh | 🔵 Xanh dương | Ghi nhận trạng thái (Đã tham gia/Vắng) cho tất cả |
| Ghi nhận/Điều chỉnh | 🔷 Xanh nước biển | Sửa từng sinh viên một nếu cần |
| Chỉnh sửa | 🟡 Vàng | Sửa thông tin hoạt động |
| Quay lại | 🟠 Xám | Quay lại danh sách hoạt động |

---

## ⚠️ Lưu Ý Quan Trọng

1. **Không thể tạo QR cho hoạt động đã kết thúc** - Hệ thống sẽ ngăn chặn
2. **Nút "Tổng kết" chỉ hiệu lực sau khi hoạt động kết thúc**
3. **Thời gian hiệu lực không thể quay ngược** - Thời gian kết thúc phải sau thời gian bắt đầu
4. **Mã QR phát lại sẽ vô hiệu hóa mã cũ** - Chỉ mã mới hoạt động
5. **Sinh viên chỉ có thể quét khi nằm trong khung thời gian hiệu lực**

---

## 🆘 Xử Lý Sự Cố

### Vấn đề: Mã QR không hoạt động
- **Nguyên nhân**: Nằm ngoài thời gian hiệu lực
- **Cách khắc phục**: Phát lại mã QR với thời gian phù hợp hơn

### Vấn đề: Sinh viên check-in nhưng quên check-out
- **Cách khắc phục**: Vào "Ghi nhận/Điều chỉnh Kết quả" → Đánh dấu "Chưa tổng kết" hoặc "Vắng" tùy tình huống

### Vấn đề: Mất mã QR hoặc cần tạo lại
- **Cách khắc phục**: Nhấp lại nút "Phát Mã QR" → Mã cũ sẽ bị vô hiệu hóa, mã mới sẽ được tạo

---

**Cần hỗ trợ thêm?** Liên hệ bộ phận IT hoặc kiểm tra tài liệu hướng dẫn chi tiết trong hệ thống.
