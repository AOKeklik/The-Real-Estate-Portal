</div>
</div>

<script src="<?php echo PUBLIC_URL?>dist_admin/js/scripts.js"></script>
<script src="<?php echo PUBLIC_URL?>dist_admin/js/custom.js"></script>
<!-- exception error -->
 <?php if(isset($error_message)):?>
    <script>
        iziToast.show({
            message: "<?php echo $error_message?>",
            position: "topRight",
            color: "red"
        })
    </script>
<?php unset($error_message);endif?>
<!-- exception success -->
<?php if(isset($success_message)):?>
    <script>
        iziToast.show({
            message: "<?php echo $success_message?>",
            position: "topRight",
            color: "green",
        })
    </script>    
<?php unset($success_message);endif?>
<!-- session error -->
<?php if(isset($_SESSION["error"])):?>
    <script>
        iziToast.show({
            message: "<?php echo $_SESSION["error"]?>",
            position: "topRight",
            color: "red"
        })
    </script>
<?php unset($_SESSION["error"]);endif?>
<!-- session success -->
<?php if(isset($_SESSION["success"])):?>
    <script>
        iziToast.show({
            message: "<?php echo $_SESSION["success"]?>",
            position: "topRight",
            color: "green"
        })
    </script>
<?php unset($_SESSION["success"]);endif?>
</body>
</html>