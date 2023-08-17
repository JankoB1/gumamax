<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 26.9.2016
 * Time: 22:24
 */

namespace Gumamax\Layout;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Menu  extends Model
{
    protected $connection = 'FW';

    protected $table = 'menu';

    protected $fillable = [
        'parent_id', 'title', 'route_name', 'url', 'icon', 'order_index', 'is_active', 'params'
    ];

    public static function getItems()
    {

        $locale = session('locale', 'en_US');

        $user_id = auth()->check() ? auth()->user()->user_id : 0;

        $cacheName = 'gmx-menuItems-' . $locale . '-' . $user_id;

        return Cache::tags(['menu'])->remember($cacheName, 1440, function () use ($locale, $user_id) {

            return Menu::join('rbac_menu_role', 'rbac_menu_role.menu_id', '=', 'menu.id')
                ->join('rbac_user_role', 'rbac_user_role.role_id', '=', 'rbac_menu_role.role_id')
                ->join('lng_designation', 'lng_designation.id', '=', 'menu.title_des_id')
                ->leftJoin('dmx', 'dmx.id', '=', 'menu.dmx_id')
                ->leftJoin('lng_translation', function ($join) use ($locale) {
                    $join->on('lng_translation.designation_id', '=', 'menu.title_des_id')->where('lng_translation.locale', '=', $locale);
                })
                ->where('menu.is_active', 1)
                ->where('rbac_user_role.user_id', $user_id)
                ->orderBy('menu.order_index')
                ->select([
                    'menu.id',
                    'menu.parent_id',
                    'menu.dmx_id',
                    DB::raw('coalesce(lng_translation.text, lng_designation.text, menu.title) as title'),
                    'menu.route_name',
                    'menu.params',
                    'menu.url',
                    'menu.url_target',
                    'menu.icon',
                    'menu.icon_badge',
                    'menu.label_html',
                    'menu.css_class as class',
                    'menu.order_index',
                    DB::connection('FW')->raw('(select count(*) from fw_001.menu cc where cc.deleted_at is null and cc.is_active=1 and cc.parent_id=menu.id) as child_count')
                ])->distinct()->get();
            });
    }

}