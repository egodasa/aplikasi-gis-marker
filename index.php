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
    <div class="leaflet-top leaflet-right" style="z-index: 2;">
      <div class="leaflet-control-layers leaflet-control-layers-expanded leaflet-control">
        <div class="w3-bar">
          <button id="button_search" style="display: none;" class="w3-bar-item w3-button w3-green" onclick="document.getElementById('filter').style.display='block'">Search</button>
          <?php if(isset($_SESSION['username'])):?>
            <button class="w3-bar-item w3-button w3-blue-gray">Hello, <?=$_SESSION['username']?></button>
            <a href="logout.php" class="w3-bar-item w3-button w3-red">Logout</a>
          <?php else:?>
            <button class="w3-bar-item w3-button w3-blue" onclick="document.getElementById('login').style.display='block'">Sign In</button>
            <button class="w3-bar-item w3-button w3-teal" onclick="document.getElementById('registrasi').style.display='block'">Sign Up</button>
          <?php endif;?>
        </div>
      </div>
    </div>
  </div>
  
  
  <!-- Hasil pencarian -->
  <div class="leaflet-control-container">
    <div id="control_filter" class="leaflet-bottom leaflet-left" style="display:none;z-index: 2;">
      <div id="control_filter_caption" class="leaflet-control-layers leaflet-control-layers-expanded leaflet-control">
        <!-- Caption Control Filter -->
      </div>
    </div>
  </div>
  
  <!-- Icon posisi user sebelah kanan bawah -->
  <div class="leaflet-control-container">
    <div class="leaflet-bottom leaflet-right" style="z-index: 2;margin-bottom:30px;">
      <div class="leaflet-control-layers leaflet-control-layers-expanded leaflet-control">
        <button type="w3-btn w3-small w3-light-gray" onclick="map.locate({setView: false, watch: true, maxZoom: 14}); "><img src="dist/css/images/marker-person.png" width="30" height="30"></button>
      </div>
    </div>
  </div>
  
  
  <?php if(!isset($_SESSION['username'])): ?>
    <!-- Modal Login -->
    <div id="login" class="w3-modal" style="display: <?= isset($_GET['login']) ? 'block' : 'none'?>; z-index: 3;">
      <div class="w3-modal-content">
        <div class="w3-container">
          <span onclick="document.getElementById('login').style.display='none'" class="w3-closebtn w3-right">&times;</span>
          <h3>Sign In</h3>
          <?= isset($_GET['login']) ? ($_GET['login'] == "false" ? '<p class="w3-text-red">Username atau Password salah!</p>' : '<p class="w3-text-green">Anda sudah bisa login!</p>') : ''?>
        </div>
        <div class="w3-container">
          <form action="login.php" method="POST">
          <p>
          <label>Username</label>
          <input class="w3-input w3-border w3-small" type="text" name="username" required>
          </p>
          <p>
          <label>Password</label>
          <input class="w3-input w3-border w3-small" type="password" name="password" required>
          </p>
        </div>
        <div class="w3-container">
          <p>
          <button type="submit" class="w3-btn w3-teal w3-small">Sign In</button>
          <button type="button" class="w3-btn w3-red w3-small" onclick="document.getElementById('login').style.display='none'; ">Close</button>
          </p>
        </div>
        </form>
      </div>
    </div>
    
    <!-- Modal Registrasi -->
    <div id="registrasi" class="w3-modal" style="display: <?= isset($_GET['reg']) == "false" ? 'block' : 'none'?>; z-index: 3;">
      <div class="w3-modal-content">
        <div class="w3-container">
          <span onclick="document.getElementById('registrasi').style.display='none'" class="w3-closebtn w3-right">&times;</span>
          <h3>Sign Up</h3>
          <?php
            if(isset($_GET['reg'])){
              switch($_GET['err']){
                case "username":
                  echo "<p class='w3-text-red'>Username tidak bisa digunakan.</p>";
                  break;
                case "email":
                  echo "<p class='w3-text-red'>Email tidak bisa digunakan.</p>";
                  break;
                case "server":
                  echo "<p class='w3-text-red'>Terjadi kesalahan pada server.</p>";
                  break;
              }
            }
          ?>
        </div>
        <div class="w3-container">
          <form action="registrasi.php" method="POST">
          <p>
          <label>Username</label>
          <input class="w3-input w3-border w3-small" type="text" name="username" required >
          </p>
          <p>
          <label>Password</label>
          <input class="w3-input w3-border w3-small" type="password" name="password" required >
          </p>
          <p>
          <label>Email</label>
          <input class="w3-input w3-border w3-small" type="email" name="email" required >
          </p>
        </div>
        <div class="w3-container">
          <p>
          <button type="submit" class="w3-btn w3-teal w3-small" id="button_login">Sign Up</button>
          <button type="button" class="w3-btn w3-red w3-small" onclick="document.getElementById('registrasi').style.display='none';"  id="button_cancel">Close</button>
          </p>
        </div>
        </form>
      </div>
    </div>
  <?php else: ?>
  
    <!-- Modal Tambah Marker -->
    <div id="tambah" class="w3-modal" style="display: <?= isset($_SESSION['koordinat_lat']) ? 'block' : 'none'?>; z-index: 3;" enctype="multipart/form-data">
      <div class="w3-modal-content">
        <div class="w3-container">
          <span onclick="document.getElementById('tambah').style.display='none'" class="w3-closebtn w3-right">&times;</span>
          <h3 id="caption_marker">Tambah Tempat Baru</h3>
        </div>
        <div class="w3-container">
          <?php
          $error ="";
          if(isset($_GET['file'])){
            switch($_GET['file']){
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
          <p class='w3-text-red' id='error_tambah'><?=$error?></p>
          <form id="tambah_marker" method="POST" action="tambah.php" enctype="multipart/form-data">
            <input type="hidden" id="id_user" name="id_user" value="<?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['id_user'], ENT_COMPAT, 'UTF-8') : ""?>">
            <input type="hidden" id="id_tempat" name="id_tempat">
            <input type="hidden" id="koordinat_lat" name="koordinat_lat" value="<?= isset($_SESSION['koordinat_lat']) ? htmlspecialchars($_SESSION['koordinat_lat'], ENT_COMPAT, 'UTF-8') : ""?>">
            <input type="hidden" id="koordinat_lng" name="koordinat_lng" value="<?= isset($_SESSION['koordinat_lng']) ? htmlspecialchars($_SESSION['koordinat_lng'], ENT_COMPAT, 'UTF-8') : ""?>">
            <p>
            <label>Judul</label> <input class="w3-input w3-border w3-small" type="text" id="judul" name="judul" value="<?= isset($_SESSION['judul']) ? htmlspecialchars($_SESSION['judul'], ENT_COMPAT, 'UTF-8') : ""?>" required></p>
            <p><label>Kategori</label>
            <select class="w3-select w3-border w3-small" name="kategori" id="kategori" value="<?= isset($_SESSION['kategori']) ? htmlspecialchars($_SESSION['kategori'], ENT_COMPAT, 'UTF-8') : ""?>">
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
            <p><label>Deskripsi</label> <textarea class="w3-input w3-border w3-small" id="deskripsi" name="deskripsi"><?= isset($_SESSION['deskripsi']) ? htmlspecialchars($_SESSION['deskripsi'], ENT_COMPAT, 'UTF-8') : ""?></textarea></p>
            <p><label>Gambar (Maks. 5 Gambar)</label> <input class="w3-input w3-border w3-small" type="file" accept="image/jpeg, images/jpg" id="gambar" name="gambar[]" multiple></p>
        </div>
        <div class="w3-container">
          <p>
          <button type="submit" class="w3-btn w3-teal w3-small">Save</button>
          <button type="button" class="w3-btn w3-red w3-small" onclick="document.getElementById('tambah').style.display='none'; el('error_tambah').innerHTML = null; refreshMap();">Close</button>
          </p>
        </div>
        </form>
      </div>
    </div>
  <?php endif; ?>
  
  <!-- Modal Detail Marker -->
  <div id="detail_marker" class="w3-modal" style="display: none; z-index: 3;">
    <div class="w3-modal-content">
      <div class="w3-container">
        <span onclick="document.getElementById('detail_marker').style.display='none'" class="w3-closebtn w3-right">&times;</span>
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
          <p class='w3-text-red' id='error_update'><?=$error?></p>
        <form id="form_marker" name="form_marker" method="POST" action="delete.php">
          <input type="hidden" id="id_tempat_marker" name="id_tempat_marker" >
          <input type="hidden" id="id_user_marker" name="id_user" value="<?= isset($_SESSION['username']) ? $_SESSION['id_user'] : ""?>">
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
        <?php if(isset($_SESSION['username'])): ?>
          <button type="submit" form="form_marker" class="w3-btn w3-teal w3-small">Delete</button>
          <button type="button" class="w3-btn w3-green w3-small" onclick="showEditMarker(current_marker)">Edit</button>
        <?php endif; ?>
        <button type="button" class="w3-btn w3-red w3-small" onclick=" el('error_update').innerHTML = null; document.getElementById('detail_marker').style.display='none';">Close</button>
        </p>
      </div>
    </div>
  </div>

  <!-- Modal Filter -->
  <div id="filter" class="w3-modal" style="display: none; z-index: 3;">
    <div class="w3-modal-content">
      <div class="w3-container">
        <span onclick="document.getElementById('filter').style.display='none'" class="w3-closebtn w3-right">&times;</span>
        <h3>Pencarian Tempat</h3>
      </div>
      <div class="w3-container">
          <p>
          <label>Kategori</label>
          <select class="w3-select w3-border w3-small" name="filter">
            <option value="username">Username</option>
            <option value="judul">Judul</option>
            <option value="deskripsi">Deskripsi</option>
            <option value="kategori">Kategori</option>
          </select>
          </p>
          <p><label>Kata Kunci</label> <input class="w3-input w3-border w3-small" type="text" name="cari" /></p>
      </div>
      <div class="w3-container">
        <p>
        <button id="button_filter" type="button" class="w3-btn w3-teal w3-small" onclick="filterMarker()">Filter</button>
        <button type="button" class="w3-btn w3-red w3-small" onclick="document.getElementById('filter').style.display='none';">Close</button>
        </p>
      </div>
    </div>
  </div>

	<script>
    // variabel
    //besar lingkaran 
    // Edit juga file get-marker.php untuk mengubah radius
    var radius_lingkaran = 1000; //1000 meter = 1 km
    var current_marker = {}
    var currentLayerJSON = null
    var clickEvent = new MouseEvent("click", {
        "view": window,
        "bubbles": true,
        "cancelable": false
    });
    var daftar_marker = []
    var marker_geojson = []
    var layer_marker = null
    var posisi = L.marker([null, null], {icon: L.icon({
        iconSize: [40, 40],
        iconAnchor: [13, 27],
        popupAnchor:  [1, -24],
        iconUrl: 'dist/css/images/marker-person.png'
    }), draggable: true});
    var lingkaran = L.circle(null, {color: "#FF00B5", fill: "#000000", fillOpacity: 0.3});
    var filter = null
    var cari = null
    var pinggiran_lingkaran = L.marker([null, null], {icon: L.icon({
          iconSize: [40, 40],
          iconAnchor: [13, 27],
          popupAnchor:  [1, -24],
          iconUrl: 'dist/css/images/icon-arrow.png'
      }), draggable: true});
    // EOF variabel
    
    // Fungsi javascript
    
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
    
    // resetSearch()
    // Reset hasil pencarian marker
    function resetSearch(){
      window.history.pushState(null, "Evantos Events", "index.php");
      searchMarkerByCircle(posisi.getLatLng());
      el('control_filter').style.display = 'none';
      cari = null
      filter = null
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
    
    // showEditMarker(d Object)
    // Method untuk menampilkan modal edit
    function showEditMarker(d){
      el("detail_marker").style.display = "none"
      el("tambah").style.display = "block"
      el("tambah_marker").action = "edit.php";
      el("caption_marker").innerHTML = "Edit Informasi Tempat";
      el("id_tempat").value = d.id_tempat
      el("judul").value = d.judul
      el("kategori").value = d.kategori
      el("deskripsi").value = d.deskripsi
    }
    
    // getDetailMarker(d Object)
    // Method untuk menampilkan modal detail tempat
    function getDetailMarker(d){
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
        <?php if(isset($_SESSION['username'])): ?>
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
        <?php endif; ?>
        gambar.push("</div></div>");
      }
      gambar.push('</div>');
      el("gambar_marker").innerHTML = gambar.join("");
      el("detail_marker").style.display = "block"
    }
    
    // refreshMap()
    // Method untuk menghapus layer marker saat penambahan marker baru dibatalkan
    function refreshMap(){
        if(currentLayerJSON) {
            currentLayerJSON.layer.remove();
        }
        map.closePopup();
    }
    
    
    // initLayerMarker(daftar_marker [Object])
    // Inisialisasi daftar marker untuk ditampilkan
    function initLayerMarker(marker){
      marker_geojson = []
      if(layer_marker){
        map.removeLayer(layer_marker)
      }
      var banyak_marker = marker.length
    
      for(var x =0; x < banyak_marker; x++){
        marker_geojson.push(turf.point( [marker[x].koordinat_lng, marker[x].koordinat_lat], marker[x] ))
      }
      //Menjalankan geojson menggunakan leaflet
      layer_marker = L.geoJSON(turf.featureCollection(marker_geojson), {
        //Method yang dijalankan ketika marker diklik
        onEachFeature: function (feature, layer) {
          //Menampilkan pesan berisi content pada saat diklik
          layer.on("click", function (k){
            getDetailMarker(feature.properties);
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
    }
    
    // searchMarkerByCircle(x Object{lat, lng})
    // Menampilkan daftar marker dalam lingkaran saja, jika reset bernilai true, filter akan hilang
    function searchMarkerByCircle(x = {lat: null, lng: null}, param_filter = null, param_cari = null){
      if(param_filter) filter = param_filter;
      if(param_cari) filter = param_cari;
      var url = "get-marker.php?lat=" + x.lat + "&lng=" + x.lng +"&rad=" + radius_lingkaran;
        if(filter && cari){
          el("button_filter").innerHTML = "Mencari Data..."
          el("button_filter").disabled = true
          url += "&filter=" + filter + "&cari=" + cari
      }
      axios.get(url)
        .then(function(res){
          initLayerMarker(res.data);
          if(filter && cari){
            el("filter").style.display = "none"
            el("control_filter").style.display = "block"
            el("control_filter_caption").innerHTML = "Hasil pencarian dengan kata kunci <b>" + cari + "</b><br/>" +
                "<a href='#' id='button_reset' class='w3-text-red' onclick='resetSearch()'><b><u>Hapus Pencarian</u></b></a>";
          }
          el("button_filter").innerHTML = "Filter"
          el("button_filter").disabled = false
        })      
        .catch(function(err){
          el("button_filter").innerHTML = "Filter"
          el("button_filter").disabled = false
          initLayerMarker([]);
        })      
    }
    
    // filterMarker()
    // Melakukan filter marker
    function filterMarker(){
      searchMarkerByCircle(posisi.getLatLng(), el("filter", "name").value, el("cari", "name").value);
    }
    
    // initUserLocation()
    // Method untuk mengaktifkan lokasi user
    function initUserLocation(){
      map.locate({setView: false, watch: true, maxZoom: 14});
      //Event ketika lokasi ditemukan
      map.on('locationfound', function(e) {
        if(el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class")[0]){
          el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class")[0].style.display = "block";
        }
        el("button_search").style.display = "block";
        searchMarkerByCircle(e.latlng);
        posisi.setLatLng(e.latlng).addTo(map); //set marker
        lingkaran.setLatLng(e.latlng).setRadius(radius_lingkaran).addTo(map) //set lingkaran
        
        map.setView(e.latlng)
        
        // Event pas marker posisi user digeser
        posisi.on("dragstart",function(e){
          if(el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class").length == 1){
            el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class")[0].style.display = "none";
          }
          map.stopLocate()
        })
        
        // Event pas marker posisi bergeser
        posisi.on("move",function(e){
          lingkaran.setLatLng(e.latlng).setRadius(radius_lingkaran) //buat lingkaran setiap bergeser
        })
        
        //Event saat proses bergeser berakhir
        posisi.on("dragend",function(e){
          map.stopLocate()
          map.removeLayer(layer_marker);
          searchMarkerByCircle(e.target._latlng)
        })
        });
        
        //Event ketika lokasi tidak ditemukan
        map.on('locationerror', function(){
          if(el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class")[0]){
            el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class")[0].style.display = "none";
          }
          el("button_search").style.display = "none";
          //~ alert("Lokasi tidak ditemukan. Silahkan refresh halaman ini.");
        })
    }
    //EOF Fungsi Javascript
    
    
    //Program
		var map = L.map('map').setView([-0.502106, 117.153709], 5);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    noWrap: false,
    attribution: 'Data by <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> | By Simon'
		}).addTo(map);
    
    map.pm.addControls({
        position: 'topleft', // toolbar position, options are 'topleft', 'topright', 'bottomleft', 'bottomright'
        drawMarker: <?php echo isset($_SESSION['username']) ? "true" : "false"; ?>, // adds button to draw markers
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
        el("tambah_marker").action = "tambah.php";
        el("caption_marker").innerHTML = "Tambah Tempat Baru";
        if(Jarak(e.layer._latlng, posisi.getLatLng()) >= (radius_lingkaran)/1000){
          alert("Marker hanya bisa ditempatkan radius maksimal 1Km dari tempat Anda!")
          e.layer.remove()
          refreshMap()
        }else{
          //layer dimasukkan ke variabel agar bisa dihapus
          currentLayerJSON = e;
          el("koordinat_lat").value = e.layer._latlng.lat
          el("koordinat_lng").value = e.layer._latlng.lng
          el("tambah").style.display = 'block'
        }
      });
      
    if(el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class")[0]){
      el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class")[0].style.display = "none";
    }
    
    initUserLocation();
    
	</script>
 </body>
</html>
