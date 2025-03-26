
<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.images'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?> Base UI <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?> Images <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row d-flex justify-content-center align-items-center vh-100">
        <div class="col-xxl-6 ">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Image Rounded & Circle</h4>
                    <div class="flex-shrink-0">
                        <div class="form-check form-switch form-switch-right form-switch-md">
                            <label for="rounded-circle-image" class="form-label text-muted">Show Code</label>
                            <input class="form-check-input code-switcher" type="checkbox" id="rounded-circle-image">
                        </div>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">

                    <p class="text-muted">Use
                        <code>rounded</code> class and <code>rounded-circle</code> class to show an image with a round border and rounded shape respectively.
                    </p>

                    <div class="live-preview">

                        <div class="row align-items-center">
                            <div class="col-6">
                                <img class="rounded shadow" alt="200x200" width="200" src="<?php echo e(URL::asset('build/images/small/img-4.jpg')); ?>" data-holder-rendered="true">
                            </div><!-- end col -->
                            <div class="col-6">
                                <div class="mt-4 mt-md-0">
                                    <img class="rounded-circle avatar-xl shadow" alt="200x200" src="<?php echo e(URL::asset('build/images/users/avatar-4.jpg')); ?>" data-holder-rendered="true">
                                </div>
                            </div><!-- end col -->
                        </div>

                    </div>

                   
    <?php $__env->stopSection(); ?>
    <?php $__env->startSection('script'); ?>
        <script src="<?php echo e(URL::asset('build/libs/prismjs/prism.js')); ?>"></script>

        <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\edeklarant\app-service-laravel\resources\views/ui-images.blade.php ENDPATH**/ ?>