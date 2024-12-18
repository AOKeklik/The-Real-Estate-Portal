</div>
</div>

<?php //echo "<pre>";print_r($_SESSION);echo "</pre>"?>

<script src="<?php echo PUBLIC_URL?>dist_admin/js/scripts.js"></script>
<script src="<?php echo PUBLIC_URL?>dist_admin/js/custom.js"></script>
<?php if(isset($error_message)):?>
    <!-- exception -->
    <script>
        iziToast.show({
            message: "<?php echo $error_message?>",
            position: 'topRight',
            color: 'red',
        })
    </script>
<?php endif?>

<?php if(isset($_SESSION["error"]) && !empty($_SESSION["error"])):?>
    <!-- session -->
    <script>
        iziToast.show({
            message: "<?php echo $_SESSION["error"]?>",
            position: 'topRight',
            color: 'red',
        })
    </script>
<?php unset($_SESSION["error"])?>
<?php endif?>

<?php if(Session::has("success")):?>
    <!-- session -->
    <script>
        iziToast.show({
            message: "<?php echo Session::flash("success")?>",
            position: 'topRight',
            color: 'green',
        })
    </script>
<?php endif?>
<script>
    /* change photo */
    if($(".js-update-photo").length > 0) {
        $(".js-update-photo").each(function () {
            $(this).change(function (evnet) {
                $(this).parent("div").find("img").each(function () {
                    if ($(this).length > 0) {
                        $(this).attr("src",URL.createObjectURL(event.target.files[0]))
                    }
                })
            })
        })
    }
</script>
</body>
</html>