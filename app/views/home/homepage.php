<?php require APPROOT.'/views/includes/components/header.php';?>
<h1>WE4U</h1>
<?php foreach($data['users'] as $user):?>
    <p><?php echo $user->name;?>-<?php echo $user->age;?></p>
    <?php endforeach;?>

<?php require APPROOT.'/views/includes/components/footer.php';?>
