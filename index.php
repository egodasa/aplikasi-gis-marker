<?php
session_start();
require_once("config/database.php");
?>
<!DOCTYPE html>
<html>
 <head>
	<title>Eventos Events</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="dist/css/images/icon.ico" />
  <link rel="stylesheet" href="dist/css/w3.css" />
  <link rel="stylesheet" href="dist/css/leaflet.css" />
  <link rel="stylesheet" href="dist/css/leaflet.pm.css" />
  
  <script src="dist/js/leaflet.js"></script>
  <script src="dist/js/leaflet.pm.min.js"></script>
  <script src="dist/js/turf.min.js"></script>
  <script src="dist/js/axios.min.js"></script>
  <script src="dist/js/store.min.js"></script>
  
  <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=Promise"></script>
	<style type="text/css">
		    body {
			    padding: 0;
			    margin: 0;
		    }
        #map{
          z-index: 1;
        }
		    html, body, #map {
			    height: 100%;
		    }
        .leaflet-bottom {
          margin-bottom: 20px;
        }
    </style>
 </head>
 <body>
	<div id="map"></div>
  
  <!-- Tombol kanan atas  -->
  <div class="leaflet-control-container">
    <div id="kontrol_kanan_atas" class="leaflet-top leaflet-right" style="z-index: 2;">
      <div class="leaflet-control-layers leaflet-control-layers-expanded leaflet-control">
        <div class="w3-bar">
          <button id="button_search" style="display: none;" class="w3-bar-item w3-button w3-green">Search</button>
            <button id="informasi_user" class="w3-bar-item w3-button w3-blue" style="display: none;"></button>
            <button id="button_logout" class="w3-bar-item w3-button w3-red" style="display: none;">Logout</button>
            <button id="button_sign_in" class="w3-bar-item w3-button w3-blue">Sign In</button>
            <button id="button_sign_up" class="w3-bar-item w3-button w3-teal">Sign Up</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Hasil pencarian -->
  <div class="leaflet-control-container">
    <div id="kontrol_kiri_bawah" class="leaflet-bottom leaflet-left" style="display:none;z-index: 2;">
      <div id="kontrol_filter_caption" class="leaflet-control-layers leaflet-control-layers-expanded leaflet-control">
        <!-- Caption Control Filter -->
      </div>
    </div>
  </div>
  
  <!-- Icon posisi user sebelah kanan bawah -->
  <div class="leaflet-control-container">
    <div id="kontrol_kanan_bawah" class="leaflet-bottom leaflet-right" style="z-index: 2;margin-bottom:30px;">
      <div class="leaflet-control-layers leaflet-control-layers-expanded leaflet-control">
        <button id="button_gps" type="w3-btn w3-small w3-light-gray"><img src="dist/css/images/marker-person.png" width="30" height="30"></button>
      </div>
    </div>
  </div>
  
    <!-- Modal Login -->
    <div id="modal_login" class="w3-modal" style="display: none; z-index: 3;">
      <div class="w3-modal-content">
        <div class="w3-container">
          <span onclick="el('modal_login').style.display='none';" class="w3-closebtn w3-right">&times;</span>
          <h3>Sign In</h3>
          <p id="pesan_login"></p>
        </div>
        <div class="w3-container">
          <form name="form_login" onsubmit="return false">
          <p>
          <label>Username</label>
          <input class="w3-input w3-border w3-small" type="text" name="username" minlength="1" maxlength="20" required>
          </p>
          <p>
          <label>Password</label>
          <input class="w3-input w3-border w3-small" type="password" name="password" minlength="1" maxlength="20" required>
          </p>
        </div>
        <div class="w3-container">
          <p>
          <button type="submit" class="w3-btn w3-teal w3-small" id="button_login">Sign In</button>
          <button type="button" class="w3-btn w3-red w3-small" onclick="el('modal_login').style.display='none';">Close</button>
          </p>
        </div>
        </form>
      </div>
    </div>
    
    <!-- Modal Registrasi -->
    <div id="modal_registrasi" class="w3-modal" style="display: none; z-index: 3;">
      <div class="w3-modal-content">
        <div class="w3-container">
          <span onclick="el('modal_registrasi').style.display='none';" class="w3-closebtn w3-right">&times;</span>
          <h3>Sign Up</h3>
          <p id="pesan_registrasi"></p>
        </div>
        <div class="w3-container">
          <form name="form_registrasi" onsubmit="return false">
          <p>
          <label>Username</label>
          <input class="w3-input w3-border w3-small" type="text" name="username" minlength="1" maxlength="20" required >
          </p>
          <p>
          <label>Password</label>
          <input class="w3-input w3-border w3-small" type="password" name="password" minlength="5" maxlength="20" required >
          </p>
          <p>
          <label>Email</label>
          <input class="w3-input w3-border w3-small" type="email" name="email"  minlength="5" maxlength="50" required >
          </p>
        </div>
        <div class="w3-container">
          <p>
          <button type="submit" class="w3-btn w3-teal w3-small" id="button_registrasi">Sign Up</button>
          <button type="button" class="w3-btn w3-red w3-small" onclick="el('modal_registrasi').style.display='none';"  id="button_cancel">Close</button>
          </p>
        </div>
        </form>
      </div>
    </div>
  
    <!-- Modal Tambah Marker -->
    <div id="modal_tambah_marker" class="w3-modal" style="display: none; z-index: 3;">
      <div class="w3-modal-content">
        <div class="w3-container">
          <span onclick="el('modal_tambah_marker').style.display='none'; event_map.refreshMap(); map.pm.disableDraw();" class="w3-closebtn w3-right">&times;</span>
          <h3 id="caption_marker">Tambah Tempat Baru</h3>
        </div>
        <div class="w3-container">
          <p class='w3-text-red' id='pesan_tambah_marker'></p>
          <form name="form_tambah_marker" method="POST" action="tambah.php" enctype="multipart/form-data">
            <input type="hidden" id="id_user" name="id_user" >
            <input type="hidden" id="id_tempat" name="id_tempat">
            <input type="hidden" id="koordinat_lat" name="koordinat_lat">
            <input type="hidden" id="koordinat_lng" name="koordinat_lng">
            <p>
            <label>Judul</label> <input class="w3-input w3-border w3-small" type="text" id="judul" name="judul"  minlength="1" maxlength="100" required></p>
            <p><label>Kategori</label>
            <select class="w3-select w3-border w3-small" name="kategori" id="kategori">
            <option value="Jual Tanah">Jual Tanah</option>
            <option value="Sewa Tanah">Sewa Tanah</option>
            <option value="Jual Rumah">Jual Rumah</option>
            <option value="Sewa Rumah">Sewa Rumah</option>
            <option value="Jual Toko">Jual Toko</option>
            <option value="Sewa Toko">Sewa Toko</option>
            <option value="Jual Ruko">Jual Ruko</option>
            <option value="Sewa Ruko">Sewa Ruko</option>
            <option value="Jual Pabrik">Jual Pabrik</option>
            <option value="Sewa Pabrik">Sewa Pabrik</option>
            <option value="Event Kondangan">Event Kondangan</option>
            <option value="Event Pameran">Event Pameran</option>
            </select>
            </p>
            <p><label>Deskripsi</label> <textarea class="w3-input w3-border w3-small" id="deskripsi" name="deskripsi" minlength="3" maxlength="255"></textarea></p>
            <p id="input_gambar"><label>Gambar (Maks. 5 Gambar)</label> <input class="w3-input w3-border w3-small" type="file" accept="image/jpeg, images/jpg" id="gambar" name="gambar[]" multiple></p>
        </div>
        <div class="w3-container">
          <p>
          <button id="button_simpan_marker" type="submit" class="w3-btn w3-teal w3-small">Save</button>
          <button id="button_batal_marker" type="button" class="w3-btn w3-red w3-small" onclick="el('modal_tambah_marker').style.display='none';">Close</button>
          </p>
        </div>
        </form>
      </div>
    </div>
  
  <!-- Modal Detail Marker -->
  <div id="modal_detail_marker" class="w3-modal" style="display: none; z-index: 3;">
    <div class="w3-modal-content">
      <div class="w3-container">
        <span onclick="el('modal_detail_marker').style.display='none';" class="w3-closebtn w3-right">&times;</span>
        <h3 id="informasi_username">Informasi Tempat</h3>
      </div>
      <div class="w3-container">
      <?php
        $error ="";
        if(isset($_GET['file_update'])){
          switch($_GET['file_update']){
            case "besar": 
                $error ="Gambar maksimal berukuran 300kb/gambar.";
              break;
            case "err": 
              $error ="Gambar tidak bisa digunakan. Silahkan pilih gambar lain.";
            break;
            case "banyak": 
              $error ="Jumlah gambar maksimal 5 gambar.";
              break;
          }
        }
      ?>
        <p class='w3-text-red' id='pesan_update_marker'><?=$error?></p>
        <form id="form_marker" name="form_marker" method="POST" action="delete.php">
          <input type="hidden" id="id_tempat_marker" name="id_tempat_marker" >
          <input type="hidden" id="id_user_marker" name="id_user">
          <input type="hidden" id="koordinat_marker" name="koordinat_marker" >
        </form>
          <label><b>Judul</b></label>
          <p id="judul_marker"></p>
          <label><b>Kategori</b></label>
          <p id="kategori_marker"></p>
          <label><b>Deskripsi</b></label>
          <p id="deskripsi_marker"></p>
          <label><b>Gambar</b></label>
          <p id="gambar_marker"></p>
      </div>
      <div class="w3-container">
        <p>
          <button id="button_delete_marker" type="submit" form="form_marker" class="w3-btn w3-teal w3-small">Delete</button>
          <button id="button_edit_marker" class="w3-btn w3-green w3-small">Edit</button>
          <button type="button" class="w3-btn w3-red w3-small" onclick="el('modal_detail_marker').style.display='none';">Close</button>
        </p>
      </div>
    </div>
  </div>

  <!-- Modal Filter -->
  <div id="modal_filter" class="w3-modal" style="display: none; z-index: 3;">
    <div class="w3-modal-content">
      <div class="w3-container">
        <span onclick="el('modal_filter').style.display='none';" class="w3-closebtn w3-right">&times;</span>
        <h3>Pencarian Tempat</h3>
      </div>
      <div class="w3-container">
        <form name="form_filter" onsubmit="return false">
          <p>
          <label>Kategori</label>
          <select class="w3-select w3-border w3-small" name="filter">
            <option value="username">Username</option>
            <option value="judul">Judul</option>
            <option value="deskripsi">Deskripsi</option>
            <option value="kategori">Kategori</option>
          </select>
          </p>
          <p><label>Kata Kunci</label> <input class="w3-input w3-border w3-small" type="text" name="cari" maxlength="50" /></p>
      </div>
      <div class="w3-container">
        <p>
        <button type="submit" class="w3-btn w3-teal w3-small" id="button_filter">Filter</button>
        <button type="button" class="w3-btn w3-red w3-small" onclick="el('modal_filter').style.display='none';">Close</button>
        </p>
        </form>
      </div>
    </div>
  </div>

	<script>
    var hitungan = 0;
    // Fungsi javascript
    // Method localstorage
    
    // el(x string)
    // Method untuk mengambil instance DOM secara singkat
    function el(x, tipe="id"){
      switch(tipe){
        case "id":
          return document.getElementById(x);
          break;
        case "class":
          return document.getElementsByClassName(x);
          break;
        case "name":
          return document.getElementsByName(x)[0];
          break;
        default:
          return null;
      }
    }
    function elForm(x, y){
      return document[x][y];
    }
    
    // MkeKm(m int)
    // Method untuk konversi meter ke kilometer
    function MkeKm(m){
      return (m/1000).toFixed(6)
    }
    
    // Jarak(arr1 array atau Object{lat,lng}, arr2 array atau Object{lat,lng})
    // Mengetahui jarak dari satu titik ke titik lain (Km)
    function Jarak(arr1, arr2){
      return MkeKm(L.latLng(arr1).distanceTo(L.latLng(arr2)))
    }
    
    function latLngFromPointWithDistance(lat1,long1,d,angle){
      // Earth Radious in KM
      var R = 6378.14;
  
      // Degree to Radian
      var latitude1 = lat1 * (Math.PI/180);
      var longitude1 = long1 * (Math.PI/180);
      var brng = angle * (Math.PI/180);
  
      var latitude2 = Math.asin(Math.sin(latitude1)*Math.cos(d/R) + Math.cos(latitude1)*Math.sin(d/R)*Math.cos(brng));
      var longitude2 = longitude1 + Math.atan2(Math.sin(brng)*Math.sin(d/R)*Math.cos(latitude1),Math.cos(d/R)-Math.sin(latitude1)*Math.sin(latitude2));
  
      // back to degrees
      latitude2 = latitude2 * (180/Math.PI);
      longitude2 = longitude2 * (180/Math.PI);
  
      //6 decimal for Leaflet and other system compatibility
      lat2 = latitude2.toFixed(6);
      long2 = longitude2.toFixed(6);
    
      // Push in array and get back
      return [lat2, long2];
    }
    const localStorage = {
      set(x,y){
          store.set(x,y)
      },
      get(x){
        return store.get(x)
      },
      clearAll(){
        store.clearAll()
      },
      remove(x){
        store.remove(x)
      }
    }
    
    // Method event 
    var my_event = function(){};
    my_event.prototype.pageUser = function(){
      el("informasi_user").style.display = "block";
      el("informasi_user").innerHTML = "Hello " + localStorage.get("informasi_user").username;
      el("button_logout").style.display = "block";
      
      el("button_sign_in").style.display = "none";
      el("button_sign_up").style.display = "none";
    };
    my_event.prototype.pageVisitor = function(){
      el("informasi_user").style.display = "none";
      el("informasi_user").innerHTML = "";
      el("button_logout").style.display = "none";
      if(el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class").length == 1){
        el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class")[0].style.display = "none";
      }
      
      el("button_sign_in").style.display = "block";
      el("button_sign_up").style.display = "block";
    };
    my_event.prototype.resetLoginModal = function(){
      elForm("form_login","username").value = ""
      elForm("form_login","password").value = ""
      el("pesan_login").innerHTML = ""
    };
    my_event.prototype.resetRegistrasiModal = function(){
      elForm("form_registrasi","username").value = ""
      elForm("form_registrasi","password").value = ""
      elForm("form_registrasi","email").value = ""
      el("pesan_registrasi").innerHTML = ""
    };
    my_event.prototype.resetFilterModal = function(){
      elForm("form_filter","filter").value = ""
      elForm("form_filter","cari").value = ""
    };
    my_event.prototype.toggleLoginModal = function(){
      if(el("modal_login").style.display == "none"){
        el("modal_login").style.display = "block";
      }else{
        el("modal_login").style.display = "none";
      }
      this.resetLoginModal();
    };
    my_event.prototype.toggleRegistrasiModal = function(){
      if(el("modal_registrasi").style.display == "none"){
        el("modal_registrasi").style.display = "block";
      }else{
        el("modal_registrasi").style.display = "none";
      }
      this.resetRegistrasiModal();
    };
    my_event.prototype.toggleFilterModal = function(){
      if(el("modal_filter").style.display == "none"){
        el("modal_filter").style.display = "block";
      }else{
        el("modal_filter").style.display = "none";
      }
      this.resetFilterModal();
    };
    
    var my_event_map = function(radius){
      radius_lingkaran = radius; //1000 meter = 1 km
      current_marker = {}
      currentLayerJSON = null
      daftar_marker = []
      marker_geojson = []
      layer_marker = null
      posisi = L.marker([-0.502106, 117.153709], {icon: L.icon({
          iconSize: [40, 40],
          iconAnchor: [13, 27],
          popupAnchor:  [1, -24],
          iconUrl: 'dist/css/images/marker-person.png'
      }), draggable: true});
      
      pinggiran_lingkaran = L.marker([null, null], {icon: L.icon({
          iconSize: [40, 40],
          iconAnchor: [13, 27],
          popupAnchor:  [1, -24],
          iconUrl: 'dist/css/images/icon-arrow.png'
      }), draggable: true});
      
      lingkaran = L.circle();
      
      filter = null
      cari = null
      
      map = L.map('map').setView([-0.502106, 117.153709], 5);
      tile_layer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      minZoom: 5,
      maxZoom: 18,
      noWrap: false,
      attribution: 'Data by <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> | By Simon & Fritz'
      }).addTo(map);
    };
    my_event_map.prototype.resetSearch = function(){
      cari = null
      filter = null
      this.searchMarkerByCircle(posisi.getLatLng())
      window.history.pushState(null, "Evantos Events", "index.php");
      el('kontrol_kiri_bawah').style.display = 'none';
    };
    my_event_map.prototype.toggleEditMarker = function(d){
      el("input_gambar").style.display = "none";
      el("modal_detail_marker").style.display = "none";
      el("modal_tambah_marker").style.display = "block";
      el("form_tambah_marker","name").action = "edit.php";
      el("caption_marker").innerHTML = "Edit Informasi Tempat";
      el("id_tempat").value = d.id_tempat;
      el("judul").value = d.judul;
      el("kategori").value = d.kategori;
      el("deskripsi").value = d.deskripsi;
    };
    my_event_map.prototype.getDetailMarker = function(d){
      var banyak_gambar = d.gambar.length;
      current_marker = d
      el("informasi_username").innerHTML = "Informasi Tempat ( oleh <b>" + d.username + "</b> )"
      el("id_tempat_marker").value = d.id_tempat
      el("judul_marker").innerHTML = d.judul
      el("kategori_marker").innerHTML = d.kategori
      el("deskripsi_marker").innerHTML = d.deskripsi
      var gambar = ['<div class="w3-row-padding w3-margin-top">']
      for(var x = 0; x < banyak_gambar; x++){
        gambar.push("<div class='w3-third'><div class='w3-card-2'><a href='gambar/" + d.gambar[x].nm_gambar + "'><img src='gambar/" + d.gambar[x].nm_gambar + "' class='w3-image w3-padding' style='height:250px;' /></a>");
        if(auth.getUserInfo()){
          el("button_delete_marker").style.display = "inline-block";
          el("button_edit_marker").style.display = "inline-block";
          gambar.push("<p class='w3-center w3-padding'>");
          gambar.push("<form id='" + d.gambar[x].id_gambar + "' action='update-gambar.php' method='POST' enctype='multipart/form-data'>");
          gambar.push("<input type='hidden' value='" + d.gambar[x].nm_gambar +"' name='nm_gambar' />");
          gambar.push("<p class='w3-padding'><label>Ganti Gambar</label><input class='w3-input w3-small w3-border' type='file' name='update_gambar' /></p>");
          gambar.push("<p class='w3-padding'>");
          gambar.push("<button type='submit' class='w3-button w3-blue w3-small w3-left'>Update</button>");
          gambar.push("<a href='delete-gambar.php?id_gambar=" + d.gambar[x].id_gambar + "&nm_gambar="+ d.gambar[x].nm_gambar +"' class='w3-button w3-red w3-small w3-right'>Hapus Gambar</a></p>");
          gambar.push("<div class='w3-clear'></div><br/>");
          gambar.push("</form>");
          gambar.push("</p>");
        }else{
          el("button_delete_marker").style.display = "none";
          el("button_edit_marker").style.display = "none";
        }
        gambar.push("</div></div>");
      }
      gambar.push('</div>');
      el("gambar_marker").innerHTML = gambar.join("");
      el("modal_detail_marker").style.display = "block"
    };
    my_event_map.prototype.refreshMap = function(){
      if(currentLayerJSON) {
          currentLayerJSON.layer.remove();
      }
      map.closePopup();
    };
    my_event_map.prototype.initLayerMarker = function(marker){
      marker_geojson = []
      if(layer_marker){
        map.removeLayer(layer_marker)
      }
      var banyak_marker = marker.length
    
      for(var x =0; x < banyak_marker; x++){
        marker_geojson.push(turf.point( [marker[x].koordinat_lng, marker[x].koordinat_lat], marker[x] ))
      }
      var self = this
      //Menjalankan geojson menggunakan leaflet
      layer_marker = L.geoJSON(turf.featureCollection(marker_geojson), {
        //Method yang dijalankan ketika marker diklik
        onEachFeature: function (feature, layer) {
          //Menampilkan pesan berisi content pada saat diklik
          layer.on("click", function (k){
            self.getDetailMarker(feature.properties);
          })
        },
        pointToLayer: function(feature, latlng) {
            return L.marker(latlng, {icon: L.icon({
                        iconSize: [40, 40],
                        iconAnchor: [13, 27],
                        popupAnchor:  [1, -24],
                        iconUrl: 'dist/css/images/icon-region.png'
                    })}); // ubah icon 
        },
      }).addTo(map);
    };
    my_event_map.prototype.searchMarkerByCircle = function(x = {lat: null, lng: null}, pfilter = null, pcari = null){
      var url = "get-marker.php?lat=" + x.lat + "&lng=" + x.lng;
      if(pfilter) filter = pfilter
      if(pcari) cari = pcari
      if(filter && cari){
        el("button_filter").innerHTML = "Mencari Data..."
        el("button_filter").disabled = true
        url += "&filter=" + filter + "&cari=" + cari
      }
      url += "&rad=" + MkeKm(radius_lingkaran);
      var self = this
      axios.get(url)
        .then(function(res){
          self.initLayerMarker(res.data);
          if(filter && cari){
            el("modal_filter").style.display = "none"
            el("kontrol_kiri_bawah").style.display = "block"
            el("kontrol_filter_caption").innerHTML = "Hasil pencarian dengan kata kunci <b>" + cari + "</b><br/>" +
                "<a href='#' id='button_reset_filter' class='w3-text-red'><b><u>Hapus Pencarian</u></b></a>";
            el("button_reset_filter").addEventListener("click",resetSearch());
          }
          el("button_filter").innerHTML = "Filter"
          el("button_filter").disabled = false
        })      
        .catch(function(err){
          if(filter && cari){
            el("button_filter").innerHTML = "Filter"
            el("button_filter").disabled = false
          }
        })      
    };
    my_event_map.prototype.filterMarker = function(){
      this.searchMarkerByCircle(posisi.getLatLng(), el("filter", "name").value, el("cari", "name").value);
    };
    my_event_map.prototype.initUserLocation = function(){
      map.locate({setView: false, watch: true, maxZoom: 15});
      //Event ketika lokasi ditemukan
      map.on('locationfound', (function(e) {
        if(posisi.getLatLng()){
          // kalau posisi tidak berubah, jangan update icon posisi user
          if(posisi.getLatLng().lat != e.latlng.lat && posisi.getLatLng().lng != e.latlng.lng){
            map.setZoom(14);
            if(auth.getUserInfo()){
              if(el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class").length == 1){
                el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class")[0].style.display = "block";
              }
            }
            if(L.latLng(posisi.getLatLng()).distanceTo(e.latlng) > 10){
              map.setView(e.latlng);
            }
            
            el("button_search").style.display = "block";
            this.searchMarkerByCircle(e.latlng);
            posisi.setLatLng(e.latlng).addTo(map); //set marker
            pinggiran_lingkaran.setLatLng(latLngFromPointWithDistance(e.latlng.lat, e.latlng.lng, MkeKm(radius_lingkaran), 90)).addTo(map); //set marker penggeser lingkaran
            lingkaran.setLatLng(e.latlng).setRadius(radius_lingkaran).addTo(map) //set lingkaran
            
            // Event pas marker posisi user digeser
            posisi.on("dragstart", (event_map_item.posisi.onDragStart).bind(this))
            
            // Event pas marker posisi bergeser
            posisi.on("move", (event_map_item.posisi.onMove).bind(this))
            
            //Event saat proses bergeser berakhir
            posisi.on("dragend", (event_map_item.posisi.onDragEnd).bind(this))
            
            //Event saat pinggiran lingkaran mulai bergeser
            pinggiran_lingkaran.on("dragstart",(event_map_item.pinggiran_lingkaran.onDragStart).bind(this))
            
            //Event saat pinggiran lingkaran bergeser
            pinggiran_lingkaran.on("move",(event_map_item.pinggiran_lingkaran.onMove).bind(this))
            
            //Event saat pinggiran lingkaran berhenti bergeser
            pinggiran_lingkaran.on("dragend",(event_map_item.pinggiran_lingkaran.onDragEnd).bind(this)) 
          }
        }
      }).bind(this));
        
        //Event ketika lokasi tidak ditemukan
        map.on('locationerror', function(){
          if(auth.getUserInfo){
            if(el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class").length == 1){
              el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class")[0].style.display = "none";
            }
          }
          el("button_search").style.display = "none";
          //~ alert("Lokasi tidak ditemukan. Silahkan refresh halaman ini.");
        });   
    };
    
    // Event item pada peta
    const my_event_map_item = function(){}
    my_event_map_item.prototype.posisi = function(){} 
    my_event_map_item.prototype.pinggiran_lingkaran = function(){} 
    my_event_map_item.prototype.posisi.onDragStart = function(e){
      if(el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class").length == 1){
        el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class")[0].style.display = "none";
      }
      pinggiran_lingkaran.off("move")
      map.stopLocate()
    }
    my_event_map_item.prototype.posisi.onMove = function(e){
      lingkaran.setLatLng(e.latlng) //buat lingkaran setiap bergeser
      pinggiran_lingkaran.setLatLng(latLngFromPointWithDistance(e.latlng.lat, e.latlng.lng, MkeKm(radius_lingkaran), 90))
    }
    my_event_map_item.prototype.posisi.onDragEnd = function(e){
      pinggiran_lingkaran.on("move",my_event_map_item.prototype.pinggiran_lingkaran.onMove)
      if(layer_marker){
        map.removeLayer(layer_marker)
      }
      this.searchMarkerByCircle(e.target._latlng)
    }
    my_event_map_item.prototype.pinggiran_lingkaran.onDragStart = function(e){
      if(el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class").length == 1){
        el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class")[0].style.display = "none";
      }
      map.stopLocate()
    }
    my_event_map_item.prototype.pinggiran_lingkaran.onMove = function(e){
      radius_lingkaran = L.latLng(posisi.getLatLng()).distanceTo(pinggiran_lingkaran.getLatLng());
      if(radius_lingkaran < 20000){
        lingkaran.setRadius(radius_lingkaran)
      }
    }
    my_event_map_item.prototype.pinggiran_lingkaran.onDragEnd = function(e){
      if(layer_marker){
        map.removeLayer(layer_marker)
      }
      map.stopLocate()
      this.searchMarkerByCircle(posisi.getLatLng())
    }
    
    // Method login dan registrasi
    const auth = {
      cekAuth(){
        if(el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class").length == 1){
          el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class")[0].style.display = "none";
        }
        axios.get("cek-login.php")
          .then(function(res){
            if(res.data.status == true){
              localStorage.set("informasi_user", res.data.data);
              event.pageUser();
            }else{
              localStorage.clearAll();
              event.pageVisitor();
            }
            event_map.initUserLocation();
          })
      },
      login(username, password, url_login){
        el("button_login").innerHTML = "Tunggu Sebentar...";
        el("button_login").disabled = true;
        axios.post(url_login, {username: username, password: password})
          .then(function(res){
            if(res.data.status == true){
              localStorage.set("informasi_user", res.data.data);
              event.toggleLoginModal();
              event.pageUser();
              event_map.searchMarkerByCircle(posisi.getLatLng())
            }else{
              el("pesan_login").innerHTML = res.data.message;
              el("pesan_login").className = "w3-text-red";
            }
          })
          .catch(function(err){
            alert("Terdapat kesalahan pada server!");
          })
          .then(function(){
            el("button_login").innerHTML = "Login";
            el("button_login").disabled = false;
          })
      },
      registrasi(username, password, email, url_reg){
        el("button_registrasi").innerHTML = "Tunggu Sebentar...";
        el("button_registrasi").disabled = true;
        axios.post(url_reg, {username: username, password: password, email: email})
          .then(function(res){
            if(res.data.status == true){
              event.toggleRegistrasiModal();
              event.toggleLoginModal();
              el("pesan_login").className = "w3-text-green";
              el("pesan_login").innerHTML = res.data.message;
            }else{
              el("pesan_registrasi").className = "w3-text-red";
              el("pesan_registrasi").innerHTML = res.data.message;
            }
            
          })
          .catch(function(err){
            el("pesan_registrasi").innerHTML = "Terdapat kesalahan pada server!";
            el("pesan_registrasi").className = "w3-text-red";
          })
          .then(function(){
            el("button_registrasi").innerHTML = "Sign Up";
            el("button_registrasi").disabled = false;
          })
      },
      logout(){
        axios.post("logout.php", this.getUserInfo())
          .then(function(res){
            if(res.data.status == true){
              localStorage.clearAll();
              event.pageVisitor();
              event_map.searchMarkerByCircle(posisi.getLatLng());
            }
          })
      },
      getUserInfo(){
        return localStorage.get("informasi_user");
      }
    }
    
    
    //EOF Fungsi Javascript
    
    
    // Event Listener
    el("form_login", "name").addEventListener("submit", function(){
      auth.login(elForm("form_login","username").value, elForm("form_login","password").value, "login.php")
    });
    el("form_registrasi", "name").addEventListener("submit", function(){
      auth.registrasi(elForm("form_registrasi","username").value, elForm("form_registrasi","password").value, elForm("form_registrasi","email").value, "registrasi.php")
    });
    el("button_search").addEventListener("click", function(){ el('modal_filter').style.display = 'block'; });
    el("button_logout").addEventListener("click", function(){ auth.logout(); });
    el("button_sign_in").addEventListener("click", function(){ event.toggleLoginModal(); });
    el("button_sign_up").addEventListener("click", function(){ event.toggleRegistrasiModal(); });
    el("button_gps").addEventListener("click", function(){ 
      radius_lingkaran = 1000;
      hitungan = 0;
      map.locate({setView: false, watch: true, maxZoom: 15});
    });
    el("button_batal_marker").addEventListener("click", function(){
      el('modal_tambah_marker').style.display='none';
      event_map.refreshMap()
      map.pm.disableDraw();
    });
    el("button_edit_marker").addEventListener("click", function(){
      event_map.toggleEditMarker(current_marker)
    });
    el("form_filter", "name").addEventListener("submit", function(){
      event_map.filterMarker()
    });
    
    el("kontrol_filter_caption").addEventListener("click", function(){
      event_map.resetSearch();
    })
    
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
    map.on('pm:create', (function(e){
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
      }).bind(this));
    
    auth.cekAuth();
	</script>
 </body>
</html>
