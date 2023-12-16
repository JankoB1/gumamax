<?php namespace App\Gumamax;

use Delmax\Cart\Models\Cart;
use Delmax\Models\ShippingMethod;
use Delmax\Models\ShippingOption;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\MessageBag;

use Validator;


class Address {
    private $valRules = array(
            "alias" => "max:32",
            "firstname" => "required_with:lastname|max:32",
            "lastname" => "required_with:firstname|max:32",
            "company_name" => "max:64",
            "address" => "required|max:64",
            "city_id" => "required|integer|regex:/^\d{5}$/",
            "phone" => array("required","max:64","regex:/^(\()*(\+)*\d+(\))*(\-|\/|\s|)\d+(\-|\s)*\d+(\-|\s)*\d+(\-|\s)*\d+$/"),
            "additional_info" => "max:128",
            "same_billing_address" => ""
    );
    private $valMsgs = array(
            "alias.required" => "Obavezno unesite alias",
            "alias.max" => "Alias može imati najviše :max karaktera",
            "firstname.required_with" => "Obavezno unesite ime",
            "firstname.max" => "Ime može imati najviše :max slova",
            "lastname.required_with" => "Obavezno unesite prezime",
            "lastname.max" => "Prezime može imati najviše :max slova",
            "company_name.required_without_all" => "Obavezno unesite naziv preduzeća",
            "company_name.max" => "Naziv preduzeća može imati najviše :max karaktera",
            "address.required" => "Obavezno unesite adresu",
            "address.max" => "Adresa može imati najviše :max karaktera",
            "city_id.required" => "Niste pravilno izabrali mesto",
            "phone.required" => "Obavezno unesite telefon",
            "phone.regex" => "Neispravan format telefonskog broja",
            "phone.max" => "Broj telefona može imati najviše :max karaktera",
            "additional_info.max" => "Dodatne informacije mogu imati najviše :max karaktera"
    );
    private $sqlKindOfOwnerWhereStatement;
    private $owner=array();

    private function setOwner($ownerId, $kindOfAddressOwner)
    {
        $this->owner=array(
            'user_id'=>0,
            'partner_id'=>0,
            'merchant_id'=>0,
            'guest_id'=>0
        );
        switch ($kindOfAddressOwner) {
            case 'user':
                $this->owner['user_id']=(int)$ownerId;
                break;
            case 'guest':
                $this->owner['guest_id']=(int)$ownerId;
                break;
            case 'partner':
                $this->owner['partner_id']=(int)$ownerId;
                break;
            case 'merchant':
                $this->owner['merchant_id']=(int)$ownerId;
                break;
            default:
                # code...
                break;
        }
    }

    public function getAddresses($ownerId, $kindOfAddressOwner)
    {
        $this->setOwner($ownerId, $kindOfAddressOwner);
        $this->sqlKindOfOwnerWhereStatement="";
        switch ($kindOfAddressOwner) {
            case 'user':
                $this->sqlKindOfOwnerWhereStatement = "a.user_id = {$this->owner['user_id']} ";
                break;
            case 'guest':
                $this->sqlKindOfOwnerWhereStatement = "a.guest_id = {$this->owner['guest_id']} ";
                break;
            case 'partner':
                $this->sqlKindOfOwnerWhereStatement = "a.partner_id = {$this->owner['partner_id']} ";
                break;
            case 'merchant':
                $this->sqlKindOfOwnerWhereStatement = "a.merchant_id = {$this->owner['merchant_id']} ";
                break;
            default:
                # code...
                break;
        }
        $address=array();

        if ((($this->owner['user_id']>0)||($this->owner['guest_id']>0)||($this->owner['partner_id']>0)||($this->owner['merchant_id']>0))&&(!($this->sqlKindOfOwnerWhereStatement===""))) {
            $address = DB::select("SELECT
                a.*,
                CONCAT(COALESCE(a.firstname,''),' ', COALESCE(a.lastname,'')) AS fullname,
                CONCAT(COALESCE(a.address,''),' ', COALESCE(a.address2,'')) AS fulladdress,
                c.city_id,
                c.city_name,
                c.postal_code
                          FROM address a
                            JOIN city c ON c.city_id = a.city_id
                          WHERE $this->sqlKindOfOwnerWhereStatement and (a.deleted_at is null)
                          ORDER BY a.is_default desc");
        }
        return $address;
    }

    public function addAddress($ownerId, $kindOfAddressOwner)
    {
        $city_id = Input::get('city_id');
        $c = City::getGeoCode($city_id);
        $this->setOwner($ownerId, $kindOfAddressOwner);
        /* Poslednja dodata adresa postaje default */
        $is_default = 1;

        switch ($kindOfAddressOwner) {
            case 'user':
                // $fn = Auth::user()->firstname;
                // $ln = Auth::user()->lastname;
                $fn = Input::get('firstname', Auth::user()->firstname);
                $ln = Input::get('lastname', Auth::user()->lastname);
                $cn = Input::get('company_name');
                break;
            case 'guest':
                $fn = Input::get('firstname');;
                $ln = Input::get('lastname');;
                $cn = Input::get('company_name');;
                break;
            case 'partner':
                $fn = '';
                $ln = '';
                $cn = Input::get('company_name');
                break;
            case 'merchant':
                $fn = '';
                $ln = '';
                $cn = Input::get('company_name');
                break;
            default:
                # code...
                break;
        }


        $new_address_id = DB::table('address')->insertGetId(
            array(
                'user_id'=>$this->owner['user_id'],
                'guest_id'=>$this->owner['guest_id'],
                'partner_id'=>$this->owner['partner_id'],
                'merchant_id'=>$this->owner['merchant_id'],
                'alias'=>Input::get('alias'),
                'address'=>Input::get('address'),
                'address2'=>Input::get('address2'),
                'phone' =>Input::get('phone'),
                'firstname'=> $fn,
                'lastname'=> $ln,
                'company_name'=> $cn,
                'additional_info'=>Input::get('additional_info'),
                'city_id'=>$c[0]->city_id,
                'latitude'=>$c[0]->latitude,
                'longitude'=>$c[0]->longitude,
                // 'is_default'=> $is_default, // resava se dole
                'created_at'=>date('Y-m-d H:i:s')
            ));

        if($is_default==1){
            $this->setDefaultAddress($new_address_id);
        }
        return $new_address_id;
    }

    public function updAddress($address_id)
    {
        $city_id = Input::get('city_id');
        $c = City::getGeoCode($city_id);
        $ownerData=$this->getAddressOwner($address_id);
        $is_default = ($ownerData['kind']=='guest') ? 1:(int)Input::get('is_default',0);

        $result =    DB::table('address')
            ->where('address_id',"=",$address_id)
            ->update(
                array(
                    'alias'=>Input::get('alias'),
                    'address'=>Input::get('address'),
                    'address2'=>Input::get('address2'),
                    'phone' =>Input::get('phone'),
                    'firstname'=>Input::get('firstname'),
                    'lastname'=>Input::get('lastname'),
                    'company_name'=>Input::get('company_name'),
                    'additional_info'=>Input::get('additional_info'),
                    'city_id'=>$c[0]->city_id,
                    'latitude'=>$c[0]->latitude,
                    'longitude'=>$c[0]->longitude,
                    'updated_at'=>date('Y-m-d H:i:s')
                    ));
            if ($is_default==1) {
                $this->setDefaultAddress($address_id);
            }
        return $result;
    }

    public function updAddress2($data)
    {
        $c = City::getGeoCode($data['city_id']);
        $ownerData=$this->getAddressOwner($data['address_id']);

        $isd = isset($data['is_default']) ? 1 : 0;
        $is_default = ($ownerData['kind']=='guest') ? 1 : $isd;

        $result =    DB::table('address')
            ->where('address_id',"=",$data['address_id'])
            ->update(
                array(
                    'alias'=>$data['alias'],
                    'address'=>$data['address'],
                    // 'address2'=>$data['address2'],
                    'phone' =>$data['phone'],
                    'firstname'=>$data['firstname'],
                    'lastname'=>$data['lastname'],
                    'company_name'=>$data['company_name'],
                    'additional_info'=>$data['additional_info'],
                    'city_id'=>$c[0]->city_id,
                    'latitude'=>$c[0]->latitude,
                    'longitude'=>$c[0]->longitude,
                    'updated_at'=>date('Y-m-d H:i:s')
                    ));
            if ($is_default==1) {
                $this->setDefaultAddress($data['address_id']);
            }
        return $result;
    }

    public function delAddress($aid)
    {
       return DB::table('address')
            ->where('address_id','=',$aid)
            ->update(array(
                'deleted_at' => date('Y-m-d H:i:s')
            ));
    }

    private function getAddressOwner($address_id)
    {
        $ownerData=array(
            'kind'=>'unknown',
            'id'=>null);

        $a = DB::table('address')
            ->select('user_id','guest_id','partner_id','merchant_id')
            ->where('address_id','=',$address_id)
            ->first();
        if (! is_null($a)){
            if ($a->user_id>0){
                $ownerData['kind'] = 'user';
                $ownerData['id'] = $a->user_id;
            }else if ($a->guest_id>0){
                $ownerData['kind'] = 'guest';
                $ownerData['id'] = $a->guest_id;
            }else if ($a->partner_id>0){
                $ownerData['kind'] = 'partner';
                $ownerData['id'] = $a->partner_id;
            }else if ($a->merchant_id>0){
                $ownerData['kind'] = 'merchant';
                $ownerData['id'] = $a->merchant_id;
            }

        }
        return $ownerData;
    }

    public function setDefaultAddress($address_id)
    {
        $result = false;
        $ownerData = $this->getAddressOwner($address_id);
        /*reset previous to null*/
        $ownerFldName = $ownerData['kind'].'_'.'id';
        if (($ownerData['kind']!='unknown')&&((int)$ownerData['id']>0)){
        DB::table('address')
            ->where($ownerFldName,'=',$ownerData['id'])
            ->update(array(
                    'is_default'=>null,
                    'updated_at'=>date('Y-m-d H:i:s'))
            );
        /*set new value*/
        DB::table('address')
            ->where('address_id','=',$address_id)
            ->update(array(
                    'is_default'=>1,
                    'updated_at'=>date('Y-m-d H:i:s')
            ));
            $result = true;
        }
        return $result;
    }

    public static function getAddressById($id)
    {
        if (isset($id)){
            $address = DB::select("
                select
                            a.address_id,
                            a.user_id,
                            a.partner_id,
                            a.guest_id,
                            a.merchant_id,
                            a.alias,
                            a.firstname,
                            a.lastname,
                            a.company_name,
                            a.address,
                            a.address2,
                            a.additional_info,
                            a.latitude,
                            a.longitude,
                            a.created_at,
                            a.updated_at,
                            a.deleted_at,
                            coalesce(a.phone,'') as phone,
                            '' as email,
                            CONCAT(COALESCE(a.firstname,''),' ', COALESCE(a.lastname,'')) AS fullname,
                            CONCAT(COALESCE(a.address,''),' ', COALESCE(a.address2,'')) AS fulladdress,
                            c.city_id,
                            c.city_name,
                            c.postal_code,
                            a.is_default
                        FROM address a
                          LEFT JOIN city c ON c.city_id=a.city_id
                WHERE (a.address_id = {$id}) and (a.deleted_at is null) ");
            if (!empty($address))
                return $address[0];

        }
    }


    public static function getShippingAddress($cart_id)
    {
        $cart = Cart::find($cart_id);
        if (isset($cart)){
            switch ($cart->shipping_option_id) {
                case ShippingOption::DELMAX_PARTNER:
                    // Partners' addresses are in DB::table('partner'), and not in DB::table('address')
                    if (isset($cart->shipping_to_partner_id)){
                        $address = DB::select("
                            SELECT
                              CONCAT(COALESCE(p.name,''),' ',COALESCE(p.department,'')) AS fullname,
                              coalesce(p.address,'')  AS fulladdress,
                              coalesce(p.phone,'-') as phone,
                              coalesce(p.email,'-') as email,
                              p.city_id,
                              c.city_name,
                              c.postal_code,
                              p.partner_id AS id,
                              '' AS additional_info
                            FROM partner p
                              LEFT JOIN city c ON p.city_id=c.city_id
                            WHERE p.partner_id = {$cart->shipping_to_partner_id}");
                        if (count($address)>0)
                            return $address[0];
                        }
                    break;
                case ShippingOption::CUSTOM_ADDRESS:
                    return self::getAddressById($cart->shipping_to_address_id);
                    break;
            }
        }
    }

    public static function getAddressByUserId($id)
    {
        $r = DB::select("SELECT
                            a.address_id,
                            a.user_id,
                            a.partner_id,
                            a.guest_id,
                            a.merchant_id,
                            a.alias,
                            a.firstname,
                            a.lastname,
                            a.company_name,
                            a.address,
                            a.address2,
                            a.additional_info,
                            a.latitude,
                            a.longitude,
                            a.created_at,
                            a.updated_at,
                            a.deleted_at,
                            coalesce(a.phone,'') as phone,
                            '' as email,
                            CONCAT(COALESCE(a.firstname,''),' ', COALESCE(a.lastname,'')) AS fullname,
                            CONCAT(COALESCE(a.address,''),' ', COALESCE(a.address2,'')) AS fulladdress,
                            c.city_id,
                            c.city_name,
                            c.postal_code,
                            a.is_default
                        FROM address a
                          LEFT JOIN city c ON c.city_id=a.city_id
                        WHERE a.user_id = {$id} and (a.deleted_at is null)
                        ORDER BY a.is_default DESC");
        if (count($r)>0) {
            return  $r[0];
        }
    }

    public static function mergeUserAddresses($guest_id, $user_id)
    {
        // $user_default_address_id  = DB::table("address")->where("user_id","=",$user_id)->where("is_default","=",1)->value("address_id");
        $guest_default_address_id = DB::table("address")
                                        ->where("guest_id", "=", $guest_id)
                                        ->where("is_default", "=", 1)
                                        ->value("address_id");

        $a = new Address;
        $a->setDefaultAddress($guest_default_address_id);

        DB::update("UPDATE address SET user_id = {$user_id} WHERE guest_id = {$guest_id}");
    }



/**
 *      Preneto iz AddressControllera *************************************
 */
    public function setShippingAddressEx(CartService $cartService, $data) {
        $errors = new MessageBag();
        if ($old = Input::old('errors')) {
            $errors = $old;
        }
        $data['errors'] = $errors;

        $validator = Validator::make($data, $this->valRules, $this->valMsgs);
        if ($validator->passes()) {
            try {
                $a = $this->addUserAddressEx($data);
                if ($a["validation_failed"] == 1) {
                    return $a;
                }

                $cart_id = (new Cart)->getOpenCartId($cartService);

                if (!(isset($cart_id)) || ($cart_id<=0)) {
                    $cart_id = $c->newCart();
                }
                $cart = Cart::find($cart_id);

                if (!is_null($cart)){
                    $cart->shipping_option_id = ShippingOption::CUSTOM_ADDRESS;
                    $cart->shipping_method_id = ShippingMethod::COURIER_PAYABLE;
                    $cart->shipping_to_address_id = $a['address_id'];
                    if(isset($data['same_billing_address'])) {
                        $cart->billing_to_address_id = $a['address_id'];
                    }
                    $cart->setSummary();
                    $cart->save();

                    // $cart->setShippingCost();
                    // $cart->setCartSum($cart_id);
                }

                return array(
                    "validation_failed"=>0,
                    "errors"=>array()
                );
            } catch (Exception $e) {
                $err[] = "Došlo je do greške: ".$e;
                return array(
                    "validation_failed"=>1,
                    "errors"=>$err
                );
            }
        }
        $data["errors"] = $validator->errors();
        return array(
            "validation_failed"=>1,
            "errors"=>$validator->messages()->toArray()
        );
    }

    public function updAddressEx($data) {
        $errors = new MessageBag();
        if($old = Input::old('errors')){
            $errors = $old;
        }
        $data['errors'] = $errors;

        $validator = Validator::make($data, $this->valRules, $this->valMsgs);
        if($validator->passes()){
            try {
                $aid = $data['address_id'];
                // $this->address->updAddress($aid);
                $this->updAddress2($data);
                return array(
                    "validation_failed"=>0,
                    "errors"=>array()
                );
            } catch (Exception $e) {
                $err[] = "Došlo je do greške: ".$e;
                return array(
                    "validation_failed" => 1,
                    "errors" => $err
                );
            }
        }
        $data["errors"] = $validator->errors();
        return array(
            "validation_failed"=>1,
            "errors"=>$validator->messages()->toArray()
        );
    }

    public function addUserAddressEx($data){
        $aid=0;
        $errors = new MessageBag();
        if($old = Input::old('errors')){
            $errors = $old;
        }
        $data['errors'] = $errors;

        $validator = Validator::make($data, $this->valRules, $this->valMsgs);
        if($validator->passes()){
            try {
                if (Auth::check()){
                    $aid = $this->addAddress(Auth::user()->user_id, 'user');
                    // return (int)$this->address->addAddress(Auth::user()->user_id, 'user');
                } else {
                    $aid = $this->addAddress(Guest::getGuestId(), 'guest');
                    // return (int)$this->address->addAddress(Guest::getGuestId(), 'guest');
                }
                return array(
                    "validation_failed"=>0,
                    "errors"=>array(),
                    "address_id"=>$aid
                );
            } catch (Exception $e) {
                $err[] = "Došlo je do greške: ".$e;
                return array(
                    "validation_failed"=>1,
                    "errors"=>$err,
                    "address_id"=>$aid
                );
            }

        }
        $data["errors"] = $validator->errors();
        return array(
            "validation_failed"=>1,
            "errors"=>$validator->messages()->toArray(),
            "address_id"=>$aid
        );
    }

}
