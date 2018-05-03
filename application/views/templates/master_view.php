<?php
$this->load->view('templates/public_parts/master_header_view');
?>
<section>
  <?php echo $the_view_content;?>
</section>
<?php
$this->load->view('templates/public_parts/master_footer_view');
?>