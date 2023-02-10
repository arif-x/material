<style type="text/css">
	table, th, td {
		border: 1px solid black;
		border-collapse: collapse;
	}
</style>
<table>
	<tr>
		<td colspan="10" style="text-align: center;font-weight: bold;font-size: 20px;">Rencana Anggaran Proyek (RAP)</td>
	</tr>
	<tr></tr>
	<tr>
		<td>Nama Proyek</td>
		<td>:</td>
		<td colspan="8">{{$nama_proyek}}</td>
	</tr>
	<tr></tr>
	<tr>
		<th colspan="5" style="border: 1px solid black;border-collapse: collapse;text-align:center;font-weight: bold;background: #92D050;">Kebutuhan Material</th>
		<th colspan="5" style="border: 1px solid black;border-collapse: collapse;text-align:center;font-weight: bold;background: #92D050;">Kebutuhan Jasa</th>
	</tr>
	<tr>
		<th style="border: 1px solid black;border-collapse: collapse;text-align:center;font-weight: bold;background: #D9D9D9;">Nama Material</th>
		<th style="border: 1px solid black;border-collapse: collapse;text-align:center;font-weight: bold;background: #D9D9D9;">Volume</th>
		<th style="border: 1px solid black;border-collapse: collapse;text-align:center;font-weight: bold;background: #D9D9D9;">Koefisien</th>
		<th colspan="2" style="border: 1px solid black;border-collapse: collapse;text-align:center;font-weight: bold;background: #D9D9D9;">Kebutuhan</th>
		<!--  -->
		<th style="border: 1px solid black;border-collapse: collapse;text-align:center;font-weight: bold;background: #D9D9D9;">Nama Jasa</th>
		<th style="border: 1px solid black;border-collapse: collapse;text-align:center;font-weight: bold;background: #D9D9D9;">Volume</th>
		<th style="border: 1px solid black;border-collapse: collapse;text-align:center;font-weight: bold;background: #D9D9D9;">Koefisien</th>
		<th colspan="2" style="border: 1px solid black;border-collapse: collapse;text-align:center;font-weight: bold;background: #D9D9D9;">Kebutuhan</th>
	</tr>
	@php for($i=0; $i < count($data); $i++){ @endphp
	<tr style="border: 1px solid black;border-collapse: collapse;">
		<td colspan="10" style="border: 1px solid black;border-collapse: collapse;background:#ED7D31;font-weight: bold;">{{$data[$i]['pekerjaan']}}</td>
	</tr>
	@php for($j=0; $j < count($data[$i]['sub_pekerjaan']); $j++){ @endphp
	<tr style="border: 1px solid black;border-collapse: collapse;">
		<td colspan="10" style="border: 1px solid black;border-collapse: collapse;background: #D9D9D9;">{{$data[$i]['sub_pekerjaan'][$j]['sub_pekerjaan']}}</td>
	</tr>
	@php

	$count = 0;
	$count_material = count($data[$i]['sub_pekerjaan'][$j]['komponen_material']);
	$count_jasa = count($data[$i]['sub_pekerjaan'][$j]['komponen_jasa']);
	if($count_material > $count_jasa){
		$count = $count_material;
	}elseif($count_material < $count_jasa){
		$count = $count_jasa;
	}elseif($count_material == $count_jasa){
		$count = $count_jasa;
	}

	for($k=0; $k < $count; $k++){ @endphp
	@php
	if(empty($data[$i]['sub_pekerjaan'][$j]['komponen_material'][$k])){
		$data[$i]['sub_pekerjaan'][$j]['komponen_material'][$k] = [
		'nama_material' => '-',
		'harga_asli' => '-',
		'koefisien' => '0',
		'harga_fix' => '-',
		'satuan' => '-',
		'volume' => '0'
		];
	} else {
		$data[$i]['sub_pekerjaan'][$j]['komponen_material'][$k]['volume'] = $data[$i]['sub_pekerjaan'][$j]['volume'];
	}

	if(empty($data[$i]['sub_pekerjaan'][$j]['komponen_jasa'][$k])){
		$data[$i]['sub_pekerjaan'][$j]['komponen_jasa'][$k] = [
		'nama_jasa' => '-',
		'harga_asli' => '-',
		'koefisien' => '0',
		'harga_fix' => '-',
		'satuan' => '-',
		'volume' => 0,
		];
	} else {
		$data[$i]['sub_pekerjaan'][$j]['komponen_jasa'][$k]['volume'] = $data[$i]['sub_pekerjaan'][$j]['volume'];
	}
	@endphp
	<tr>
		<td style="border: 1px solid black;border-collapse: collapse;">{{$data[$i]['sub_pekerjaan'][$j]['komponen_material'][$k]['nama_material']}}</td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;">{{$data[$i]['sub_pekerjaan'][$j]['komponen_material'][$k]['volume']}}</td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;" data-format="#,##0.00_-">{{$data[$i]['sub_pekerjaan'][$j]['komponen_material'][$k]['koefisien']}}</td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;" data-format="#,##0.00_-">{{($data[$i]['sub_pekerjaan'][$j]['komponen_material'][$k]['koefisien'] * $data[$i]['sub_pekerjaan'][$j]['volume'])}}</td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;">{{$data[$i]['sub_pekerjaan'][$j]['komponen_material'][$k]['satuan']}}</td>
		
		<td style="border: 1px solid black;border-collapse: collapse;">{{$data[$i]['sub_pekerjaan'][$j]['komponen_jasa'][$k]['nama_jasa']}}</td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;">{{$data[$i]['sub_pekerjaan'][$j]['komponen_jasa'][$k]['volume']}}</td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;" data-format="#,##0.00_-">{{$data[$i]['sub_pekerjaan'][$j]['komponen_jasa'][$k]['koefisien']}}</td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;" data-format="#,##0.00_-">{{($data[$i]['sub_pekerjaan'][$j]['komponen_jasa'][$k]['koefisien'] * $data[$i]['sub_pekerjaan'][$j]['volume'])}}</td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;">{{$data[$i]['sub_pekerjaan'][$j]['komponen_jasa'][$k]['satuan']}}</td>
	</tr>
	@php } @endphp
	@php } @endphp
	@php } @endphp
</table>