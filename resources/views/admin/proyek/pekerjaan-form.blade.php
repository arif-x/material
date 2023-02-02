@extends('layouts/admin')
@section('content')

<div class="page-content">
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h3>Pekerjaan Proyek {{$proyek->nama_proyek}}</h3>
          <hr/>
          <form>
            <div class="form-group">
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
                  <tbody class="form-data">
                  </tbody>
                </table>
              </div>
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

    // var table = $('#dataTableExample').DataTable({
    //   "ordering": false,
    // });

    $('.checked-all').on('change', function(e){
      e.preventDefault()
      $('input[name=check]').prop('checked', this.checked)
    })

    $('#pekerjaan_id').on('change', function(){
      $.get("{{ route('admin.proyek.pekerjaan-proyek.get-sub-pekerjaan', ['id' => ':id']) }}".replace(':id', $(this).val()), function (data) {
        $('.form-data').empty()
        form_data = ""
        for (var i = 0; i < data.length; i++) {
          (
            form_data += '<tr>' +
            '<td class="text-center"><input type="checkbox" name="check" id="checkbox"></td>' +
            '<td>' + data[i].nama_sub_pekerjaan + '</td>' +
            '<td><input type="number" class="form-control" name="volume[]"></td>' +
            '<td><button class="btn btn-outline-primary">Detail</button></td>' +
            '</tr>'
            )
        }
        $('.form-data').html(form_data)
        $('.detail').show();
      })
    })
  })
</script>
@endsection