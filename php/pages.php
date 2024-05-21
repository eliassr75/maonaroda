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
print_r($values);
?>

<div class="row">
    <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                        <p class="mb-0 fs-15">Collabs Públicas</p><span class="fs-5"><?=$values["public_collabs"]["total"]?></span>
                        <span class="fs-12 text-primary ms-1"><i class="ti ti-trending-<?=$values["public_collabs"]["icon"]?> mx-1"></i><?=$values["public_collabs"]["percent"]?>%</span>
                    </div>
                    <div class=" ms-3">
                        <span class="avatar avatar-md br-5 bg-primary-transparent text-primary"><i class="fe fe-user fs-18"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                        <p class="mb-0 fs-15">Collabs Privadas</p><span class="fs-5"><?=$values["private_collabs"]["total"]?></span>
                        <span class="fs-12 text-danger ms-1"><i class="ti ti-trending-<?=$values["private_collabs"]["icon"]?> mx-1"></i><?=$values["private_collabs"]["percent"]?>%</span>
                    </div>
                    <div class=" ms-3">
                        <span class="avatar avatar-md br-5 bg-danger-transparent text-danger"><i class="fe fe-package fs-18"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                        <p class="mb-0 fs-15">Usuários da plataforma</p><span class="fs-5"><?=$values["users"]["total"]?></span>
                        <span class="fs-12 text-info ms-1"><i class="ti ti-trending-<?=$values["users"]["icon"]?> mx-1"></i><?=$values["users"]["percent"]?>%</span>
                    </div>
                    <div class=" ms-3">
                        <span class="avatar avatar-md br-5 bg-info-transparent text-info"><i class="fe fe-user-plus fs-18"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                        <p class="mb-0 fs-15">Total de Transações</p><span class="fs-5"><?=$values["payments"]["total"]?></span>
                        <span class="fs-12 text-warning ms-1"><i class="ti ti-trending-<?=$values["payments"]["icon"]?> mx-1"></i><?=$values["payments"]["percent"]?>%</span>
                    </div>
                    <div class=" ms-3">
                        <span class="avatar avatar-md br-5 bg-warning-transparent text-warning"><i class="fe fe-credit-card fs-18"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

}


?>