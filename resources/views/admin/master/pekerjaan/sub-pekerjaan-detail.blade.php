@extends('layouts/admin')
@section('content')

<div class="page-content">
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h3>Harga Komponen {{$data->nama_sub_pekerjaan}}</h3>
          <hr/>
          <h4 class="mb-2">Harga Komponen Jasa</h4>
          <div class="table-responsive">
            <table id="dataTableExample1" class="table">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Jasa</th>
                  <th>Koefisien</th>
                  <th>Harga Komponen</th>
                </tr>
              </thead>  
            </table>
            <hr>
            <h4 class="mb-2">Harga Komponen Material</h4>
            <table id="dataTableExample2" class="table">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Material</th>
                  <th>Koefisien</th>
                  <th>Harga Komponen</th>
                </tr>
              </thead>  
            </table>

            <script type="text/javascript">
             $(function () {

              $('input[class="form-control"]').prop('required', true);

              function formatRupiah(angka, prefix) {
                var number_string = angka.toString(),
                split = number_string.split(","),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                  separator = sisa ? "." : "";
                  rupiah += separator + ribuan.join(".");
                }

                rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
                return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
              }

              $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
              });

              $('.select2').select2({theme: "bootstrap"});

              var table1 = $('#dataTableExample1').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                ajax: "{{ route('admin.master.pekerjaan.sub-pekerjaan.detail.jasa', ['id' => $data->id]) }}",
                columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'jasa.nama_jasa', name: 'jasa.nama_jasa'},
                  {data: 'koefisien', name: 'koefisien'},
                  {
                    data: 'harga_komponen_jasa', name: 'harga_komponen_jasa', orderable: false, searchable: false,
                    render: function(a, b, row){
                      return formatRupiah(row.harga_komponen_jasa, "Rp. "); 
                    }
                  },
                  ]
              });

              var table2 = $('#dataTableExample2').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                ajax: "{{ route('admin.master.pekerjaan.sub-pekerjaan.detail.material', ['id' => $data->id]) }}",
                columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'material.nama_material', name: 'material.nama_material'},
                  {data: 'koefisien', name: 'koefisien'},
                  {
                    data: 'harga_komponen_material', name: 'harga_komponen_material', orderable: false, searchable: false,
                    render: function(a, b, row){
                      return formatRupiah(row.harga_komponen_material, "Rp. "); 
                    }
                  },
                  ]
              });

        
            });
          </script>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection