<?php 
    $required_styles = [
        'careseeker/careseekerCreateProfile',
        // 'components/sidebar',
        
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>

<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>
<page-body-container> 
<?php require APPROOT.'/views/includes/components/sidebar.php'; ?>
    
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'?>