@extends('layouts.app')

@section('title', 'Daftarkan Pengguna Baru')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="bi bi-person-plus"></i> Pendaftaran Akun oleh Admin
                    <small class="ms-2">SRS-001</small>
                </h4>
                <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <form method="POST" action="{{ route('admin.users.store') }}">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nama Lengkap *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="Nama lengkap penghuni" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" 
                                           placeholder="email@urbantown.com" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Nomor Telepon</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}" 
                                           placeholder="0812-3456-7890">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="unit_number" class="form-label">Nomor Unit</label>
                                    <input type="text" class="form-control @error('unit_number') is-invalid @enderror" 
                                           id="unit_number" name="unit_number" value="{{ old('unit_number') }}" 
                                           placeholder="Tower A-1001">
                                    @error('unit_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="role" class="form-label">Role Pengguna *</label>
                                <select class="form-control @error('role') is-invalid @enderror" 
                                        id="role" name="role" required>
                                    <option value="">-- Pilih Role --</option>
                                    <option value="penghuni" {{ old('role') == 'penghuni' ? 'selected' : '' }}>
                                        Penghuni
                                    </option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                        Admin
                                    </option>
                                    <option value="tr" {{ old('role') == 'tr' ? 'selected' : '' }}>
                                        Technical (TR)
                                    </option>
                                    <option value="dept" {{ old('role') == 'dept' ? 'selected' : '' }}>
                                        Departemen
                                    </option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Department field (shown only when role is 'dept') -->
                            <div class="mb-3" id="departmentField" style="display: none;">
                                <label for="department_id" class="form-label">Departemen</label>
                                <select class="form-control @error('department_id') is-invalid @enderror" 
                                        id="department_id" name="department_id">
                                    <option value="">-- Pilih Departemen --</option>
                                    <option value="1">Housekeeping</option>
                                    <option value="2">Engineering</option>
                                    <option value="3">Security</option>
                                    <option value="4">Gardening</option>
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="alert alert-info">
                                <h6><i class="bi bi-info-circle"></i> Proses Pendaftaran</h6>
                                <p class="mb-0">
                                    1. Admin mengisi data pengguna<br>
                                    2. Sistem mengirim email aktivasi ke pengguna<br>
                                    3. Pengguna set password via link aktivasi (SRS-002)<br>
                                    4. Pengguna dapat login (SRS-003)
                                </p>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <button type="reset" class="btn btn-secondary">
                                    <i class="bi bi-arrow-clockwise"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send-check"></i> Daftarkan & Kirim Email Aktivasi
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="bi bi-lightbulb"></i> Panduan</h6>
                            </div>
                            <div class="card-body">
                                <h6>Role Penjelasan:</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <span class="badge bg-primary">Penghuni</span>
                                        <small class="d-block">Mengajukan keluhan, melihat status</small>
                                    </li>
                                    <li class="mb-2">
                                        <span class="badge bg-success">Admin</span>
                                        <small class="d-block">Mendaftarkan user, melihat laporan</small>
                                    </li>
                                    <li class="mb-2">
                                        <span class="badge bg-warning">TR</span>
                                        <small class="d-block">Membuat work order, follow-up</small>
                                    </li>
                                    <li class="mb-2">
                                        <span class="badge bg-info">Departemen</span>
                                        <small class="d-block">Memproses keluhan, upload bukti</small>
                                    </li>
                                </ul>
                                
                                <hr>
                                
                                <div class="alert alert-warning small">
                                    <strong>Note:</strong> Password akan dibuat oleh user melalui link aktivasi.
                                    Admin tidak perlu membuat password.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Show/hide department field based on role selection
    document.getElementById('role').addEventListener('change', function() {
        const departmentField = document.getElementById('departmentField');
        const departmentSelect = document.getElementById('department_id');
        
        if (this.value === 'dept') {
            departmentField.style.display = 'block';
            departmentSelect.required = true;
        } else {
            departmentField.style.display = 'none';
            departmentSelect.required = false;
            departmentSelect.value = '';
        }
    });
</script>
@endpush
@endsection