<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'app_name' => SystemSetting::getValue('app_name', config('app.name')),
            'support_email' => SystemSetting::getValue('support_email', ''),
            'maintenance_mode' => SystemSetting::getValue('maintenance_mode', 'off'),
        ];

        return view('admin.system_settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:500',
            'support_email' => 'nullable|email',
            'maintenance_mode' => 'required|in:on,off',
        ]);

        foreach ($validated as $key => $value) {
            SystemSetting::setValue($key, $value);
        }

        return redirect()->route('admin.system-settings.index')->with('success', 'Settings updated successfully.');
    }
}


