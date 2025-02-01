<?php include "./layout_top.php"?>

<!-- ///////////////////////
            BANNER
 /////////////////////////// -->
 <?php 
    $page_title="Contact";
    include "./section_banner.php"
?>
<!-- ///////////////////////
            BANNER
 /////////////////////////// -->


<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="contact-form">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" />
                            <small class='form-text text-danger input-error input-name'></small>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Email Address</label>
                            <input type="text" class="form-control" name="email" />
                            <small class='form-text text-danger input-error input-email'></small>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Message</label>
                            <textarea class="form-control" rows="3" name="message"></textarea>
                            <small class='form-text text-danger input-error input-message'></small>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary bg-website" name="form">
                                <span class="button-loader"></span>
                                <span>Send Message</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <?php if(ProviderSetting::get("map")):?>
                <div class="col-lg-6 col-md-12">
                    <div class="map"><?php echo html_entity_decode(ProviderSetting::get("map"))?></div>
                </div>
            <?php endif?>
        </div>
    </div>
</div>

<script>
    /* send message */
    $(document).ready(function(){
        $("button[name=form]").click(async function(e){
            e.preventDefault()

            const el = $(this)
            const form = el.closest("form")
            const name = form.find("input[name=name]")
            const email = form.find("input[name=email]")
            const message = form.find("textarea[name=message]")
            const formData = new FormData()

            form.find(".input-error").each(function(){
                $(this).html("")
            })
            el.addClass("pending")
            el.removeClass("active")
            
            await new Promise(resolve=>setTimeout(resolve,1000))
            formData.append("name",btoa(name.val()))
            formData.append("email",btoa(email.val()))
            formData.append("message",btoa(message.val()))
            

            $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL?>page_contact_submit_ajax.php",
                data: formData,
                processData: false,
                contentType:false,
                success:function(response){
                    const res = JSON.parse(response)

                    if(res.success) {
                        name.val("")
                        email.val("")
                        message.val("")

                        iziToast.show({
                            title: res.success?.message,
                            position: "topRight",
                            color: res.success ? "green" : "red"
                        })
                    }

                    if(res.error) {
                        $.each(res.error, function (key,val) {
                            $(".input-error.input-"+key).text(val[0])
                        })
                    }

                    el.removeClass("pending")
                    el.addClass("active")
                }
            })
        })
    })
</script>
<?php include "./layout_footer.php"?>