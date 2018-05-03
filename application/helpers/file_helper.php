<?php 
	/**
	 * [upload_pdf_file description]
	 * @param  [type]  $image_input_id    [description]
	 * @param  [type]  $image_name        [description]
	 * @param  [type]  $upload_path       [description]
	 * @param  string  $upload_thumb_path [description]
	 * @param  integer $thumbs_with       [description]
	 * @param  integer $thumbs_height     [description]
	 * @return [type]                     [description]
	 */
	function upload_pdf_file($image_input_id, $image_name, $upload_path) {
		$CI =& get_instance();
		
        $image = '';
        if (!empty($image_name)) {
            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = 'pdf|jpg|jpeg|png|gif';
            $config['file_name'] = $image_name;
            $config['max_size'] = '20971520';
            $config['encrypt_name'] = TRUE;

            $CI->load->library('upload', $config);
            $CI->upload->initialize($config);
            if($CI->upload->do_upload($image_input_id)){
            	$upload_data = $CI->upload->data();
            	$image = $upload_data['file_name'];
            }
        }

        return $image;
    }
 ?>