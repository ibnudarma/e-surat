@extends('app.template')

@section('content')
<style>
  .kartu-disposisi-container {
    font-family: Arial, sans-serif;
    border: 2px solid #000;
    padding: 20px;
    width: 800px;
    margin: 40px auto;
    background-color: #fff;
  }
  .kartu-disposisi-container h2,
  .kartu-disposisi-container h3 {
    text-align: center;
    margin: 5px 0;
  }
  .kartu-disposisi-container .header {
    text-align: center;
    margin-bottom: 20px;
  }
  .kartu-disposisi-container .row {
    display: flex;
    justify-content: space-between;
    margin: 5px 0;
  }
  .kartu-disposisi-container .row label {
    width: 150px;
    font-weight: bold;
  }
  .kartu-disposisi-container .box {
    border: 1px solid #000;
    padding: 5px;
    margin-top: 10px;
  }
  .kartu-disposisi-container .signature {
    margin-top: 40px;
    text-align: right;
  }
</style>

<div class="kartu-disposisi-container">
  <div class="header">
    <h3>KARTU DISPOSISI</h3>
  </div>

<div class="row">
  <label>Index:</label>
  <span>123</span>
  <label>Tanggal Penyelesaian:</label>
  <span>12</span>
<label>Diterima Tgl:</label>
<span>12</span>
</div>

<div class="row">
  <label>Dari:</label>
  <span></span>
    <label>Perihal:</label>
  <span></span>
    <label>Tgl. Surat:</label>
  <span></span>
</div>

<div class="row">
  <label>No. Surat:</label>
  <span></span>
</div>

<div class="row">
  <label>Intruksi / Informasi:</label>
  <span></span>
  <label>Diteruskan Kepada:</label>
  <span></span>
</div>

<div class="box">
  <strong>Catatan:</strong> 
</div>

<div class="signature">
    <p><strong>KEPALA BAGIAN PEREKONOMIAN DAN SDM KABUPATEN KARAWANG</strong></p>
    <br><br><br>
    <!-- tambahkan ttd disini -->
    <p><strong>HJ. YAYAT ROHAYATI, MM.</strong><br>
    Pembina Utama Muda<br>
    NIP. 19671108 199303 2 003</p>
  </div>
@endsection
