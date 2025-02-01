<div class="page-top" style="background-image: url(
    <?php 
        if(ProviderSetting::get("banner")) echo PUBLIC_URL."uploads/setting/".ProviderSetting::get("banner");
        else echo "https://placehold.co/600x200?text=Banner";
    ?>
)">
    <div class="bg"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><?php echo $page_title?></h2>
            </div>
        </div>
    </div>
</div>