<?php 

session_start(); 
$page = (isset($_GET['p']) && !empty($_GET['p']) ? strtolower($_GET['p']) : false );

?>

<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="horizontal" data-theme-mode="dark" data-header-styles="dark" data-menu-styles="dark" data-toggled="close" loader="disable" data-page-style="modern" data-nav-style="menu-click">

<?php include('php/head.php');?>

<body>

    <?php //include('php/switcher.php'); ?>

    <!-- Loader -->
    <div id="loader" >
    </div>
    <!-- Loader -->

    <?php include('php/sidebar.php'); ?>

        <!-- Start::app-content -->
        <div class="main-content app-content">
            <div class="container-fluid">
                <!-- Page Header -->
                <?php include('php/header.php'); ?>
                <?php include('php/pages.php'); ?>   
            </div>
        </div>
        <!-- End::app-content -->
        
        <div class="modal fade effect-scale" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" id="modal">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    
                </div>
            </div>
        </div>
          
        <!-- Footer Start -->
        <footer class="mt-auto bg-white text-center">
            <div class=" py-3">
                <span class="text-dark"> Copyright © <span id="year"></span>
                    Designed with <span class="bi bi-heart-fill text-danger"></span> by <a href="https://etecsystems.com.br/" target="_blank" class="text-primary fw-semibold">EtecSystems</a> All rights reserved
                </span>
            </div>
        </footer>
        <!-- Footer End -->

    <!-- Scroll To Top -->
    <div class="scrollToTop">
        <span class="arrow"><i class="ri-arrow-up-s-fill fs-20"></i></span>
    </div>
    <div id="responsive-overlay"></div>
    <!-- Scroll To Top -->

    <?php include('php/scripts.php'); ?>
    
    <?php if($table_id) { ?>
    <script>
    
        $(document).ready(() => {
            var _table = $("#<?=$table_id?>").dataTable(configDataTable)
        })
        
    </script>
    <?php } ?>

</body>

</html>