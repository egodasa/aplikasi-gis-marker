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
