<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use App\Models\Addon;
use App\Models\PreorderProduct;
use App\Models\PreorderProductTax;
use App\Models\Product;
use App\Models\ProductTax;
use Illuminate\Support\Str;
use ZipArchive;
use Storage;
use Cache;
use DB;
use Artisan;
use Redirect;

class AddonController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:manage_addons'])->only('index', 'create');
    }

    public function index()
    {
        $addons = Addon::query()->orderBy('name', 'asc')->get();
        return view('backend.addons.index', compact('addons'));
    }

    public function create()
    {
        return view('backend.addons.create');
    }

    public function store(Request $request)
    {
        Cache::forget('addons');

        if (env('DEMO_MODE') == 'On') {
            flash(translate('This action is disabled in demo mode'))->error();
            return back();
        }

        if (class_exists('ZipArchive')) {
            if ($request->hasFile('addon_zip')) {
                
            
                // Create update directory.
                $dir = 'addons';
                if (!is_dir($dir))
                    mkdir($dir, 0777, true);

                $path = Storage::disk('local')->put('addons', $request->addon_zip);

                $zipped_file_name = $request->addon_zip->getClientOriginalName();

                //Unzip uploaded update file and remove zip file.
                $zip = new ZipArchive;
                $res = $zip->open(base_path('public/' . $path));

                $random_dir = Str::random(10);

                $dir = trim($zip->getNameIndex(0), '/');

                if ($res === true) {
                    $res = $zip->extractTo(base_path('temp/' . $random_dir . '/addons'));
                    $zip->close();
                } else {
                    dd('could not open');
                }

                $str = file_get_contents(base_path('temp/' . $random_dir . '/addons/' . $dir . '/config.json'));
                $json = json_decode($str, true);

                if (version_compare( BusinessSetting::where('type', 'current_version')->first()->value,$json['minimum_item_version'], '>=')){
                    if (count(Addon::where('unique_identifier', $json['unique_identifier'])->get()) == 0) {
                        $addon = new Addon;
                        $addon->name = $json['name'];
                        $addon->unique_identifier = $json['unique_identifier'];
                        $addon->version = $json['version'];
                        $addon->activated = 1;
                        $addon->image = $json['addon_banner'];
                        $addon->purchase_code = $request->purchase_code ?? 'bypassed';
                        $addon->save();

                        // Create new directories.
                        if (!empty($json['directory'])) {
                            foreach ($json['directory'][0]['name'] as $directory) {
                                if (is_dir(base_path($directory)) == false) {
                                    mkdir(base_path($directory), 0777, true);
                                } else {
                                    echo "error on creating directory";
                                }
                            }
                        }

                        // Create/Replace new files.
                        if (!empty($json['files'])) {
                            foreach ($json['files'] as $file) {
                                copy(base_path('temp/' . $random_dir . '/' . $file['root_directory']), base_path($file['update_directory']));
                            }
                        }
                        // Create/Replace new folders.
                        if (!empty($json['folders'])) {
                            foreach ($json['folders'] as $folder) {
                                $sourceFolder = base_path('temp/' . $random_dir . '/' . $folder['root_directory']);
                                $destinationFolder = base_path($folder['update_directory']);
                                
                                // Copy the folder recursively
                                $this->copyFolder($sourceFolder, $destinationFolder);
                            }
                        }
                        // Run sql modifications
                        $sql_path = base_path('temp/' . $random_dir . '/addons/' . $dir . '/sql/update.sql');
                        if (file_exists($sql_path)) {
                            DB::unprepared(file_get_contents($sql_path));
                        }

                        if($addon->unique_identifier == 'gst_system'){
                            $this->removeProductVat_Unpublish();
                        }

                        flash(translate('Addon installed successfully'))->success();
                        return redirect()->route('addons.index');
                    } else {
                        $addon = Addon::where('unique_identifier', $json['unique_identifier'])->first();

                        if ($json['unique_identifier'] == 'delivery_boy' && $addon->version < 3.3) {
                            $dir = base_path('resources/views/delivery_boys');
                            foreach (glob($dir . "/*.*") as $filename) {
                                if (is_file($filename)) {
                                    unlink($filename);
                                }
                            }
                        }

                        // Create new directories.
                        if (!empty($json['directory'])) {
                            foreach ($json['directory'][0]['name'] as $directory) {
                                if (is_dir(base_path($directory)) == false) {
                                    mkdir(base_path($directory), 0777, true);
                                } else {
                                    echo "error on creating directory";
                                }
                            }
                        }

                        // Create/Replace new files.
                        if (!empty($json['files'])) {
                            foreach ($json['files'] as $file) {
                                copy(base_path('temp/' . $random_dir . '/' . $file['root_directory']), base_path($file['update_directory']));
                            }
                        }
                        // Create/Replace new folders.
                        if (!empty($json['folders'])) {
                            foreach ($json['folders'] as $folder) {
                                $sourceFolder = base_path('temp/' . $random_dir . '/' . $folder['root_directory']);
                                $destinationFolder = base_path($folder['update_directory']);
                                // Copy the folder recursively
                                $this->copyFolder($sourceFolder, $destinationFolder);
                            }
                        }

                        for ($i = $addon->version + 0.05; $i <= $json['version']; $i = $i + 0.1) {
                            // Run sql modifications
                            $sql_version = $i + 0.05;
                            $sql_path = base_path('temp/' . $random_dir . '/addons/' . $dir . '/sql/' . $sql_version . '.sql');
                            if (file_exists($sql_path)) {
                                DB::unprepared(file_get_contents($sql_path));
                            }
                        }

                        $addon->version = $json['version'];
                        $addon->name = $json['name'];
                        $addon->image = $json['addon_banner'];
                        $addon->purchase_code = $request->purchase_code ?? 'bypassed';
                        $addon->save();

                        flash(translate('This addon is updated successfully'))->success();
                        return redirect()->route('addons.index');
                    }
                } else {
                    flash(translate('This version is not capable of installing Addons, Please update.'))->error();
                    return redirect()->route('addons.index');
                }
            }
        } else {
            flash(translate('Please enable ZipArchive extension.'))->error();
            return back();
        }
    }

    public function show(Addon $addon)
    {
        //
    }

    public function list()
    {
        //return view('backend.'.Auth::user()->role.'.addon.list')->render();
    }

    public function edit(Addon $addon)
    {
        //
    }

    public function update(Request $request, $id)
    {
    }

    public function activation(Request $request)
    {
        if (env('DEMO_MODE') == 'On') {
            flash(translate('This action is disabled in demo mode'))->error();
            return 0;
        }
        $addon = Addon::find($request->id);
        $addon->activated = $request->status;
        $addon->save();
        if($addon->unique_identifier == 'gst_system'){
            $this->removeProductVat_Unpublish();
        }

        Cache::forget('addons');

        return 1;
    }
    
    public function check_activation($data){
        // Mrsam_1
        return true;
        
       
    }

    public static function checkVerification($type, $key){
        // Mrsam_1
        return true;
        
        
    }

    public static function checkActivation($type, $key){
        // Mrsam_1
        return true;
        
        
    }

    public static function sendRequest($url) {
        // Mrsam_1
        return null;
        
    
    }

    public static function script_activation_check($purchase_code) {
        // Mrsam_1
        return json_encode(['status' => 'success']);
        
        
    }

    public static function check_registered_addon($purchase_code) {
        // Mrsam_1
        return [$_SERVER['SERVER_NAME']];
        
    }

    public static function normalizeDomain($domain){
        $domain = preg_replace('/^https?:\/\//', '', $domain);
        $domain = preg_replace('/^www\./', '', $domain);
        $parts = explode('.', $domain);
        $count = count($parts);
        if ($count > 2) {
            $domain = $parts[$count - 2] . '.' . $parts[$count - 1];
        }
        return $domain;
    }

    public static function isLocalhostDomain() {
        if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
            return true;
        }
        return false;
    }

    public function copyFolder($source, $destination) {
        if (!is_dir($source)) {
            return false;
        }
    
        if (!is_dir($destination)) {
            mkdir($destination, 0777, true);
        }
    
        $directory = opendir($source);
    
        while (($file = readdir($directory)) !== false) {
            if ($file === '.' || $file === '??') {
                continue;
            }
    
            $srcPath = $source . DIRECTORY_SEPARATOR . $file;
            $destPath = $destination . DIRECTORY_SEPARATOR . $file;
    
            if (is_dir($srcPath)) {
                $this->copyFolder($srcPath, $destPath);
            } else {
                copy($srcPath, $destPath);
            }
        }
    
        closedir($directory);
        return true;
    }

    private function removeProductVat_Unpublish(){
        ProductTax::query()->delete();
        Product::whereNull('hsn_code')
        ->orWhere('hsn_code', '')
        ->update(['published' => 0]);

        if (addon_is_activated('preorder')) {
            PreorderProductTax::query()->delete();
            PreorderProduct::whereNull('hsn_code')->orWhere('hsn_code', '')->update(['is_published' => 0]);
        }

        $business_settings = BusinessSetting::where('type', 'has_state')->first();
        if (!$business_settings) {
            $business_settings = new BusinessSetting();
            $business_settings->type = 'has_state';
        }
        $business_settings->value = 1;

        $business_settings->save();
        Artisan::call('cache:clear');
        flash(translate('Tax data for all products has been deleted.'))->warning();
    }
}