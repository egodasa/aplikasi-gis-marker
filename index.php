<?php
session_start();
require_once("config/database.php");
?>
<!DOCTYPE html>
<html>
 <head>
	<title>Eventos Events</title>
	<meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
  
  <!-- Menu -->
  <!-- Tombol kanan atas  -->
<!--
  <div class="leaflet-control-container">
    <div id="kontrol_kanan_atas" class="leaflet-top leaflet-right" style="z-index: 2;">
      <div class="leaflet-control-layers leaflet-control-layers-expanded leaflet-control">
        <div class="w3-dropdown-click">
          <button onclick="toggleMenu()" class="w3-button w3-hover-white">&#9776; Menu</button>
          <div id="menu" class="w3-dropdown-content w3-border">
            <a href="#" id="button_search" style="display: none;" class="w3-green">Search</a>
            <a href="#" id="informasi_user" style="display: none;"></a>
            <a href="#" id="button_logout" style="display: none;">Logout</a>
            <a href="#" id="button_sign_in">Sign In</a>
            <a href="#" id="button_sign_up">Sign Up</a> 
          </div>
        </div>
      </div>
    </div>
  </div>
-->
  <div class="leaflet-control-container">
    <div id="kontrol_kanan_atas" class="leaflet-top leaflet-right" style="z-index: 2;">
      <div class="leaflet-control-layers leaflet-control-layers-expanded leaflet-control w3-white">
        <button id="button_menu" class="w3-button w3-hover-white">&#9776; Menu</button>
        <div id="menu" style="display: none;">
          <button href="#" id="button_search" style="display: block; margin-bottom:3px;" class="w3-btn w3-teal w3-block">Search</button>
          <button href="#" id="informasi_user" style="display: none; margin-bottom:3px;" class="w3-btn w3-blue w3-block"></button>
          <button href="#" id="button_logout" style="display: none; margin-bottom:3px;" class="w3-btn w3-cyan w3-block">Logout</button>
          <button href="#" id="button_sign_in" class="w3-btn w3-green w3-block" style="display: block; margin-bottom:3px;">Sign In</button>
          <button href="#" id="button_sign_up" class="w3-btn w3-blue w3-block" style="display: block; margin-bottom:3px;">Sign Up</button> 
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
          <input class="w3-input w3-border w3-small" type="text" name="username" minlength="1" maxlength="50" required>
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

	<script src="dist/js/main.js"></script>
 </body>
</html>
