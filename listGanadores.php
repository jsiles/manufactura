<?php
include('common.php');
session_start();
//check_security(1);
$fldjue_id = get_param("jue_id");
$fldper_id = get_param("per_id");
$gandores = listaGanadores($fldjue_id, $fldper_id);
if(is_array($gandores))
{
	foreach($gandores as $key => $value)
	{
	   $ranking[$value] = get_db_value("select usu_nombre from tb_usuarios where usu_id=$key");
	}
	
	$html = "Primer lugar:". $ranking[1]  ."<br />Segundo lugar:". $ranking[2] ."<br />Tercer lugar: ". $ranking[3] ."<br />&Uacute;ltimo lugar: ". $ranking[4] . "<br />";
}else $html="";

echo $html;


function listaGanadores($fldjue_id, $fldper_id)
{
global $db;
					if(!(int)$fldjue_id) $fldjue_id=0;
					if(!(int)$fldper_id) $fldper_id=0;
					
					$sSQL="select * from tb_responsabilidad where res_jue_id=$fldjue_id and res_per_id=$fldper_id order by res_id";
					$db->query($sSQL);
					if($db->num_rows()>0)
					{
					
					while($db->next_record()){
						
						$RS[$db->f("res_id")]=$db->f("res_nombre");
						$beneficio[$db->f("res_id")][1]=$db->f("res_beneficio1");
						$beneficio[$db->f("res_id")][2]=$db->f("res_beneficio2");
						$beneficio[$db->f("res_id")][3]=$db->f("res_beneficio3");
						$beneficio[$db->f("res_id")][4]=$db->f("res_beneficio4");
						$costo[$db->f("res_id")]=$db->f("res_precio");
					}
					
					$cantidadUsuariosTotal = get_db_value("select count(*) from tb_usuarios where usu_jue_id=$fldjue_id");
					foreach($RS as $key => $value)
					{
						$sSQL="select * from tb_inclusion where inc_res_id=$key";
						$db->query($sSQL);
						$cantidadUsuariosEnJuego = 0;
						while($db->next_record())
						{
							$inclusion[$key][$db->f("inc_usu_id")]=$db->f("inc_usu_id");
							$usuarios[$db->f("inc_usu_id")] =$db->f("inc_usu_id");
							$cantidadUsuariosEnJuego ++; 
						}
						$cantidadUsuarios[$key] = $cantidadUsuariosEnJuego; 
					}
					
						$sSQL="select * from tb_responsabilidadgeneral where reg_jue_id=$fldjue_id and reg_per_id=$fldper_id order by reg_id";
						$db->query($sSQL);
						while($db->next_record())
						{
							$indirecto[1]=$db->f("reg_beneficio1");
							$indirecto[2]=$db->f("reg_beneficio2");
							$indirecto[3]=$db->f("reg_beneficio3");
							$indirecto[4]=$db->f("reg_beneficio4");
						}
					
					$i=1;
					
					
					$arrayUser = db_fill_array("select usu_id, usu_nombre from tb_usuarios where usu_jue_id=$fldjue_id");
					$totalUser = $arrayUser;
					$totalUser = count($totalUser)-1; 
					$totalUserIncluidos = count($usuarios);
					
					/*if($totalUserIncluidos == $totalUser) $valSw = true;
					else $valSw = false;
					*/
					foreach($arrayUser as $usuarioKey => $usuarioValue)
					{
					 $k=0;
					 $totalinversion=0;
					 $totalbeneficio=0;
						
						foreach($RS as $key=>$value)
						{
							//Inversion
							$inversion = (count($inclusion[$key][$usuarioKey])!=0)?$costo[$key]:0;
							//$cantidadUsuarios[$key] = $cantidadUsuariosEnJuego; 
							$valSw=false;
							if($cantidadUsuarios[$key] == $totalUser)
							$valSw=true;
							//Beneficio
							if($valSw)
							$beneficiodirecto = (count($inclusion[$key][$usuarioKey])!=0)?$beneficio[$key][count($inclusion[$key])]:$beneficio[$key][4];
							else
							$beneficiodirecto = (count($inclusion[$key][$usuarioKey])!=0)?$beneficio[$key][count($inclusion[$key])]:0;
							
							//$beneficiodirecto = $cantidadUsuarios[$key]."-".$totalUser;
							
							//ROI
							$totalinversion+=$inversion;
							$totalbeneficio+=$beneficiodirecto;
							$k+=3;
						}
					
					 $ranking[$usuarioKey] = $totalbeneficio;
					 $rankingInv[$usuarioKey] = $totalinversion;
					 $j++;	
					} //Fin foreach
					/*
					 * TOTAL PROGRAMAS
					 *
					 */

					foreach($arrayUser as $usuarioKey => $usuarioValue)
					{
					  $k=0;
					  $beneficiosNetosDirectos[$usuarioKey] = $ranking[$usuarioKey] - $rankingInv[$usuarioKey];
					} //Fin foreach
					
					arsort($beneficiosNetosDirectos);
					$i=1;
					
					foreach($beneficiosNetosDirectos as $key => $value)
					{
						$rankingBeneficios[$i]= $value;
						$rankingBeneficiosTmp[$i]= $key;
						$i++;
					}
					for($x=1;$x<$i;$x++)
					{
						$z=0;
						for($y=$x+1;$y<$i;$y++)
							if($rankingBeneficios[$x]==$rankingBeneficios[$y]) $z++;
						$posicionRank[$rankingBeneficiosTmp[$x]] = $x+$z;
					}
					$j=4;
					
					//RS beneficio directo 1er 2do 3er y único sin participar
					
					//RS GENERAL Imagen Beneficio Indirecto %
					
					//RS GENERAL Beneficio Indirecto 1ero 2do 3er y ultimo 
					
					//RS NOTAS TRABAJO
					
					//RS RANKING notas ranking directo
					
					//Calificacion imagen = (notas ranking directo * imagen beneficio indirecto % ) + (NOTAS TRABAJO * (1-imagen beneficio indirecto %)) 
					$arrayNotas = db_fill_array("select ren_usu_id, ren_nota from tb_responsabilidadnotas where ren_jue_id=$fldjue_id and ren_per_id=$fldper_id order by ren_id");
					$arrayRanking = db_fill_array("select rer_posicion, rer_nota from tb_responsabilidadranking where rer_jue_id=$fldjue_id and rer_per_id=$fldper_id order by rer_id");					
					$imagenBeneficio = get_db_value("select reg_beneficioDirecto from tb_responsabilidadgeneral where reg_jue_id=$fldjue_id and reg_per_id=$fldper_id order by reg_id");
					$imagenBeneficio = $imagenBeneficio /100;

					
					foreach($arrayUser as $usuarioKey => $usuarioValue)
					{
					  $calificacionImagen[$usuarioKey] = ($arrayRanking[$posicionRank[$usuarioKey]]* $imagenBeneficio) + ($arrayNotas[$usuarioKey]*( 1 -  $imagenBeneficio));
					} 					
					//RankingCalificacionImagen
					arsort($calificacionImagen);
					$i=1;
					
					foreach($calificacionImagen as $key => $value)
					{
						$rankingCalificacionImagen[$i]= $value;
						$rankingCalificacionImagenTmp[$i]= $key;
						$i++;
					}
					for($x=1;$x<$i;$x++)
					{
						$z=0;
						for($y=$x+1;$y<$i;$y++)
							if($rankingCalificacionImagen[$x]==$rankingCalificacionImagen[$y]) $z++;
						$posicionRankCalificacionImagen[$rankingCalificacionImagenTmp[$x]] = $x+$z;
						
					}
					
					//Total Beneficio Indirecto = RS GENERAL Beneficio indirecto [rankingCalificacion]
					
					$j=4;
					foreach($arrayUser as $usuarioKey => $usuarioValue)
					{
					  $k=0;
					  $RSGeneralBeneficioIndirecto[$usuarioKey] = $indirecto[$posicionRankCalificacionImagen[$usuarioKey]]; 
					}
					
					//Total Beneficio RS = Total indirecto + $ranking[$usuarioKey]
					
					foreach($arrayUser as $usuarioKey => $usuarioValue)
					{
					  $k=0;
					  $RSGeneralBeneficioRS[$usuarioKey] = $RSGeneralBeneficioIndirecto[$usuarioKey] +  $ranking[$usuarioKey];
					}
					
					//ROI RS = (Total Beneficio RS - $rankingInv[$usuarioKey])/$rankingInv[$usuarioKey]
					foreach($arrayUser as $usuarioKey => $usuarioValue)
					{
					  $k=0;
					  $ROIRS[$usuarioKey] = ($rankingInv[$usuarioKey]!=0)?($RSGeneralBeneficioRS[$usuarioKey] - $rankingInv[$usuarioKey])/$rankingInv[$usuarioKey]*100:0;
					}
			}
	return $posicionRankCalificacionImagen;
}
?>