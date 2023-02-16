<style type="text/css">
	table, th, td {
		border: 1px solid black;
		border-collapse: collapse;
	}
</style>
<?php
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
?>
<table>
	<tr>
		<td colspan="10" style="text-align: center;font-weight: bold;font-size: 20px;">Rencana Anggaran Biaya (RAB)</td>
	</tr>
	<tr></tr>
	<tr>
		<td colspan="3">Nama Proyek</td>
		<td>:</td>
		<td colspan="6">{{$proyek->nama_proyek}}</td>
	</tr>
	<tr>
		<td colspan="3">Anggaran Biaya</td>
		<td>:</td>
		<td>Rp.</td>
		<td colspan="5" data-format="#,##0.00_-" style="text-align: left;">{{$total_all}}</td>
	</tr>
	<tr></tr>
	<tr>
		<th style="border: 1px solid black;border-collapse: collapse;text-align: center;background: #D9D9D9;"><strong>NO</strong></th>
		<th colspan="2" style="border: 1px solid black;border-collapse: collapse;text-align: center;background: #D9D9D9;"><strong>NAMA PEKERJAAN</strong></th>
		<th colspan="3" style="border: 1px solid black;border-collapse: collapse;text-align: center;background: #D9D9D9;"><strong>KODE SUB PEKERJAAN</strong></th>
		<th colspan="2" style="border: 1px solid black;border-collapse: collapse;text-align: center;background: #D9D9D9;"><strong>VOLUME</strong></th>
		<th style="border: 1px solid black;border-collapse: collapse;text-align: center;background: #D9D9D9;"><strong>HARGA SATUAN (Rp)</strong></th>
		<th style="border: 1px solid black;border-collapse: collapse;text-align: center;background: #D9D9D9;"><strong>TOTAL HARGA (Rp)</strong></th>
	</tr>
	@for($i=0; $i < count($datas); $i++)
	<tr>
		<td style="border: 1px solid black;border-collapse: collapse;text-align: center; background:#D9D9D9"><strong>{{romawi($i+1)}}</strong></td>
		<td style="border: 1px solid black;border-collapse: collapse;background:#D9D9D9" colspan="9"><strong>{{$datas[$i]['nama_pekerjaan']}}</strong></td>
	</tr>
		@for($j=0; $j < count($datas[$i]['detail']); $j++)
		<tr>
			<td style="border: 1px solid black;border-collapse: collapse;text-align: center">{{($j+1)}}</td>
			<td colspan="2" style="border: 1px solid black;border-collapse: collapse;">{{$datas[$i]['detail'][$j]['sub_pekerjaan']}}</td>
			<td colspan="3" style="border: 1px solid black;border-collapse: collapse;text-align: center">{{$datas[$i]['detail'][$j]['kode_analisa']}}</td>
			<td style="border: 1px solid black;border-collapse: collapse;text-align: center">{{$datas[$i]['detail'][$j]['volume']}}</td>
			<td style="border: 1px solid black;border-collapse: collapse;text-align: center">{{$datas[$i]['detail'][$j]['satuan_sub_pekerjaan']}}</td>
			<td style="border: 1px solid black;border-collapse: collapse;" data-format="#,##0.00_-">{{$datas[$i]['detail'][$j]['fix_komponen']}}</td>
			<td style="border: 1px solid black;border-collapse: collapse;" data-format="#,##0.00_-">{{$datas[$i]['detail'][$j]['komponen_total']}}</td>
		</tr>
		@endfor
	<tr>
		<td style="border: 1px solid black;border-collapse: collapse;background:#D9D9D9" colspan="9"><strong>SUB TOTAL {{romawi($i+1)}}</strong></td>
		<td style="border: 1px solid black;border-collapse: collapse;background:#D9D9D9" data-format="#,##0.00_-"><strong>{{$datas[$i]['total']}}</strong></td>
	</tr>
	@endfor
</table>