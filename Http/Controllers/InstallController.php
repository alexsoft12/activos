<?php

namespace Modules\Activos\Http\Controllers;

use App\System;
use Composer\Semver\Comparator;
use Exception;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Log;

class InstallController extends Controller
{
    /**
     * @var string
     */
    private $module_name;
    /**
     * @var string
     */
    private $appVersion;

    /**
     * InstallController constructor.
     */
    public function __construct()
    {
        $this->module_name = 'activos';
        $this->appVersion = config('activos.module_version');
    }

    /**
     * Install
     *
     * Display a listing of the resource.
     * @return Application|Factory|Response|View
     */
    public function index()
    {
        if (!auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');

        //clear cache & config file
        config(['app.debug' => true]);
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        //Check if Connector is installed or not.
        $is_installed = System::getProperty($this->module_name . '_version');
        if (!empty($is_installed)) {
            abort(404);
        }

        $action_url = route('activos_install');

        return view('install.install-module')
            ->with(compact('action_url'));

    }

    /**
     * Install activos Module
     * @throws Exception
     */
    public function install()
    {
        try {
            request()->validate(
                ['license_code' => 'required',
                    'login_username' => 'required'],
                ['license_code.required' => 'License code is required',
                    'login_username.required' => 'Username is required']
            );

            DB::beginTransaction();

            $license_code = request()->license_code;
            $email = request()->email;
            $login_username = request()->login_username;
            $pid = config('connector.pid');
            //Validate
            $response = pos_boot(url('/'), __DIR__, $license_code, $email, $login_username, $type = 1, $pid);

            if (!empty($response)) {
                return $response;
            }

            $is_installed = System::getProperty($this->module_name . '_version');
            if (!empty($is_installed)) {
                abort(404);
            }

            DB::statement('SET default_storage_engine=INNODB;');
            Artisan::call('module:migrate', ['module' => "Activos", '--force' => true]);
            Artisan::call('module:publish', ['module' => "Activos"]);
            System::addProperty($this->module_name . '_version', $this->appVersion);


            DB::commit();

            $output = ['success' => 1,
                'msg' => 'Activos module installed successfully'
            ];

        } catch (Exception $e) {
            DB::rollBack();

            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => $e->getMessage()
            ];
        }
        return redirect()
            ->route('manage-modules.index')
            ->with('status', $output);
    }

    /**
     * Uninstall
     * @return RedirectResponse
     */
    public function uninstall()
    {
        if (!auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            System::removeProperty($this->module_name . '_version');

            $output = ['success' => true,
                'msg' => __("lang_v1.success")
            ];
        } catch (Exception $e) {
            $output = ['success' => false,
                'msg' => $e->getMessage()
            ];
        }

        return redirect()->back()->with(['status' => $output]);
    }


    /**
     * Update Module
     * @return RedirectResponse
     * @throws Exception
     */
    public function update()
    {
        //Check if activos_version is same as appVersion then 404
        //If appVersion > activos_version - run update script.
        //Else there is some problem.

        if (!auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();
            config(['app.debug' => true]);
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '512');

            $activos_version = System::getProperty($this->module_name . '_version');
            if (Comparator::greaterThan($this->appVersion, $activos_version)) {

                // clear cache & config file
                Artisan::call('config:clear');
                Artisan::call('cache:clear');

                DB::statement('SET default_storage_engine=INNODB;');
                Artisan::call('module:migrate', ['module' => "Activos", '--force' => true]);
                Artisan::call('module:publish', ['module' => "Activos"]);

                System::setProperty($this->module_name . '_version', $this->appVersion);

            } else {
                abort(404);
            }

            DB::commit();
            $output = ['success' => 1,
                'msg' => 'Activos module updated Successfully to version ' . $this->appVersion . ' !!'
            ];
            return redirect()->back()->with(['status' => $output]);
        } catch (Exception $e) {
            DB::rollBack();
            die($e->getMessage());
        }
    }

}