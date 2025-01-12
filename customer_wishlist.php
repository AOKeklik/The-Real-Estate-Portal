<?php
    include "./layout_top.php";

    if(!isset($_SESSION["customer"])){
        header("Location: ".BASE_URL."customer-login");
        exit();
    }

    try{
        $stmt=$pdo->prepare("
            SELECT
                properties.*,
                agents.company
            FROM
                wishlists
            LEFT JOIN
                properties ON properties.id=wishlists.property_id
            LEFT JOIN
                agents ON agents.id=properties.agent_id
            WHERE
                wishlists.customer_id=?
            ORDER BY
                rand()
        ");
        $stmt->execute([$_SESSION["customer"]["id"]]);
        $wishlists=$stmt->fetchAll(pdo::FETCH_ASSOC);
    } catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>
<div class="page-top" style="background-image: url('uploads/banner.jpg')">
    <div class="bg"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Wishlist</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-content user-panel">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <?php include "./layout_nav_customer.php"?>
            </div>
            <div class="col-lg-9 col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered" id="datatable">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Company</th>
                                <th>Price</th>
                                <th class="w-100">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($wishlists as $wishlist):?>
                                <tr>
                                    <td><?php echo $wishlist["id"]?></td>
                                    <td><?php echo $wishlist["name"]?></td>
                                    <td><?php echo $wishlist["company"]?></td>
                                    <td><?php echo $wishlist["price"]?> PLN</td>
                                    <td>
                                        <a href="<?php echo BASE_URL?>property/<?php echo $wishlist["id"]?>/<?php echo $wishlist["slug"]?>" class="btn btn-primary btn-sm text-white">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a 
                                            href="" 
                                            class="btn btn-danger btn-sm text-white wishlist active"
                                            data-property-id="<?php echo $wishlist["id"]?>" 
                                            data-customer-id="<?php echo $_SESSION["customer"]["id"]?>"
                                        >
                                            <div class="wishlist-loader"></div>
                                            <i class="wishlist-heart-full fa fa-times fs-5"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        
        function handlerClickWishlistButton () {
            $(".wishlist").click(async function(e){
                e.preventDefault()

                if(!confirm("Are you sure?")) return

                $(this).closest("tr").css("pointer-events","none")
                $(this).addClass("pending")
                $(this).removeClass("active")

                await new Promise((resolve) => setTimeout(resolve,1000))

                const formData = new FormData()

                formData.append("property_id",btoa($(this).data("property-id")))
                formData.append("customer_id",btoa($(this).data("customer-id")))

                removeItemFromWishlist(formData,$(this))
            })
        }   
        handlerClickWishlistButton()

        function removeItemFromWishlist(formData,el){
            $.ajax({
                url:"<?php echo BASE_URL?>customer_wishlist_remove.php",
                type: "POST",
                contentType: false,
                processData: false,
                data: formData,
                success: function(responsive){
                    const res = JSON.parse(responsive)

                    iziToast.show({
                        title: res.error?.message ?? res.success.message,
                        position: "topRight",
                        color: res.error ? "red" : "green"
                    })

                    el.closest("tr").slideUp()
                }
            })
        }
    })
</script>
<?php include "./layout_footer.php"?>