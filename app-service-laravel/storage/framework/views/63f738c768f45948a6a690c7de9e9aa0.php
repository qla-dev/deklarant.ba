
<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.starter'); ?>  <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>


<?php $__env->startComponent('components.breadcrumb'); ?>
<?php $__env->slot('li_1'); ?> Pages <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?> Starter  <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class=" d-flex justify-content-center align-items-center" >
    <div class="dropzone" id="dropzone">
        <input type="file" id="fileInput" multiple>
        <div class="corner corner-top-left"></div>
        <div class="corner corner-top-right"></div>
        <div class="corner corner-bottom-left"></div>
        <div class="corner corner-bottom-right"></div>
        <div class="text-center" id="dropzone-content">
            <i class="ri-file-2-line text-info fs-1"></i>
            <p class="mt-3">Povucite i ispustite dokumente ovdje ili kliknite za odabir</p>
        </div>
        <div class="file-list" id="fileList" style="display: none;"></div>
        <div class="progress mt-3" style="width: 100%; display: none;" id="uploadProgressContainer">
            <div id="uploadProgressBar" class="progress-bar bg-info" role="progressbar" style="width: 0%">0%</div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\edeklarant\app-service-laravel\resources\views/pages-starter.blade.php ENDPATH**/ ?>