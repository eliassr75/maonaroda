<?php
include('config/function.php');

if ($page && $_SESSION["logged"]){

    ?>
    
    <!-- Page Header Close -->
    <div class="row">
        <div class="col">
            <div class="card custom-card">
                <div class="card-body">
    
    <?php

    switch ($page) {
    
        case 'my-collabs':
            
            $collabs = get_collabs();
            $table_id = "table-my-collabs";
            
            ?>
            
            <div class="table-responsive">
                <table id="<?=$table_id?>" class="table table-responsive table-hover w-100">
                    <thead>
                        <tr>
                            <th scope="col">Collab</th>
                            <th scope="col">Categorias</th>
                            <th scope="col">Criada/Atualizada</th>
                            <th scope="col">Equipe</th>
                            <th scope="col">Status</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($collabs as $collab) { ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2 avatar-rounded bg-success">
                                        <i class="bi bi-wallet2"></i>
                                    </div>
                                    <div>
                                        <div class="lh-1">
                                            <span><?=truncateString($collab['name'], 15)?></span>
                                        </div>
                                        <div class="lh-1">
                                            <span class="fs-11 text-muted">
                                                <?= ($collab['type'] ? '<i class="bi bi-incognito"></i> Privada' : '<i class="bi bi-people"></i>Pública') ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php foreach (json_decode($collab["category"]) as $categoria) { ?>
                                <span class="badge bg-primary-transparent"><?=$categoria?></span>
                                <?php } ?>
                            </td>
                            <td>
                                <?=date('d/m/Y H:i', strtotime($collab['created']))?> /  
                                <?=($collab['updated'] ? date('d/m/Y H:i', strtotime($collab['updated'])) : "--")?> 
                            </td>
                            <td>
                                <div class="avatar-list-stacked">
                                
                                    <?php 
                                    $users = get_contributors($collab);
                                    if(is_array($users) && count($users)) {
                                        foreach($users as $user) { ?>
                                        <span class="avatar avatar-sm avatar-rounded bg-<?=($collab["created_by"] == $user["id"] ? "info" : "primary")?>" data-bs-toggle="tooltip" data-bs-title="<?=$user["name"]?> <?=($collab["created_by"] == $user["id"] ? " (Admin)" : "")?>">
                                            <i class="bi bi-person"></i>
                                        </span>
                                        <?php }
                                        
                                    }else{
                                        print($users);
                                    } ?>

                                </div>
                            </td>
                            <td class="text-center">
                                <?php if($collab["value_total"]){ 
                                    
                                    $percent = number_format((float)(100 - ($collab["value_total"] - $collab["value_deposit"]) * 100 / $collab["value_total"]), 2, '.', ',');
                                
                                ?>
                                <div data-bs-toggle="tooltip" data-bs-title="<?=$percent?>%">
                                <div class="progress progress-xs">
                                    
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?=$percent?>%" aria-valuenow="<?=$percent?>"     aria-valuemin="0" aria-valuemax="100" >
                                    </div>
                                    
                                </div>
                                <span class="fs-11 text-muted">
                                    <?=formatarDinheiro($collab["value_deposit"])?> de <?=formatarDinheiro($collab["value_total"])?>
                                </span>
                                </div>
                                <?php } else{ 
                                    echo formatarDinheiro($collab["value_deposit"]);
                                } ?>
                            </td>
                            <td class="text-center">
                                <div class="mb-md-0 mb-2">
                                    <a data-bs-toggle="modal" href="#modal" onclick="body_modal('edit-collab', {id: <?=$collab['id']?>})">
                                        <button class="btn btn-icon btn-warning-transparent rounded-pill btn-wave" data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="Editar Collab">
                                            <i class="ri-edit-box-line"></i>
                                        </button>
                                    </a>
                                    <?php if ($collab["active"]){ ?>
                                    <button class="btn btn-icon btn-primary-transparent rounded-pill btn-wave" data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="Inativar">
                                        <i class="bi bi-power"></i>
                                    </button>
                                    <?php } else { ?>
                                        <button class="btn btn-icon btn-danger-transparent rounded-pill btn-wave" data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="Ativar">
                                            <i class="bi bi-power"></i>
                                        </button>
                                    <?php } ?>
                                    <?php if ($collab["have_amounts"]){ ?>
                                    <button class="btn btn-icon btn-orange-light rounded-pill btn-wave" data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="Existem depositos vinculados a esta collab por tanto, não é possível apagá-la, apenas arquivar">
                                        <i class="bi bi-folder-symlink"></i>
                                    </button>
                                    <?php } else { ?>
                                        <button class="btn btn-icon btn-secondary-transparent rounded-pill btn-wave" data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="Apagar Collab">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    <?php } ?>
                                    
                                    <button class="btn btn-icon btn-success-transparent rounded-pill btn-wave" data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="Histórico de Depósitos">
                                        <i class="ri-chat-history-line"></i>
                                    </button>
                                    <button class="btn btn-icon btn-light rounded-pill btn-wave me-5" data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="a">
                                        <i class="ri-restart-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            
            <?php
            
            break;
            
        default:  // page not
            break;
    
    }

    ?>
    
                </div>
            </div>
        </div>
    </div>
    
    <?php
}else{

$values = counters_dashboard();
?>

    <div class="col-12 mt-4">
        <div class="card custom-card item-card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-0 my-1">
                            <select class="form-control select2">
                                <option>
                                -- Sort By --
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 text-center d-none d-xxl-block">
                        <select class="form-control select2">
                            <option>
                                -- Sort By --
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4 text-center d-none d-xxl-block">
                        <select class="form-control select2">
                            <option>
                                -- Sort By --
                            </option>
                        </select>
                    </div>
                    <div class="col-xl">
                        <div class="input-group my-1">
                            <input type="text" class="form-control" placeholder="Search ...">
                            <button type="button" class="input-group-text btn btn-primary text-fixed-white">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row products-main">
            <div class="col-12 col-md-6 col-sm-6 col-lg-6 col-xxl-3 product-each">
                <div class="card custom-card item-card">
                    <div class="product-grid6 card-body p-0">
                        <div class="product-image6">
                            <a href="product-details.html" class="img-container">
                                <img class="img-fluid w-100" src="../assets/images/pngs/4.png" alt="img">
                            </a>
                            <div class="icon-container">
                                <ul class="icons">
                                    <li>
                                        <a aria-label="anchor" href="product-details.html" data-tip="Quick View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-inner-icn" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M12,8c-2.2091675,0-4,1.7908325-4,4s1.7908325,4,4,4c2.208252-0.0021973,3.9978027-1.791748,4-4C16,9.7908325,14.2091675,8,12,8z M12,15c-1.6568604,0-3-1.3431396-3-3s1.3431396-3,3-3c1.6561279,0.0018311,2.9981689,1.3438721,3,3C15,13.6568604,13.6568604,15,12,15z M21.960022,11.8046875C19.9189453,6.9902344,16.1025391,4,12,4s-7.9189453,2.9902344-9.960022,7.8046875c-0.0537109,0.1246948-0.0537109,0.2659302,0,0.390625C4.0810547,17.0097656,7.8974609,20,12,20s7.9190063-2.9902344,9.960022-7.8046875C22.0137329,12.0706177,22.0137329,11.9293823,21.960022,11.8046875z M12,19c-3.6396484,0-7.0556641-2.6767578-8.9550781-7C4.9443359,7.6767578,8.3603516,5,12,5s7.0556641,2.6767578,8.9550781,7C19.0556641,16.3232422,15.6396484,19,12,19z"></path></svg>
                                        </a>
                                    </li>
                                    <li>
                                        <a aria-label="anchor" href="wishlist.html" data-tip="Add to Wishlist">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-inner-icn" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M20.2246094,4.5693359C17.9908447,2.3260498,14.4058838,2.1826172,12,4.2402344C9.5941162,2.1826172,6.0091553,2.3260498,3.7753906,4.5693359c-2.3632812,2.3798218-2.3632812,6.2207642,0,8.6005859l7.8701172,7.8955078c0.09375,0.0944824,0.2213745,0.147583,0.3544922,0.1474609c0.1331177,0.0001221,0.2607422-0.0529785,0.3544922-0.1474609l7.8701172-7.8955078C22.5878906,10.7901001,22.5878906,6.9491577,20.2246094,4.5693359z M19.515625,12.4649048L12,20.0048828l-7.515625-7.5400391c-1.9755859-1.9897461-1.9755859-5.2007446,0-7.1904297C5.430603,4.319458,6.7201538,3.7837524,8.0644531,3.7871094c1.3446655-0.00354,2.6345825,0.5322266,3.5810547,1.4873657c0.1983643,0.1894531,0.5106201,0.1894531,0.7089844,0c0.0047607-0.0047607,0.0094604-0.0095215,0.0142212-0.0142822c1.9775391-1.9696045,5.1773071-1.9632568,7.1469116,0.0142822C21.4912109,7.2641602,21.4912109,10.4751587,19.515625,12.4649048z"></path></svg>
                                        </a>
                                    </li>
                                    <li>
                                        <a aria-label="anchor" href="cart.html" data-tip="Add to Cart">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-inner-icn" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M9,18c-1.1045532,0-2,0.8954468-2,2s0.8954468,2,2,2c1.1040039-0.0014038,1.9985962-0.8959961,2-2C11,18.8954468,10.1045532,18,9,18z M9,21c-0.5523071,0-1-0.4476929-1-1s0.4476929-1,1-1c0.552124,0.0003662,0.9996338,0.447876,1,1C10,20.5523071,9.5523071,21,9,21z M17,18c-1.1045532,0-2,0.8954468-2,2s0.8954468,2,2,2c1.1040039-0.0014038,1.9985962-0.8959961,2-2C19,18.8954468,18.1045532,18,17,18z M17,21c-0.5523071,0-1-0.4476929-1-1s0.4476929-1,1-1c0.552124,0.0003662,0.9996338,0.447876,1,1C18,20.5523071,17.5523071,21,17,21z M19.4985352,12.0502319l1.9848633-7.4213257c0.0111694-0.0419312,0.0167847-0.085083,0.0167847-0.128479C21.5002441,4.2241211,21.2763062,4.000061,21,4H5.9198608L5.4835205,2.371582C5.4249268,2.1530151,5.2268677,2.0009766,5.0005493,2.0009766H3.5048218C3.5031128,2.0009766,3.501709,2,3.5,2C3.223877,2,3,2.223877,3,2.5S3.223877,3,3.5,3v0.0009766L4.6162109,3l2.579834,9.6288452C7.2546387,12.8477783,7.453064,13,7.6796875,13H11h6.8603516H19c0.8284302,0,1.5,0.6715698,1.5,1.5S19.8284302,16,19,16H5c-0.276123,0-0.5,0.223877-0.5,0.5S4.723877,17,5,17h14c1.3807373,0,2.5-1.1192627,2.5-2.5C21.5,13.2900391,20.6403809,12.2813721,19.4985352,12.0502319z M18.4761963,12h-0.6158447H11H8.0634766L6.1878052,5h14.1608276L18.4761963,12z"></path></svg>
                                        </a>
                                    </li>
                                    <li>
                                        <a aria-label="anchor" href="javascript:void(0);" data-tip="notify me">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-inner-icn" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M18,14.1V10c0-3.1-2.4-5.7-5.5-6V2.5C12.5,2.2,12.3,2,12,2s-0.5,0.2-0.5,0.5V4C8.4,4.3,6,6.9,6,10v4.1c-1.1,0.2-2,1.2-2,2.4v2C4,18.8,4.2,19,4.5,19h3.7c0.5,1.7,2,3,3.8,3c1.8,0,3.4-1.3,3.8-3h3.7c0.3,0,0.5-0.2,0.5-0.5v-2C20,15.3,19.1,14.3,18,14.1z M7,10c0-2.8,2.2-5,5-5s5,2.2,5,5v4H7V10z M13,20.8c-1.6,0.5-3.3-0.3-3.8-1.8h5.6C14.5,19.9,13.8,20.5,13,20.8z M19,18H5v-1.5C5,15.7,5.7,15,6.5,15h11c0.8,0,1.5,0.7,1.5,1.5V18z"></path></svg>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="product-content w-100 p-3">
                            <div class="mb-2">
                                <h6 class="mb-1 fs-16 text-normal fw-normal"><a href="javascript:void(0);">Multi Colour Flower Pot Set</a></h6>
                                <p class="mb-0 text-muted text-start">brand name</p>
                            </div>
                            <p class="mb-2 text-warning">
                                                <span class="align-middle">
                                                    <i class="ri-star-fill"></i>
                                                    <i class="ri-star-fill"></i>
                                                    <i class="ri-star-fill"></i>
                                                    <i class="ri-star-fill"></i>
                                                    <i class="ri-star-half-line"></i>
                                                </span>
                                <span class="ms-2">(4.5)</span>
                            </p>
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="mb-0">
                                    <span class="fs-20">$200</span>
                                    <span class="text-decoration-line-through text-muted fs-13 ms-1">$320</span>
                                </p>
                                <p class="mb-0 fs-13 text-secondary">item unavailable</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

<?php

}


?>