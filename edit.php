<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  //~ $banyak_gambar = count($_FILES['gambar']['name']) - 1;
  
  //~ if($banyak_gambar > 4){
    //~ header("Location: index.php?file=banyak");
  //~ }
  require "config/database.php";
  $data = array(
    "judul"	=> $_POST['judul'],
    "kategori"	=> $_POST['kategori'],
    "deskripsi"		=> $_POST['deskripsi']
  );
  $db->from('tbl_tempat')->where("id_tempat", $_POST['id_tempat'])->update($data)->execute();
  header("Location: index.php?edit=true");
  //~ $id = $db->insert_id;
  //~ $nomor = $db->sql("SELECT COUNT(a.id_tempat) AS jumlah FROM tbl_gambar_tempat a JOIN tbl_tempat b on a.id_tempat = b.id_tempat WHERE b.id_user = ".$_SESSION['id_user'])->one()['jumlah'];

  //~ $nama_file = $_SESSION['id_user']."_";
  
  //~ foreach($_FILES['gambar']['name'] as $key=>$f){
    //~ if($_FILES['gambar']['size'][$key] > 300000){
      //~ header("Location: index.php?file=err");
    //~ }
  //~ }
  
  //~ foreach($_FILES['gambar']['name'] as $key=>$f){
    //~ if($f != 0 || empty($f) == false){
      //~ $path = "gambar/";
      //~ $path = $path.basename($nama_file.($nomor+1).".jpg");
      //~ move_uploaded_file($_FILES['gambar']['tmp_name'][$key], $path);
      //~ $db->from('tbl_gambar_tempat')->insert(array("id_tempat" => $id, "nm_gambar"	=> $nama_file.($nomor+1).".jpg"))->execute();
      //~ $nomor++;
    //~ }
    //~ if($key == $banyak_gambar){
      //~ header("Location: index.php?file=true");
    //~ }
	//~ }
  
  
}else{
  echo "Anda dilarang mengakses halaman ini!";
}
?>
