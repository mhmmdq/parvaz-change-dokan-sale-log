
<div class="parvaz report-dokan-prvz" style="width: 99%;margin-top:40px">

<table class="wp-list-table widefat fixed striped">
    <thead>
        <tr>
            <th class="column order_id">
                شناسه سفارش
            </th>
            <th class="column vendor_id">
                فروشنده
            </th>
            <th class="column order_total">
                مجموع سفارش
            </th><th class="column vendor_earning">
                درآمد فروشنده
            </th>
            <th class="column commission">
                پورسانت
            </th>
           
            <th class="column shipping_total">
                حمل و نقل
            </th>
            <th class="column tax_total">
                مالیات
            </th>
            <th class="column status">
                وضعیت
            </th>
            <th class="column date">
                زمان
            </th>
        </tr>
    </thead>
    <tbody>
        <?php 
            global $_reports;
            $datas = $_reports->paginate_render(20);
            $datas = !empty($datas) ? $datas : [];
            foreach($datas as $data):
        ?>
        <tr>
            <td class="column order_id">
                <a target="_blank" href="<?= get_site_url() . '/wp-admin/post.php?action=edit&post=' . $data['order_id'];?>">#<?=$data['order_id'] . ' ' . $data['name']?></a>
            </td>
            <td class="column vendor_id">
                <a target="_blank" href="<?= get_site_url() . '/wp-admin/user-edit.php?user_id=' . $data['vendor_id'] ;?>"> <?php echo get_userdata($data['vendor_id'])->display_name; ?> </a> 
            </td>
            <td class="column order_total">
                <div><?=$data['total'];?> </div>
            </td>
            <td class="column vendor_earning">
                <div><?=$data['seller'];?> </div>
            </td>
            <td class="column commission">
                <div><?=$data['commission']?> </div>
            </td>
            
            <td class="column shipping_total">
                <div><?=$data['shipping_tax']?> </div> <!---->
            </td>
            <td class="column tax_total">
                <div><?=$data['tax']?> </div>
            </td>
            <td class="column status">
              <?=$data['status'];?>
             </td>
             <td class="column date">
                <?=$data['time'];?>
             </td>
        </tr>
        <?php
            endforeach;
        ?>
    </tbody>

</table>    
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
<div class="links" style="padding:10px;">
<?php 
    echo $_reports->render_links();
?>

</div>


</div>