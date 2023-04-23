<style type="text/css">
	table, th, td {
		border: 1px solid black;
		border-collapse: collapse;
	}
</style>
@php
function romawi($angka){
	$angka = intval($angka);
	$result = '';

	$array = array('M' => 1000,
		'CM' => 900,
		'D' => 500,
		'CD' => 400,
		'C' => 100,
		'XC' => 90,
		'L' => 50,
		'XL' => 40,
		'X' => 10,
		'IX' => 9,
		'V' => 5,
		'IV' => 4,
		'I' => 1);

	foreach($array as $roman => $value){
		$matches = intval($angka/$value);

		$result .= str_repeat($roman,$matches);

		$angka = $angka % $value;
	}

	return $result;
}

function tanggal($tanggal){
	$bulan = array (
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$pecahkan = explode('-', $tanggal);
 
	return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}
@endphp
<table>
	<tr>
		<td colspan="12" style="text-align: center;font-weight: bold;font-size: 20px;">Rencana Anggaran Proyek (RAP)</td>
	</tr>
	<tr></tr>
	<tr>
		<td colspan="2">Nama Proyek</td>
		<td style="text-align: right;">:</td>
		<td colspan="10">{{$nama_proyek}}</td>
	</tr>
	<tr></tr>
	<tr>
		<th rowspan="2" style="border: 1px solid black;border-collapse: collapse;text-align:center;font-weight: bold;background: #D9D9D9;">NO.</th>
		<th rowspan="2" style="border: 1px solid black;border-collapse: collapse;text-align:center;font-weight: bold;background: #D9D9D9;">PEKERJAAN</th>
		<th colspan="5" style="border: 1px solid black;border-collapse: collapse;text-align:center;font-weight: bold;background: #D9D9D9;">KEBUTUHAN MATERIAL</th>
		<th colspan="5" style="border: 1px solid black;border-collapse: collapse;text-align:center;font-weight: bold;background: #D9D9D9;">KEBUTUHAN JASA</th>
	</tr>
	<tr>
		<th style="border: 1px solid black;border-collapse: collapse;text-align:center;font-weight: bold;">NAMA MATERIAL</th>
		<th style="border: 1px solid black;border-collapse: collapse;text-align:center;font-weight: bold;">VOLUME</th>
		<th style="border: 1px solid black;border-collapse: collapse;text-align:center;font-weight: bold;">KOEFISIEN</th>
		<th colspan="2" style="border: 1px solid black;border-collapse: collapse;text-align:center;font-weight: bold;">KEBUTUHAN</th>
		<!--  -->
		<th style="border: 1px solid black;border-collapse: collapse;text-align:center;font-weight: bold;">NAMA JASA</th>
		<th style="border: 1px solid black;border-collapse: collapse;text-align:center;font-weight: bold;">VOLUME</th>
		<th style="border: 1px solid black;border-collapse: collapse;text-align:center;font-weight: bold;">KOEFISIEN</th>
		<th colspan="2" style="border: 1px solid black;border-collapse: collapse;text-align:center;font-weight: bold;">KEBUTUHAN</th>
	</tr>
	@php for($i=0; $i < count($data); $i++){ @endphp
	<tr style="border: 1px solid black;border-collapse: collapse;">
		<td style="border: 1px solid black;border-collapse: collapse;background: #D9D9D9;">{{romawi($i+1)}}</td>
		<td colspan="11" style="border: 1px solid black;border-collapse: collapse;background:#D9D9D9;font-weight: bold;">{{$data[$i]['pekerjaan']}}</td>
	</tr>
	@php for($j=0; $j < count($data[$i]['sub_pekerjaan']); $j++){ @endphp
	<tr style="border: 1px solid black;border-collapse: collapse;">
		<td style="border: 1px solid black;border-collapse: collapse;background: #D9D9D9;">{{($j+1)}}</td>
		<td colspan="11" style="border: 1px solid black;border-collapse: collapse;background: #D9D9D9;">{{$data[$i]['sub_pekerjaan'][$j]['sub_pekerjaan']}}</td>
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

	for($k=0; $k < $count; $k++){ 
	if(empty($data[$i]['sub_pekerjaan'][$j]['komponen_material'][$k])){
		
	} else {
		$data[$i]['sub_pekerjaan'][$j]['komponen_material'][$k]['volume'] = $data[$i]['sub_pekerjaan'][$j]['volume'];
	}

	if(empty($data[$i]['sub_pekerjaan'][$j]['komponen_jasa'][$k])){
		
	} else {
		$data[$i]['sub_pekerjaan'][$j]['komponen_jasa'][$k]['volume'] = $data[$i]['sub_pekerjaan'][$j]['volume'];
	}
	@endphp
	<tr>
		@if(!empty($data[$i]['sub_pekerjaan'][$j]['komponen_material'][$k]))
		<td style="border: 1px solid black;border-collapse: collapse;"></td>
		<td style="border: 1px solid black;border-collapse: collapse;"></td>
		<td style="border: 1px solid black;border-collapse: collapse;">{{$data[$i]['sub_pekerjaan'][$j]['komponen_material'][$k]['nama_material']}}</td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;">{{$data[$i]['sub_pekerjaan'][$j]['komponen_material'][$k]['volume']}}</td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;" data-format="#,##0.00_-">{{$data[$i]['sub_pekerjaan'][$j]['komponen_material'][$k]['koefisien']}}</td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;" data-format="#,##0.00_-">{{($data[$i]['sub_pekerjaan'][$j]['komponen_material'][$k]['koefisien'] * $data[$i]['sub_pekerjaan'][$j]['volume'])}}</td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;">{{$data[$i]['sub_pekerjaan'][$j]['komponen_material'][$k]['satuan']}}</td>
		@else
		<td style="border: 1px solid black;border-collapse: collapse;"></td>
		<td style="border: 1px solid black;border-collapse: collapse;"></td>
		<td style="border: 1px solid black;border-collapse: collapse;"></td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;"></td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;" data-format="#,##0.00_-"></td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;" data-format="#,##0.00_-"></td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;"></td>
		@endif
		@if(!empty($data[$i]['sub_pekerjaan'][$j]['komponen_jasa'][$k]))
		<td style="border: 1px solid black;border-collapse: collapse;">{{$data[$i]['sub_pekerjaan'][$j]['komponen_jasa'][$k]['nama_jasa']}}</td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;">{{$data[$i]['sub_pekerjaan'][$j]['komponen_jasa'][$k]['volume']}}</td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;" data-format="#,##0.00_-">{{$data[$i]['sub_pekerjaan'][$j]['komponen_jasa'][$k]['koefisien']}}</td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;" data-format="#,##0.00_-">{{($data[$i]['sub_pekerjaan'][$j]['komponen_jasa'][$k]['koefisien'] * $data[$i]['sub_pekerjaan'][$j]['volume'])}}</td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;">{{$data[$i]['sub_pekerjaan'][$j]['komponen_jasa'][$k]['satuan']}}</td>
		@else
		<td style="border: 1px solid black;border-collapse: collapse;"></td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;"></td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;" data-format="#,##0.00_-"></td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;" data-format="#,##0.00_-"></td>
		<td style="border: 1px solid black;border-collapse: collapse;text-align:center;"></td>
		@endif
	</tr>
	@php } @endphp
	@php } @endphp
	@php } @endphp
</table>