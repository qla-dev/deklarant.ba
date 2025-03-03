
<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.remix'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?> Forms <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?> Remix Icons <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    <div class="row">

    </div><!-- end row -->

    <div class="row">
        <div class="col-12" id="icons"></div> <!-- end col-->
    </div><!-- end row -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('build/js/pages/remix-icons-listing.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp_new\htdocs\edeklarant\app-service-laravel\resources\views/icons-remix.blade.php ENDPATH**/ ?>