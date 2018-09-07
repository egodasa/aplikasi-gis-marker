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
