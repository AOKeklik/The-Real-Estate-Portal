<?php include "./layout_top.php"?>
<div class="page-top" style="background-image: url('')">
    <div class="bg"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Contact</h2>
            </div>
        </div>
    </div>
</div>

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
            <div class="col-lg-6 col-md-12">
                <div class="map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d387190.2799198932!2d-74.25987701513004!3d40.69767006272707!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sbd!4v1645362221879!5m2!1sen!2sbd" width="600" height="450" style="border: 0" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
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