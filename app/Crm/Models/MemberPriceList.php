<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 20.10.2016
 * Time: 10:41
 */

namespace Crm\Models;


use Delmax\Products\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

class MemberPriceList extends Model
{
    use SoftDeletes;

    protected $connection = 'CRM';

    protected $table = 'member_price_list';

    protected $fillable = ['member_id', 'product_id', 'price_without_tax', 'price_with_tax'];

    public static function tyreServicePrices($memberId, $vehicleCategory = 'Putničko', $diameter=0) {

        if (is_null($memberId)||($memberId=='')) {

            return [
                'price'=>[
                    'cl'=>null,
                    'al'=>null
                ]
            ];
        }

        $sql = "
            SELECT p.product_id, p.description_id, p.additional_description, price_list.price_with_tax as price
            FROM delmax_catalog.tyre_services_package pack
                join delmax_catalog.product p on pack.description_id=p.description_id
                JOIN delmax_catalog.product_dimension pd10 ON pd10.dimension_id=10 and pd10.product_id=p.product_id AND pd10.value_text=?
                JOIN delmax_catalog.product_dimension pd13 ON pd13.dimension_id=13 and pd13.product_id=p.product_id AND pd13.value_text=?
                join delmax_crm.member_price_list price_list on price_list.product_id=p.product_id and price_list.member_id=? and price_list.deleted_at IS NULL";

        $services = DB::connection('CRM')->select($sql, [$vehicleCategory, $diameter, $memberId]);

        $wheel_removal = null;
        $tyre_fitting  = null;
        $wheel_balance_al = null;
        $wheel_balance_cl = null;

        foreach ($services as $service) {
            switch ($service->description_id) {
                case '2182': // Skidanje - namestanje tocka
                    $wheel_removal = $service->price;
                    break;
                case '2183': // Montaza - demontaza gume
                    $tyre_fitting = $service->price;
                    break;
                case '2187': // Balansiranje: Aluminijumska i Celicna felna
                    if(strpos($service->additional_description,'Al feln')>0) {
                        $wheel_balance_al = $service->price;
                    } else {
                        $wheel_balance_cl = $service->price;
                    }
                    break;
            }
        }

        $wheel_removal      = !is_null($wheel_removal) ? (float)$wheel_removal : 0;
        $tyre_fitting       = !is_null($tyre_fitting) ? (float)$tyre_fitting : 0;
        $wheel_balance_cl   = !is_null($wheel_balance_cl) ? (float)$wheel_balance_cl : 0;
        $wheel_balance_al   = !is_null($wheel_balance_al) ? (float)$wheel_balance_al : 0;

        $cl = $wheel_removal + $tyre_fitting + $wheel_balance_cl;
        $al = $wheel_removal + $tyre_fitting + $wheel_balance_al;

        return [
            'price'=>[
                'cl'=>$cl,
                'al'=>$al
            ]
        ];
    }

    public static function apiDatatables($memberId){

        $query = Product::join('product_group', 'product.group_id', '=', 'product_group.group_id')
            ->join('description', 'product.description_id','=', 'description.description_id')
            ->leftJoin('product_dimension as vehicle_category', function ($join) {
                $join->on('vehicle_category.product_id', '=', 'product.product_id')->where('vehicle_category.dimension_id', '=', 10);})
            ->leftJoin('product_dimension as diameter', function ($join) {
                $join->on('diameter.product_id', '=', 'product.product_id')->where('diameter.dimension_id', '=', 13);})
            ->leftJoin('product_dimension as wheel_material', function ($join) {
                $join->on('wheel_material.product_id', '=', 'product.product_id')->where('wheel_material.dimension_id', '=', 24);})
            ->leftJoin('delmax_crm.member_price_list', function ($join) use($memberId){
                $join->on('delmax_crm.member_price_list.product_id', '=', 'product.product_id')
                    ->where('delmax_crm.member_price_list.member_id', '=', $memberId);})
            ->whereIn('product_group.group_id', [438,439])
            ->whereNull('member_price_list.deleted_at')
            ->orderBy('product_group.description','desc')
            ->orderBy('product_group.description')
            ->orderBy('vehicle_category.value_text')
            ->orderBy('product.product_id')
            ->orderBy('product_group.description')
            ->select(
                'product_group.description as product_group',
                'vehicle_category.value_text as vehicle_category',
                'product.product_id',
                'description.description',
                'product.additional_description',
                'diameter.value_text AS diameter',
                'wheel_material.value_text AS wheel_material',
                'member_price_list.id',
                DB::raw("coalesce(member_price_list.price_with_tax,'') as price_with_tax"));

        return datatables()::of($query)->make(true);

    }

    public static function apiDtTyresServicesAllDistinct($memberId){

        $query = Product::join('product_group', 'product.group_id', '=', 'product_group.group_id')
            ->join('description', 'product.description_id','=', 'description.description_id')
            ->leftJoin('product_dimension as vehicle_category', function ($join) {
                $join->on('vehicle_category.product_id', '=', 'product.product_id')->where('vehicle_category.dimension_id', '=', 10);})
            ->leftJoin('product_dimension as diameter', function ($join) {
                $join->on('diameter.product_id', '=', 'product.product_id')
                    ->where('diameter.dimension_id', '=', 13);})
            ->leftJoin('product_dimension as wheel_material', function ($join) {
                $join->on('wheel_material.product_id', '=', 'product.product_id')->where('wheel_material.dimension_id', '=', 24);})
            ->leftJoin('delmax_crm.member_price_list', function ($join) use($memberId){
                $join->on('delmax_crm.member_price_list.product_id', '=', 'product.product_id')
                    ->where('delmax_crm.member_price_list.member_id', '=', $memberId);})
            ->whereIn('product_group.group_id', [438])
            ->whereNull('member_price_list.deleted_at')
            ->where('member_price_list.price_with_tax','<>','')
            ->orderBy('product_group.description','desc')
            ->orderBy('product_group.description')
            ->orderBy('vehicle_category.value_text')
            ->orderBy('product.product_id')
            ->orderBy('product_group.description')
            ->groupBy('description.description')
            ->select(
                'product.product_id',
                'description.description',
                'product.additional_description',
                'diameter.value_text AS diameter',
                'wheel_material.value_text AS wheel_material',
                'member_price_list.id',
                DB::raw("coalesce(member_price_list.price_with_tax,'') as price_with_tax"));

        return datatables()::of($query)->make(true);

    }

    public static function apiDtTyresServices($memberId, $vehicleCategory, $diameter){

        $query = Product::join('product_group', 'product.group_id', '=', 'product_group.group_id')
            ->join('description', 'product.description_id','=', 'description.description_id')
            ->leftJoin('product_dimension as vehicle_category', function ($join) {
                $join->on('vehicle_category.product_id', '=', 'product.product_id')->where('vehicle_category.dimension_id', '=', 10);})
            ->leftJoin('product_dimension as diameter', function ($join) {
                $join->on('diameter.product_id', '=', 'product.product_id')
                    ->where('diameter.dimension_id', '=', 13);})
            ->leftJoin('product_dimension as wheel_material', function ($join) {
                $join->on('wheel_material.product_id', '=', 'product.product_id')->where('wheel_material.dimension_id', '=', 24);})
            ->leftJoin('delmax_crm.member_price_list', function ($join) use($memberId){
                $join->on('delmax_crm.member_price_list.product_id', '=', 'product.product_id')
                    ->where('delmax_crm.member_price_list.member_id', '=', $memberId);})
            ->whereIn('product_group.group_id', [438])
            ->whereNull('member_price_list.deleted_at')
            ->where('vehicle_category.value_text','=', $vehicleCategory)
            ->where('diameter.value_text','=', $diameter)
            ->where('member_price_list.price_with_tax','<>','')
            ->orderBy('product_group.description','desc')
            ->orderBy('product_group.description')
            ->orderBy('vehicle_category.value_text')
            ->orderBy('product.product_id')
            ->orderBy('product_group.description')
            ->select(
                'product.product_id',
                'description.description',
                'product.additional_description',
                'diameter.value_text AS diameter',
                'wheel_material.value_text AS wheel_material',
                'member_price_list.id',
                DB::raw("coalesce(member_price_list.price_with_tax,'') as price_with_tax"));

        return datatables()::of($query)->make(true);

    }

    public static function apiDtTyresOtherServices($memberId){

        $query = Product::join('product_group', 'product.group_id', '=', 'product_group.group_id')
            ->join('description', 'product.description_id','=', 'description.description_id')
            ->leftJoin('product_dimension as vehicle_category', function ($join) {
                $join->on('vehicle_category.product_id', '=', 'product.product_id')->where('vehicle_category.dimension_id', '=', 10);})
            ->leftJoin('product_dimension as diameter', function ($join) {
                $join->on('diameter.product_id', '=', 'product.product_id')
                    ->where('diameter.dimension_id', '=', 13);})
            ->leftJoin('product_dimension as wheel_material', function ($join) {
                $join->on('wheel_material.product_id', '=', 'product.product_id')->where('wheel_material.dimension_id', '=', 24);})
            ->leftJoin('delmax_crm.member_price_list', function ($join) use($memberId){
                $join->on('delmax_crm.member_price_list.product_id', '=', 'product.product_id')
                    ->where('delmax_crm.member_price_list.member_id', '=', $memberId);})
            ->whereIn('product_group.group_id', [438])
            ->whereNull('member_price_list.deleted_at')
            ->where(DB::raw("coalesce(vehicle_category.value_text,'')"),'=','')
            ->where('member_price_list.price_with_tax','<>','')
            ->orderBy('product_group.description','desc')
            ->orderBy('product_group.description')
            ->orderBy('vehicle_category.value_text')
            ->orderBy('product.product_id')
            ->orderBy('product_group.description')
            ->select(
                'product.product_id',
                'description.description',
                'product.additional_description',
                'diameter.value_text AS diameter',
                'wheel_material.value_text AS wheel_material',
                'member_price_list.id',
                DB::raw("coalesce(member_price_list.price_with_tax,'') as price_with_tax"));

        return datatables()::of($query)->make(true);
    }

    public static function apiDtWheelAlignmentServices($memberId, $vehicleCategory, $diameter){

        $query = Product::join('product_group', 'product.group_id', '=', 'product_group.group_id')
            ->join('description', 'product.description_id','=', 'description.description_id')
            ->leftJoin('product_dimension as vehicle_category', function ($join) {
                $join->on('vehicle_category.product_id', '=', 'product.product_id')->where('vehicle_category.dimension_id', '=', 10);})
            ->leftJoin('product_dimension as diameter', function ($join) {
                $join->on('diameter.product_id', '=', 'product.product_id')
                    ->where('diameter.dimension_id', '=', 13);})
            ->leftJoin('product_dimension as wheel_material', function ($join) {
                $join->on('wheel_material.product_id', '=', 'product.product_id')->where('wheel_material.dimension_id', '=', 24);})
            ->leftJoin('delmax_crm.member_price_list', function ($join) use($memberId){
                $join->on('delmax_crm.member_price_list.product_id', '=', 'product.product_id')
                    ->where('delmax_crm.member_price_list.member_id', '=', $memberId);})
            ->whereIn('product_group.group_id', [439])
            ->whereNull('member_price_list.deleted_at')
            ->where('member_price_list.price_with_tax','<>','')
            ->where('vehicle_category.value_text','=', $vehicleCategory)
            ->where('diameter.value_text','=', $diameter)
            ->orderBy('product_group.description','desc')
            ->orderBy('product_group.description')
            ->orderBy('vehicle_category.value_text')
            ->orderBy('product.product_id')
            ->orderBy('product_group.description')
            ->select(
                'product.product_id',
                'description.description',
                'product.additional_description',
                'diameter.value_text AS diameter',
                'wheel_material.value_text AS wheel_material',
                'member_price_list.id',
                DB::raw("coalesce(member_price_list.price_with_tax,'') as price_with_tax"));

        return datatables()::of($query)->make(true);

    }

    public static function apiDtWheelAlignmentOtherServices($memberId){

        $query = Product::join('product_group', 'product.group_id', '=', 'product_group.group_id')
            ->join('description', 'product.description_id','=', 'description.description_id')
            ->leftJoin('product_dimension as vehicle_category', function ($join) {
                $join->on('vehicle_category.product_id', '=', 'product.product_id')->where('vehicle_category.dimension_id', '=', 10);})
            ->leftJoin('product_dimension as diameter', function ($join) {
                $join->on('diameter.product_id', '=', 'product.product_id')
                    ->where('diameter.dimension_id', '=', 13);})
            ->leftJoin('product_dimension as wheel_material', function ($join) {
                $join->on('wheel_material.product_id', '=', 'product.product_id')->where('wheel_material.dimension_id', '=', 24);})
            ->leftJoin('delmax_crm.member_price_list', function ($join) use($memberId){
                $join->on('delmax_crm.member_price_list.product_id', '=', 'product.product_id')
                    ->where('delmax_crm.member_price_list.member_id', '=', $memberId);})
            ->whereIn('product_group.group_id', [439])
            ->whereNull('member_price_list.deleted_at')
            ->where('member_price_list.price_with_tax','<>','')
            ->where(DB::raw("coalesce(vehicle_category.value_text,'')"),'=','')

            ->orderBy('product_group.description','desc')
            ->orderBy('product_group.description')
            ->orderBy('vehicle_category.value_text')
            ->orderBy('product.product_id')
            ->orderBy('product_group.description')
            ->select(
                'product.product_id',
                'description.description',
                'product.additional_description',
                'diameter.value_text AS diameter',
                'wheel_material.value_text AS wheel_material',
                'member_price_list.id',
                DB::raw("coalesce(member_price_list.price_with_tax,'') as price_with_tax"));

        return datatables()::of($query)->make(true);
    }

}
