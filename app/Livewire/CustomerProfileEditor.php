<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CustomerProfileEditor extends Component
{
    use WithFileUploads;

    public ?string $name = null;
    public ?string $phone = null;
    public ?string $nik = null;
    public ?string $address = null;
    public mixed $ktp_image = null;
    public mixed $sim_image = null;
    
    public ?string $existing_ktp = null;
    public ?string $existing_sim = null;

    public function mount()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $this->name = $user->name;
        $this->phone = $user->phone;
        $this->nik = $user->nik;
        $this->address = $user->address;
        $this->existing_ktp = $user->ktp_image;
        $this->existing_sim = $user->sim_image;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'nik' => 'required|string|max:20',
            'address' => 'required|string',
            'ktp_image' => 'nullable|image|max:2048',
            'sim_image' => 'nullable|image|max:2048',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'nik' => $this->nik,
            'address' => $this->address,
        ]);

        if ($this->ktp_image) {
            $user->ktp_image = $this->ktp_image->store('customers/ktp', 'public');
        }

        if ($this->sim_image) {
            $user->sim_image = $this->sim_image->store('customers/sim', 'public');
        }

        $user->save();
        
        $this->dispatch('profileUpdated');

        session()->flash('message', 'Profil dan dokumen berhasil diperbarui!');
        $this->mount(); // Reset state
    }

    public function render()
    {
        return view('livewire.customer-profile-editor');
    }
}
