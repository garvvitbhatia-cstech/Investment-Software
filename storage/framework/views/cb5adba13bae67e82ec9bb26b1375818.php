<?php
$siteUrl = env('APP_URL');
?>
<?php if($records->count()>0): ?>

    <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <tr>
    <td><?php echo e($row->id); ?>.</td>
    <td><?php echo e($row->title); ?></td>
    
    <td>
    <?php
    if($row->status == 1){$class = 'bg-label-success'; $label = 'Active';}else{$class = 'bg-label-danger'; $label = 'In-Active';}
    ?>
    <a style="cursor:pointer" onclick="changeStatus('vehicle_types','<?php echo $row->id; ?>');" id="status_<?php echo e($row->id); ?>" class="badge <?php echo e($class); ?> me-1"><?php echo e($label); ?></a>
    <input type="hidden" id="status_value_<?php echo e($row->id); ?>" value="<?php echo $row->status; ?>" />
    </td>
    
    <td><?php echo date('d M, Y h:i A',strtotime($row->created_at)); ?></td>
    <td>
    <div class="dropdown">
    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
    <i class="icon-base ti tabler-dots-vertical"></i>
    </button>
    <div class="dropdown-menu">
    <a class="dropdown-item" style="cursor:pointer" onclick="getDetails('<?php echo e($row->id); ?>');" data-bs-toggle="modal" data-bs-target="#addNewCCModal"><i class="icon-base ti tabler-pencil me-1"></i> Edit</a>
    <a class="dropdown-item" onclick="deleteData('vehicle_types','<?php echo e($row->id); ?>');" href="javascript:void(0);"><i class="icon-base ti tabler-trash me-1"></i> Delete</a>
    </div>
    </div>
    </td>
    </tr>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
    <tr>

        <td align="center" colspan="6">Record not found</td>

    </tr>



    <?php endif; ?>



    <tr>



        <td align="center" colspan="10">



            <div id="pagination"><?php echo $records->links('pagination.front'); ?></div>



        </td>



    </tr><?php /**PATH /var/www/vhosts/caabaa.achtunglabs.co/httpdocs/resources/views//panel/vehicle_type/paginate.blade.php ENDPATH**/ ?>