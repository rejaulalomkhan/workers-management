<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Setting;
use Livewire\WithFileUploads;

class SettingsManager extends Component
{
    use WithFileUploads;

    public $company_name;
    public $company_name_arabic;
    public $trn;
    public $address;
    public $phone;
    public $email;
    public $currency;
    public $vat_rate;
    
    public $new_logo;
    public $new_header_image;

    public function mount()
    {
        $setting = Setting::first() ?? new Setting();
        
        $this->company_name = $setting->company_name;
        $this->company_name_arabic = $setting->company_name_arabic;
        $this->trn = $setting->trn;
        $this->address = $setting->address;
        $this->phone = $setting->phone;
        $this->email = $setting->email;
        $this->currency = $setting->currency ?? 'AED';
        $this->vat_rate = $setting->vat_rate ?? 5.00;
    }

    public function save()
    {
        $this->validate([
            'company_name' => 'required|string',
            'vat_rate' => 'required|numeric',
        ]);

        $setting = Setting::first() ?? new Setting();

        if ($this->new_logo) {
            $path = $this->new_logo->store('settings', 'public');
            $setting->logo_path = 'storage/' . $path;
        }

        if ($this->new_header_image) {
            $path = $this->new_header_image->store('settings', 'public');
            $setting->header_image_path = 'storage/' . $path;
        }

        $setting->company_name = $this->company_name;
        $setting->company_name_arabic = $this->company_name_arabic;
        $setting->trn = $this->trn;
        $setting->address = $this->address;
        $setting->phone = $this->phone;
        $setting->email = $this->email;
        $setting->currency = $this->currency;
        $setting->vat_rate = $this->vat_rate;
        
        $setting->save();
        
        session()->flash('success', 'Settings successfully updated.');
    }

    public function render()
    {
        return view('livewire.settings.settings-manager');
    }
}
