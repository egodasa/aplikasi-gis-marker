var event = new my_event();
var event_map = new my_event_map(1000);
var event_map_item = new my_event_map_item();

map.pm.addControls({
    position: 'topleft', // toolbar position, options are 'topleft', 'topright', 'bottomleft', 'bottomright'
    drawMarker: true, // adds button to draw markers
    drawPolyline: false, // adds button to draw a polyline
    drawRectangle: false, // adds button to draw a rectangle
    drawPolygon: false, // adds button to draw a polygon
    drawCircle: false, // adds button to draw a cricle
    cutPolygon: false, // adds button to cut a hole in a polygon
    editMode: false, // adds button to toggle edit mode for all layers
    removalMode: false, // adds a button to remove layers
});

//event ketika marker baru ditambah
map.on('pm:create', function(e){
    // Diserver juga saya cek hak aksesnya. jadi gk bisa manipulasi ya...
    if(Jarak(e.layer._latlng, posisi.getLatLng()) >= 1 && auth.getUserInfo().tipe_user != "Admin"){
      alert("Marker hanya bisa ditempatkan radius maksimal 1Km dari tempat Anda!");
      e.layer.remove();
      event_map.refreshMap();
    }else{
      el("form_tambah_marker", "name").action = "tambah.php";
      el("caption_marker").innerHTML = "Tambah Tempat Baru";
      //layer dimasukkan ke variabel agar bisa dihapus
      currentLayerJSON = e;
      el("koordinat_lat").value = e.layer._latlng.lat;
      el("koordinat_lng").value = e.layer._latlng.lng;
      el("modal_tambah_marker").style.display = 'block';
    }
  });
var locate = setInterval(event_map.initUserLocation, 3500)
auth.cekAuth();
el("button_simpan_marker").disabled = true;
