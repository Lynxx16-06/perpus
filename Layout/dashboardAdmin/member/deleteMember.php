<?php 
require "../../../data/config.php"; 

$nisnMember = $_GET["id"];
if(deleteMember($nisnMember) > 0) {
    echo "<script>
    alert('Member berhasil dihapus!');
    document.location.href = 'member.php';
    </script>";
  }else {
    echo "<script>
    alert('Member gagal dihapus!');
    document.location.href = 'member.php';
    </script>";
}
?>