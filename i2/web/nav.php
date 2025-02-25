<?php
if(!function_exists('nav_item')){
    function nav_item (string $lien, string $titre): string
    {
        $classe = 'nav-link';
        if ($_SERVER['SCRIPT_NAME'] === $lien){
            $classe .= ' active';
        }    
        return <<<HTML
        <li class="nav-item">
            <a class="$classe" href="$lien">$titre</a>
        </li>
HTML;       
    }
}
?>
<?= nav_item('/var/www/html/menu.php','Statistic'); ?>
<?= nav_item('/var/www/html/index.php','Billing'); ?>
<?= nav_item('/var/www/html/database.php', 'Database'); ?>
<?= nav_item('/var/www/html/records.php', 'Records'); ?>
