<form action="<?php echo $action; ?>" method="post" id="checkout">
    <div class="buttons">
        <div class="pull-right">
            <input type="submit" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" />
        </div>
    </div>
</form>
<script type="text/javascript"><!--
    $('#button-confirm').bind('click', function() {
        $('#checkout').submit();
    });
    //--></script>