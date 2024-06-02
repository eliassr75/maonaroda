<?php


if ($page && $_SESSION["logged"]){

    switch ($page) {
    
        case 'my-campaigns':
        
            ?>
            
            <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                <h1 class="page-title fw-semibold fs-20 mb-0"><i class="bi bi-newspaper"></i> Minhas Campanhas</h1>
                <div class="ms-md-1 ms-0">
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Perfil</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Campanhas</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <?php
        
            break;
        default:
            
            ?>
            
            <div class="d-md-flex d-block align-items-center justify-content-between page-header-breadcrumb">
                <h1 class="page-title fw-semibold fs-20 mb-0"><i class="bi bi-wallet2"></i> Minhas Collabs</h1>
                <div class="ms-md-1 ms-0">
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Cadastros</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Collabs</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <?php
        
            break;
        
    }

}

?>