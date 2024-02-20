let searchPartnersInput = document.querySelector('#search_partners');
let searchContent = document.querySelector('.search-content');
let partnerBoxesCont = document.querySelector('#choose-partner .row .col-md-6');
let deliveryPlace1 = document.querySelector('#delivery_place1');
let deliveryPlace2 = document.querySelector('#delivery_place2');
searchPartnersInput.addEventListener('input', function() {
    jQuery.ajax({
        url: window.origin + '/cities/json',
        method: 'GET',
        data: {
            term: this.value
        },
        success: function (response) {
            let cities = response;
            let html = '<div class="search-content-header">' +
                '<p><strong>Mesto</strong></p><p><strong>Gumamax</strong></p><p><strong>Brza pošta</strong></p>' +
                '</div>';
            cities.forEach((city) => {
                html += '<div class="search-content-line" data-city-id="' + city.city_id + '" data-latitude="' + city.latitude + '" data-longitude="' + city.longitude + '"><p>' + city.city_name + '</p>';
                html += '<p>' + city.free_shipment + 'h</p>';
                html += '<p>' + city.courier_shipment + 'h</p></div>';
            });
            searchContent.innerHTML = html;
            if(cities.length > 0) {
                searchContent.classList.add('active');
            }

            let searchContentLines = document.querySelectorAll('.search-content-line');
            searchContentLines.forEach((searchContentLine) => {
                searchContentLine.addEventListener('click', function() {
                    let data = {
                        order: 'distance+asc',
                        radius: 5,
                        city_id: this.dataset.cityId,
                        latitude: this.dataset.latitude,
                        longitude: this.dataset.longitude,
                        history: false,
                        delatnost: 2,
                    }
                    jQuery.ajax({
                        url: window.origin + '/api/partner/locator',
                        method: 'GET',
                        contentType: "application/json; charset=utf-8",
                        dataType: 'json',
                        data: {
                            order: 'distance+asc',
                            radius: 5,
                            city_id: this.dataset.cityId,
                            latitude: this.dataset.latitude,
                            longitude: this.dataset.longitude,
                            history: false,
                            delatnost: 2,
                        },
                        success: function(response2) {
                            searchContent.classList.remove('active');
                            document.querySelector('#choose-partner').scrollIntoView(true);
                            let partners = response2.rows;
                            initMap(partners);
                            partnerBoxesCont.innerHTML = '';
                            partners.forEach((partner) => {
                                if((partner.is_installer === 1 && deliveryPlace1.checked) || (partner.is_installer !== 1 && deliveryPlace2.checked)) {
                                    let partnerHtml = `<div class="single-choose-partner">
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <img src="http://127.0.0.1/images/visuals/product-image.png" alt="">
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <h5>${partner.name}</h5>`;
                                    if(partner.is_installer === 1) {
                                        partnerHtml += `<p><i class="fa-solid fa-gear"></i> servis sa mogućnošću montaže</p>`;
                                    }
                                    partnerHtml += `<p><i class="fa-solid fa-location-dot"></i> ${partner.city_name}, ${partner.address}</p>
                                            </div>
                                            <div class="col-md-2">
                                                <img src="http://127.0.0.1/images/visuals/delmax-logo.png" alt="">
                                                <h6>DELMAX PRODAVNICA</h6>
                                            </div>
                                        </div>
                                    </div>`;
                                    partnerBoxesCont.innerHTML += partnerHtml;
                                }
                            });
                        }
                    });
                });
            });
        }
    });
});

function initMap(partners) {
    const initLatLng = { lat: 44.787197, lng: 20.457273 };
    const map = new google.maps.Map(document.getElementById("partners-map"), {
        zoom: 7,
        center: initLatLng,
    });

    partners.forEach((partner) => {
        let lat = partner.latitude;
        let lng = partner.longitude;

        let marker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            icon: window.origin + '/images/visuals/custom-pin.svg',
            map,
        });

        let content = document.createElement('div');
        let title = partner.name;
        let address = partner.address;
        let city = partner.city_name;
        let phone = partner.phone;
        let zip = partner.postal_code;
        content.innerHTML = `<div>
                                <p><strong>${title}</strong><br>
                                ${address}<br>
                                ${zip} ${city}<br>
                                Telefon: ${phone}
                                </p>
                            </div>`;
        let infowindow = new google.maps.InfoWindow({
            content: ''
        });
        google.maps.event.addListener(marker, 'click', (function (marker, content) {
            return function () {
                infowindow.setContent(content);
                infowindow.open(map, marker);
                map.panTo(this.getPosition());
            }
        })(marker, content));
    });
}

window.initMap = initMap;
