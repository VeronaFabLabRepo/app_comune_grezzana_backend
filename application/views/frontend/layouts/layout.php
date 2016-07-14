<?php
$this->load->view('frontend/layouts/header');

echo '<div class="content_wrapper">';
echo $contentBlock;
echo '</div>';

$this->load->view('frontend/layouts/footer');
?>