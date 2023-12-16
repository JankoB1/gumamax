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
