<?php 
  const DATABASE_NAME = "test2";
  const TABLE_NAME = "sinhvien";
  const ACCOUNT_NAME = "root";
  const PASSWORD = "Lenghia0108@";
  const HOST = "localhost";
  


  const PRIMARY_FIELD_KEY = "masv";
  const PRIMARY_FIELD_VALUE = "Mã Sinh viên";


  const IMAGE_FIELD_KEY = "anh";
  const IMAGE_FIELD_VALUE = "Ảnh";


  $columns = [
    IMAGE_FIELD_KEY => IMAGE_FIELD_VALUE,
    PRIMARY_FIELD_KEY => PRIMARY_FIELD_VALUE,
    'hoten' => 'Tên sinh viên',
    'toan' => 'Toán',
    'ly' => 'Lý',
    'hoa' => 'Hóa',
    "tong" => "Tổng Điểm"
];

$calculatedFields = [
    'toan' => 'Toán',
    'ly' => 'Lý',
    'hoa' => 'Hóa',
];

$calculatedFieldKey = "tong";

$isCalculate = true;
    
?>