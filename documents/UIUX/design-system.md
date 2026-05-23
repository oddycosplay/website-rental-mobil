# Design System — Siliwangi Rental

**Nama File:** `design-system.md`  
**Lokasi:** `documents/UIUX/`  
**Tujuan:** Token desain, komponen, dan panduan Tailwind CSS untuk konsistensi UI sistem.

---

## 1. Tailwind Config

```js
// tailwind.config.js
module.exports = {
  content: ['./resources/**/*.blade.php', './resources/**/*.js'],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
      colors: {
        primary: {
          50: '#EFF6FF',
          100: '#DBEAFE',
          500: '#3B82F6',
          600: '#2563EB',
          700: '#1D4ED8',
        },
        brand: {
          DEFAULT: '#1D4ED8',
          dark: '#1E3A8A',
          light: '#60A5FA',
        }
      },
      borderRadius: {
        '2xl': '1rem',
        '3xl': '1.5rem',
      },
    },
  },
}
```

---

## 2. Design Tokens

### Spacing Scale

 | Token | Value | Usage |
|---|---|---|
 | `space-2` | 0.5rem | Padding kecil, gap |
 | `space-4` | 1rem | Padding standar |
 | `space-6` | 1.5rem | Card padding |
 | `space-8` | 2rem | Section gap |
 | `space-16` | 4rem | Section spacing |
 | `space-24` | 6rem | Hero padding |

### Shadow Scale

 | Token | Usage |
|---|---|
 | `shadow-sm` | Input field |
 | `shadow-md` | Card |
 | `shadow-lg` | Dropdown, modal |
 | `shadow-xl` | Hero card, featured |

---

## 3. Component Specs

### Car Card

```html
<div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
  <!-- Gambar -->
  <div class="relative">
    <img class="w-full h-48 object-cover" />
    <!-- Badge status -->
    <span class="absolute top-3 right-3 bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
      Tersedia
    </span>
    <!-- Badge featured -->
    <span class="absolute top-3 left-3 bg-amber-400 text-white text-xs px-2 py-1 rounded-full">
      ⭐ Unggulan
    </span>
  </div>
  <!-- Info -->
  <div class="p-5">
    <h3 class="font-semibold text-gray-900 text-lg">Toyota Avanza 2023</h3>
    <p class="text-sm text-gray-500 mb-3">MPV · 7 Kursi · Matic</p>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-blue-600 font-bold text-xl">Rp 350.000</span>
        <span class="text-gray-400 text-sm">/hari</span>
      </div>
    </div>
    <!-- Buttons -->
    <div class="flex gap-2 mt-4">
      <a href="/cars/{slug}" class="flex-1 text-center border border-blue-600 text-blue-600 py-2 rounded-lg text-sm hover:bg-blue-50">
        Detail
      </a>
      <a href="/checkout/{slug}" class="flex-1 text-center bg-blue-600 text-white py-2 rounded-lg text-sm hover:bg-blue-700">
        Booking
      </a>
    </div>
  </div>
</div>
```

### Status Badge Colors

 | Status | Classes |
|---|---|
 | Available | `bg-green-100 text-green-800` |
 | Booked | `bg-yellow-100 text-yellow-800` |
 | On Rent | `bg-blue-100 text-blue-800` |
 | Maintenance | `bg-red-100 text-red-800` |
 | Completed | `bg-gray-100 text-gray-600` |
 | Pending | `bg-orange-100 text-orange-800` |
 | Confirmed | `bg-cyan-100 text-cyan-800` |
 | Cancelled | `bg-red-100 text-red-800` |
 | Expired | `bg-gray-100 text-gray-500` |

### Form Elements

```html
<!-- Input Standard -->
<input class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 
              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
              placeholder-gray-400 text-sm" />

<!-- Select -->
<select class="w-full border border-gray-300 rounded-lg px-4 py-3 
               focus:ring-2 focus:ring-blue-500 bg-white text-sm"></select>

<!-- Textarea -->
<textarea class="w-full border border-gray-300 rounded-lg px-4 py-3 
                 focus:ring-2 focus:ring-blue-500 resize-none text-sm" rows="4"></textarea>
```

### Buttons

```html
<!-- Primary -->
<button class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-lg transition-colors duration-200">
  Booking Sekarang
</button>

<!-- Outline -->
<button class="border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-medium px-6 py-3 rounded-lg transition-colors duration-200">
  Lihat Detail
</button>

<!-- Danger -->
<button class="bg-red-600 hover:bg-red-700 text-white font-medium px-6 py-3 rounded-lg transition-colors duration-200">
  Batalkan Booking
</button>

<!-- Secondary -->
<button class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-6 py-3 rounded-lg transition-colors duration-200">
  Kembali
</button>
```

---

## 4. Alert Components (Alpine.js)

```html
<!-- Success Alert -->
<div x-data="{ show: true }" x-show="show" x-transition
     class="flex items-start gap-3 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
  <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0">...</svg>
  <div class="flex-1">
    <p class="text-green-800 text-sm font-medium">Booking berhasil dibuat!</p>
  </div>
  <button @click="show = false" class="text-green-400 hover:text-green-600">✕</button>
</div>
```

---

Versi: 1.0.0 | Tanggal: 2026-05-14
