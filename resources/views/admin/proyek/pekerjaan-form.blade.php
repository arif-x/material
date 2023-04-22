@extends('layouts/admin')
@section('content')

<div class="page-content">
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <h3>Pekerjaan Proyek {{$proyek->nama_proyek}}</h3>
          </div>
          <hr/>
          <div class="text-right">
            <a class="btn btn-secondary mb-3" onclick="history.back()"><i class="fa fa-arrow-left"></i> Kembali</a>
          </div>
          <form method="POST" action="{{route('admin.proyek.pekerjaan-proyek.store')}}">
            @csrf
            <div class="form-group">
              <input type="hidden" name="proyek_id" value="{{$proyek->id}}">
              <label for="pekerjaan_id">Pekerjaan</label>
              <select class="form-control select2" name="pekerjaan_id" id="pekerjaan_id">
                <option value="" disabled selected>Pilih Pekerjaan</option>
                @foreach($pekerjaan as $key => $value)
                <option value="{{$value}}">{{$key}}</option>
                @endforeach
              </select>
            </div>
            <div class="detail" style="display:none">
              <div class="table-responsive">
                <table id="dataTableExample" class="table">
                  <thead>
                    <tr>
                      <th class="text-center"><input type="checkbox" class="checked-all"></th>
                      <th>Sub Pekerjaan</th>
                      <th>Volume</th>
                      <th>Detail</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
              <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $(function () {
    $('.select2').select2({theme: "bootstrap"});

    var table = $('#dataTableExample').DataTable({
      "columnDefs": [
        {className: "text-center", "targets": [0]},
        {"targets": 0,"orderable": false}
        ],
      paging: false,
      ordering: false,
      info: false,
    });

    $('.checked-all').on('change', function(e){
      e.preventDefault()
      if($(this).prop("checked") == true){
        $('input[id=checkbox]').prop('checked', true)
        $('input[id=c_input]').val(1)
      } else {
        $('input[id=checkbox]').prop('checked', false)
        $('input[id=c_input]').val(0)
      }
    })

    $('#pekerjaan_id').on('change', function(){
      $.get("{{ route('admin.proyek.pekerjaan-proyek.get-sub-pekerjaan', ['id' => ':id']) }}".replace(':id', $(this).val()), function (data) {
        $('table#dataTableExample > tbody').empty()
        table.clear().draw();
        for (var i = 0; i < data.length; i++) {
          table.row.add(
            [
              `<input type="checkbox" name="check" id="checkbox" data-id="`+(i+1)+`"><input type="hidden" name="checkbox[]" id="c_input" value="0" data-id="`+(i+1)+`">`, ``+ data[i].nama_sub_pekerjaan +`<input type="hidden" name="sub_pekerjaan_id[]" value="`+data[i].id+`">`, `<input type="number" class="form-control" name="volume[]" id="volume" step=".001" data-id="`+(i+1)+`">`, `<a href="{{route("admin.master.pekerjaan.sub-pekerjaan.detail", ["id" => ":id"])}}" target="_blank" class="btn btn-outline-primary">Detail</a>`.replace(":id", data[i].id)
              ]
            ).draw();
          $('input[id="checkbox"]').click(function(){
            var data_id = $(this).data('id')
            if($(this).prop("checked") == true){
              $('#c_input[data-id="'+data_id+'"]').val(1)
              $('#volume[data-id="'+data_id+'"]').attr('required', true)
            }
            else if($(this).prop("checked") == false){
              $('#c_input[data-id="'+data_id+'"]').val(0)
              $('#volume[data-id="'+data_id+'"]').attr('required', false)
            }
          });
        }
        $('.detail').show();
      })
    })
  })
</script>
@endsection