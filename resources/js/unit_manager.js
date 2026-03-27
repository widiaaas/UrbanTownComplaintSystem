// Data untuk unitManager
function unitManager() {
    return {
        // State
        openCreateUnit: false,
        openEdit: false,
        openEditPenghuni: false,
        openDelete: false,
        openToggle: false,
        openReset: false,
        selectedUnit: { id: null, no_unit: '', gedung: '', lantai: '', currentPenghuni: '' },
        newUnit: { no_unit: '', gedung: '', lantai: '', nomor_kamar: '' },
        editForm: { gedung: '', lantai: '', nomor_kamar: '' },
        createdUnit: null,
        search: '',
        floorFilter: '',
        unitsData: [],
        filteredUnits: [],
        penghuniList: [],
        selectedPenghuniId: '',
        selectedPenghuniDetail: null,
        passwordGenerated: false,
        newPassword: '',
        toggleAction: '',
        resetPasswordGenerated: false,

        init(initialUnits) {
            this.unitsData = initialUnits;
            this.filteredUnits = initialUnits;
            this.fetchPenghuniList();

            // Listen for custom events from dropdowns
            window.addEventListener('edit-unit', (e) => {
                this.editUnit(e.detail.id, e.detail.gedung, e.detail.lantai, e.detail.nomor_kamar);
            });
            window.addEventListener('ganti-penghuni', (e) => {
                this.openGantiPenghuni(e.detail.id, e.detail.no_unit, e.detail.currentPenghuni);
            });
            window.addEventListener('reset-password', (e) => {
                this.openResetPassword(e.detail.id, e.detail.no_unit);
            });
            window.addEventListener('toggle-status', (e) => {
                this.toggleStatus(e.detail.id, e.detail.no_unit, e.detail.action);
            });
            window.addEventListener('confirm-delete', (e) => {
                this.confirmDelete(e.detail.id, e.detail.no_unit);
            });
        },

        applyFilter() {
            this.filteredUnits = this.unitsData.filter(unit => {
                let match = true;
                if (this.search) {
                    match = unit.no_unit.toLowerCase().includes(this.search.toLowerCase()) ||
                            unit.gedung.toLowerCase().includes(this.search.toLowerCase());
                }
                if (this.floorFilter && unit.lantai != this.floorFilter) {
                    match = false;
                }
                return match;
            });
        },

        resetFilter() {
            this.search = '';
            this.floorFilter = '';
            this.applyFilter();
        },

        fetchPenghuniList() {
            fetch('/penghuni-available')
                .then(res => res.json())
                .then(data => this.penghuniList = data)
                .catch(err => console.error(err));
        },

        fetchPenghuniDetail() {
            if (this.selectedPenghuniId) {
                this.selectedPenghuniDetail = this.penghuniList.find(p => p.id == this.selectedPenghuniId);
            } else {
                this.selectedPenghuniDetail = null;
            }
        },

        saveUnit() {
            fetch('/units', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(this.newUnit)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.createdUnit = data.unit;
                    this.createdUnit.password = data.password;
                    this.newUnit = { no_unit: '', gedung: '', lantai: '', nomor_kamar: '' };
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                } else {
                    alert('Gagal menambah unit: ' + data.message);
                }
            })
            .catch(err => console.error(err));
        },

        editUnit(id, gedung, lantai, nomor_kamar) {
            this.selectedUnit.id = id;
            this.editForm.gedung = gedung;
            this.editForm.lantai = lantai;
            this.editForm.nomor_kamar = nomor_kamar;
            const unit = this.unitsData.find(u => u.id == id);
            if (unit) this.selectedUnit.no_unit = unit.no_unit;
            this.openEdit = true;
        },

        updateUnit() {
            fetch(`/units/${this.selectedUnit.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(this.editForm)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Unit berhasil diperbarui');
                    location.reload();
                } else {
                    alert('Gagal memperbarui unit: ' + data.message);
                }
            })
            .catch(err => console.error(err));
        },

        confirmDelete(id, no_unit) {
            this.selectedUnit.id = id;
            this.selectedUnit.no_unit = no_unit;
            this.openDelete = true;
        },

        deleteUnit() {
            fetch(`/units/${this.selectedUnit.id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Unit berhasil dihapus');
                    location.reload();
                } else {
                    alert('Gagal menghapus unit: ' + data.message);
                }
            })
            .catch(err => console.error(err));
        },

        openGantiPenghuni(id, no_unit, currentPenghuni) {
            this.selectedUnit.id = id;
            this.selectedUnit.no_unit = no_unit;
            this.selectedUnit.currentPenghuni = currentPenghuni;
            const unit = this.unitsData.find(u => u.id == id);
            if (unit) {
                this.selectedUnit.gedung = unit.gedung;
                this.selectedUnit.lantai = unit.lantai;
            }
            this.selectedPenghuniId = '';
            this.selectedPenghuniDetail = null;
            this.passwordGenerated = false;
            this.newPassword = '';
            this.openEditPenghuni = true;
        },

        submitGantiPenghuni() {
            if (!this.selectedPenghuniId) {
                alert('Pilih penghuni baru terlebih dahulu');
                return;
            }
            fetch(`/units/${this.selectedUnit.id}/change-occupant`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ penghuni_id: this.selectedPenghuniId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.passwordGenerated = true;
                    this.newPassword = data.new_password;
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                } else {
                    alert('Gagal mengganti penghuni: ' + data.message);
                }
            })
            .catch(err => console.error(err));
        },

        openResetPassword(id, no_unit) {
            this.selectedUnit.id = id;
            this.selectedUnit.no_unit = no_unit;
            this.resetPasswordGenerated = false;
            this.newPassword = '';
            this.openReset = true;
        },

        submitResetPassword() {
            fetch(`/units/${this.selectedUnit.id}/reset-password`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.resetPasswordGenerated = true;
                    this.newPassword = data.new_password;
                } else {
                    alert('Gagal reset password: ' + data.message);
                }
            })
            .catch(err => console.error(err));
        },

        toggleStatus(id, no_unit, action) {
            this.selectedUnit.id = id;
            this.selectedUnit.no_unit = no_unit;
            this.toggleAction = action;
            this.openToggle = true;
        },

        submitToggle() {
            fetch(`/units/${this.selectedUnit.id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Gagal mengubah status: ' + data.message);
                }
            })
            .catch(err => console.error(err));
        }
    };
}

function dropdownMenu(data) {
    return {
        open: false,
        style: { top: '0px', left: '0px' },
        status: data.status,
        id: data.id,
        gedung: data.gedung,
        lantai: data.lantai,
        nomor_kamar: data.nomor_kamar,
        no_unit: data.no_unit,
        currentPenghuni: data.currentPenghuni,

        toggle(event) {
            if (this.open) {
                this.open = false;
                return;
            }
            const rect = event.currentTarget.getBoundingClientRect();
            this.style = {
                top: rect.bottom + window.scrollY + 'px',
                left: rect.left + window.scrollX + 'px',
            };
            this.open = true;
        },

        editUnit() {
            window.dispatchEvent(new CustomEvent('edit-unit', {
                detail: { id: this.id, gedung: this.gedung, lantai: this.lantai, nomor_kamar: this.nomor_kamar }
            }));
            this.open = false;
        },

        gantiPenghuni() {
            window.dispatchEvent(new CustomEvent('ganti-penghuni', {
                detail: { id: this.id, no_unit: this.no_unit, currentPenghuni: this.currentPenghuni }
            }));
            this.open = false;
        },

        resetPassword() {
            window.dispatchEvent(new CustomEvent('reset-password', {
                detail: { id: this.id, no_unit: this.no_unit }
            }));
            this.open = false;
        },

        toggleStatus(action) {
            window.dispatchEvent(new CustomEvent('toggle-status', {
                detail: { id: this.id, no_unit: this.no_unit, action: action }
            }));
            this.open = false;
        },

        confirmDelete() {
            window.dispatchEvent(new CustomEvent('confirm-delete', {
                detail: { id: this.id, no_unit: this.no_unit }
            }));
            this.open = false;
        }
    };
}

// Register Alpine components
if (typeof window.Alpine !== 'undefined') {
    window.Alpine.data('unitManager', unitManager);
    window.Alpine.data('dropdownMenu', dropdownMenu);
}