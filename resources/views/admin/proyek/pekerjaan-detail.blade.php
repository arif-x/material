@extends('layouts/admin')
@section('content')

<div class="page-content">
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <h3>Detail Pekerjaan "{{$pekerjaan->pekerjaan->nama_pekerjaan}}" pada Proyek "{{$proyek->nama_proyek}}"</h3>
            <a class="btn btn-secondary h-100" onclick="history.back()">Kembali</a>
          </div>
          <hr/>
          <h4 class="mb-2">Detail Pekerjaan</h4>
          <div class="table-responsive">
            <table id="dataTableExample" class="table">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Sub Pekerjaan</th>
                  <th>Volume</th>
                  <!-- <th>Action</th> -->
                </tr>
              </thead>  
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(function () {
      // function formatRupiah(angka, prefix) {
      //   var number_string = angka.toString(),
      //   split = number_string.split(","),
      //   sisa = split[0].length % 3,
      //   rupiah = split[0].substr(0, sisa),
      //   ribuan = split[0].substr(sisa).match(/\d{3}/gi);

      //   if (ribuan) {
      //     separator = sisa ? "." : "";
      //     rupiah += separator + ribuan.join(".");
      //   }

      //   rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
      //   return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
      // }

    $('.select2').select2({theme: "bootstrap"});

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    var table = $('#dataTableExample').DataTable({
      processing: true,
      serverSide: true,
      paging: true,
      ajax: "{{ route('admin.proyek.detail-pekerjaan-proyek.datatable', ['id' => $pekerjaan->id]) }}",
      columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'sub_pekerjaan.nama_sub_pekerjaan', name: 'sub_pekerjaan.nama_sub_pekerjaan'},
          // {data: 'koefisien', name: 'koefisien'},
          // {
          //   data: 'harga_komponen_jasa', name: 'harga_komponen_jasa', orderable: false, searchable: false,
          //   render: function(a, b, row){
          //     return formatRupiah(row.harga_komponen_jasa, "Rp. "); 
          //   }
          // },
        {data: 'volume', name: 'volume'},
          // {
          //   data: 'action', name: 'action', orderable: false, searchable: false,
          //   render: function(a, b, row){
          //     return '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'+row.id+'" data-original-title="Edit" class="edit btn btn-outline-primary edit-data-jasa">Edit</a> <a href="javascript:void(0)" data-toggle="tooltip" data-id="'+row.id+'" data-original-title="Hapus" class="hapus btn btn-outline-danger delete-data-jasa">Hapus</a>'; 
          //   }
          // }
        ]
    });
  });
</script>
@endsection