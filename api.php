<?php
	error_reporting(E_ALL ^ E_DEPRECATED);
	require_once('User.php');
	require_once('Brand.php');
	require_once('DataBase.php');
	session_start();
	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
	$html = '';
	if(empty($action)) {		
		echo "action is required.";
	} else {
		switch ($action) {
			
		  	case "getMobilesforhomepage":
		  	$html = '';
			$mobiles = NULL;
			$DB = new DataBase();
			$mobiles = $DB->getMobilesAsend();
			if($mobiles == 0 || $mobiles == "error" || sizeof($mobiles)==0){
				$mobiles = NULL;
			}
			if($mobiles != NULL){
				foreach ($mobiles as $mobile) {
					if($DB->getBrandById($mobile->BrandID)) {
					$html .= "	
						<div class='col-lg-3 col-md-3 col-sm-6 col-xs-12'>
							<div class='my-list'>
								<img src='$mobile->ImageUrl' alt='dsadas' />
								<h3>" . $DB->getBrandById($mobile->BrandID)->Name . " $mobile->Model</h3>
								<span>Platform:</span>
								<span class='pull-right'>$mobile->Platform</span>
								<div class='offer'>$mobile->Price EGP</div>
								<div class='detail'>
									<p>Click 'Details' for more info</p>
									<img src='$mobile->ImageUrl' alt='dsadas' />
									<form action='Details.php' method='post'>
										<input type='hidden' value='$mobile->ID' id='id' name='id'>
										<input type='submit' value='Details' class='btn btn-info'>
									</form>
								</div>
							</div>
						</div>";
					}
				}
			}
			echo $html;
			break;
			
			case "getmobilelistafterlogin": 
				$html = '';
				$UserID="";
				$user = null;
				$mobiles = NULL;
				$DB = NULL;
				if(isset($_SESSION['UserID']) && !empty($_SESSION['UserID'])) {
					$UserID=$_SESSION['UserID'];
					$DB = new DataBase();
					$user = $DB->getUserById($UserID);
					$DB = new DataBase();
					$mobiles = $DB->getMobilesAsend();
					if($mobiles == 0 || $mobiles == "error" || sizeof($mobiles)==0){
						$mobiles = NULL;
					}
				}else{
					header('Location: Login.php');
				}
				if($mobiles != NULL){
						foreach ($mobiles as $mobile){
							$html .= "
				
									<div class='col-lg-3 col-md-3 col-sm-6 col-xs-12'>
										<div class='my-list'>
											<img src='$mobile->ImageUrl' alt='dsadas' />
											<h3>".$DB->getBrandById($mobile->BrandID)->Name." "."$mobile->Model</h3>
											<span>Platform:</span>
											<span class='pull-right'>$mobile->Platform</span>
											<div class='offer'>$mobile->Price EGP</div>
											<div class='detail'>
												<p>Click 'Details' for more info</p>
												<img src='$mobile->ImageUrl' alt='dsadas' />
												<form action='Details.php' method='post'>
													<input type='hidden' value='$mobile->ID' id='id' name='id'>
													<input type='submit' value='Details' class='btn btn-info'>
												</form>
											</div>
										</div>
									</div>
								";
							}
						}
			echo $html;
			break;
			
			  case "listforadminpage":
			  		$html = '';
					$AdminID="";
					$admin = NULL;
					$mobiles = NULL;
					$brands = NULL;
					
					if(isset($_SESSION['AdminID']) && !empty($_SESSION['AdminID'])) {
						$UserID=$_SESSION['AdminID'];
						$DB = new DataBase();
						$admin = $DB->getAdminById($UserID);
						$mobiles = $DB->getMobilesAsend();
						$brands = $DB->getBrands();
						if($brands == 0 || $brands == "error" || sizeof($brands) == 0){
							$brands = NULL;
						}
						if($mobiles == 0 || $mobiles == "error" || sizeof($mobiles)==0){
							$mobiles = NULL;
						}
					}else{
						header('Location: LoginAsAdmin.php');
					}
					if($mobiles != NULL){
						foreach ($mobiles as $mobile){
							if($DB->getBrandById($mobile->BrandID)) {
							$html .="
									<form action='EditMobile.php' id='m_form' method='post'>
										<img src='".$mobile->ImageUrl."' alt='dsadas' width='180' height='180' style='margin:10px;'/>
										<p style='float: left;'>".$mobile->Price." : ".$DB->getBrandById($mobile->BrandID)->Name." | ".$mobile->Model." </p>
										<div style='margin: 30px;'>
											<input type='hidden' value='".$mobile->ID."' name='ID' id='ID' >
											<input type='submit' value='Edit' class='btn btn-info'>
											<input type='button' value='Delete' id='delete_mobile' onclick='deleteMobile(".$mobile->ID.")' class='btn btn-info'> 
										</div>
									</form>
								";
							}
							}
					}
				echo $html;
				break;
				
				case "editmobile":	

				$mobile = null;
				$DB = new DataBase();

				if(	isset($_SESSION['AdminID']) && !empty($_SESSION['AdminID'])	&& 
					!empty($_POST['ID']) && isset($_POST['ID']) && 
					isset($_FILES['image']) && !empty($_FILES['image']) && 
					isset($_POST['features']) && !empty($_POST['features'])
						){
					
					$id = $_POST['ID'];
					$model = $_POST['model'];
					$brand = $_POST['brand'];
					$price = $_POST['price'];
					$date = $_POST['date'];
					$discount = $_POST['discount'];
					$tmp_name = $_FILES['image']['tmp_name'];
					$camera = $_POST['camera'];
					$memory = $_POST['memory'];
					$network = $_POST['network'];
					$platform = $_POST['platform'];
					$cpu = $_POST['cpu'];
					$features = $_POST['features'];
					//$mobile = new Mobile($id, $model, $brand, $price, $date, $discount, $camera, $memory, $network, $platform, $cpu, $features);
					
					$location = "MobilesPictures/";
					
					if(move_uploaded_file($tmp_name,$location.$brand.$model.".jpg")){
						$ImageURL =$location.$brand.$model.".jpg";
						$mobile = new Mobile($id, $model, $brand, $price, $date, $discount, $ImageURL, $camera, $memory, $network, $platform, $cpu, $features);	
						$DB->editMobile($mobile);	
						$response = array('status' => 'success');									
					} else {
						$response = array('status' => 'fail');	
					}					
					
					echo json_encode($response);				
				}
				elseif((isset($_SESSION['AdminID']) && !empty($_SESSION['AdminID'])) && (isset($_POST['ID']) && !empty($_POST['ID']))) {
					$DB = new DataBase();
					$id = $_POST['ID'];
					$mobile = $DB->getMobileById($id);
				}else{
					header('Location: AdminPage.php');
				}				
				break;
				
				case "deletemobile":
					if((isset($_SESSION['AdminID']) && !empty($_SESSION['AdminID'])) && (isset($_POST['ID']) && !empty($_POST['ID']))) {
						$DB = new DataBase();
						$id = $_POST['ID'];
						$result = $DB->deleteMobileById($id);
						if($result == 'valid') {
							echo json_encode(array('status' => 'success'));
						} else {
							echo json_encode(array('status' => 'fail'));
						}
					}else{
						header('Location: AdminPage.php');
					}		
				break;
		  default: 
			echo "Your favorite color is neither red, blue, nor green!";
		}
	}
	
?>



