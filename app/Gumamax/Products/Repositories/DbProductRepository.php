<?php namespace Gumamax\Products\Repositories;
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 17.3.2015
 * Time: 19:29
 */


class DbProductRepository  implements ProductRepositoryInterface{

    private $fromSQL = "
                FROM delmaxapi.merchant m
                    join product p on p.company_id=m.company_id

                    join description d on d.description_id=p.description_id

                    left join manufacturer m on m.manufacturer_id=p.manufacturer_id

                    left join packaging pack on pack.packaging_id=p.packaging_id

                    left join delmax_images.product_image img on img.product_id=p.product_id and img.is_default=1 and img.deleted_at is null

                    left join stock s on s.company_id=p.company_id and s.product_id=p.product_id and s.merchant_id=m.merchant_id

                    left join stock ls on ls.company_id=m.lookup_stock_company_id and ls.merchant_id=m.lookup_stock_merchant_id and ls.product_id=s.product_id";

    private $tiresFromSQL = "
              join product_dimension tires_vehicle on tires_vehicle.product_id=p.product_id and tires_vehicle.dimension_id=10
              join product_dimension tires_width on tires_width.product_id=p.product_id and tires_width.dimension_id=11
              join product_dimension tires_ratio on tires_ratio.product_id=p.product_id and tires_ratio.dimension_id=12
              join product_dimension tires_diameter on tires_diameter.product_id=p.product_id and tires_diameter.dimension_id=13
              join product_dimension tires_season on tires_season.product_id=p.product_id and tires_season.dimension_id=14
        ";

    private $selectSQL = "
        SELECT

          p.company_id,

          p.product_id,

          d.description as description,

          p.additional_description,

          p.dmx_primary_type,

          m.description as manufacturer,

          p.cat_no,

          p.uom_id,

          pack.description as packing_unit,

          p.ean,

          p.cross_ref,

          p.note,

          if(img.image_id>0,

          concat('http://delmaxapi.com/_img?img=/images/products/',
                 (1000 * ceil(p.product_id/1000)),
                 '/',
                 img.image_id,
                 img.ext,
                 '&transform=resize,54,50'),
                 'http://delmaxapi.com/_img?img=/images/products/noimage.png&transform=resize,54,50')
          as thumbnail_url_54x50,

          if(img.image_id>0,
           concat('http://delmaxapi.com/_img?img=/images/products/',
                 (1000 * ceil(p.product_id/1000)),
                 '/',
                 img.image_id,
                 img.ext,
                 '&transform=resize,80,60'),
                 'http://delmaxapi.com/_img?img=/images/products/noimage.png&transform=resize,80,60')
          as thumbnail_url_80x60,

          if(img.image_id>0,
           concat('http://delmaxapi.com/_img?img=/images/products/',
                 (1000 * ceil(p.product_id/1000)),
                 '/',
                 img.image_id,
                 img.ext,
                 '&transform=resize,120,90'),
                 'http://delmaxapi.com/_img?img=/images/products/noimage.png&transform=resize,120,90')
          as thumbnail_url_120x90,

           if(img.image_id>0,
           concat('http://delmaxapi.com/_img?img=/images/products/',
                 (1000 * ceil(p.product_id/1000)),
                 '/',
                 img.image_id,
                 img.ext),
                 'http://delmaxapi.com/_img?img=/images/products/noimage.png')

          as image_url,

          m.merchant_id,

          if((coalesce(s.qty,0)=0),
              if((ls.qty>0), 5,
                 if(((s.purchase_on_demand=1)||(ls.purchase_on_demand=1)), 4, 0)),
                    if(((s.min_stock_qty>0)&&(s.qty<=s.min_stock_qty)), 2, 1)) as stock_status,

          if(((coalesce(s.qty,0)=0) and (coalesce(ls.qty,0)>0)), ls.price_with_tax, s.price_with_tax) as price_with_tax,

          if(((coalesce(s.qty,0)=0) and (coalesce(ls.qty,0)>0)), ls.price_without_tax, s.price_without_tax) as price_without_tax,

          s.min_order_qty,

          s.max_order_qty,

          s.purchase_on_demand,

          p.visible

        ";



    public function range($fromId, $toId)
    {
        // TODO: Implement range() method.
        /*
         * select * from product between from and to
         */
    }

    public function tyresSearch($query=[], $order='', $perPage=0, $page=-1)
    {
        // TODO: Implement tyresSearch() method.

        return $query;
    }

    public function tyresWidths($query = [])
    {
        // TODO: Implement tyresWidths() method.
    }

    public function tyresRatios($query = [])
    {
        // TODO: Implement tyresRatios() method.
    }

    public function tyresDiameters($query = [])
    {
        // TODO: Implement tyresDiameters() method.
    }

    public function getTotal()
    {
        // TODO: Implement getTotal() method.
    }

    /**
     * @param array $query array of product_id
     * @return mixed
     */
    public function getById($query = [])
    {
        // TODO: Implement getById() method.
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        // TODO: Implement findById() method.
    }

    public function tyresReplacements($query = [], $order = '', $perPage = 0, $page = -1)
    {
        // TODO: Implement tyresReplacements() method.
    }

    public function productCount()
    {
        // TODO: Implement productCount() method.
    }
}
