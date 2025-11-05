<?php

return [
    'disable' => env('CAPTCHA_DISABLE', false),
    'characters' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
        'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd',
        'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
        't', 'u', 'v', 'w', 'x', 'y', 'z', 0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
    'fontsDirectory' => dirname(__DIR__) . '/assets/fonts',
    'bgsDirectory' => dirname(__DIR__) . '/assets/backgrounds',
    'default' => [
        'length' => 5,              // Số ký tự
        'width' => 120,             // Chiều rộng
        'height' => 36,             // Chiều cao
        'quality' => 90,            // Chất lượng
        'math' => false,            // Dùng phép tính (true) hay chữ (false)
        'expire' => 60,             // Thời gian hết hạn (phút)
        'encrypt' => false,         // Mã hóa
        
        // Tùy chỉnh giao diện
        'bgImage' => false,
        'bgColor' => '#ecf2f4',
        'fontColors' => ['#2c3e50', '#c0392b', '#16a085', '#c0392b', '#8e44ad'],
        'lines' => 3,               // Số đường kẻ nhiễu
        'invert' => false,
        'contrast' => -5,
        'sensitive' => false,       // Phân biệt hoa thường
    ],
    'flat' => [
        'length' => 6,
        'fontColors' => ['#2c3e50', '#c0392b', '#16a085', '#c0392b', '#8e44ad', '#303f9f', '#f57c00', '#795548'],
        'width' => 345,
        'height' => 65,
        'math' => false,
        'quality' => 100,
        'lines' => 6,
        'bgImage' => true,
        'bgColor' => '#28faef',
        'contrast' => 0,
    ],
    'mini' => [
        'length' => 3,
        'width' => 60,
        'height' => 32,
    ],
    'inverse' => [
        'length' => 5,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'sensitive' => true,
        'angle' => 12,
        'sharpen' => 10,
        'blur' => 2,
        'invert' => false,
        'contrast' => -5,
    ],
    'math' => [
        'length' => 9,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
    ],
];
