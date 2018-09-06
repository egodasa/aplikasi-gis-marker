    var locate;
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
    
    // Method untuk menu sebelah kanan atas
    function toggleMenu() {
      if(el("menu").style.display == "none") el("menu").style.display = "block";
      else el("menu").style.display = "none";
    }
    
    // Method untuk mempermudah pengambilan DOM pada form
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
      
      this.toggleMarkerEditor("disable");
      
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
    
    my_event.prototype.toggleMarkerEditor = function(x = "enable"){
      if(el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class").length == 1){
        var status = el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class")[0].style.display;
        switch(x){
          case "enable": 
            el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class")[0].style.display = "block";
          break;
          case "disable": 
            el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class")[0].style.display = "none";
          break;
        }
      }
      if(auth.getUserInfo()){
        if(auth.getUserInfo().tipe_user == "Admin"){
          el("leaflet-pm-toolbar leaflet-bar leaflet-control", "class")[0].style.display = "block";
        }
      }
    }
    
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
      //~ map.locate({setView: false, watch: true, maxZoom: 15});
      if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition((function(position){
          var pos = {lat: position.coords.latitude, lng: position.coords.longitude};
          if(auth.getUserInfo()){
          event.toggleMarkerEditor("enable");
          }
          if(posisi.getLatLng()){
            // kalau posisi tidak berubah, jangan update icon posisi user
            if(posisi.getLatLng().lat != pos.lat && posisi.getLatLng().lng != pos.lng){
              map.setZoom(14);
              if(L.latLng(posisi.getLatLng()).distanceTo(pos) > 10){
                map.setView(pos);
              }
              
              el("button_search").style.display = "block";
              event_map.searchMarkerByCircle(pos);
              posisi.setLatLng(pos).addTo(map); //set marker
              pinggiran_lingkaran.setLatLng(latLngFromPointWithDistance(pos.lat, pos.lng, MkeKm(radius_lingkaran), 90)).addTo(map); //set marker penggeser lingkaran
              lingkaran.setLatLng(pos).setRadius(radius_lingkaran).addTo(map) //set lingkaran
              
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
      }else{
        if(auth.getUserInfo()){
          event.toggleMarkerEditor("disable");
        }
        el("button_search").style.display = "none";
        //~ alert("Lokasi tidak ditemukan. Silahkan refresh halaman ini.");
      }
    };
    
    // Event item pada peta
    const my_event_map_item = function(){}
    my_event_map_item.prototype.posisi = function(){} 
    my_event_map_item.prototype.pinggiran_lingkaran = function(){} 
    my_event_map_item.prototype.posisi.onDragStart = function(e){
      event.toggleMarkerEditor("disable");
      pinggiran_lingkaran.off("move")
      clearInterval(locate)
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
      event_map.searchMarkerByCircle(e.target._latlng)
    }
    my_event_map_item.prototype.pinggiran_lingkaran.onDragStart = function(e){
      event.toggleMarkerEditor("disable");
      clearInterval(locate)
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
      clearInterval(locate)
      event_map.searchMarkerByCircle(posisi.getLatLng())
    }
    
    // Method login dan registrasi
    const auth = {
      cekAuth(){
        event.toggleMarkerEditor("disable");
        axios.get("cek-login.php")
          .then(function(res){
            if(res.data.status == true){
              localStorage.set("informasi_user", res.data.data);
              event.pageUser();
            }else{
              localStorage.clearAll();
              event.pageVisitor();
            }
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
      clearInterval(locate)
      locate = setInterval(event_map.initUserLocation, 3500)
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
    el("kontrol_kanan_atas").addEventListener("click", function(){
      toggleMenu();
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
    var locate = setInterval(event_map.initUserLocation, 3500)
    auth.cekAuth();
