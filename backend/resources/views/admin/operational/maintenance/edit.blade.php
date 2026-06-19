@extends('layouts.admin')

@section('title', 'Update Maintenance – Siliwangi Admin')

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
    .form-premium-input:disabled {
        background: rgba(0, 0, 0, 0.05);
        opacity: 0.7;
        cursor: not-allowed;
    }
    [data-theme="dark"] .form-premium-input:disabled {
        background: rgba(255, 255, 255, 0.05);
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
        <h1 style="font-size: 24px; font-weight: 800; font-family: 'Poppins', sans-serif; color: var(--text-main); margin-bottom: 4px;">Update Maintenance</h1>
        <div style="font-size: 12px; color: var(--text-muted);">
            <span>Car Operations</span>
            <i class="fas fa-chevron-right" style="font-size: 8px; margin: 0 8px; opacity: 0.4;"></i>
            <span style="color: var(--secondary); font-weight: 700;">Update Record #{{ $maintenance->id }}</span>
        </div>
    </div>
    <a href="{{ route('admin.maintenances.index') }}" class="btn-secondary-premium" style="padding: 10px 20px; font-size: 11px;">
        <i class="fas fa-arrow-left mr-2"></i> Back to List
    </a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px; align-items: start;">
    <div class="form-premium-card">
        <form action="{{ route('admin.maintenances.update', $maintenance->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 24px;">
                <label class="form-premium-label">Vehicle</label>
                <input type="text" class="form-premium-input" value="{{ $maintenance->car->car_name }} ({{ $maintenance->car->plate_number }})" disabled>
                <input type="hidden" name="car_id" value="{{ $maintenance->car_id }}">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <div>
                    <label class="form-premium-label">Status <span style="color: var(--danger);">*</span></label>
                    <select name="status" class="form-premium-input" required>
                        <option value="scheduled" {{ $maintenance->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="in_progress" {{ $maintenance->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ $maintenance->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div>
                    <label class="form-premium-label">Service Type</label>
                    <input type="text" class="form-premium-input" value="{{ $maintenance->maintenance_type }}" disabled>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <div>
                    <label class="form-premium-label">Real Completion Date</label>
                    <input type="date" name="end_date" class="form-premium-input" value="{{ $maintenance->end_date ? $maintenance->end_date->format('Y-m-d') : date('Y-m-d') }}">
                </div>
                <div>
                    <label class="form-premium-label">Final Cost (IDR)</label>
                    <input type="number" name="amount" class="form-premium-input" value="{{ $maintenance->cost }}">
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <label class="form-premium-label">Additional Notes</label>
                <textarea name="description" class="form-premium-input" rows="4" style="resize: none;">{{ $maintenance->description }}</textarea>
            </div>

            <div style="margin-bottom: 32px;">
                <label class="form-premium-label">New Receipt Attachment (Optional)</label>
                <input type="file" name="attachment" class="form-premium-input">
                @if($maintenance->attachment)
                <div style="margin-top: 12px; font-size: 11px; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-paperclip" style="color: var(--secondary);"></i> 
                    Current Attachment: <a href="{{ Storage::url($maintenance->attachment) }}" target="_blank" style="color: var(--secondary); font-weight: 700; text-decoration: none;">View File</a>
                </div>
                @endif
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--card-border); padding-top: 32px;">
                <button type="submit" class="btn-gold">
                    <i class="fas fa-sync-alt mr-2"></i> Update Record
                </button>
            </div>
        </form>
    </div>

    <div class="space-y-6">
        <div class="info-box">
            <div class="info-box-title">
                <i class="fas fa-info-circle"></i> Status Flow
            </div>
            <div class="info-box-text">
                If you change the status to <strong>Completed</strong>, the system will automatically revert the vehicle status to <strong>Available</strong>.
                Ensure all final costs and receipts are accurate before closing the record.
            </div>
        </div>
        
        <div class="form-premium-card" style="padding: 24px; border-color: var(--info);">
            <h4 style="font-size: 12px; font-weight: 800; color: var(--info-text); margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px;">Service Information</h4>
            <div style="font-size: 11px; color: var(--text-muted);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span>Created At</span>
                    <span style="color: var(--text-main); font-weight: 600;">{{ $maintenance->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Last Updated</span>
                    <span style="color: var(--text-main); font-weight: 600;">{{ $maintenance->updated_at->format('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
