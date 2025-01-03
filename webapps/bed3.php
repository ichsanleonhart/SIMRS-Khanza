<?php
 //fitur update kamar aplicare ini adalah penyempurnaan dari kontribusi Mas Tirta dari RSUK Ciracas Jakarta Timur
 session_start();
 require_once('conf/conf.php');
 header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
 header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); 
 header("Cache-Control: no-store, no-cache, must-revalidate"); 
 header("Cache-Control: post-check=0, pre-check=0", false);
 header("Pragma: no-cache"); // HTTP/1.0
 date_default_timezone_set("Asia/Bangkok");
 $tanggal= mktime(date("m"),date("d"),date("Y"));
 $jam=date("H:i");
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="css/default.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="conf/validator.js"></script>
    <meta http-equiv="refresh" content="20"/>
    <title>Informasi Ketersediaan Kamar</title>
    <script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
    <script src="Scripts/AC_ActiveX.js" type="text/javascript"></script>
	<style type="text/css">
	<!--
	body {
		background-image: url();
		background-repeat: no-repeat;
		background-color: #FFFFCC;
	}
	-->
	</style>
</head>
<body>

<div align="left">
	<script type="text/javascript">
		AC_AX_RunContent( 'width','32','height','32' ); //end AC code
	</script>
	<noscript>
       <object width="32" height="32">
         <embed width="32" height="32"></embed>
       </object>
     </noscript>
     <?php
		$setting=  mysqli_fetch_array(bukaquery("select setting.nama_instansi,setting.alamat_instansi,setting.kabupaten,setting.propinsi,setting.kontak,setting.email,setting.logo from setting"));
		echo "   
		   <table width='100%' align='center' border='0' class='tbl_form' cellspacing='0' cellpadding='0'>
			  <tr>
				<td  width='10%' align='right' valign='center'>
					<img width='90' height='90' src='data:image/jpeg;base64,". base64_encode($setting['logo']). "'/>
				</td>
				<td>
				   <center>
					  <font size='7' color='#AA00AA' face='Tahoma'><b>".$setting["nama_instansi"]."</b></font><br>
					  <font size='5' color='#AA00AA' face='Tahoma'>
						  ".$setting["alamat_instansi"].", ".$setting["kabupaten"].", ".$setting["propinsi"]."<br>   
					  </font> 
					  <font size='5' color='#AAAA00' face='Tahoma' >".date("d-M-Y", $tanggal)."  ". $jam."</font>
					  <br><br>
				   </center>
				</td>   
				<td  width='10%' align='left'>
					&nbsp;
				</td>  
				<td  width='10%' align='left' valign='top'>
					<img width='180' height='130' src='header-kanan.jpg'/>
				</td>                                                          
			 </tr>
		  </table> "; 
	?>
	<table width='100%' bgcolor='FFFFFF' border='0' align='center' cellpadding='0' cellspacing='0'>
	     <tr class='head5'>
              <td width='100%'><div align='center'></div></td>
         </tr>
    </table>
	<table width='100%' bgcolor='FFFFFF' border='0' align='center' cellpadding='0' cellspacing='0'>
	     <tr class='head4'>
              <td width='40%'><div align='center'><font size='5'><b>NAMA KAMAR</b></font></div></td>
			  <td width='20%'><div align='center'><font size='5'><b>KELAS</b></font></div></td>              
              <td width='20%'><div align='center'><font size='5'><b>JUMLAH BED</b></font></div></td>
              <td width='10%'><div align='center'><font size='5'><b>BED TERISI</b></font></div></td>
              <td width='10%'><div align='center'><font size='5'><b>BED KOSONG</b></font></div></td>
         </tr>

	<?php  
		//$_sql="Select * From bangsal where status='1' and kd_bangsal in(select kd_bangsal from kamar)" ;
		$_sql="Select bangsal.kd_bangsal, bangsal.nm_bangsal, kamar.kelas, bangsal.status From bangsal 
				left join kamar
				on bangsal.kd_bangsal = kamar.kd_bangsal
				where bangsal.status='1' 
				and kamar.statusdata ='1'
				and bangsal.kd_bangsal in(select kd_bangsal from kamar)
				group by kd_bangsal 
				order by kamar.kelas asc, bangsal.nm_bangsal asc
				" ;  
				
		$hasil=bukaquery($_sql);

		while ($data = mysqli_fetch_array ($hasil)){
			echo "<tr class='isi7' >					
					<td align='left'><font size='5' color='#BB00BB' face='Tahoma'><b>".$data['nm_bangsal']."</b></font></td>
					<td align='left'><font size='5' color='#BB00BB' face='Tahoma'><b>".$data['kelas']."</b></font></td>
					<td align='center'>
					     <font size='6' color='red' face='Tahoma'>
					      <b>";
					       $data2=mysqli_fetch_array(bukaquery("select count(kd_bangsal) from kamar where kamar.statusdata='1' and kd_bangsal='".$data['kd_bangsal']."'"));
					       echo $data2[0];
					echo "</b>
					      </font>
					</td>
					<td align='center'>
					     <font color='#DDDD00' size='6'  face='Tahoma'>
					      <b>";
						   $data2=mysqli_fetch_array(bukaquery("select count(kd_bangsal) from kamar where kamar.statusdata='1' and kd_bangsal='".$data['kd_bangsal']."' and status='ISI'"));
						   echo $data2[0];
					echo "</b>
					      </font>
					</td>
					<td align='center'>
					      <font color='gren' size='6'  face='Tahoma'>
					      <b>";
						   $data2=mysqli_fetch_array(bukaquery("select count(kd_bangsal) from kamar where kamar.statusdata='1' and kd_bangsal='".$data['kd_bangsal']."' and status='KOSONG'"));
						   echo $data2[0];
					echo "</b>
					     </font>
					</td>
				</tr> ";
		}
	?>
	</table>
	<table width='100%' bgcolor='FFFFFF' border='0' align='center' cellpadding='0' cellspacing='0'>
	     <tr class='head5'>
              <td width='100%'><div align='center'></div></td>
         </tr>
    </table>
	<img src="ft-2.jpg" alt="bar-pic" width="100%" height="83">
</body>
