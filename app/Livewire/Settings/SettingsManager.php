<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Setting;
use Livewire\WithFileUploads;

class SettingsManager extends Component
{
    use WithFileUploads;

    public $company_name;
    public $short_name;
    public $company_name_arabic;
    public $trn;
    public $address;
    public $phone;
    public $email;
    public $currency;
    public $vat_rate;
    public $bank_details;
    
    public $new_logo;
    public $new_header_image;
    public $new_footer_image;
    public $existing_logo;
    public $existing_header_image;
    public $existing_footer_image;

    public function mount()
    {
        $setting = Setting::first() ?? new Setting();
        
        $this->company_name = $setting->company_name;
        $this->short_name = $setting->short_name;
        $this->company_name_arabic = $setting->company_name_arabic;
        $this->trn = $setting->trn;
        $this->address = $setting->address;
        $this->phone = $setting->phone;
        $this->email = $setting->email;
        $this->currency = $setting->currency ?? 'AED';
        $this->vat_rate = $setting->vat_rate ?? 5.00;
        $this->bank_details = $setting->bank_details;
        
        $this->existing_logo = $setting->logo_path;
        $this->existing_header_image = $setting->header_image_path;
        $this->existing_footer_image = $setting->footer_image_path;
    }

    public function save()
    {
        $this->validate([
            'company_name' => 'required|string',
            'short_name' => 'nullable|string',
            'vat_rate' => 'required|numeric',
        ]);

        $setting = Setting::first() ?? new Setting();

        if ($this->new_logo) {
            if ($setting->logo_path) {
                $oldPath = str_replace('storage/', '', $setting->logo_path);
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }
            }
            $path = $this->new_logo->store('settings', 'public');
            $setting->logo_path = 'storage/' . $path;
            $this->existing_logo = $setting->logo_path;
        }

        if ($this->new_header_image) {
            if ($setting->header_image_path) {
                $oldPath = str_replace('storage/', '', $setting->header_image_path);
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }
            }
            $path = $this->new_header_image->store('settings', 'public');
            $setting->header_image_path = 'storage/' . $path;
            $this->existing_header_image = $setting->header_image_path;
        }

        if ($this->new_footer_image) {
            if ($setting->footer_image_path) {
                $oldPath = str_replace('storage/', '', $setting->footer_image_path);
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }
            }
            $path = $this->new_footer_image->store('settings', 'public');
            $setting->footer_image_path = 'storage/' . $path;
            $this->existing_footer_image = $setting->footer_image_path;
        }

        $setting->company_name = $this->company_name;
        $setting->short_name = $this->short_name;
        $setting->company_name_arabic = $this->company_name_arabic;
        $setting->trn = $this->trn;
        $setting->address = $this->address;
        $setting->phone = $this->phone;
        $setting->email = $this->email;
        $setting->currency = $this->currency;
        $setting->vat_rate = $this->vat_rate;
        $setting->bank_details = $this->bank_details;
        
        $setting->save();
        
        $this->new_logo = null;
        $this->new_header_image = null;
        $this->new_footer_image = null;
        
        session()->flash('success', 'Settings successfully updated.');
    }

    public function removeLogo()
    {
        $setting = Setting::first();
        if ($setting && $setting->logo_path) {
            $path = str_replace('storage/', '', $setting->logo_path);
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
            }
            $setting->logo_path = null;
            $setting->save();
            $this->existing_logo = null;
        }
        $this->new_logo = null;
        session()->flash('success', 'Logo removed successfully.');
    }

    public function removeHeaderImage()
    {
        $setting = Setting::first();
        if ($setting && $setting->header_image_path) {
            $path = str_replace('storage/', '', $setting->header_image_path);
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
            }
            $setting->header_image_path = null;
            $setting->save();
            $this->existing_header_image = null;
        }
        $this->new_header_image = null;
        session()->flash('success', 'Header image removed successfully.');
    }

    public function removeFooterImage()
    {
        $setting = Setting::first();
        if ($setting && $setting->footer_image_path) {
            $path = str_replace('storage/', '', $setting->footer_image_path);
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
            }
            $setting->footer_image_path = null;
            $setting->save();
            $this->existing_footer_image = null;
        }
        $this->new_footer_image = null;
        session()->flash('success', 'Footer image removed successfully.');
    }

    public function render()
    {
        return view('livewire.settings.settings-manager');
    }
}
