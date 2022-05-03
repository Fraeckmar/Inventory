<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Valuestore\Valuestore;

class Settings extends Controller
{
    private $settings;
    function __construct()
    {
        $this->settings = Valuestore::make(storage_path('app/settings.json'));
    }
    // Settings page
    public function page(Request $request)
    {
        if ($request->has('debug')) {
            dd($this->settings->all());
        }
        return view('settings.settings', [
            'settings' => $this->settings->all()
        ]);
    }
    // Save settings value
    public function save(Request $request)
    {        
        if($request->hasFile('app_logo')){
            $request->validate([
                'app_logo' => 'mimes:jpg,png,jpeg|max:1054'
            ]);
            $fileName = 'logo.'.$request->app_logo->extension(); 
            $request->app_logo->storeAs('images', $fileName,'public');
            //$fileName = $request->app_logo->store('images', 'public');
            $this->settings->put('app_logo', $fileName);
            dd('filename '.$fileName);
        } else {
            dd('No App logo');
        }
        $fields = $request->except(['_token', 'app_logo']);
        if(!empty($fields)){
            foreach($fields as $key => $value){
                $this->settings->put($key, $value);
            }
            return back()->with([
                'success' => 'Settings save successfully!',
                'settings' => $this->settings,
            ]);
        }
        return back()->with('error', 'Something went wrong during saving.');

    }
    // Get Settings value
    public static function get($key)
    {
        $valueStore = Valuestore::make(storage_path('app/settings.json'));
        if (in_array($key, ['items_category']) && !empty($valueStore->get($key))){
            return explode(',', $valueStore->get($key, []));
        }
        return $valueStore->get($key, '');
    }
}
