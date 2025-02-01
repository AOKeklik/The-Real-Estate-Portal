<?php
    include "./layout_top.php";

    try{
        $stmt=$pdo->prepare("
            SELECT
                *
            FROM
                posts
            WHERE
                status=?
            ORDER BY
                rand()
        ");
        $stmt->execute([1]);

        $posts=$stmt->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>

<!-- ///////////////////////
            BANNER
 /////////////////////////// -->
 <?php 
    $page_title="Blog";
    include "./section_banner.php"
?>
<!-- ///////////////////////
            BANNER
 /////////////////////////// -->


<div class="blog">
    <div class="container" id="pagination">
        <?php if($stmt->rowCount() > 0):?>
            <div id="pagination-loader"></div>

            <div id="pagination-body">
                <div class="row" id="pagination-data">
                    <?php foreach($posts as $post):?>
                        <div class="col-lg-4 col-md-6">
                            <div class="item">
                                <div class="photo">
                                <?php if(is_null($post["photo"])):?>
                                    <img class="w_50" src="https://placehold.co/600x400" alt="">
                                <?php else:?>
                                    <img class="w_50" src="<?php echo PUBLIC_URL?>uploads/post/<?php echo $post["photo"]?>" alt="">
                                <?php endif?>
                                </div>
                                <div class="text">
                                    <h2>
                                        <a href="<?php echo BASE_URL?>post/<?php echo $post["id"]?>/<?php echo $post["slug"]?>"><?php echo $post["title"]?></a>
                                    </h2>
                                    <div class="short-des">
                                        <p><?php echo $post["excerpt"]?></p>
                                    </div>
                                    <div class="button">
                                        <a href="<?php echo BASE_URL?>post/<?php echo $post["id"]?>/<?php echo $post["slug"]?>" class="btn btn-primary">Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach?>
                </div>

                <nav aria-label="Page navigation" id="pagination-control">
                    <ul class="pagination justify-content-end">
                        <!-- Pagination links will be generated here -->
                    </ul>
                </nav>
            </div>
        <?php else:?>
            <p class="alert alert-warning text-center">No Posts are available to display.</p>
        <?php endif?>
    </div>
</div>

<script>
    /* pagination */
    $(document).ready(function(){
        const totalItems = $("#pagination-data").children().length
        const itemsPerPage = Number("<?php echo MAX_POSTS_PER_PAGE?>")
        const totalPages = Math.ceil(totalItems/itemsPerPage)
        let currentPage = 1

        function hideLoaderAndShowBody(){
            setTimeout(() =>{
                $("#pagination-loader").hide()
                $("#pagination-body").show()
            },1000)
        }

        function displayItems(page=1){
            const start = (page - 1) * itemsPerPage
            const end = start + itemsPerPage

            $("#pagination-data").children().hide().slice(start,end).show()
            
            let paginationLink = "";
            
            if(totalPages>1)
                $("#pagination-control").show()
            else
                $("#pagination-control").hide()

            paginationLink+=`
                <li class="page-item ${page === 1 ? "disabled" : ""}">
                    <a class="page-link" href="" data-page="${page-1}">
                        <span aria-hiddien="true">&laquo;</span>
                    </a>
                </li>
            `

            for(let i=1;i<=totalPages;i++){
                paginationLink+=`
                    <li class="page-item ${page === i ? "disabled" : ""}">
                        <a class="page-link" href="" data-page="${i}">
                            <span aria-hiddien="true">${i}</span>
                        </a>
                    </li>
                `
            }

            paginationLink+=`
                <li class="page-item ${page === totalPages ? "disabled" : ""}">
                    <a class="page-link" href="" data-page="${page+1}">
                        <span aria-hiddien="true">&raquo;</span>
                    </a>
                </li>
            `

            $("#pagination-control > ul").html(paginationLink)
        }

        function displayItemsByClick(){
            $(document).on("click",".page-link",function(e){
                e.preventDefault()

                const nextPage=$(this).data("page")

                if(nextPage>=1 && nextPage<=totalPages){
                    currentPage=nextPage
                    displayItems(currentPage)
                }
            })
        }

        displayItems(currentPage)
        displayItemsByClick()
        hideLoaderAndShowBody()
    })
</script>
<?php include "./layout_footer.php"?>`