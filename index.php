<!doctype html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>تقویم شاهنشاهی</title>
  <style>
    body{
      margin:0;
      padding:24px;
      background:#f5f5f5;
      font-family: Tahoma, Arial, sans-serif;
    }
    .card{
      max-width:420px;
      background:#fff;
      border-radius:14px;
      padding:18px 20px;
      box-shadow:0 6px 18px rgba(0,0,0,.08);
    }
    .title{
      font-size:18px;
      font-weight:700;
      margin-bottom:10px;
    }
    .date{
      font-size:28px;
      font-weight:800;
      letter-spacing:.5px;
      margin:8px 0 12px;
    }
    .sub{
      font-size:18px;
      color:#222;
      line-height:1.8;
    }
    .muted{
      margin-top:10px;
      font-size:13px;
      color:#666;
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="title">تاریخ شاهنشاهی</div>
    <div id="line2" class="date">--/--/----</div>
    <div id="line3" class="sub">---</div>
    <div class="muted">بر اساس تاریخ دستگاه شما</div>
  </div>

  <script>
    // ---------- Helpers ----------
    const toPersianDigits = (s) => String(s).replace(/\d/g, d => "۰۱۲۳۴۵۶۷۸۹"[d]);
    const pad2 = (n) => String(n).padStart(2, "0");

    const months = [
      "", "فروردین","اردیبهشت","خرداد","تیر","مرداد","شهریور",
      "مهر","آبان","آذر","دی","بهمن","اسفند"
    ];
    // JS getDay(): 0=Sunday ... 6=Saturday
    const weekdays = ["یکشنبه","دوشنبه","سه‌شنبه","چهارشنبه","پنجشنبه","جمعه","شنبه"];

    // ---------- Gregorian -> Jalali (Jalaali) ----------
    function gregorianToJalali(gy, gm, gd) {
      // gm: 1..12
      const g_d_m = [0,31,59,90,120,151,181,212,243,273,304,334];
      const gy2 = (gm > 2) ? (gy + 1) : gy;

      let days = 355666
        + (365 * gy)
        + Math.floor((gy2 + 3) / 4)
        - Math.floor((gy2 + 99) / 100)
        + Math.floor((gy2 + 399) / 400)
        + gd
        + g_d_m[gm - 1];

      let jy = -1595 + 33 * Math.floor(days / 12053);
      days %= 12053;

      jy += 4 * Math.floor(days / 1461);
      days %= 1461;

      if (days > 365) {
        jy += Math.floor((days - 1) / 365);
        days = (days - 1) % 365;
      }

      let jm, jd;
      if (days < 186) {
        jm = 1 + Math.floor(days / 31);
        jd = 1 + (days % 31);
      } else {
        jm = 7 + Math.floor((days - 186) / 30);
        jd = 1 + ((days - 186) % 30);
      }
      return { jy, jm, jd };
    }

    // ---------- Render ----------
    function renderForDate(dateObj) {
      const gy = dateObj.getFullYear();
      const gm = dateObj.getMonth() + 1; // 1..12
      const gd = dateObj.getDate();

      const { jy, jm, jd } = gregorianToJalali(gy, gm, gd);

      const shahYear = jy + 1180;
      const weekdayName = weekdays[dateObj.getDay()];

      const line2 = `${pad2(jd)}/${pad2(jm)}/${shahYear}`;
      const line3 = `${weekdayName}- ${pad2(jd)} ${months[jm]} ${shahYear}`;

      document.getElementById("line2").textContent = toPersianDigits(line2);
      document.getElementById("line3").textContent = toPersianDigits(line3);
    }

    renderForDate(new Date());
  </script>
</body>
</html>
