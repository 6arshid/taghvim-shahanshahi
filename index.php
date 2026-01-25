<?php
// -------------------- Helpers --------------------
function toPersianDigits($str) {
    $en = ['0','1','2','3','4','5','6','7','8','9'];
    $fa = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
    return str_replace($en, $fa, $str);
}

function pad2($n) { return str_pad((string)$n, 2, '0', STR_PAD_LEFT); }

// الگوریتم تبدیل میلادی به شمسی (جلالی)
function gregorian_to_jalali($gy, $gm, $gd) {
    $g_d_m = [0,31,59,90,120,151,181,212,243,273,304,334];
    $gy2 = ($gm > 2) ? ($gy + 1) : $gy;

    $days = 355666
        + (365 * $gy)
        + (int)(($gy2 + 3) / 4)
        - (int)(($gy2 + 99) / 100)
        + (int)(($gy2 + 399) / 400)
        + $gd
        + $g_d_m[$gm - 1];

    $jy = -1595 + 33 * (int)($days / 12053);
    $days %= 12053;

    $jy += 4 * (int)($days / 1461);
    $days %= 1461;

    if ($days > 365) {
        $jy += (int)(($days - 1) / 365);
        $days = ($days - 1) % 365;
    }

    if ($days < 186) {
        $jm = 1 + (int)($days / 31);
        $jd = 1 + ($days % 31);
    } else {
        $jm = 7 + (int)(($days - 186) / 30);
        $jd = 1 + (($days - 186) % 30);
    }

    return [$jy, $jm, $jd];
}

// -------------------- Main --------------------
// تاریخ امروز (میلادی)
$gy = (int)date('Y');
$gm = (int)date('n');
$gd = (int)date('j');

// تبدیل به شمسی
[$jy, $jm, $jd] = gregorian_to_jalali($gy, $gm, $gd);

// شاهنشاهی = شمسی + 1180
$shahy = $jy + 1180;

// نام ماه‌ها و روزهای هفته
$months = [1=>'فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'];
$weekdays = ['یکشنبه','دوشنبه','سه‌شنبه','چهارشنبه','پنجشنبه','جمعه','شنبه'];

// روز هفته از PHP: 0=Sunday ... 6=Saturday
$w = (int)date('w');
$weekdayName = $weekdays[$w];

// خروجی‌ها
$line1 = "تاریخ شاهنشاهی";
$line2 = toPersianDigits(pad2($jd) . "/" . pad2($jm) . "/" . $shahy);
$line3 = $weekdayName . " - " . toPersianDigits(pad2($jd)) . " " . $months[$jm] . " " . toPersianDigits($shahy);
?>
<!doctype html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8">
<title>تقویم شاهنشاهی</title>
<style>
  body{font-family:tahoma, Arial; background:#f5f5f5; margin:0; padding:24px;}
  .card{max-width:420px; background:#fff; border-radius:14px; padding:18px 20px; box-shadow:0 6px 18px rgba(0,0,0,.08);}
  .title{font-size:18px; font-weight:700; margin-bottom:10px;}
  .date{font-size:28px; font-weight:800; letter-spacing:.5px; margin:8px 0 12px;}
  .sub{font-size:18px; color:#222;}
</style>
</head>
<body>
  <div class="card">
    <div class="title"><?= htmlspecialchars($line1, ENT_QUOTES, 'UTF-8') ?></div>
    <div class="date"><?= htmlspecialchars($line2, ENT_QUOTES, 'UTF-8') ?></div>
    <div class="sub"><?= htmlspecialchars($line3, ENT_QUOTES, 'UTF-8') ?></div>
  </div>
</body>
</html>
