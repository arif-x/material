@extends('layouts/admin')
@section('content')

<div class="page-content">
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <h3>Rincian Pekerjaan</h3>
          </div>
          <hr/>
          <div class="text-right">
            <a class="btn btn-secondary mb-3" onclick="history.back()"><i class="fa fa-arrow-left"></i> Kembali</a>
          </div>
          <div class="row mb-2">
            <div class="col-3 py-2">
              Nama Proyek
            </div>
            <div class="col-9 py-2">
              : <strong>{{$proyek->nama_proyek}}</strong>
            </div>
            <div class="col-3 py-2">
              Total RAB
            </div>
            <div class="col-9 py-2">
              : <strong>Rp{{number_format($total_all, '0', ',', '.')}}</strong>
            </div>
            <div class="col-3 py-2">
              Total RAB + Profit
            </div>
            <div class="col-9 py-2">
              : <strong>Rp{{number_format($total_all_profit, '0', ',', '.')}}</strong>
            </div>
            
            <!-- <div class="col-3 py-2">
              Rincian
            </div>
            <div class="col-9 py-2">
              : <br/>
            </div> -->
            <hr>
            <!-- <div class="table-responsive">
              <table class="table detail-table">
                <thead>
                  <tr>
                    <th>Pekerjaan</th>
                    <th>Total Pekerjaan</th>
                  </tr>
                </thead>
                <tbody>
                @foreach($arr_fix as $key => $value)
                <tr>
                  <td>{{$value['nama_pekerjaan']}}</td>
                  <td>Rp{{number_format($value['total'], '0', ',', '.')}}</td>
                </tr>
                @endforeach
                </tbody>
              </table>
            </div> -->
          </div>
          <hr/>
          <div class="card-description">
            <a class="btn btn-primary" href="{{route('admin.proyek.pekerjaan-proyek.form', ['id' => $proyek->id])}}" id="tambah">+ Tambah</a>
          </div>
          <div class="table-responsive">
            <table id="dataTableExample" class="table">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Pekerjaan</th>
                  <th>Total Sub Pekerjaan</th>
                  <th>Total Sub Pekerjaan + Profit</th>
                  <th>Profit</th>
                  <th>Action</th>
                </tr>
              </thead>
            </table>

            <div class="modal fade" id="theDeleteModal" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title" id="theModalDeleteHeading"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="id" id="id_delete">
                    <h5 class="mb-3">Ingin Menghapus <strong id="name_delete"></strong>?</h5>
                    <button type="submit" class="btn btn-danger" id="saveDeteleBtn" value="delete"><i class="fa fa-trash"></i> Hapus</button>
                  </div>
                </div>
              </div>
            </div>

            <script type="text/javascript">
             $(function () {
              $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
              });
              var table = $('#dataTableExample').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                ajax: "{{ route('admin.proyek.pekerjaan-proyek.datatable', ['id' => $proyek->id]) }}",
                columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'pekerjaan.nama_pekerjaan', name: 'pekerjaan.nama_pekerjaan'},
                  {
                    data: 'total', name: 'total', orderable: false, searchable: false,
                    render: function(a, b, row){
                      return $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(row.total)
                    }
                  },
                  {
                    data: 'total_profit', name: 'total_profit', orderable: false, searchable: false,
                    render: function(a, b, row){
                      return $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(row.total_profit)
                    }
                  },
                  {
                    data: 'total_profit', name: 'total_profit', orderable: false, searchable: false,
                    render: function(a, b, row){
                      return $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(row.total_profit - row.total)
                    }
                  },
                  {
                    data: 'action', name: 'action', orderable: false, searchable: false,
                    render: function(a, b, row){
                      var detail = "{{route('admin.proyek.detail-pekerjaan-proyek.index', ['id' => ':id'])}}".replace(':id', row.id);
                      return `
                      <div class="dropdown">
                      <button class="btn" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                      <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path>
                      </svg>
                      </button>

                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a href="`+detail+`" data-toggle="tooltip" data-id="`+row.id+`" data-original-title="Detail" class="dropdown-item detail detail-data"><i class="fa fa-eye"></i> Sub Pekerjaan</a>
                      
                      <a href="javascript:void(0)" data-toggle="tooltip" data-id="`+row.id+`" data-original-title="Delete" class="dropdown-item hapus delete-data"><i class="fa fa-trash"></i> Hapus</a>
                      </div>
                      </div>
                      `; 
                    }
                  }
                  ]
              });

              $('body').on('click', '.delete-data', function () {
                var id = $(this).data('id');
                $.get("{{ route('admin.proyek.pekerjaan-proyek.show', ['id' => ':id']) }}".replace(':id', id), function (data) {
                  console.log(data)
                  $('#theModalDeleteHeading').html("Hapus Pekerjaan");
                  $('#saveDeteleBtn').val("delete");
                  $('#id_delete').val(data.id);
                  $('#name_delete').html(data.pekerjaan.nama_pekerjaan);
                  $('#theDeleteModal').modal('show');
                })
              });

              $('#saveDeteleBtn').click(function (e) {
                var id = $('#id_delete').val();
                $.ajax({
                  type: "DELETE",
                  url: "{{ route('admin.proyek.pekerjaan-proyek.destroy', ['id' => ':id']) }}".replace(':id', id),
                  success: function (data) {
                    table.draw();
                    $('#theDeleteModal').modal('hide');
                  },
                  error: function (data) {
                    console.log('Error:', data);
                  }
                });
              });
            });
          </script>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection