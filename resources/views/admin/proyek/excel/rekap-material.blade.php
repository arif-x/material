<style>
	table, th, td {
		border: 1px solid black;
		border-collapse: collapse;
	}
</style>
<table>
	<tr>
		<td colspan="5" style="text-align: center;font-weight: bold;font-size: 20px;">Rekapitulasi Material</td>
	</tr>
	<tr></tr>
	<tr>
		<td>Nama Proyek</td>
		<td align="right">:</td>
		<td colspan="3">{{$nama_proyek}}</td>
	</tr>
	<tr>
		<th style="border: 1px solid black;border-collapse: collapse;background:#D9D9D9;font-weight: bold;">Nama Material</th>
		<th style="border: 1px solid black;border-collapse: collapse;background:#D9D9D9;font-weight: bold;">Kebutuhan</th>
		<th style="border: 1px solid black;border-collapse: collapse;background:#D9D9D9;font-weight: bold;">Satuan</th>
		<th style="border: 1px solid black;border-collapse: collapse;background:#D9D9D9;font-weight: bold;">Harga Satuan</th>
		<th style="border: 1px solid black;border-collapse: collapse;background:#D9D9D9;font-weight: bold;">Jumlah</th>
	</tr>
	@php
	$jumlah = 0;
	@endphp
	@foreach($data as $key => $value)
	<tr>
		<td style="border: 1px solid black;border-collapse: collapse;">{{$value['nama_material']}}</td>
		<td style="border: 1px solid black;border-collapse: collapse;">{{$value['jumlah']}}</td>
		<td style="border: 1px solid black;border-collapse: collapse;">{{$value['nama_satuan']}}</td>
		<td style="border: 1px solid black;border-collapse: collapse;" data-format="#,##0.00_-">{{$value['harga_satuan']}}</td>
		<td style="border: 1px solid black;border-collapse: collapse;" data-format="#,##0.00_-">{{$value['jumlah']*$value['harga_satuan']}}</td>
		@php
		$jumlah = $jumlah + ($value['jumlah']*$value['harga_satuan']);
		@endphp
	</tr>
	@endforeach
	<tr>
		<td style="border: 1px solid black;border-collapse: collapse;" colspan="4">Jumlah</td>
		<td style="border: 1px solid black;border-collapse: collapse;" data-format="#,##0.00_-">{{$jumlah}}</td>
	</tr>
</table>