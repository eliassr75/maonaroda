<?php

if ($page && $_SESSION["logged"]){

    ?>
    
    <!-- Page Header Close -->
    <div class="row">
        <!--<div class="col">
            <div class="card custom-card">
                <div class="card-body">-->
    
    <?php

    switch ($page) {
    
        case 'my-campaigns':

            $campaigns = get_all('campaign', true, 'entity_id', $_SESSION['entity_id'], 'entity', 'id', 'entity_id');

            ?>

            <div class="col-12 mt-4">
                <div class="row products-main">
                    <div class="col-12 col-md-6 col-sm-6 col-lg-6 col-xxl-3 product-each">

                        <?php foreach ($campaigns as $campaign) {

                            $campaign['admin'] = true;
                            $campaign['created'] = date('d/m/Y H:i', strtotime($campaign['created']));
                            $campaign['updated'] = date('d/m/Y H:i', strtotime($campaign['updated']));
                            $campaign['value'] = formatarDinheiro($campaign['value']);

                            ?>
                            <div class="card custom-card item-card border border-5 <?= $campaign['active'] ? "border-primary" : "border-danger" ?>">
                                <div class="product-grid6 card-body p-0">
                                    <div class="product-image6">
                                        <a data-bs-toggle="modal" onclick="body_modal('edit-campaign', { id: <?=$campaign['id']?>})" data-bs-target="#modal" class="img-container">
                                            <img class="img-fluid img-container w-100 p-1 rounded-2" src="<?=$campaign['logo'] ?? '/assets/images/logo_lg_default.png'?>" alt="img">
                                        </a>
                                        <div class="icon-container">
                                            <ul class="icons">
                                                <li>
                                                    <a aria-label="anchor" data-bs-toggle="modal" onclick="body_modal('view-campaign', <?= htmlspecialchars(json_encode($campaign, true)) ?>)" data-bs-target="#modal" data-tip="Ver Detalhes">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-inner-icn" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M12,8c-2.2091675,0-4,1.7908325-4,4s1.7908325,4,4,4c2.208252-0.0021973,3.9978027-1.791748,4-4C16,9.7908325,14.2091675,8,12,8z M12,15c-1.6568604,0-3-1.3431396-3-3s1.3431396-3,3-3c1.6561279,0.0018311,2.9981689,1.3438721,3,3C15,13.6568604,13.6568604,15,12,15z M21.960022,11.8046875C19.9189453,6.9902344,16.1025391,4,12,4s-7.9189453,2.9902344-9.960022,7.8046875c-0.0537109,0.1246948-0.0537109,0.2659302,0,0.390625C4.0810547,17.0097656,7.8974609,20,12,20s7.9190063-2.9902344,9.960022-7.8046875C22.0137329,12.0706177,22.0137329,11.9293823,21.960022,11.8046875z M12,19c-3.6396484,0-7.0556641-2.6767578-8.9550781-7C4.9443359,7.6767578,8.3603516,5,12,5s7.0556641,2.6767578,8.9550781,7C19.0556641,16.3232422,15.6396484,19,12,19z"></path></svg>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a aria-label="anchor" data-bs-toggle="modal" onclick="body_modal('edit-campaign', { id: <?=$campaign['id']?>})" data-bs-target="#modal" data-tip="Editar Campanha">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                                        </svg>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-content w-100 p-3">
                                        <div class="mb-2">
                                            <h6 class="mb-1 fs-16 text-normal fw-normal"><a href="javascript:void(0);"><?=$campaign['name']?></a></h6>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between">
                                            <p class="mb-0">
                                                <span class="fs-15 me-2"> <i class="bi bi-binoculars"></i> <?=$campaign['views']?></span>
                                                <span class="fs-15"> <i class="bi bi-globe2"></i> <?=$campaign['local']?></span>
                                            </p>
                                            <?=$campaign['value'] ? '<p class="mb-0 fs-13 text-primary">' . $campaign['value'] . '</p>' : ''?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            
            <?php
            
            break;
            
        default:  // page not
            break;
    
    }

    ?>
    
                <!--</div>
            </div>
        </div>-->
    </div>
    
    <?php
}else{

$campaigns = get_all('campaign', true, 'active', 'true', 'entity', 'id', 'entity_id');
?>

    <div class="ms-auto col-md-8 mt-4 me-auto">
        <div class="card custom-card item-card">
            <div class="card-body">
                <div class="row">
                    <div class="ms-auto col-md-12 text-center align-items-center me-auto">
                        <form name="get-city" method="get">
                        <select class="form-control select2 mt-2" id="select-local" name="c">
                            <option></option>
                            <?php
                                $citys = [];
                                foreach ($campaigns as $campaign) {
                                    if (!in_array($campaign['local'], $citys)) {
                                        $citys[] = $campaign['local'] . " ({$campaign['total']}) ";
                                    }
                                }
                            ?>
                            <?php foreach ($citys as $city) { ?>
                                <option value="<?= htmlspecialchars($city) ?>" <?php if (isset($_GET["c"]) && $_GET["c"] == $city) { echo "selected"; } ?> ><?= htmlspecialchars($city) ?></option>
                            <?php } ?>
                        </select>
                        </form>
                        <button class="btn btn-info-gradient label-btn mt-3 col-md-4" data-bs-toggle="modal" onclick="body_modal('campaign', { form: 'new-campaign'})" data-bs-target="#modal">
                            <i class="label-btn-icon bi bi-plus-lg"></i> Publicar Campanha
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 mt-4">
        <?php if($campaigns) { ?>
        <div class="row products-main">
            <div class="col-12 col-md-6 col-sm-6 col-lg-6 col-xxl-3 product-each">

                <?php foreach ($campaigns as $campaign) {

                    $campaign['created'] = date('d/m/Y H:i', strtotime($campaign['created']));
                    $campaign['updated'] = date('d/m/Y H:i', strtotime($campaign['updated']));
                    $campaign['value'] = formatarDinheiro($campaign['value']);

                    ?>
                <div class="card custom-card item-card">
                    <div class="product-grid6 card-body p-0">
                        <div class="product-image6">
                            <a data-bs-toggle="modal" onclick="body_modal('view-campaign', <?= htmlspecialchars(json_encode($campaign, true)) ?>)" data-bs-target="#modal" class="img-container">
                                <img class="img-fluid img-container w-100 p-3" src="<?=$campaign['logo'] ?? '/assets/images/logo_lg_default.png'?>" alt="img">
                            </a>
                            <div class="icon-container">
                                <ul class="icons">
                                    <li>
                                        <a aria-label="anchor" data-bs-toggle="modal" onclick="body_modal('view-campaign', <?= htmlspecialchars(json_encode($campaign, true)) ?>)" data-bs-target="#modal" data-tip="Ver Detalhes">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-inner-icn" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M12,8c-2.2091675,0-4,1.7908325-4,4s1.7908325,4,4,4c2.208252-0.0021973,3.9978027-1.791748,4-4C16,9.7908325,14.2091675,8,12,8z M12,15c-1.6568604,0-3-1.3431396-3-3s1.3431396-3,3-3c1.6561279,0.0018311,2.9981689,1.3438721,3,3C15,13.6568604,13.6568604,15,12,15z M21.960022,11.8046875C19.9189453,6.9902344,16.1025391,4,12,4s-7.9189453,2.9902344-9.960022,7.8046875c-0.0537109,0.1246948-0.0537109,0.2659302,0,0.390625C4.0810547,17.0097656,7.8974609,20,12,20s7.9190063-2.9902344,9.960022-7.8046875C22.0137329,12.0706177,22.0137329,11.9293823,21.960022,11.8046875z M12,19c-3.6396484,0-7.0556641-2.6767578-8.9550781-7C4.9443359,7.6767578,8.3603516,5,12,5s7.0556641,2.6767578,8.9550781,7C19.0556641,16.3232422,15.6396484,19,12,19z"></path></svg>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="product-content w-100 p-3">
                            <div class="mb-2">
                                <h6 class="mb-1 fs-16 text-normal fw-normal"><a href="javascript:void(0);"><?=$campaign['name']?></a></h6>
                            </div>

                            <div class="d-flex align-items-center justify-content-between">
                                <p class="mb-0">
                                    <span class="fs-15"> <i class="bi bi-globe2"></i> <?=$campaign['local']?></span>
                                </p>
                                <?=$campaign['value'] ? '<p class="mb-0 fs-13 text-primary">' . $campaign['value'] . '</p>' : ''?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php }else { ?>
            <div class="col-md-8 ms-auto alert custom-alert1 alert-warning me-auto my-md-auto">
                <div class="text-center px-5 pb-0">
                    <svg class="custom-alert-icon svg-warning" xmlns="http://www.w3.org/2000/svg" height="1.5rem" viewBox="0 0 24 24" width="1.5rem" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    <h5>Ops!</h5>
                    <p class="fs-6">Parece que ainda não existem campanhas cadastradas.</p>
                </div>
            </div>
        <?php } ?>
    </div>

<?php

}


?>