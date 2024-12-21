  const tglPeminjaman = document.getElementById('tgl_peminjaman');
  const tglPengembalian = document.getElementById('tgl_pengembalian');

  tglPeminjaman.addEventListener('change', function() {
    const pinjamDate = new Date(tglPeminjaman.value); 
    pinjamDate.setDate(pinjamDate.getDate() + 7); 

    const yyyy = pinjamDate.getFullYear();
    const mm = String(pinjamDate.getMonth() + 1).padStart(2, '0');
    const dd = String(pinjamDate.getDate()).padStart(2, '0');
    tglPengembalian.value = `${yyyy}-${mm}-${dd}`;
  });