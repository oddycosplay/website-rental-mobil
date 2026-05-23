@extends('layouts.app')
@section('title', ($car->car_name ?? 'Vehicle Detail') . ' – Siliwangi Rental')
@section('styles')
<style>
.detail-hero{padding:110px 0 40px;background:linear-gradient(135deg,#0F172A,#1E293B);}
.detail-grid{display:grid;grid-template-columns:1fr 380px;gap:36px;align-items:start;padding:40px 0 80px;}
.car-gallery{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:20px;overflow:hidden;}
.gallery-main{aspect-ratio:16/9;background:linear-gradient(135deg,#1E293B,#162032);display:flex;align-items:center;justify-content:center;font-size:8rem;opacity:.15;color:#fff;overflow:hidden;}
.gallery-main img{width:100%;height:100%;object-fit:cover;opacity:1;}
.gallery-thumbs{display:flex;gap:10px;padding:14px;}
.thumb{flex:1;aspect-ratio:4/3;background:rgba(255,255,255,.06);border-radius:8px;border:1px solid rgba(255,255,255,.08);display:flex;align-items:center;justify-content:center;font-size:1.5rem;opacity:.3;cursor:pointer;transition:.3s;}
.thumb:hover,.thumb.active{border-color:#D4AF37;opacity:.7;}
.car-info-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:20px;padding:28px;margin-top:28px;}
.car-info-title{font-family:'Poppins',sans-serif;font-weight:800;font-size:1.8rem;margin-bottom:4px;}
.car-info-sub{color:#64748B;margin-bottom:20px;}
.car-info-rating{display:flex;align-items:center;gap:8px;margin-bottom:20px;}
.stars-big{color:#D4AF37;font-size:1.1rem;}
.specs-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px;}
.spec-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);border-radius:12px;padding:14px;text-align:center;}
.spec-icon{font-size:1.1rem;color:#D4AF37;margin-bottom:6px;}
.spec-val{font-weight:700;font-size:.85rem;margin-bottom:2px;}
.spec-lbl{font-size:.72rem;color:#64748B;}
.desc-text{color:#94A3B8;font-size:.9rem;line-height:1.75;}
/* Sidebar */
.booking-sidebar{position:sticky;top:88px;}
.sidebar-card{background:rgba(30,41,59,.95);border:1px solid rgba(212,175,55,.2);border-radius:20px;padding:24px;backdrop-filter:blur(20px);}
.sidebar-title{font-family:'Poppins',sans-serif;font-weight:700;margin-bottom:20px;color:#D4AF37;font-size:1rem;}
.price-daily-big{font-family:'Poppins',sans-serif;font-size:2rem;font-weight:900;color:#D4AF37;}
.price-monthly{color:#64748B;font-size:.82rem;margin-bottom:20px;}
.sb-form{display:flex;flex-direction:column;gap:14px;}
.sb-label{font-size:.73rem;font-weight:600;color:#94A3B8;letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:5px;}
.sb-input{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:11px 14px;color:#fff;font-size:.88rem;font-family:'Inter',sans-serif;width:100%;transition:.3s;}
.sb-input:focus{outline:none;border-color:#D4AF37;}
.driver-toggle{display:flex;gap:10px;}
.driver-opt{flex:1;padding:10px;border:1px solid rgba(255,255,255,.1);border-radius:8px;text-align:center;cursor:pointer;font-size:.8rem;font-weight:600;color:#64748B;transition:.3s;}
.driver-opt:hover,.driver-opt.active{border-color:#D4AF37;color:#D4AF37;background:rgba(212,175,55,.08);}
.promo-input{display:flex;gap:8px;}
.promo-field{flex:1;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:10px 14px;color:#fff;font-size:.85rem;font-family:'Inter',sans-serif;}
.promo-field:focus{outline:none;border-color:#D4AF37;}
.promo-btn{background:rgba(212,175,55,.15);border:1px solid rgba(212,175,55,.3);color:#D4AF37;border-radius:8px;padding:10px 14px;font-size:.82rem;font-weight:700;cursor:pointer;transition:.3s;}
.promo-btn:hover{background:rgba(212,175,55,.25);}
.summary-box{background:rgba(255,255,255,.04);border-radius:10px;padding:14px;font-size:.83rem;}
.summary-row{display:flex;justify-content:space-between;padding:4px 0;color:#94A3B8;}
.summary-row.total{color:#fff;font-weight:700;border-top:1px solid rgba(255,255,255,.08);margin-top:8px;padding-top:10px;font-size:.95rem;}
.summary-row.total span:last-child{color:#D4AF37;}
.book-btn{width:100%;padding:14px;border-radius:12px;background:linear-gradient(135deg,#D4AF37,#A88B1D);color:#0F172A;font-weight:800;font-size:1rem;cursor:pointer;border:none;font-family:'Poppins',sans-serif;transition:all .3s;margin-top:4px;display:flex;align-items:center;justify-content:center;gap:10px;text-decoration:none;}
.book-btn:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(212,175,55,.4);}
.wa-book{display:flex;align-items:center;justify-content:center;gap:8px;margin-top:10px;padding:11px;border-radius:10px;background:rgba(37,211,102,.12);border:1px solid rgba(37,211,102,.25);color:#25D366;font-weight:600;font-size:.85rem;transition:.3s;text-decoration:none;}
.wa-book:hover{background:rgba(37,211,102,.2);}
@media(max-width:1024px){.detail-grid{grid-template-columns:1fr;}.booking-sidebar{position:static;}}
@media(max-width:640px){.specs-grid{grid-template-columns:repeat(2,1fr);}}
</style>
@endsection

@section('content')
<div class="detail-hero">
  <div class="container mx-auto px-4">
    <div style="display:flex;align-items:center;gap:8px;color:#64748B;font-size:.85rem;margin-bottom:20px;">
      <a href="{{ url('/') }}" style="color:#64748B;transition:.2s;" onmouseover="this.style.color='#D4AF37'" onmouseout="this.style.color='#64748B'">Home</a>
      <i class="fas fa-chevron-right" style="font-size:.65rem;"></i>
      <a href="{{ url('/cars') }}" style="color:#64748B;transition:.2s;" onmouseover="this.style.color='#D4AF37'" onmouseout="this.style.color='#64748B'">Fleet</a>
      <i class="fas fa-chevron-right" style="font-size:.65rem;"></i>
      <span style="color:#D4AF37;">{{ $car->car_name }}</span>
    </div>
  </div>
</div>

<div class="container mx-auto px-4">
  <div class="detail-grid">
    <!-- LEFT -->
    <div>
      <div class="car-gallery">
        <div class="gallery-main">
            @if($car->thumbnail)
                <img src="{{ Storage::url($car->thumbnail) }}" alt="{{ $car->car_name }}">
            @else
                <i class="fas fa-car"></i>
            @endif
        </div>
        <div class="gallery-thumbs">
            @if($car->thumbnail)
                <div class="thumb active"><img src="{{ Storage::url($car->thumbnail) }}" class="w-full h-full object-cover rounded"></div>
            @endif
            @for($i=0;$i<3;$i++)<div class="thumb"><i class="fas fa-car"></i></div>@endfor
        </div>
      </div>
      <div class="car-info-card">
        <h1 class="car-info-title">{{ $car->car_name }}</h1>
        <div class="car-info-sub">{{ $car->brand->name ?? 'Luxury' }} · {{ $car->type->name ?? 'Premium' }} · {{ $car->year ?? '2023' }}</div>
        <div class="car-info-rating">
          <span class="stars-big">★★★★★</span>
          <span style="font-weight:700">4.9</span>
          <span style="color:#64748B;font-size:.82rem">(128 reviews)</span>
          <span class="badge {{ $car->status == 'available' ? 'badge-emerald' : 'badge-red' }}">
              <i class="fas fa-circle" style="font-size:.5rem"></i> {{ ucfirst($car->status) }}
          </span>
        </div>
        <div class="specs-grid">
          @php $sp=[
              ['fas fa-cog', $car->transmission, 'Transmission'],
              ['fas fa-gas-pump', $car->fuel_type, 'Fuel'],
              ['fas fa-users', $car->seat . ' Seats', 'Capacity'],
              ['fas fa-road', $car->year ?? '2023', 'Year'],
              ['fas fa-tachometer-alt', '150 PS', 'Power'],
              ['fas fa-snowflake', 'Dual Zone AC', 'Cooling']
          ]; @endphp
          @foreach($sp as $s)
          <div class="spec-card">
            <div class="spec-icon"><i class="{{ $s[0] }}"></i></div>
            <div class="spec-val">{{ $s[1] }}</div>
            <div class="spec-lbl">{{ $s[2] }}</div>
          </div>
          @endforeach
        </div>
        <h3 style="margin-bottom:12px;font-family:'Poppins',sans-serif; color: white;">Vehicle Description</h3>
        <p class="desc-text">{{ $car->description ?: 'This vehicle offers a premium driving experience with state-of-the-art features and unmatched comfort. Perfect for both business trips and family vacations.' }}</p>
        <p class="desc-text" style="margin-top:12px;">Our fleet is always in prime condition, professionally cleaned before handover, and ready for pickup or delivery to your location.</p>
      </div>
    </div>

    <!-- SIDEBAR -->
    <div class="booking-sidebar">
      <div class="sidebar-card">
        <div class="sidebar-title"><i class="fas fa-calendar-check"></i> Booking Summary</div>
        <div class="price-daily-big">Rp {{ number_format($car->daily_price, 0, ',', '.') }}</div>
        <div class="price-monthly">per day · Rp {{ number_format($car->daily_price * 22, 0, ',', '.') }} / month</div>
        
        <div class="sb-form">
          <div class="summary-box">
            <p class="text-slate-400 text-xs mb-3">Instant booking is available through our streamlined checkout process.</p>
            <div class="summary-row"><span>Base Rental</span><span>Rp {{ number_format($car->daily_price, 0, ',', '.') }}</span></div>
            <div class="summary-row"><span>Driver Service</span><span>Optional</span></div>
            <div class="summary-row total"><span>Est. Total (1 Day)</span><span>Rp {{ number_format($car->daily_price, 0, ',', '.') }}</span></div>
          </div>

          @if($car->status == 'available')
          <a href="{{ route('checkout', $car->slug) }}" class="book-btn">
              <i class="fas fa-credit-card"></i> Book & Pay Now
          </a>
          @else
          <button disabled class="book-btn opacity-50 cursor-not-allowed">
              <i class="fas fa-times-circle"></i> Currently Rented
          </button>
          @endif
          
          <a href="https://wa.me/6281234567890?text=Hello,%20I%20am%20interested%20in%20renting%20the%20{{ urlencode($car->car_name) }}" target="_blank" class="wa-book">
              <i class="fab fa-whatsapp fa-lg"></i> Inquire via WhatsApp
          </a>
        </div>
        
        <div style="margin-top:16px;display:flex;align-items:center;justify-content:center;gap:8px;">
          <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/ee/Midtrans-logo.png/200px-Midtrans-logo.png" alt="Midtrans" style="height:22px;filter:grayscale(1) brightness(1.5);opacity:.5;">
          <span style="color:#475569;font-size:.75rem;">Payment secured by Midtrans</span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
