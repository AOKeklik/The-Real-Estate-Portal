<?php
    include "./layout_top.php";

    if(!isset($_GET["slug"])){
        header("Location: ".BASE_URL."404");
        exit();
    }

    $slug=$_GET["slug"];

    try{
        $stmt=$pdo->prepare("
            select
                *
            from
                locations
            where
                slug=?
            limit
                1
        ");
        $stmt->execute([$slug]);
        $location=$stmt->fetch(pdo::FETCH_ASSOC);

        if($stmt->rowCount() == 0){
            header("Location: ".BASE_URL."404");
            exit();
        }
    }catch(PDOException $err){
        header("Location: ".BASE_URL."404");
        exit();
    }

    try{
        $stmtProperties=$pdo->prepare("
            SELECT
                properties.*,
                locations.name as location_name,
                types.name as type_name,
                agents.id as agent_id,
                agents.slug as agent_slug,
                agents.full_name as agent_name,
                agents.photo as agent_photo
            FROM
                properties
            left JOIN
                locations on locations.id=properties.location_id
            LEFT JOIN
                types on types.id=properties.type_id
            LEFT JOIN
                agents on agents.id=properties.agent_id
            WHERE
                locations.slug=?
            ORDER BY
                properties.name ASC
        ");
        $stmtProperties->execute([$slug]);
        $properties=$stmtProperties->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>

<!-- ///////////////////////
            BANNER
 /////////////////////////// -->
 <?php 
    $page_title=$location["name"];
    include "./section_banner.php"
?>
<!-- ///////////////////////
            BANNER
 /////////////////////////// -->
  

<div class="property" id="pagination">
    <div id="pagination-loader"></div>
    <div class="container" id="pagination-body">
        <div class="row" id="pagination-data">
            <?php if($stmtProperties->rowCount() > 0): foreach($properties as $property):?>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="item">
                        <div class="photo">
                            <img class="main" src="<?php echo PUBLIC_URL?>uploads/property/<?php echo $property["featured_photo"]?>" alt="">
                            <div class="top">
                                <?php if(strpos($property["purpose"],"Sale") !== false):?>
                                    <div class="status-sale">For Sale</div>
                                <?php else:?>
                                    <div class="status-rent">For Rent</div>
                                <?php endif?>
                                <?php if($property["is_featured"] == 1):?>
                                    <div class="featured">Featured</div>
                                <?php endif?>
                            </div>
                            <div class="price"><?php echo $property["price"]?> PLN</div>
                            <div class="wishlist"><a href=""><i class="far fa-heart"></i></a></div>
                        </div>
                        <div class="text">
                            <h3>
                                <a href="<?php echo BASE_URL?>/property/<?php echo $property["id"]?>/<?php echo $property["slug"]?>">
                                    <?php echo $property["name"]?>
                                </a>
                            </h3>
                            <div class="detail">
                                <div class="stat">
                                    <div class="i1"><?php echo $property["size"]?> sqft</div>
                                    <?php if(!is_null($property["bedroom"])):?>
                                        <div class="i2"><?php echo $property["bedroom"]?> Bed</div>
                                    <?php endif?>
                                    <?php if(!is_null($property["bathroom"])):?>
                                        <div class="i3"><?php echo $property["bathroom"]?> Bath</div>
                                    <?php endif?>
                                </div>
                                <div class="address">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo $property["address"]?>
                                </div>                               
                                <div class="type-location">
                                    <div class="i1">
                                        <i class="fas fa-edit"></i> <?php echo $property["location_name"]?>
                                    </div>
                                    <div class="i2">
                                        <i class="fas fa-location-arrow"></i> <?php echo $property["type_name"]?>
                                    </div>
                                </div>
                                <div class="agent-section">
                                    <?php if(is_null($property["agent_photo"])):?>
                                        <img class="agent-photo" src="<?php echo PUBLIC_URL?>uploads/user.png" alt="">
                                    <?php else:?>
                                            <img class="agent-photo" src="<?php echo PUBLIC_URL?>uploads/agent/<?php echo $property["agent_photo"]?>" alt="">
                                    <?php endif?>
                                    <a href="<?php echo BASE_URL?>/agent/<?php echo $property["agent_id"]?>/<?php echo $property["agent_slug"]?>">
                                        <?php echo $property["agent_name"]?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach;endif?>
        </div>
        <nav aria-label="Page navigation" id="pagination-control">
            <ul class="pagination justify-content-end">
                <!-- Pagination links will be generated here -->
            </ul>
        </nav>
    </div>
</div>

<script>
    $(document).ready(function(){
        const itemsPerPage = Number("<?php echo MAX_POSTS_PER_PAGE?>")
        let currentPage = 1

        function hideLoaderAndShowBody () {
            setTimeout(() => {
                $("#pagination-loader").hide()
                $("#pagination-body").show()
            },1000)
        }
        hideLoaderAndShowBody ()

        function displayItems (page=1) {
            const start = (page - 1) * itemsPerPage
            const end = start + itemsPerPage

            $("#pagination-data").children().hide().slice(start,end).show()

            const totalItems = $("#pagination-data").children().length
            const totalPages = Math.ceil(totalItems/itemsPerPage)
            let paginationLink = ""

            if(totalPages>1)
                $("#pagination-control").show()
            else
                $("#pagination-control").hide()

            paginationLink += `
                <li class="page-item ${page === 1 ? "disabled" : ""}">
                    <a class="page-link" href="" data-page="${page-1}">
                        <span aria-hiddien="true">&laquo;</span>
                    </a>
                </li>
            `

            for(let i=1;i<=totalPages;i++){
                paginationLink +=`
                    <li class="page-item ${i === page ? "disabled" : ""}">
                        <a class="page-link" href="" data-page="${i}">${i}</a>
                    </li>
                `
            }

            paginationLink += `
                <li class="page-item ${page === totalPages ? "disabled" : ""}">
                    <a class="page-link" href="" data-page="${page+1}">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            `

            $("#pagination-control > ul").html(paginationLink)
        }
        displayItems (currentPage)

        $(document).on("click",".page-link",function(e){
            e.preventDefault()

            const totalItems = $("#pagination-data").children().length
            const itemsPerPage = "<?php echo MAX_POSTS_PER_PAGE?>"

            const totalPages = Math.ceil(totalItems/itemsPerPage)
            const nextPage = $(this).data("page")

            if(nextPage>=1 && nextPage<=totalPages){
                currentPage = nextPage
                displayItems(currentPage)
            }
        })
    })
</script>
<?php include "./layout_footer.php"?>