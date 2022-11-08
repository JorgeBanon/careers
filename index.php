
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Positions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main id="primary" class="site-main  container-fluid px-xxl-5">
<article>
    <div class="entry-content mt-md-5 col-12">
    <!-- Greenhouse API -->
    <?php 
        $json = file_get_contents('https://boards-api.greenhouse.io/v1/boards/eshopworld/offices');
        $obj = json_decode($json);
        
        
        $jobsdepartment  = array();
        $miejsce = array();
        $dzial = array();
        $ogloszenia = array();

        foreach ($obj->offices as $item) {
            if(in_array($item->name, $miejsce) == false){
                $miejsce[] = $item->name;
            }
            foreach ($item->departments as $depart){
                if(in_array($depart->name, $dzial) == false) {
                    $dzial[] = $depart->name;
                }
                if($depart->jobs){
                    foreach ($depart->jobs as $job) {
                        $job->location = $item->name;
                        $job->departs = $depart->name;
                        $ogloszenia[] = $job; 
                    }
                }
            }
        }
    ?>
    <div class="careers__open-positions <?php echo (isset($_GET['gh_jid'])) ? "mt-n3" : ""; ?>">
    <?php 
    $departments = array();
    $locations = array();
    foreach ($ogloszenia as $offer){
        if( empty($departments[$offer->departs]) || !in_array($offer->location, $departments[$offer->departs]) )
            $departments[$offer->departs][] = $offer->location;

        if ( !in_array($offer->location, $locations) )
            $locations[] = $offer->location;
		$i++;
	}
    if(!isset($_GET['gh_jid'])) : ?>
    <div class="col-lg-8 mx-auto">
        <div class="row g-3">
        <h2>ESW Open Positions</h2>
        </div>
    </div>
	<div class="col-lg-6 mx-auto mb-3 mb-md-4">
        <form autocomplete="off"  class="row g-3">
            <div class="col-md-6">
                <select class="job-select-location form-select ns" id="locselect">
                    <option value="location_all" name="location">All Locations</option>
                    <?php foreach ($locations as $location) : ?>
                        <option 
                            value="location_<?php echo preg_replace('/\s+/', '', str_replace("&", "and", $location)); ?>"
                            name="location">
                                <?php echo $location; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>	
            <div class="col-md-6">
                <select class="job-select-depart form-select ns" >
                    <option value="depart_all" data-location="all" name="location">All Departments</option>
                    <?php foreach ($departments as $department => $depLocations) :
                        $inlocation = '';
                        foreach ($depLocations as $depLocation) :
                            $inlocation .= 'location_'.preg_replace('/\s+/', '', str_replace("&", "and", $depLocation)).' ';
                        endforeach; ?>
                        <option 
                            value="depart_<?php echo preg_replace('/\s+/', '', str_replace("&", "and", $department)); ?>"
                            name="depart"
                            data-location="<?php echo $inlocation; ?>"
                            >
                            <?php echo $department; ?>
                        </option>
                        
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
    <div class="col-lg-8 mx-auto">
        <div class="row g-3">
            <?php foreach ($ogloszenia as $item) : ?>
                <div class="careers__open-positions__position col-md-6 item-to-filter location_all location_<?php echo preg_replace('/\s+/', '', str_replace("&", "and", $item->location)); ?> depart_all depart_<?php echo preg_replace('/\s+/', '', str_replace("&", "and", $item->departs)); ?>"" data-location="" data-departments="">
                    <div class="inner bg-light ps-3 pe-2 py-2">
                        <h3 class="text-extrabold border-bottom--short  mt-1"><?php echo $item->title; ?></h3>	
                        <p><?php echo $item->location; ?></p>
                        <p class="text-end small">
                            <a class="btn-esw" href="?gh_jid=<?php echo $item->id; ?>">Read More</a>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="rg-search-nope d-none">
                <div class="col-md-12">
                    <h2>No job offers for this filter</h2>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>							 								  
    </div> 
    <!-- careers__open-positions -->
    </div><!-- .entry-content -->

<!-- Recruitee Open Postions -->
    <div class="col-lg-8 mx-auto" id="scalefast"> 
        <div class="row g-3">
            <h2>Scalefast Open Positions</h2>
        </div>
    </div>
    <?php
        $curl = curl_init();
        curl_setopt_array($curl, [
        CURLOPT_URL => "https://scalefast.recruitee.com/api/offers/?department=Operational%2520Software",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "accept: application/json"
        ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
		echo gettype($response);
            $objeto = json_decode($response);
		echo gettype($objeto);
            $offersList = $objeto['offers'];
		echo gettype($offersList);

            foreach($offersList as $offer) {
                echo "Oferta: " . $offers->department;
            }
        

        }
    ?>																		   
</article>
</main><!-- #main -->

    </body>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>
</html>
