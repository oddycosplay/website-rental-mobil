@extends('layouts.sbadmin')

@section('title', 'Live Tracking Armada')

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 600px; border-radius: 15px; border: 4px solid white; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    .car-marker { text-align: center; }
    .car-marker i { font-size: 24px; color: #1e293b; background: #D4AF37; padding: 10px; border-radius: 50%; border: 3px solid white; box-shadow: 0 5px 15px rgba(212,175,55,0.4); transition: all 0.3s; }
    .car-marker:hover i { transform: scale(1.2); background: #1e293b; color: #D4AF37; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Live Tracking Armada</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Live Tracking</li>
    </ol>

    <div class="row">
        <div class="col-xl-9">
            <div id="map"></div>
        </div>
        <div class="col-xl-3">
            <div class="card shadow border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-slate-900 text-white font-weight-bold">
                    <i class="fas fa-list me-1"></i> Daftar Unit Aktif
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" id="car-list">
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                            <p>Mencari sinyal GPS...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    var map = L.map('map').setView([-6.200000, 106.816666], 12); // Jakarta Default

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var markers = {};
    var carList = document.getElementById('car-list');

    function updateTracking() {
        fetch('/api/v1/tracking/latest')
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) carList.innerHTML = '';
                
                data.forEach(car => {
                    var latlng = [car.lat, car.lng];
                    
                    if (markers[car.id]) {
                        markers[car.id].setLatLng(latlng);
                    } else {
                        var customIcon = L.divIcon({
                            className: 'car-marker',
                            html: '<i class="fas fa-car-side"></i>',
                            iconSize: [40, 40],
                            iconAnchor: [20, 20]
                        });

                        markers[car.id] = L.marker(latlng, { icon: customIcon })
                            .addTo(map)
                            .bindPopup(`<b>${car.name}</b><br>${car.plate}<br>Speed: ${car.speed} km/h<br>Last seen: ${car.updated_at}`);
                    }

                    // Update List
                    var item = document.createElement('div');
                    item.className = 'list-group-item list-group-item-action p-3';
                    item.style.cursor = 'pointer';
                    item.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold text-slate-900">${car.name}</div>
                                <div class="small text-muted">${car.plate}</div>
                            </div>
                            <span class="badge bg-gold text-slate-900 font-black">${car.speed} km/h</span>
                        </div>
                        <div class="small text-muted mt-1"><i class="fas fa-clock me-1"></i> ${car.updated_at}</div>
                    `;
                    item.onclick = function() {
                        map.setView(latlng, 15);
                        markers[car.id].openPopup();
                    };
                    carList.appendChild(item);
                });
            });
    }

    updateTracking();
    setInterval(updateTracking, 10000); // Update every 10 seconds
</script>
@endpush
