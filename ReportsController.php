<?php

class ReportsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /reports
	 *
	 * @return Response
	 */
	public function index()
	{
		$data = array(
			'page' => 'Reportes'
		);

		return View::make('pages.reports', $data);
	}

/* Reporte Semanal por Zona */
    public function weeklyPerZone(){

		$year = date('Y');
		$week = date('W');

		$weekStart  = date('Y-m-d', strtotime($year . 'W' . str_pad($week , 2, '0', STR_PAD_LEFT)));
		$weekEnd 	= date('Y-m-d', strtotime($weekStart.' 6 day'));
		//$weekStart  = date( '2015-01-01' );
		//$weekEnd    = date( '2016-12-23' );

		$staffList=Staff::with(array('customer' => function($query) use($weekStart, $weekEnd){
			$query->with(array('bills' => function($query) use($weekStart, $weekEnd){
				$query->with(array('deposit' => function($query) use($weekStart, $weekEnd){
					$query->whereBetween('deposit_date', array($weekStart, $weekEnd));

				}))->get();
			}))->orderBy('staff_id')->get();

		}))->get();
		//return Response::json( $staffList );
		$fileName = "Reporte Semanal por Zona-".$weekStart."-".$weekEnd;

		Excel::create($fileName, function ($excel) use ($staffList) {
			$excel->sheet('staffList', function ($sheet) use ($staffList) {
				/*
					+--------------------------------------------+
					| Encargado de Zona                          |
					+--------------------------------------------+-------+----------+------------+------------+
					| Deudor                                     | Abono | Prestamo | Recuperado | Fechas ... |
					+--------------------------------------------+-------+----------+------------+------------+
				*/
				/* Titulos */
				$sheet->row(1, array(
					'Encargado de Zona',
					'Deudor',
					'Monto de Abonos',
					'Prestamo Total',
					'Monto Calculado de Recuperación',
					'Total Pagado en el Periodo ',
					'Pago 1',
					'Pago 2',
					'Pago 3',
					'Pago 4',
					'Pago 5',
					'Pago 6',
					'Pago 7',
					'Pago 8',
					'Pago 9',
					'Pago 10',
					'Pago 11',
					'Pago 12',
					'Pago 13',
					'Pago 14',
					'Pago 15'));

				/* Ancho de Columna */
				$sheet->setWidth(array(
					'A' => 30, /* Encargado de Zona*/
					'B' => 30, /* Deudor */
					'C' => 15, /* Abono */
					'D' => 15, /* Prestamos */
					'E' => 15, /* Recuperado */
					'F' => 15,  /* Total */
					'G' => 15  /* Restante */
				));


				/* Atributos Especiales */
				$sheet->freezeFirstRow();

				/* Fuente de Columnas Principales */
				$sheet->cells('A1:V1', function ($cells) {
					$cells->setFontWeight('bold');
					$cells->setAlignment('center');
					$cells->setValignment('middle');
					$cells->setBackground('#000000');
					$cells->setFontColor('#ffffff');
				});
				$x='';
				foreach ($staffList as $sKey => $staff){
					
					foreach ($staff->customer as $cKey => $customer) {

						foreach ($customer->bills as $bKey => $bill) {
							$total = 0;

							foreach($bill->deposit as $deposit){
								$total = $total + $deposit->amount;
							}
							if(strcmp($x, $staff->first_name.' '.$staff->last_name.', '.$staff->name)!==0){					
								$sheet->row($cKey+2, array(
									$staff->first_name.' '.$staff->last_name.', '.$staff->name,
									$customer->first_name.' '.$customer->last_name.', '.$customer->name,
									($bill->amount * .10),
									$bill->amount,
									($bill->amount * 1.5),
									$total, 
									(isset($bill->deposit[0])) ? $bill->deposit[0]->amount : '$ -',
									(isset($bill->deposit[1])) ? $bill->deposit[1]->amount : '$ -',
									(isset($bill->deposit[2])) ? $bill->deposit[2]->amount : '$ -',
									(isset($bill->deposit[3])) ? $bill->deposit[3]->amount : '$ -',
									(isset($bill->deposit[4])) ? $bill->deposit[4]->amount : '$ -',
									(isset($bill->deposit[5])) ? $bill->deposit[5]->amount : '$ -',
									(isset($bill->deposit[6])) ? $bill->deposit[6]->amount : '$ -',
									(isset($bill->deposit[7])) ? $bill->deposit[7]->amount : '$ -',
									(isset($bill->deposit[8])) ? $bill->deposit[8]->amount : '$ -',
									(isset($bill->deposit[9])) ? $bill->deposit[9]->amount : '$ -',
									(isset($bill->deposit[10])) ? $bill->deposit[10]->amount : '$ -',
									(isset($bill->deposit[11])) ? $bill->deposit[11]->amount : '$ -',
									(isset($bill->deposit[12])) ? $bill->deposit[12]->amount : '$ -',
									(isset($bill->deposit[13])) ? $bill->deposit[13]->amount : '$ -',
									(isset($bill->deposit[14])) ? $bill->deposit[14]->amount : '$ -',
								));
								
							}else{
								$sheet->row($cKey+2, array(
									null,
									$customer->first_name.' '.$customer->last_name.', '.$customer->name,
									($bill->amount * .10),
									$bill->amount,
									($bill->amount * 1.5),
									$total, 
									(isset($bill->deposit[0])) ? $bill->deposit[0]->amount : '$ -',
									(isset($bill->deposit[1])) ? $bill->deposit[1]->amount : '$ -',
									(isset($bill->deposit[2])) ? $bill->deposit[2]->amount : '$ -',
									(isset($bill->deposit[3])) ? $bill->deposit[3]->amount : '$ -',
									(isset($bill->deposit[4])) ? $bill->deposit[4]->amount : '$ -',
									(isset($bill->deposit[5])) ? $bill->deposit[5]->amount : '$ -',
									(isset($bill->deposit[6])) ? $bill->deposit[6]->amount : '$ -',
									(isset($bill->deposit[7])) ? $bill->deposit[7]->amount : '$ -',
									(isset($bill->deposit[8])) ? $bill->deposit[8]->amount : '$ -',
									(isset($bill->deposit[9])) ? $bill->deposit[9]->amount : '$ -',
									(isset($bill->deposit[10])) ? $bill->deposit[10]->amount : '$ -',
									(isset($bill->deposit[11])) ? $bill->deposit[11]->amount : '$ -',
									(isset($bill->deposit[12])) ? $bill->deposit[12]->amount : '$ -',
									(isset($bill->deposit[13])) ? $bill->deposit[13]->amount : '$ -',
									(isset($bill->deposit[14])) ? $bill->deposit[14]->amount : '$ -',
								));
							}

							
						}
					}
				}
			});
		})->export('xls');

    	return Response::json("Done");

    }

/* Reporte de Supervision */
	public function supervisionReport(){
	
		$year = date('Y');
		$week = date('W');
	
		$weekStart  = date('Y-m-d', strtotime($year . 'W' . str_pad($week , 2, '0', STR_PAD_LEFT)));
		$weekEnd = date('Y-m-d', strtotime($weekStart.' 6 day'));
		
		
		// $supervisionReport = User::with(aray('Staff_comission'=>function($query){
		// 	$query->with()
		// })->where('level_id', 3)->get();
	
		$fileName = "Reporte de Supervicion-".$weekStart."-".$weekEnd;
	
		Excel::create($fileName, function ($excel) use ($staffList) {
			$excel->sheet('staffList', function ($sheet) use ($staffList) {
				/* Titulos */
				$sheet->row(1, array(
					'Encargado de Zona',
					'Deudor',
					'Abono',
					'Prestamo',
					'Recuperado',
					'Total',
					'Pago 1',
					'Pago 2',
					'Pago 3',
					'Pago 4',
					'Pago 5',
					'Pago 6',
					'Pago 7',
					'Pago 8',
					'Pago 9',
					'Pago 10',
					'Pago 11',
					'Pago 12',
					'Pago 13',
					'Pago 14',
					'Pago 15'));
	
				/* Ancho de Columna */
				$sheet->setWidth(array(
					'A' => 30, /* Encargado de Zona*/
					'B' => 30, /* Deudor */
					'C' => 15, /* Abono */
					'D' => 15, /* Prestamos */
					'E' => 15, /* Recuperado */
					'F' => 15  /* Total */
				));
	
				/* Atributos Especiales */
				$sheet->freezeFirstRow();
	
				/* Fuente de Columnas Principales */
				$sheet->cells('A1:U1', function ($cells) {
					$cells->setFontWeight('bold');
					$cells->setAlignment('center');
					$cells->setValignment('middle');
					$cells->setBackground('#000000');
					$cells->setFontColor('#ffffff');
				});
	
				foreach ($staffList as $sKey => $staff){
					foreach ($staff->customer as $cKey => $customer) {
						foreach ($customer->bills as $bKey => $bill) {
							$total = 0;
	
							foreach($bill->deposit as $deposit){
								$total = $total + $deposit->amount;
							}
	
							$sheet->row($cKey+2, array(
								$staff->first_name.' '.$staff->last_name.', '.$staff->name,
								$customer->first_name.' '.$customer->last_name.', '.$customer->name,
								"$ ".($bill->amount * .10),
								"$ ".$bill->amount,
								"$ ".($bill->amount * 1.5),
								"$ ".$total,
								(isset($bill->deposit[0])) ? "$ ".$bill->deposit[0]->amount : '$ -',
								(isset($bill->deposit[1])) ? "$ ".$bill->deposit[1]->amount : '$ -',
								(isset($bill->deposit[2])) ? "$ ".$bill->deposit[2]->amount : '$ -',
								(isset($bill->deposit[3])) ? "$ ".$bill->deposit[3]->amount : '$ -',
								(isset($bill->deposit[4])) ? "$ ".$bill->deposit[4]->amount : '$ -',
								(isset($bill->deposit[5])) ? "$ ".$bill->deposit[5]->amount : '$ -',
								(isset($bill->deposit[6])) ? "$ ".$bill->deposit[6]->amount : '$ -',
								(isset($bill->deposit[7])) ? "$ ".$bill->deposit[7]->amount : '$ -',
								(isset($bill->deposit[8])) ? "$ ".$bill->deposit[8]->amount : '$ -',
								(isset($bill->deposit[9])) ? "$ ".$bill->deposit[9]->amount : '$ -',
								(isset($bill->deposit[10])) ? "$ ".$bill->deposit[10]->amount : '$ -',
								(isset($bill->deposit[11])) ? "$ ".$bill->deposit[11]->amount : '$ -',
								(isset($bill->deposit[12])) ? "$ ".$bill->deposit[12]->amount : '$ -',
								(isset($bill->deposit[13])) ? "$ ".$bill->deposit[13]->amount : '$ -',
								(isset($bill->deposit[14])) ? "$ ".$bill->deposit[14]->amount : '$ -',
							));
						}
					}
				}
			});
		})->export('xls');
	
	}

/* Reporte Total de Gastos por Semana */
	public function expensesTotal(){

		$year = date('Y');
		$week = date('W');

		$weekStart  = date('Y-m-d', strtotime($year . 'W' . str_pad($week , 2, '0', STR_PAD_LEFT)));
		$weekEnd = date('Y-m-d', strtotime($weekStart.' 6 day'));

		$expenses = Expense::whereBetween('created_at', array($weekStart." 00:00:00", $weekEnd." 00:00:00"))->get();

		$fileName = "Reporte Semanal de Gastos-".$weekStart."-".$weekEnd;

		Excel::create($fileName, function ($excel) use ($expenses) {
			$excel->sheet('staffList', function ($sheet) use ($expenses) {

				/* Titulos */
				$sheet->row(1, array(
					'',
					'Concepto',
					'Cantidad'
				));

				/* Ancho de Columna */
				$sheet->setWidth(array(
					'A' => 15, /* Blank */
					'B' => 15, /* Concepto */
					'C' => 15, /* Cantidad */
					'D' => 15  /* Total */

				));

				/* Atributos Especiales */
				$sheet->freezeFirstRow();

				/* Fuente de Columnas Principales */
				$sheet->cells('A1:D1', function ($cells) {
					$cells->setFontWeight('bold');
					$cells->setAlignment('center');
					$cells->setValignment('middle');
					$cells->setBackground('#000000');
					$cells->setFontColor('#ffffff');
				});

				$totalExpenses = 0;
				$rowAux = 0;
				foreach ($expenses as $key => $expns) {
					$sheet->row($key+2, array(
						'',
						$expns->concept,
						'$ '.$expns->amount
					));
					$totalExpenses = $totalExpenses + $expns->amount;
					$rowAux = $key+2;
				}

				$sheet->cell('B'.($rowAux+1), function ($cell) {
					$cell->setFontWeight('bold');
					$cell->setAlignment('center');
					$cell->setValignment('middle');
					$cell->setBackground('#000000');
					$cell->setFontColor('#ffffff');
				});

				$sheet->row($rowAux+1,array('','Total','$ '.$totalExpenses));
			});
		})->export('xls');




		return "Here's yout report!!!";

	}
	public function pruebaQuery(){
		/*$algo=DB::table('deposits')
			->select(
				'deposits.amount',
				'deposits.payed',
				'bills.amount',
				'bills.completed',
				'customers.first_name',
				'customers.last_name',
				'staffs.first_name',
				'staffs.last_name',
				'staffs.name'
			)
			->join('bills', 'deposits.bill_id', '=', 'bills.id')
			->join('customers', 'bills.customer_id', '=', 'customers.id')
			->join('staffs', 'customers.staff_id', '=', 'staffs.id')
			->get();
			dd($algo);*/
		$algo=DB::select(
			'SELECT 
				d.amount,
				d.payed,
				b.amount as amountb,
				b.completed,
				c.name as namec,
				c.first_name as first_namec,
				c.last_name as last_namec,
				s.name,
				s.first_name,
				s.last_name
			FROM 
				deposits d
			JOIN 
				bills b ON d.bill_id = b.id
			JOIN 
				customers c ON b.customer_id = c.id
			JOIN 
				staffs s ON c.staff_id = s.id
			where	
				b.completed <> 1
			'
			
		);
		$x='';
		$x2='';
		foreach ($algo as $key) {
			if(strcmp($x, $key->name.' '.$key->first_name.' '.$key->last_name)!==0){
				echo '<strong>'.$key->name.' '.$key->first_name.' '.$key->last_name.'</strong><br>';
				echo $key->namec.' '.$key->first_namec.' '.$key->last_namec.'        ';
				echo '<br>';
				$x=$key->name.' '.$key->first_name.' '.$key->last_name;
				$x2=$key->namec.' '.$key->first_namec.' '.$key->last_namec;
			}else{
				if(strcmp($x2, $key->namec.' '.$key->first_namec.' '.$key->last_namec)!==0){
					echo null;
					echo $key->namec.' '.$key->first_namec.' '.$key->last_namec.'        ';
					echo '<br>';
					$x2=$key->namec.' '.$key->first_namec.' '.$key->last_namec;	
				}else{
					echo null;
					echo null;
				}
				
			}
		}
		/*$tablac=array();
		$total=0;
		foreach ($algo as $key) {
			/*foreach ($algo->deposit as $key2) {
				$total+=$algo->deposit;
			}dd($algo);
			if(strcmp($x, $key->name.' '.$key->first_name.' '.$key->last_name)!==0){
				$tablac=[
					$key->name.' '.$key->first_name.' '.$key->last_name,
					$key->namec.' '.$key->first_namec.' '.$key->last_namec,
					($key->amount*.10),
					($key->amount*1.5),
					$total];
					/*(isset($key->deposit[0])) ? $key->deposit[0]->amount : '$ -',
						(isset($key->deposit[1])) ? $key->deposit[1]->amount : '$ -',
						(isset($key->deposit[2])) ? $key->deposit[2]->amount : '$ -',
						(isset($key->deposit[3])) ? $key->deposit[3]->amount : '$ -',
						(isset($key->deposit[4])) ? $key->deposit[4]->amount : '$ -',
						(isset($key->deposit[5])) ? $key->deposit[5]->amount : '$ -',
						(isset($key->deposit[6])) ? $key->deposit[6]->amount : '$ -',
						(isset($key->deposit[7])) ? $key->deposit[7]->amount : '$ -',
						(isset($key->deposit[8])) ? $key->deposit[8]->amount : '$ -',
						(isset($key->deposit[9])) ? $key->deposit[9]->amount : '$ -',
						(isset($key->deposit[10])) ? $key->deposit[10]->amount : '$ -',
						(isset($key->deposit[11])) ? $key->deposit[11]->amount : '$ -',
						(isset($key->deposit[12])) ? $key->deposit[12]->amount : '$ -',
						(isset($key->deposit[13])) ? $key->deposit[13]->amount : '$ -',
						(isset($key->deposit[14])) ? $key->deposit[14]->amount : '$ -',
				
			}else{
				if(strcmp($x2, $key->namec.' '.$key->first_namec.' '.$key->last_namec)!==0){
					$tablac=[
					null,
					$key->namec.' '.$key->first_namec.' '.$key->last_namec,
					($key->amount*.10),
					($key->amount*1.5),
					$total];
					/*(isset($bill->deposit[0])) ? $bill->deposit[0]->amount : '$ -',
						(isset($key->deposit[1])) ? $key->deposit[1]->amount : '$ -',
						(isset($key->deposit[2])) ? $key->deposit[2]->amount : '$ -',
						(isset($key->deposit[3])) ? $key->deposit[3]->amount : '$ -',
						(isset($key->deposit[4])) ? $key->deposit[4]->amount : '$ -',
						(isset($key->deposit[5])) ? $key->deposit[5]->amount : '$ -',
						(isset($key->deposit[6])) ? $key->deposit[6]->amount : '$ -',
						(isset($key->deposit[7])) ? $key->deposit[7]->amount : '$ -',
						(isset($key->deposit[8])) ? $key->deposit[8]->amount : '$ -',
						(isset($key->deposit[9])) ? $key->deposit[9]->amount : '$ -',
						(isset($key->deposit[10])) ? $key->deposit[10]->amount : '$ -',
						(isset($key->deposit[11])) ? $key->deposit[11]->amount : '$ -',
						(isset($key->deposit[12])) ? $key->deposit[12]->amount : '$ -',
						(isset($key->deposit[13])) ? $key->deposit[13]->amount : '$ -',
						(isset($key->deposit[14])) ? $key->deposit[14]->amount : '$ -',
					
				}else{
					$tablac=[null,
					null,
					($key->amount*.10),
					($key->amount*1.5),
					$total];
					/*(isset($bill->deposit[0])) ? $bill->deposit[0]->amount : '$ -',
						(isset($key->deposit[1])) ? $key->deposit[1]->amount : '$ -',
						(isset($key->deposit[2])) ? $key->deposit[2]->amount : '$ -',
						(isset($key->deposit[3])) ? $key->deposit[3]->amount : '$ -',
						(isset($key->deposit[4])) ? $key->deposit[4]->amount : '$ -',
						(isset($key->deposit[5])) ? $key->deposit[5]->amount : '$ -',
						(isset($key->deposit[6])) ? $key->deposit[6]->amount : '$ -',
						(isset($key->deposit[7])) ? $key->deposit[7]->amount : '$ -',
						(isset($key->deposit[8])) ? $key->deposit[8]->amount : '$ -',
						(isset($key->deposit[9])) ? $key->deposit[9]->amount : '$ -',
						(isset($key->deposit[10])) ? $key->deposit[10]->amount : '$ -',
						(isset($key->deposit[11])) ? $key->deposit[11]->amount : '$ -',
						(isset($key->deposit[12])) ? $key->deposit[12]->amount : '$ -',
						(isset($key->deposit[13])) ? $key->deposit[13]->amount : '$ -',
						(isset($key->deposit[14])) ? $key->deposit[14]->amount : '$ -',
					
				}
			}
		}
		dd($tablac);*/
		/*
		Excel::create('documento', function($excel) use ($algo){
			$excel->sheet('cuentas', function($sheet){
				$sheet->fromArray($algo);
			});
		})->export('xls');*/
		
	}

}
 /**
 * 
 */

