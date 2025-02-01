<?php
    include "./layout_top.php";

    try{
        $stmtAgents=$pdo->prepare("
            SELECT
                agents.*
            FROM
                agents
            INNER JOIN
                orders ON orders.agent_id=agents.id
            WHERE
                agents.status=1 
            AND
                now() BETWEEN orders.purchase_date AND orders.expire_date
            AND
                orders.currently_active=?
            ORDER BY
                rand()
        ");
        $stmtAgents->execute([1]);
        $agents=$stmtAgents->fetchAll(pdo::FETCH_ASSOC);
    }  catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>

<!-- ///////////////////////
            BANNER
 /////////////////////////// -->
<?php 
    $page_title="Agents";
    include "./section_banner.php"
?>
<!-- ///////////////////////
            BANNER
 /////////////////////////// -->

<div class="agent pb_40"  id="pagination">
    <div id="pagination-loader"></div>

    <div class="container" id="pagination-body">
        <div class="row" id="pagination-data">
            <?php if($stmtAgents->rowCount() > 0): foreach($agents as $agent):?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="item">
                        <div class="photo">
                            <a href="<?php echo BASE_URL?>agent/<?php echo $agent["id"]?>/<?php echo $agent["slug"]?>">
                                <?php if(is_null($agent["photo"])):?>
                                    <img src="<?php echo PUBLIC_URL?>uploads/user.png" alt="">
                                <?php else:?>
                                    <img src="<?php echo PUBLIC_URL?>uploads/agent/<?php echo $agent["photo"]?>" alt="">
                                <?php endif?>
                            </a>
                        </div>
                        <div class="text">
                            <h2>
                                <a href="<?php echo BASE_URL?>agent/<?php echo $agent["id"]?>/<?php echo $agent["slug"]?>">
                                    <?php echo $agent["full_name"]?>
                                </a>
                            </h2>
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
        const itemsPerPage = 8
        let currentPage=1

        function hideLoaderAndShowBody(){
            setTimeout(() => {
                $("#pagination-loader").hide()
                $("#pagination-body").show()
            },1000)
        }
        hideLoaderAndShowBody()

        function displayItems(page=1){
            const start = (page-1)*itemsPerPage
            const end =start+itemsPerPage

            $("#pagination-data").children().hide().slice(start,end).show()

            const totalItems = $("#pagination-data").children().length
            const totalPages = Math.ceil(totalItems/itemsPerPage)
            let paginationLink=""

            if(totalPages > 1)
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
                    <li class="page-item ${i === page ? "disabled" : ""}">
                        <a class="page-link" href="" data-page="${i}">${i}</a>
                    </li>
                `
            }

            paginationLink+=`
                <li class="page-item ${page === totalPages ? "disabled" : ""}">
                    <a class="page-link" href="" data-page="${page+1}">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            `

            $("#pagination-control > ul").html(paginationLink)
        }
        displayItems(currentPage)

        function displayItemsByClick(){
            $(document).on("click", "#pagination-control .page-link", function(e){
                e.preventDefault()

                const totalItems = $("#pagination-data").children().length
                const totalPages = Math.ceil(totalItems/itemsPerPage)
                const nextPage = $(this).data("page")

                if(nextPage>=1 && nextPage<=totalPages) {
                    currentPage= nextPage
                    displayItems(currentPage)
                }
            })
        }
        displayItemsByClick()
    })
</script>
<?php include "./layout_footer.php"?>