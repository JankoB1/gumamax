let searchPartnersInput = document.querySelector('#search_partners');
let searchContent = document.querySelector('.search-content');
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
                    console.log(data)
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
                            console.log(response2);
                        }
                    });
                });
            });
        }
    });
});

function initMap() {
    const initLatLng = { lat: 44.787197, lng: 20.457273 };
    const map = new google.maps.Map(document.getElementById("partners-map"), {
        zoom: 7,
        center: initLatLng,
    });

    let partners = document.querySelectorAll('.single-partner-in-list');
    partners.forEach((partner) => {
        let lat = parseFloat(partner.dataset.partnerLat);
        let lng = parseFloat(partner.dataset.partnerLng);

        let marker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            icon: window.origin + '/images/visuals/custom-pin.svg',
            map,
        });

        let content = document.createElement('div');
        let title = partner.dataset.partnerDescription;
        let address = partner.dataset.partnerAddress;
        let city = partner.dataset.partnerCity;
        let phone = partner.dataset.partnerPhone;
        let zip = partner.dataset.partnerZip;
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
