<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>@yield('title', 'الفة')</title>

<link rel="preconnect" href="https://fonts.googleapis.com" />
<link href="https://fonts.googleapis.com/css2?family=Aref+Ruqaa:wght@400;700&family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

{{-- ملاحظة: هذا الـ CDN مناسب للتطوير فقط. قبل الإطلاق الفعلي، ثبّت
     Tailwind عبر Vite (npm install -D tailwindcss ثم شغّل npm run build)
     بدل تحميله من الإنترنت وقت التشغيل. --}}
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          emerald: { 950: "#0E2B23", 900: "#123D2E", 800: "#1B4F3C", 700: "#256349" },
          sand: { 50: "#FBF8EF", 100: "#F6F1E0", 200: "#ECE2C6" },
          bronze: { 400: "#C99A46", 500: "#B8863B", 600: "#9C6F2E" },
          ink: "#1B2621",
        },
        fontFamily: {
          display: ["'Aref Ruqaa'", "serif"],
          body: ["'IBM Plex Sans Arabic'", "sans-serif"],
        },
      },
    },
  };
</script>

<style>
  body { font-family: "IBM Plex Sans Arabic", sans-serif; background-color: #FBF8EF; color: #1B2621; }
  .font-display { font-family: "Aref Ruqaa", serif; }
  .geo-pattern {
    background-image: radial-gradient(circle at 1px 1px, rgba(201, 154, 70, 0.25) 1.5px, transparent 0);
    background-size: 28px 28px;
  }
  :focus-visible { outline: 2px solid #B8863B; outline-offset: 2px; }
  .field-input {
    width: 100%; border-radius: 0.5rem; border: 1px solid rgba(18,61,46,0.15);
    background-color: rgba(251,248,239,0.4); padding: 0.625rem 1rem; outline: none;
  }
  .field-input:focus { border-color: #B8863B; }
</style>
@stack('styles')
</head>
<body class="bg-sand-50">

@yield('content')

@stack('scripts')
</body>
</html>
