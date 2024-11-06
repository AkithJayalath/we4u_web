<?php require APPROOT.'/views/includes/components/header.php';?>
<h1>WE4U</h1>
<?php foreach($data['users'] as $user):?>
    <p><?php echo $user->username;?>-<?php echo $user->email;?></p>
    <?php endforeach;?>

<?php require APPROOT.'/views/includes/components/footer.php';?>
