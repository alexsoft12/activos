<?php

namespace Modules\Activos\Http\Controllers;

use App\Utils\ModuleUtil;
use Illuminate\Routing\Controller;
use Nwidart\Menus\Facades\Menu;

class DataController extends Controller
{
    /**
     * @return array[]
     */
    public function superadmin_package()
    {
        return [
            [
                'name' => 'activos_module',
                'label' => __('activos::lang.activos_module'),
                'default' => false
            ]
        ];
    }

    /**
     * Adds Account menus
     * @return void
     */
    public function modifyAdminMenu()
    {
        $module_util = new ModuleUtil();
        if (auth()->user()->can('superadmin')) {
            $is_account_enabled = $module_util->isModuleInstalled('Activos');
        } else {
            $business_id = auth()->user()->business_id;
            $is_account_enabled = (boolean)$module_util->hasThePermissionInSubscription($business_id, 'activos_module', 'superadmin_package');
        }
        if ($is_account_enabled) {
            Menu::modify('admin-sidebar-menu', function ($menu) {
                $menu->url(
                    route('activos_dashboard.index'),
                    __('activos::lang.activos'). ' <small class="label bg-red no-print" >Nuevo</small>',
                    ['icon' => 'fas fa-boxes', 'active' => request()->segment(2) == 'activos']
                )->order(92);
            });
        }
    }

}
