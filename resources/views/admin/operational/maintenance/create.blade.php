@extends('layouts.admin')

@section('title', 'Record Maintenance – Siliwangi Admin')

@section('styles')
<style>
    .form-premium-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        padding: 32px;
        box-shadow: var(--card-shadow);
    }
    .form-premium-label {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        margin-bottom: 8px;
        display: block;
    }
    .form-premium-input {
        width: 100%;
        background: rgba(0, 0, 0, 0.03);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 14px;
        color: var(--text-main);
        transition: all 0.2s;
    }
    [data-theme="dark"] .form-premium-input {
        background: rgba(255, 255, 255, 0.03);
    }
    .form-premium-input:focus {
        border-color: var(--secondary);
        box-shadow: 0 0 0 4px var(--secondary-glow);
        outline: none;
    }
    .info-box {
        background: var(--info-bg);
        border-left: 4px solid var(--info);
        padding: 20px;
        border-radius: 12px;
    }
    .info-box-title {
        font-size: 14px;
        font-weight: 800;
        color: var(--info-text);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .info-box-text {
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.6;
    }
    .btn-gold {
        background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
        color: #0F172A;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 14px 28px;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
    }
    .btn-gold:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
    }
    .btn-secondary-premium {
        background: rgba(0, 0, 0, 0.05);
        border: 1px solid var(--card-border);
        color: var(--text-muted);
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 14px 28px;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    [data-theme="dark"] .btn-secondary-premium {
        background: rgba(255, 255, 255, 0.05);
    }
    .btn-secondary-premium:hover {
        background: rgba(0, 0, 0, 0.1);
        color: var(--text-main);
    }
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 style="font-size: 24px; font-weight: 800; font-family: 'Poppins', sans-serif; color: var(--text-main); margin-bottom: 4px;">Record Maintenance</h1>
        <div style="font-size: 12px; color: var(--text-muted);">
            <span>Fleet Operations</span>
            <i class="fas fa-chevron-right" style="font-size: 8px; margin: 0 8px; opacity: 0.4;"></i>
            <span style="color: var(--secondary); font-weight: 700;">New Service Entry</span>
        </div>
    </div>
    <a href="{{ route('admin.maintenances.index') }}" class="btn-secondary-premium" style="padding: 10px 20px; font-size: 11px;">
        <i class="fas fa-arrow-left mr-2"></i> Back to List
    </a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px; align-items: start;">
    <div class="form-premium-card">
        <form action="{{ route('admin.maintenances.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <div>
                    <label class="form-premium-label">Vehicle <span style="color: var(--danger);">*</span></label>
                    <select name="car_id" class="form-premium-input @error('car_id') is-invalid @enderror" required>
                        <option value="">Select Vehicle</option>
                        @foreach($cars as $car)
                        <option value="{{ $car->id }}" {{ old('car_id') == $car->id ? 'selected' : '' }}>
                            {{ $car->car_name }} ({{ $car->plate_number }})
                        </option>
                        @endforeach
                    </select>
                    @error('car_id') <div style="color: var(--danger); font-size: 11px; margin-top: 4px;">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-premium-label">Branch <span style="color: var(--danger);">*</span></label>
                    <select name="branch_id" class="form-premium-input @error('branch_id') is-invalid @enderror" required>
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('branch_id') <div style="color: var(--danger); font-size: 11px; margin-top: 4px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <div>
                    <label class="form-premium-label">Start Date <span style="color: var(--danger);">*</span></label>
                    <input type="date" name="start_date" class="form-premium-input @error('start_date') is-invalid @enderror" value="{{ old('start_date', date('Y-m-d')) }}" required>
                    @error('start_date') <div style="color: var(--danger); font-size: 11px; margin-top: 4px;">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-premium-label">Estimated Completion</label>
                    <input type="date" name="end_date" class="form-premium-input @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                    @error('end_date') <div style="color: var(--danger); font-size: 11px; margin-top: 4px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <div>
                    <label class="form-premium-label">Maintenance Type <span style="color: var(--danger);">*</span></label>
                    <select name="maintenance_type" class="form-premium-input @error('maintenance_type') is-invalid @enderror" required>
                        <option value="Servis Rutin">Routine Service</option>
                        <option value="Perbaikan Mesin">Engine Repair</option>
                        <option value="Body Repair">Body Repair</option>
                        <option value="Ganti Ban">Tire Replacement</option>
                        <option value="Cuci & Detailing">Wash & Detailing</option>
                        <option value="Lainnya">Others</option>
                    </select>
                </div>
                <div>
                    <label class="form-premium-label">Estimated Cost (IDR)</label>
                    <input type="number" name="amount" class="form-premium-input" value="{{ old('amount', 0) }}">
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <label class="form-premium-label">Description / Issues</label>
                <textarea name="description" class="form-premium-input" rows="4" style="resize: none;">{{ old('description') }}</textarea>
            </div>

            <div style="margin-bottom: 32px;">
                <label class="form-premium-label">Receipt Attachment (Image/PDF)</label>
                <input type="file" name="attachment" class="form-premium-input">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--card-border); padding-top: 32px;">
                <button type="submit" class="btn-gold">
                    <i class="fas fa-save mr-2"></i> Save Record
                </button>
            </div>
        </form>
    </div>

    <div class="space-y-6">
        <div class="info-box">
            <div class="info-box-title">
                <i class="fas fa-info-circle"></i> Operational Note
            </div>
            <div class="info-box-text">
                Recording maintenance will automatically change the vehicle status to <strong>Maintenance</strong>. 
                The vehicle will be hidden from the available rental fleet until the maintenance status is marked as <strong>Completed</strong>.
            </div>
        </div>

        <div class="form-premium-card" style="padding: 24px;">
            <h4 style="font-size: 14px; font-weight: 800; color: var(--text-main); margin-bottom: 16px; text-transform: uppercase; letter-spacing: 1px;">Need Help?</h4>
            <p style="font-size: 12px; color: var(--text-muted); line-height: 1.6; margin-bottom: 0;">
                If you encounter any issues while recording maintenance, please contact the System Administrator or refer to the internal Standard Operating Procedure (SOP).
            </p>
        </div>
    </div>
</div>
@endsection
