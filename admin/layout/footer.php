</div>
</div>


<script src="<?php echo PUBLIC_URL?>dist/js/scripts.js"></script>
<script src="<?php echo PUBLIC_URL?>dist/js/custom.js"></script>
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
<?php if(Session::has("error")):?>
    <!-- session -->
    <script>
        iziToast.show({
            message: "<?php echo Session::get("error")?>",
            position: 'topRight',
            color: 'red',
        })
    </script>
<?php endif?>
<?php if(Session::has("success")):?>
    <!-- session -->
    <script>
        iziToast.show({
            message: "<?php echo Session::get("success")?>",
            position: 'topRight',
            color: 'green',
        })
    </script>
<?php endif?>

</body>
</html>