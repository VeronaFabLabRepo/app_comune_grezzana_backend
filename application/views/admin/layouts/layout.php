<?php
$this->load->view('admin/layouts/header');
$this->load->view('admin/layouts/menu');
$this->load->view('admin/layouts/success');

if (isset($popupDeleteTitle)) {

	$this->load->view('admin/layouts/dialogConfirm', array('dialogTitle' => $popupDeleteTitle, 'dialogText'  => $popupDeleteText));

}

echo $contentBlock;

$this->load->view('admin/layouts/footer');
?>
