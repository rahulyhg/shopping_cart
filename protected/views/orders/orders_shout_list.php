<style type="text/css">
    #del{ cursor:pointer;}
</style>

<script type="text/javascript">
    function del_list_order(id, product_id) {
        //alert(product_id);
        var url = "<?= Yii::app()->createUrl('frontend/orders/del_list_order') ?>";
        var data = {id: id, product_id: product_id};

        $.post(url, data,
                function (success) {
                    //alert('ลบสินค้าออกจากตะกร้าแล้ว');
                    //window.location.reload();
                    load_cart_list();
                }
        );// endpost
    }

    function edit_num(id, new_num, price) {
        var url = "<?= Yii::app()->createUrl('frontend/orders/edit_num_order') ?>";
        var price_total = (price * new_num);
        var data = {
            id: id,
            new_num: new_num,
            price_total: price_total
        };

        $.post(url, data,
                function (success) {
                    //alert('ลบสินค้าออกจากตะกร้าแล้ว');
                    //window.location.reload();
                    load_cart_list();
                });
    }
</script>




<table width="100%" class="table table-hover" id="order_list_use">
    <tbody>
        <?php
        $product_model = new Product();
        $totalall = 0;
        $i = 1;
        foreach ($product as $products):
            $img = $product_model->get_last_img($products['product_id']);
            $product_price = $products['product_price'];
            ?>
            <tr id="tr_b" style=" color: #000;">
                <td id="td_b">
                    <img src="<?php echo Yii::app()->baseUrl; ?>/uploads/<?php echo $img; ?>" style=" max-width: 100px;"/>
                </td>
                <td>
                    <b>สินค้า</b> <?= $products['product_name']; ?><br/>
                    <b>ราคา</b> <?= number_format($products['product_price']); ?> <b>บาท/หน่วย</b><br/>
                    <b>จำนวน</b> <select id="num" onchange="edit_num('<?= $products['id'] ?>', this.value, '<?= $product_price ?>');" 
                                         style=" width:50px; padding-left:5%;">
                                             <?php for ($i = 1; $i <= 20; $i++) { ?>
                            <option value="<?php echo $i; ?>"<?php
                            if ($i == $products['product_num']) {
                                echo "selected";
                            }
                            ?>><?php echo $i; ?></option>
                                <?php } ?>
                    </select><br/>
                    <b>รวม</b> <?= number_format(($products['product_price'] * $products['product_num']), 2); ?> <b>บาท</b>
                </td>
                <td id="td_b" style=" text-align: center;">
                    <img src="<?= Yii::app()->baseUrl; ?>/images/error.png" onclick="return del_list_order('<?= $products['id'] ?>', '<?= $products['product_id'] ?>');" id="del" title="ลบสินค้าออกจากตะกร้า"/>
                </td>
                <?php
                $total = (($products['product_price'] * $products['product_num']));
                $totalall = $totalall + $total;
                ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" id="td_b2" align="center">
                <font style="text-decoration:none;font-size: 24px; color: #000;">ราคารวม </font>
                <font style="text-decoration:none; font-size: 24px; margin: 0px 10px;"><?= number_format($totalall, 2) ?></font> 
                <font style="text-decoration:none;font-size: 24px; color: #000;">บาท</font>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <a href="<?php echo Yii::app()->createUrl('frontend/main'); ?>">
                    <button class="btn btn-default" style=" font-size: 14px;">
                        <span class="glyphicon glyphicon-chevron-left"></span> 
                        ช๊อปสินค้าอื่น
                    </button></a>
                <a href="<?php echo Yii::app()->createUrl('frontend/orders/show_order_list'); ?>">
                    <button class="btn btn-default" style=" font-size: 14px;">ทำรายการถัดไป 
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </button></a>
            </td>
        </tr>
    </tfoot>
</table>


