@extends('layouts.app')
@section('title','Vehicle Car – Siliwangi Rental')
@section('description','Choose a premium vehicle according to your needs. SUV, Sedan, MPV, Minibus available.')

@section('styles')
<style>
.page-hero{padding:140px 0 60px;background:linear-gradient(135deg,#0F172A 0%,#162032 60%,#1E293B 100%);border-bottom:1px solid rgba(212,175,55,.12);}
.page-hero-inner{text-align:center;}
.filter-bar{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:16px;padding:20px 24px;margin-bottom:40px;display:flex;gap:12px;flex-wrap:wrap;align-items:center;}
.filter-select{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:10px 14px;color:#fff;font-size:.85rem;font-family:'Inter',sans-serif;min-width:160px;}
.filter-select:focus{outline:none;border-color:#D4AF37;}
.filter-select option{background:#1E293B;}
.filter-search{flex:1;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:10px 14px;color:#fff;font-size:.85rem;font-family:'Inter',sans-serif;min-width:200px;}
.filter-search:focus{outline:none;border-color:#D4AF37;}
.filter-search::placeholder{color:#64748B;}
.cars-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:28px;}
.car-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:20px;overflow:hidden;transition:all .35s;}
.car-card:hover{border-color:rgba(212,175,55,.3);transform:translateY(-6px);box-shadow:0 20px 50px rgba(0,0,0,.4);}
.car-img-wrap{aspect-ratio:16/9;overflow:hidden;background:linear-gradient(135deg,#1E293B,#162032);display:flex;align-items:center;justify-content:center;position:relative;}
.car-img-icon{font-size:5rem;opacity:.15;color:#fff;}
.car-type-badge{position:absolute;top:12px;left:12px;background:rgba(15,23,42,.85);border:1px solid rgba(212,175,55,.3);color:#D4AF37;font-size:.68rem;font-weight:700;padding:4px 10px;border-radius:100px;letter-spacing:.5px;text-transform:uppercase;}
.avail-badge{position:absolute;top:12px;right:12px;background:rgba(16,185,129,.15);border:1px solid rgba(16,185,129,.3);color:#10B981;font-size:.68rem;font-weight:700;padding:4px 10px;border-radius:100px;}
.car-body{padding:20px;}
.car-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;}
.car-name{font-family:'Poppins',sans-serif;font-weight:700;font-size:1rem;}
.car-brand{color:#64748B;font-size:.78rem;}
.car-rating{display:flex;align-items:center;gap:4px;font-size:.8rem;color:#D4AF37;}
.car-specs{display:flex;gap:12px;margin-bottom:16px;flex-wrap:wrap;}
.spec{display:flex;align-items:center;gap:5px;font-size:.76rem;color:#94A3B8;}
.spec i{color:#D4AF37;font-size:.72rem;}
.car-footer{display:flex;justify-content:space-between;align-items:center;padding-top:14px;border-top:1px solid rgba(255,255,255,.06);}
.price-wrap .daily{font-family:'Poppins',sans-serif;font-size:1.1rem;font-weight:800;color:#D4AF37;}
.price-wrap small{color:#64748B;font-size:.72rem;}
.btn-view{padding:8px 18px;border-radius:8px;background:linear-gradient(135deg,#D4AF37,#A88B1D);color:#0F172A;font-size:.78rem;font-weight:700;transition:all .3s;display:inline-block;}
.btn-view:hover{transform:scale(1.04);}
@media(max-width:1024px){.cars-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:640px){.cars-grid{grid-template-columns:1fr;}.filter-bar{flex-direction:column;}.filter-select,.filter-search{min-width:100%;width:100%;}}
</style>
@endsection

@section('content')
<section class="page-hero">
  <div class="container">
    <div class="page-hero-inner">
      <span class="section-tag">Premium Car</span>
      <h1 class="section-title">Choose Your <span class="text-gold">Dream</span> Vehicle</h1>
      <p class="section-sub" style="margin:0 auto;text-align:center;">Hundreds of premium vehicles ready for use — SUV, Sedan, MPV, to Minibus for all your travel needs.</p>
    </div>
  </div>
</section>

<section style="padding:40px 0 80px;">
  <div class="container">
    <!-- Filter -->
    <div class="filter-bar">
      <input type="text" class="filter-search" placeholder="🔍 Search vehicle name...">
      <select class="filter-select">
          <option value="">All Types</option>
          <option>SUV</option>
          <option>Sedan</option>
          <option>MPV</option>
          <option>City Car</option>
          <option>Minibus</option>
      </select>
      <select class="filter-select">
          <option value="">All Transmissions</option>
          <option>Automatic</option>
          <option>Manual</option>
      </select>
      <select class="filter-select">
          <option value="">Capacity</option>
          <option>4-5 Seats</option>
          <option>6-7 Seats</option>
          <option>8+ Seats</option>
      </select>
      <select class="filter-select">
          <option value="">Price</option>
          <option>Under 500K</option>
          <option>500K – 1 Million</option>
          <option>Above 1 Million</option>
      </select>
    </div>

    <!-- Grid -->
    <div class="cars-grid">
      @php 
        $cars = \App\Models\Car::with(['brand', 'type'])->where('is_available', true)->get();
      @endphp
      
      @forelse($cars as $i => $car)
      <div class="car-card reveal" style="animation-delay:{{ $i*0.07 }}s">
        <div class="car-img-wrap">
          @if($car->thumbnail)
            <img src="{{ Storage::url($car->thumbnail) }}" alt="{{ $car->car_name }}" class="w-full h-full object-cover">
          @else
            <div class="car-img-icon"><i class="fas fa-car-side"></i></div>
          @endif
          <div class="car-type-badge">{{ $car->type->name ?? 'Premium' }}</div>
          <div class="avail-badge">● Available</div>
        </div>
        <div class="car-body">
          <div class="car-header">
            <div>
                <div class="car-name">{{ $car->car_name }}</div>
                <div class="car-brand">{{ $car->brand->name ?? 'Luxury' }}</div>
            </div>
            <div class="car-rating">★ 4.9</div>
          </div>
          <div class="car-specs">
            <div class="spec"><i class="fas fa-cog"></i> {{ $car->transmission }}</div>
            <div class="spec"><i class="fas fa-gas-pump"></i> {{ $car->fuel_type }}</div>
            <div class="spec"><i class="fas fa-users"></i> {{ $car->seat }} Seats</div>
          </div>
          <div class="car-footer">
            <div class="price-wrap">
              <div class="daily">Rp {{ number_format($car->daily_price, 0, ',', '.') }}</div>
              <small>per day</small>
            </div>
            <a href="{{ route('cars.show', $car->slug) }}" class="btn-view">View Details</a>
          </div>
        </div>
      </div>
      @empty
      <div class="col-span-full text-center py-24 bg-slate-800/20 rounded-[3rem] border border-dashed border-white/10">
          <i class="fas fa-car-side text-6xl text-slate-700 mb-6"></i>
          <h3 class="text-xl font-bold text-slate-400">Car Currently Unavailable</h3>
          <p class="text-slate-600 mt-2">Please contact admin via WhatsApp for more information.</p>
      </div>
      @endforelse
    </div>
  </div>
</section>
@endsection
