(function($) {
    $.Shop = function(element) {
        this.$element = $(element);
        this.init();
    };

    $.Shop.prototype = {
        init: function () {
            this.cookiesAllowed = this.areCookiesEnabled();

            if (!this.cookiesAllowed) {
                var msg = 'Sajt gumamax.com koristi kolačiće (cookies) kako bi poboljšao funkcionalnost stranice i prilagodio sistem pretrage. Više o kolačićima pročitajte u uslovima korišćenja. Ukoliko želite da nastavite sa korišćenjem sajta potrebno je da omogućite rad sa kolačićima.';
                swal("Kolačići", msg, "info");
            }

            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });

            // Properties
            this.cartPrefix = "gmx-"; // Prefix string to be prepended to the cart's name in the session storage
            this.cartName = this.cartPrefix + "cart"; // Cart name in the session storage
            this.storage = sessionStorage; // shortcut to the sessionStorage object
            this.currency_str = "din"; // Currency symbol as textual string
            this.currency = "RSD"; // Currency ISO 4217 symbol https://en.wikipedia.org/wiki/ISO_4217
            this.calcInProgressMessage = 'Izračunavam...';
            this.subdomain = this._getGumamaxSubdomain(window.location.origin);

            if (this.subdomain != null) {
                this.getSubdomainInfo();
            } else {
                this.init2()
            }
        },

        init2: function () {
            var self = this;
            // Method invocation

            self._bindElements();

            self.createCart();

            self.displayShippingPage();
            self.handleAddToCartForm();
            self.emptyCart();
            self.displayCart();
            self.deleteProduct();

            self.handleShippingToPartner();
            self.handleShippingToAddress();
            self.handleChangeShippingMethod();
        },

        _bindElements: function () {
            //Popover cart
            this.$pageHeader = this.$element.find(".cart.cart_popup_link > span.count"); // Placeholder for total qty counter
            this.$cartPopoverPlaceHolder = this.$element.find(".cart-popover"); //Popover cart view
            this.$cartPopoverContent = this.$element.find(".dropdown-menu .cart-menu"); // Placeholder for mouse over for cart popover

            //Add to cart
            this.$addToCartBtn = this.$element.find(".addToCartBtn"); // Forms for adding items to the cart

            //Cart page
            this.$emptyCartBtn = this.$element.find(".empty-cart"); // Empty cart button
            this.$cartIsEmptyHeader = this.$element.find("#cart-is-empty-header"); //This header will be shown when cart is empty
            this.$cartIsNotEmptyHeader = this.$element.find("#cart-not-empty-header"); //This header will be shown when cart is not empty
            this.$cartItems = this.$element.find("#cart-items"); // Shopping cart items
            this.$cartShippingOption = this.$element.find("#cart-shipping-option");
            this.$cartShippingMethod = this.$element.find("#cart-shipping-method");
            this.$cartTotals = this.$element.find("#cart-totals"); // Shopping cart items
            this.$cartInstallationCosts = this.$element.find("#cart-installation-costs"); // Shopping cart items
            this.$cartCheckOutForm = this.$element.find("#cart-checkout-form");


            //Shipping
            this.$shippingSelectedAddress = this.$element.find("#selected-shipping-address"); //Selected address for shipping custom or partners)
            this.$shippingChangeLocation = this.$element.find("#btnChangeShippingLocation"); //Change shipping location button
            this.$shippingTabs = this.$element.find("#search-shipping-location"); // Search  shipping location form (tabs)
            this.$shippingSetPartnerForm = this.$element.find(".set_partner"); // Search  shipping location form (tabs)
            this.$shippingPartnerLinkMoreInfo = this.$element.find(".link-more-info"); // Link for more info about partner
            this.$shippingCustomAddressForm = this.$element.find('#custom-shipping-address');//custom address form
        },


        // Public methods

        //Bind products list to shop
        //Using after fetching products search results

        bindProducts: function () {
            this._bindElements();
            this._renderCartPopover2();
            this.handleAddToCartForm();
        },

        // Creates the cart keys in the session storage

        createCart: function () {
            var self = this;
            var cart = {};
            if (this.storage.getItem(this.cartName) == null) {

                cart.uuid = generateUUID();
                cart.member_id = null;
                cart.subdomain = self.subdomain;
                cart.currency_str = self.currency_str;
                cart.currency = self.currency;
                cart.shipping_option_id = 1; //1 - Shipping to gumamax partner location, 2 - custom address
                cart.shipping_method_id = 1; //1 - Free(gumamax), 2 - courier
                cart.shipping_to_partner_id = null;

                cart.payment_method_id = 5; //Kartica

                cart.shipping_recipient = null;
                cart.shipping_address = null;
                cart.shipping_address2 = null;
                cart.shipping_postal_code = null;
                cart.shipping_city = null;
                cart.shipping_phone = null;
                cart.shipping_email = null;
                cart.shipping_additional_info = null;
                cart.shipping_country_code = null;
                cart.shipping_country_iso_alpha_2 = null;
                cart.shipping_country_iso_alpha_3 = null;

                cart.shipping_courier_price = 0.00;

                if (self.subdomain != null) {
                    var subdomainShipping = self._getSubDomainObj();
                    cart.member_id = subdomainShipping.member_id;
                    cart.shipping_to_partner_id = subdomainShipping.partner_id;
                    cart.shipping_recipient = subdomainShipping.shipping_recipient;
                    cart.shipping_address = subdomainShipping.shipping_address;
                    cart.shipping_address2 = subdomainShipping.shipping_address2;
                    cart.shipping_postal_code = subdomainShipping.shipping_postal_code;
                    cart.shipping_city = subdomainShipping.shipping_city;
                    cart.shipping_phone = subdomainShipping.shipping_phone;
                    cart.shipping_email = subdomainShipping.shipping_email;
                    cart.shipping_country_code = subdomainShipping.shipping_country_code;
                }

                cart.billing_recipient = null;
                cart.billing_address = null;
                cart.billing_address2 = null;
                cart.billing_postal_code = null;
                cart.billing_city = null;
                cart.billing_additional_info = null;
                cart.billing_phone = null;
                cart.billing_email = null;
                cart.billing_country_code = null;

                cart.items = [];

                cart.installation = {alu: 0, cel: 0};

                cart.showInstallationCosts = false;

                cart.list_amount = 0;
                cart.discount_amount = 0;
                cart.amount_with_tax = 0;

                cart.shipping_amount_without_tax = 0;
                cart.shipping_tax_amount = 0;
                cart.shipping_amount_with_tax = 0;

                cart.total_amount_without_tax = 0;
                cart.total_tax_amount = 0;
                cart.total_amount_with_tax = 0;

                cart.total_qty = 0;
                cart.items_count = 0;
                cart.total_weight = 0;

                cart.available_payment_methods = [];

                self._saveCartObj(cart);

            } else {
                cart = self._getCartObj();
            }

            self._renderCartPopover2(cart);
        },

        //Shipping
        bindShipping: function () {
            this._bindElements();
            this.handleShippingToPartner();
            this.handleShippingToAddress();
        },

        displayShippingPage: function () {
            var self = this;
            var cart = self._getCartObj();

            self.$shippingChangeLocation.on('click', function (e) {
                self._setShippingInputForm(cart.shipping_option_id);
            });

            if (cart.shipping_recipient === null) {
                self._setShippingInputForm(cart.shipping_option_id);
            } else {
                self.$shippingTabs.hide();
                self.$shippingSelectedAddress.show();
                self.$shippingSelectedAddress.find('.recipient').text(cart.shipping_recipient);
                self.$shippingSelectedAddress.find('.address').text(
                    cart.shipping_address + ', ' + cart.shipping_postal_code + ' ' + cart.shipping_city);
            }
        },

        handleShippingToPartner: function () {
            var self = this;
            self.$shippingSetPartnerForm.each(function () {
                var $partner = $(this);

                $partner.on("click", function (e) {
                    e.preventDefault();
                    showLoading();
                    var cart = self._getCartObj();
                    cart.member_id = $partner.data('member_id');

                    cart.shipping_option_id = 1;
                    cart.shipping_to_partner_id = $partner.data('erp_partner_id');
                    cart.shipping_method_id = 1;

                    cart.shipping_recipient = $partner.data('recipient');
                    cart.shipping_address = $partner.data('address');
                    cart.shipping_address2 = '';
                    cart.shipping_postal_code = $partner.data('postal_code');
                    cart.shipping_city = $partner.data('city_name');
                    cart.shipping_email = $partner.data('email');
                    cart.shipping_phone = $partner.data('phone');
                    cart.shipping_country_code = $partner.data('country_code');
                    cart.shipping_country_iso_alpha_2 = $partner.data('country_iso_alpha_2');
                    cart.shipping_country_iso_alpha_3 = $partner.data('country_iso_alpha_3');

                    self._saveCartObj(cart);
                    self._updateCosts(cart, self._redirectToCartPage);
                });
            });

            self.$shippingPartnerLinkMoreInfo.each(function () {
                var link = $(this);
                link.on('click', function (e) {
                    e.preventDefault();
                    $(this).toggleClass('closed open');
                    var moreInfoDiv = $(this).siblings('.more-info');
                    var installationCostsDiv = moreInfoDiv.find('.installation-costs');
                    installationCostsDiv.empty();
                    moreInfoDiv.slideToggle(200);
                    var memberId = $(this).closest('.servis').data('member_id');
                    if ($(this).hasClass('open')) {
                        installationCostsDiv.append('<div class="loader"></div>');
                        self._getShippingPartnerMoreInfo(memberId, installationCostsDiv);
                    }
                });
            });
        },

        //Shipping to the custom address

        handleShippingToAddress: function () {
            var self = this;
            self.$shippingCustomAddressForm.on('submit', function (e) {
                if (!self.$shippingCustomAddressForm.valid()) return false;
                e.preventDefault();
                var cart = self._getCartObj();
                var $cityElement = $('#shipping_postal_code');

                cart.member_id = null;

                cart.shipping_option_id = 2;
                cart.shipping_to_partner_id = null;
                cart.shipping_method_id = 2;

                cart.shipping_recipient = $('#shipping_recipient').val();
                cart.shipping_address = $('#shipping_address').val();
                cart.shipping_postal_code = $cityElement.val();
                cart.shipping_city = $cityElement.find('option:selected').text();
                cart.shipping_phone = $('#shipping_phone').val();
                cart.shipping_email = $('#shipping_email').val();
                cart.shipping_additional_info = $('#shipping_additional_info').val();
                cart.shipping_country_code = 'SRB';
                cart.shipping_country_iso_alpha_2 = 'RS';
                cart.shipping_country_iso_alpha_3 = 'SRB';

                self._saveCartObj(cart);
                self._updateCosts(cart, self._redirectToCartPage);
            });
        },

        _fillShippingAddressForm: function () {
            var self = this;
            var cart = self._getCartObj();
            if ((cart.shipping_method_id == 2) && (cart.shipping_recipient != null)) {
                $('#shipping_recipient').val(cart.shipping_recipient);
                $('#shipping_address').val(cart.shipping_address);
                $('#shipping_postal_code').val(cart.shipping_postal_code);
                $('#shipping_phone').val(cart.shipping_phone);
                $('#shipping_email').val(cart.shipping_email);
                $('#shipping_additional_info').val(cart.shipping_additional_info);
            }
        },

        // Delete a product from the shopping cart

        deleteProduct: function () {
            var self = this;
            self.$cartItems.on("click", ".remove", function (e) {
                e.preventDefault();
                var productId = $(this).data('product_id');
                var merchantId = $(this).data('merchant_id');
                var msg = $(this).data('additional_description');
                swal({
                        title: "Brisanje stavke",
                        text: msg,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Da",
                        cancelButtonText: "Ne",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    },

                    function (isConfirm) {
                        if (isConfirm) {
                            self._deleteItem(merchantId, productId);
                        }
                    });
            });
        },

        // Displays the shopping cart

        displayCart: function (cart) {
            var self = this;
            if (typeof cart === 'undefined') {
                cart = self._getCartObj();
            }
            self._renderCartHeader(cart);
            self._renderCartItems(cart);
            self._renderShippingOption(cart);
            self._renderShippingMethod(cart);
            self._renderCartTotal(cart);
            self._renderInstallationCosts(cart);
            self._renderCheckOutForm(cart);
        },

        // Empties the cart by calling the _emptyCart() method
        // @see $.Shop._emptyCart()

        emptyCart: function () {
            var self = this;
            if (self.$emptyCartBtn.length) {
                self.$emptyCartBtn.on("click", function () {
                    var cart = self._getCartObj();
                    if (cart.items_count > 0) {
                        swal({
                                title: "Brisanje svih stavki iz korpe",
                                text: '',
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "Da",
                                cancelButtonText: "Ne",
                                closeOnConfirm: true,
                                closeOnCancel: true
                            },

                            function (isConfirm) {
                                if (isConfirm) {
                                    self._emptyCart(cart);
                                }
                            }
                        );
                    }
                })
            }
        },


        // Adds items to the shopping cart

        handleAddToCartForm: function () {
            var self = this;
            self.$addToCartBtn.each(function () {
                var $form = $(this).parent();
                var $product = $(this);
                $(this).on("click", function (e) {
                    e.preventDefault();
                    // showLoading();
                    var item = self._getItemDataFromHtml($product);
                    var qty = self._convertString($form.find('select[name="quantity"]').val());
                    self._calcItem(item, qty);
                    self._addToCart(item);
                });
            });
        },

        checkShippingOptions: function(cart) {
            if ((cart.shipping_option_id == 1) && (!cart.shipping_to_partner_id)) {
                swal({
                    title: "Nije izabrano mesto isporuke.",
                    text: '',
                    type: "warning",
                    confirmButtonColor: "#DD6B55",
                    closeOnConfirm: true,
                });
                return false;
            } else {
                return true;
            }
        },

        // Handles the checkout form by adding a validation routine and saving user's info into the session storage

        handleCheckoutOrderForm: function () {
            var self = this;

            self.$cartBtnStartCheckOut.on('click', function (e) {
                e.preventDefault();

                if (!authCheck()) {
                    self._redirectToLoginPage('cart/edit');
                    return false;
                }

                var cart = self._getCartObj();
                if (cart.total_qty == 0) {
                    return false;
                }

                if (!self.checkShippingOptions(cart)) {
                    return false;
                }

                if (!self.$cartCheckOutFormPaymentMethod.val()) {
                    return false;
                }

                self.$cartBtnStartCheckOut.prop('disabled', true);

                showLoading();

                $.ajax({
                    type: "post",
                    url: urlTo("checkout/start"),
                    data: {cart: cart},
                    dataType: "json"
                }).done(function (data) {
                    self.$cartBtnStartCheckOut.prop('disabled', false);
                    if (data.error != null) {
                        self._renderCheckoutError(data);
                    } else
                    if (data.erp_result.status == "10.00.00") {
                        self._handleOrderSuccessful(data);
                    } else {
                        self._handleOrderUnsuccessful(data);
                    }
                }).fail(function (xhr, textStatus, errorThrown) {
                    self.$cartBtnStartCheckOut.prop('disabled', false);
                    dmxErrorModalDialogLight('Greška.handleCheckoutOrderForm.fail()', xhr.responseJSON.error).open();
                }).always(function(){
                    hideLoading();
                });
            });

            self.$cartCheckOutFormPaymentMethod.on('change', function () {
                if ($(this).val() != "") {
                    var cart = self._getCartObj();
                    cart.payment_method_id = $(this).val();

                    self.$cartBtnStartCheckOut.prop('disabled', (cart.items_count==0));

                    self._saveCartObj(cart);
                }
            })
        },

        _redirectToPayPage: function (orderId, checkoutId) {

            window.location.href = urlTo('checkout/payment/execute/' + orderId + '/' + checkoutId);

        },

        _redirectToPayResultPage: function (checkoutId) {

            window.location.href = urlTo('checkout/payment/execute/' + orderId + '/' + checkoutId);

        },

        _redirectToPaymentInstructionsPage: function (orderId) {

            window.location.href = urlTo('checkout/instructions/' + orderId);

        },

        _redirectToThanksPage: function (orderId) {

            window.location.href = urlTo('checkout/thanks?order_id=' + orderId);

        },

        _redirectToLoginPage: function (fromPage) {
            var loginUri = 'login';
            if (typeof fromPage != 'undefined') {
                loginUri = 'login?intended=' + fromPage;
            }
            window.location.href = urlTo(loginUri);

        },

        _renderCheckoutError: function (data) {
            var self = this;
            console.warn(data.error);
            dmxErrorModalDialogLight('Greška', data.error).open();
            /*var dlg = dmxErrorModalDialogLight('Greška..._renderErrorCheckout', data.error);
            dlg.open();
            return false;*/
        },

        _handleOrderUnsuccessful: function (data) {
            var self = this;
            if (data.erp_result.order.items == null) {
                sweetAlert("Oops...", data.status, "warning");
            } else {
                self._handleResultWithReplacements(data);
            }
            return false;
        },

        _handleResultWithReplacements: function(data){
            $('html, body').stop().animate({scrollTop: 0}, 1000);
            var self = this;
            var index;
            var cart = self._getCartObj();
            var erpItems = data.erp_result.order.items;
            for (index = 0; index < erpItems.length; ++index) {
                var erpItem = erpItems[index];
                var item = self._findItem(cart.items, self._convertString(erpItem.product_id), self._convertString(erpItem.merchant_id));
                if (item != null) {
                    item.requested_qty = self._convertString(erpItem.requested_qty);
                    self._calcItem(item, self._convertString(erpItem.qty));
                }
            }
            self._updateCosts(cart);
            self._renderCartItems(cart);
        },

        _handleOrderSuccessful: function (data) {
            var self = this;
            var cart = self._getCartObj();
            var payment_method_id = cart.payment_method_id;
            var orderId = data.erp_result.newOrder.id;
            var checkoutId = data.erp_result.newOrder.checkout_id;

            self._emptyCart(cart, function() {
                if (payment_method_id == 5) {
                    self._redirectToPayPage(orderId, checkoutId);
                } else {
                    self._redirectToPaymentInstructionsPage(orderId);
                }
                hideLoading();
                return false;
            });
        },


        handleChangeItemQty: function() {
            var self = this;
            self.$cartItemQty.on('change', function(e) {
                e.preventDefault();
                var cart = self._getCartObj(),
                    t = $(this),
                    productId = t.data('product_id'),
                    merchantId = t.data('merchant_id'),
                    qty = self._convertString(t.val()),
                    item = self._findItem(cart.items, productId, merchantId);
                self._updateCartItem(item, qty);
            });
        },

        handleChangeShippingMethod: function() {
            var self = this;
            var cart = self._getCartObj();
            self.$shippingMethodRadio = self.$element.find('.radio-container');
            if (self.$shippingMethodRadio.length>0){
                self.$shippingMethodRadio.on('click', function() {

                    var cart = self._getCartObj();
                    var radio = $(this).find(".radiobutton input[type=radio]");
                    var newValue = radio.val();
                    if ((cart.shipping_option_id==2)&&(newValue==1))
                    {
                        sweetAlert('Ops...', 'Isporuka na Vašu adresu je moguća samo brzom poštom.');

                        return false;
                    }
                    if (newValue==1){
                        cart.shipping_method_id=1;
                    }else {
                        cart.shipping_method_id=2;
                    }
                    self._updateCosts(cart);
                });
            }
        },

        // Private methods


        _setShippingInputForm: function(shippingOptionId) {
            var self=this;
            var tab = getUrlParameter('tab');

            if (shippingOptionId == 1) {
                tab = 'partner';

            } else if (shippingOptionId == 2) {
                tab = 'address';

            } else
            if (typeof tab === 'undefined') {
                tab = 'partner';
            }

            self.$shippingTabs.show();

            $('.search ul.nav.nav-tabs li > a[data-shipping_option="' + tab + '"]').tab('show');

            if (tab == 'partner') {
                $('#cities').focus();

            } else {
                self._fillShippingAddressForm();
                $('#shipping_recipient').focus();
               }

        },

        _getShippingPartnerMoreInfo: function($memberId, $installationCostsDiv) {
            var cart = this._getCartObj();
            $.ajax({
                type: "GET",
                url: urlTo("api/partner/shipping-more-info"),
                contentType: "application/json; charset=utf-8",
                data: {
                    member_id: $memberId,
                    items: cart.items
                },
                dataType: "json"
            }).done(function(data) {
                $installationCostsDiv.empty();

                if ((data.install_price.alu!=0) || (data.install_price.cel!=0)) {
                    T.render('gmx-shipping-partner-more-info', function(t) {
                        $installationCostsDiv.append(t(data));
                    });
                }

            }).fail(function() {
                $installationCostsDiv.append('Error!!!');
            });

        },

        //Bind cart item on the Cart page

        _bindCartItems: function() {
            this.$cartItemQty = this.$element.find(".cart_qty"); // Quantity select on cart page
            this.$cartItemShowReplacementsBtn = this.$element.find(".show-replacements");
            this.handleChangeItemQty();
            this.handleShowReplacements();
        },

        _handlePaymentSuccessful: function(checkout_id) {
            var cart = self._getCartObj();

            self._emptyCart(cart, function() {
                self._redirectToPayPage(orderId, checkoutId);
            });
        },

        handleShowReplacements : function(){
          var self = this;
            self.$cartItemShowReplacementsBtn.each(function() {
                var $replacementHolder = $(this).parent().parent().parent().parent().find('.cart-item-replacements');
                getReplacements($replacementHolder);
                $(this).on("click", function(e) {
                    e.preventDefault();
                    getReplacements($replacementHolder);
                    $(this).hide();
                });
            });
        },

        // Update item quantity on the cart page

        _updateCartItem: function(item, qty, callback) {
            var self = this;
            var cart = self._getCartObj();
            var oldItem = self._findItem(cart.items, item.product_id, item.merchant_id);

            if (oldItem !== null) {
                self._calcItem(oldItem, qty);
                self._updateCosts(cart, callback);
            }
        },

        _renderCartHeader:function(cart){
            var self = this;
            if (cart.items_count == 0) {
                self.$cartIsNotEmptyHeader.hide();
                self.$cartIsEmptyHeader.show();
            } else {
                self.$cartIsNotEmptyHeader.show();
                self.$cartIsEmptyHeader.hide();
            }
        },

        _renderCartItems:function (cart){
            // var self = this;
            // self.$cartItems.empty();
            // T.render('gmx-cart-item', function(t) {
            //     self.$cartItems.append(t(cart));
            //     self._bindCartItems();
            // });
        },

        _renderCartTotal:function(cart){
            // var self = this;
            // self.$cartTotals.empty();
            // T.render('gmx-cart-totals', function(t) {
            //     self.$cartTotals.append(t(cart));
            // });
        },

        _renderShippingOption:function(cart){
            // var self=this;
            // if (cart.subdomain==null){
            //     self.$cartShippingOption.show();
            //     self.$cartShippingOption.empty();
            //     T.render('gmx-cart-shipping-option', function(t) {
            //         self.$cartShippingOption.append(t(cart));
            //     });
            // } else {
            //     self.$cartShippingOption.hide();
            // }

        },

        _renderShippingMethod:function(cart){
            // var self = this;
            // self.$cartShippingMethod.empty();
            // T.render('gmx-cart-shipping-method', function(t) {
            //     self.$cartShippingMethod.append(t(cart));
            //     self.handleChangeShippingMethod();
            // });
        },

        _renderInstallationCosts:function(cart){
            // var self = this;
            // self.$cartInstallationCosts.empty();
            // cart.showInstallationCosts = (cart.shipping_to_partner_id != null) &&
            //     ((cart.installation.alu != 0.00) || (cart.installation.cel != 0.00));
            //
            // T.render('gmx-cart-installation-costs', function(t) {
            //     self.$cartInstallationCosts.append(t(cart));
            // });
        },

        _renderCheckOutForm:function(cart){
            // var self = this;
            // self.$cartCheckOutForm.empty();
            // T.render('gmx-cart-checkout-form', function(t) {
            //     self.$cartCheckOutForm.append(t(cart));
            //
            //     self.$cartBtnStartCheckOut = self.$element.find("#btnStartCheckOut");
            //     self.$cartCheckOutFormPaymentMethod = self.$element.find("#payment_method_id");
            //
            //     if (cart.available_payment_methods.length>0) {
            //
            //         self.$cartCheckOutFormPaymentMethod.val(cart.payment_method_id);
            //
            //         if (!self.$cartCheckOutFormPaymentMethod.val()) {
            //             self.$cartCheckOutFormPaymentMethod.val("");
            //         }
            //
            //         self.handleCheckoutOrderForm();
            //     }
            //
            //     self.$cartBtnStartCheckOut.prop('disabled', (cart.items_count==0 ||
            //         !self.$cartCheckOutFormPaymentMethod.val()));
            // });
        },


        // Empties the session storage
        _emptyCart: function(cart, callback) {
            var self = this;
            cart.items = [];

            if(callback && typeof(callback) === "function") {
                self._updateCosts(cart, callback);
            } else  {
                self._updateCosts(cart, self.displayCart.bind(self));
            }
        },

        /* Format a number by decimal places
         * @param num Number the number to be formatted
         * @param places Number the decimal places
         * @returns n Number the formatted number
         */

        _formatNumber: function(num, places) {
            return num.toFixed(places);
        },

        /* Extract the numeric portion from a string
         * @param element Object the jQuery element that contains the relevant string
         * @returns price String the numeric string
         */


        _extractPrice: function(element) {
            var self = this;
            var text = element.text();
            return text.replace(self.currency_str, "").replace(" ", "");
        },

        /* Converts a numeric string into a number
         * @param numStr String the numeric string to be converted
         * @returns num Number the number
         */

        _convertString: function(numStr) {
            var num;
            if (/^[-+]?[0-9]+\.[0-9]+$/.test(numStr)) {
                num = parseFloat(numStr);
            } else if (/^\d+$/.test(numStr)) {
                num = parseInt(numStr, 10);
            } else {
                num = Number(numStr);
            }

            if (!isNaN(num)) {
                return num;
            } else {
                console.warn(numStr + " cannot be converted into a number");
                return false;
            }
        },

        /* Converts a number to a string
         * @param n Number the number to be converted
         * @returns str String the string returned
         */

        _convertNumber: function(n) {
            var str = n.toString();
            return str;
        },

        /* Converts a JSON string to a JavaScript object
         * @param str String the JSON string
         * @returns obj Object the JavaScript object
         */

        _toJSONObject: function(str) {
            var obj = JSON.parse(str);
            return obj;
        },

        /* Converts a JavaScript object to a JSON string
         * @param obj Object the JavaScript object
         * @returns str String the JSON string
         */

        _toJSONString: function(obj) {
            var str = JSON.stringify(obj);
            return str;
        },

        _getCartObj: function() {
            var self = this;
            var cart = self.storage.getItem(self.cartName);
            return self._toJSONObject(cart);
        },

        _saveCartObj: function(cart) {

            var self = this;

            self.storage.setItem(self.cartName, self._toJSONString(cart));

        },

        /* Add an object to the cart as a JSON string
         * @param values Object the object to be added to the cart
         * @returns void
         */

        _addToCart: function(newItem) {
            var self = this;
            var cart = self._getCartObj();
            var items = cart.items;
            var oldItem = self._findItem(items, newItem.product_id, newItem.merchant_id);

            if (oldItem === null) {
                cart.items.push(newItem);
                self._updateCosts(cart, self._redirectToCartPage);
            } else {
                var newQty = oldItem.qty + newItem.qty;
                self._updateCartItem(oldItem, newQty, self._redirectToCartPage);
            }
        },

        _findItem: function(items, productId, merchantId) {

            var result = $.grep(items, function(e) {
                return ((e.product_id === productId) && (e.merchant_id === merchantId));
            });

            if (result.length == 0) {
                return null;
            } else if (result.length == 1) {
                return result[0];
            }

            throw "Multiple rows";
        },

        _deleteItem: function(merchantId, productId) {
            var self = this;
            var cart = self._getCartObj();

            for (var i = 0; i < cart.items.length; ++i) {
                var item = cart.items[i];
                if ((item.product_id == productId) && (item.merchant_id == merchantId)) {
                    cart.items.splice(i, 1);
                    cart.total_qty -= item.qty;
                    cart.items_count--;
                }
            }

            self._updateCosts(cart, self.displayCart.bind(self));
        },

        _setCartSum: function(cart) {
            var self = this;
            var index;
            cart.total_qty = 0;
            cart.items_count = cart.items.length;

            cart.list_amount = 0.00;
            cart.discount_amount = 0.00;
            cart.amount_with_tax = 0.00;
            cart.amount_without_tax = 0.00;
            cart.tax_amount = 0.00;

            cart.total_amount_with_tax = 0.00;
            cart.total_tax_amount = 0.00;
            cart.total_amount_without_tax = 0.00;
            cart.total_weight = 0.00;

            for (index = 0; index < cart.items.length; ++index) {
                var item = cart.items[index];
                cart.amount_without_tax += item.amount_without_tax;
                cart.tax_amount += item.tax_amount;
                cart.amount_with_tax += item.amount_with_tax;
                cart.list_amount += item.list_amount;
                cart.total_qty += item.qty;
                cart.total_weight += item.weight;
            }
            cart.discount_amount = cart.list_amount - cart.amount_with_tax;

            cart.total_amount_without_tax = cart.amount_without_tax + cart.shipping_amount_without_tax;
            cart.total_tax_amount       = cart.tax_amount + cart.shipping_tax_amount;
            cart.total_amount_with_tax = cart.amount_with_tax + cart.shipping_amount_with_tax;

            cart.discount_amount = cart.list_amount - cart.amount_with_tax;

            cart.total_amount_without_tax = self._numberFormat(cart.total_amount_without_tax,2,'.','');
            cart.total_tax_amount = self._numberFormat(cart.total_tax_amount, 2,'.','');
            cart.total_amount_with_tax = self._numberFormat(cart.total_amount_with_tax, 2,'.','');

            self._saveCartObj(cart);
            self._renderCartPopover2(cart);
            self._renderCartTotal(cart);
        },

        _renderCartPopover2: function(cart) {
            // var self = this;
            //
            // if (typeof cart==='undefined'){
            //     cart = self._getCartObj();
            // }
            //
            // self.$pageHeader.text(cart.total_qty);
            //
            // if (self.$cartPopoverContent.length > 0) {
            //     self.$cartPopoverContent.remove();
            // }
            //
            // T.render('gmx-cart-popover', function(t) {
            //     self.$cartPopoverContent = $(t(cart));
            //     self.$cartPopoverContent.appendTo(self.$cartPopoverPlaceHolder);
            // });

        },
        //Get shipping costs for courier service (shipping_method_id=2)

        _updateCosts: function(cart, callback) {
            var self = this;
            self._setCalcInProgressLabel();
            $.ajax({
                type: "post",
                url: urlTo("api/cart/costs"),
                data: {cart:cart},
                dataType: "json"
            }).done(function(costs) {
                cart.shipping_courier_price = costs.shipping.courier_price;
                cart.available_payment_methods = costs.available_payment_methods;

                if ((cart.shipping_method_id == 2)) {
                    cart.shipping_amount_with_tax = self._numberFormat(costs.shipping.amount_with_tax,2,'.','');
                    cart.shipping_amount_without_tax = self._numberFormat(costs.shipping.amount_without_tax,2,'.','');
                    cart.shipping_tax_amount = self._numberFormat(costs.shipping.tax_amount,2,'.','');
                }else{
                    cart.shipping_amount_with_tax = 0.00;
                    cart.shipping_amount_without_tax = 0.00;
                    cart.shipping_tax_amount = 0.00;
                }

                if (cart.member_id != null){
                    cart.installation = costs.installation;
                } else {
                    cart.installation.alu=0.00;
                    cart.installation.cel=0.00;
                }

                self._setCartSum(cart);
                self._renderShippingMethod(cart);
                self._renderCartTotal(cart);
                self._renderInstallationCosts(cart);
                self._renderCheckOutForm(cart);

                if (typeof callback === "function") {
                    callback(cart);
                }

            }).fail(function(jqXHR, textStatus) {
            });
        },

        //Extract hostname
        _getHostname: function(url) {
            var match = url.match(/:\/\/(www[0-9]?\.)?(.[^/:]+)/i);
            if (match != null && match.length > 2 && typeof match[2] === 'string' && match[2].length > 0) return match[2];
        },

        //Get gumamax subdomain
        _getGumamaxSubdomain: function(url) {
            var hostname = this._getHostname(url);
            var parts = hostname.split('.');
            var sub = parts[0];
            return '';
            return ((sub != 'gumamax') && (sub != 'GUMAMAX')&&(sub!='dev')&&(sub!='devgumamax')&&(sub!='gumamaxl8')) ? sub : null;
        },

        getSubdomainInfo :function(){
            var self = this;
            $.ajax({
                type: "post",
                url: urlTo('/api/shipping/subdomain'),
                data :'',
                dataType: "json"
            }).done(function(data){
                self._saveSubdomainInfo(data);
                self.init2();
            })
            .fail(function(){
                //
            });
        },

        _saveSubdomainInfo: function(data){
               var self = this;
                self.storage.setItem(self.subdomain, self._toJSONString(data));
        },

        // Calculate item amounts

        _calcItem: function(item, newQty) {
            item.qty = newQty;
            item.list_amount = item.qty * item.list_price;
            item.amount_with_tax = item.qty * item.price_with_tax;
            item.discount_amount = item.discount > 0 ? item.list_amount - item.amount_with_tax : 0;
            item.tax_amount = item.amount_with_tax * item.tax_rate / (100 + item.tax_rate);
            item.amount_without_tax = item.amount_with_tax - item.tax_amount;
            item.weight = item.qty * item.product_weight;
        },

        //Get product data for item from an HTML element

        _getItemDataFromHtml: function($element) {
            var self = this;
            return {
                product_id: $element.data("product_id"),
                merchant_id: $element.data("merchant_id"),
                description: $element.data("description"),
                description_id: $element.data("description_id"),
                additional_description: $element.data("additional_description"),
                manufacturer: $element.data("manufacturer"),
                manufacturer_id: $element.data("manufacturer_id"),
                uom_id: $element.data("uom_id"),
                packing: $element.data("packing"),
                cat_no: $element.data("cat_no"),
                img_xs_url: $element.data("img_xs_url"),
                img_sm_url: $element.data("img_sm_url"),
                img_lg_url: $element.data("img_lg_url"),
                diameter: $element.data("diameter"),
                vehicle_category: $element.data("vehicle_category"),
                tax_id: $element.data("tax_id"),
                product_weight: $element.data("product_weight"),
                season: $element.data("season"),
                country_of_origin: $element.data("country_of_origin"),
                year_of_production: $element.data("year_of_production"),
                action_price: self._convertString($element.data("action_price")),
                list_price: self._convertString($element.data("list_price")),
                super_price: self._convertString($element.data("super_price")),
                price_with_tax: self._convertString($element.data("price_with_tax")), //check sql for synchronisation
                discount: self._convertString($element.data("discount")),
                weight: self._convertString($element.data("product_weight")),
                tax_rate: self._convertString($element.data("tax_rate")),
                requested_qty : null
            };
        },

        _redirectToCartPage: function(cart){
                if (useSubDomain!==''){
                    window.location.href=urlTo('cart/edit');
                }else
                    if (cart.shipping_recipient==null){
                        if (cart.shipping_option_id == 1){
                            window.location.href=urlTo('shipping/location?tab=partner');
                        }else if (cart.shipping_option_id == 2){
                            window.location.href=urlTo('shipping/location?tab=address');
                        }
                    } else {
                        window.location.href=urlTo('cart/edit');
                    }

        },

        _setCalcInProgressLabel:function(){
            var self = this;
            var labels=[
                self.$element.find(".shipping_courier_offer"), // Shipping value for courier method selector
                self.$cartTotals.find('.shipping_amount_with_tax'),
                self.$cartTotals.find('.total_amount_with_tax'),
                self.$cartInstallationCosts.find('.costs-alu span'),
                self.$cartInstallationCosts.find('.costs-cel span')];
            $.each(labels, function(){
                var label=$(this);
                if (label.length > 0) {
                    label.text(self.calcInProgressMessage);
                }
            });
        },

        _numberFormat :function  (num, decimals, dec_point, thousands_sep, show_null_as_zero) {
            if(show_null_as_zero==='undefined') show_null_as_zero = true;
            if(!show_null_as_zero)
                if(isNaN(num) || num==='undefined' || num==null)
                    return '';
            num = (num + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+num) ? 0 : +num,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function (n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return Number(s.join(dec));
        },

        _getSubDomainObj:function(){
            var self = this;
            var data = self.storage.getItem(self.subdomain);
            return self._toJSONObject(data);
        },

        areCookiesEnabled :function() {
            var self = this;
            var r = false;
            self._createCookie("testing", "Hello", 1);
            if (self._readCookie("testing") != null) {
                r = true;
                self._eraseCookie("testing");
            }
            return r;
        },
        _createCookie :function (name, value, days) {
        var expires;
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        }
        else expires = "";
        document.cookie = name + "=" + value + expires + "; path=/";
        },

        _readCookie : function (name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        },

        _eraseCookie : function (name) {
        var self =this;
            self._createCookie(name, "", -1);
        }
    };

/*
     $(function() {
         var shop = new $.Shop(".container" );
     });
*/
})(jQuery);

var shop = new $.Shop(".container-fluid");
console.log('radi')
