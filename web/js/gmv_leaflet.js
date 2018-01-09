var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    osm = L.tileLayer(osmUrl, {maxZoom: 18, attribution: osmAttrib}),
    map = new L.Map('mapedit', {center: new L.LatLng(53.9043, 27.5431), zoom: 13}),
    drawnItems = L.featureGroup().addTo(map);


L.control.layers({
    'osm': osm.addTo(map),
    "google": L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', {
        attribution: 'google'
    })
});//, { 'drawlayer': drawnItems }, { position: 'topleft', collapsed: false }).addTo(map);


var GeoSearchControl = window.GeoSearch.GeoSearchControl;
var OpenStreetMapProvider = window.GeoSearch.OpenStreetMapProvider;

var provider = new OpenStreetMapProvider();

var searchControl = new GeoSearchControl({
    provider: provider,
    style: 'topright',
    showMarker: false,
    retainZoomLevel: false,
    animateZoom: true,
    autoClose: true,
    keepResult: true,

});


map.addControl(new L.Control.Draw({
    edit: {
        polygon: false,
        rectangle: false,
        circle: false,
        featureGroup: drawnItems,
        poly: {
            allowIntersection: false
        }
    },
    draw: {
        polygon: false,
        rectangle: false,
        circle: false
    }

}));


map.on(L.Draw.Event.CREATED, function (event) {
    var layer = event.layer;
    var type = event.layerType;
    drawnItems.addLayer(layer);

});

map.addControl(searchControl);

lc = L.control.locate({
    strings: {
        title: "Показать местоположение!"
    }
}).addTo(map);

function getValJson() {
    return $.parseJSON($('#gmv_gmveventbundle_event_coordinates').val());
}

var geojsonLayer = L.geoJson(getValJson());
drawnItems.addLayer(geojsonLayer);
map.fitBounds(geojsonLayer.getBounds());

function setValJson() {
    var data = drawnItems.toGeoJSON();
    var convertedData = (JSON.stringify(data));
    $('#gmv_gmveventbundle_event_coordinates').val(convertedData);
}