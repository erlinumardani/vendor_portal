<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function page_view($title = '', $view_name = '', $content_data = array(), $extra_data = array()){
    $CI = &get_instance();

    $configs = array();
    foreach($CI->db->get_where('configs')->result_array() as $config){
        $configs[$config['name']] = $config['value'];
    }

    $data = array(
        'content' => $CI->load->view($CI->uri->segment(1).'/'.$view_name, $content_data, TRUE),
        'extrascript' => $CI->load->view($CI->uri->segment(1).'/'.'js', $content_data, TRUE),
        'content_title' => $title,
        'base_url' => base_url(),
        'menus' => get_menus(),
        'process_menus' => get_process_menus()
    );
    
    return $CI->parser->parse('layout', array_merge($data, $extra_data, $configs));
}

function get_menus(){
    $CI = &get_instance();

    $get_menus = $CI->db
        ->where('type in("Single","Main")')
        ->where('status','Active')
        ->get_where('menus',"JSON_CONTAINS(`privileges`, '[".$CI->session->userdata('role_id')."]')")
        ->result();

    $menus = '';

    if(isset($get_menus)){
        foreach ($get_menus as $menu) {

            if(strpos($menu->url,'http://')>0){
                $url = $menu->url;
            }else{
                $url = base_url().$menu->url;
            }

            if($menu->type == 'Main'){

                $menus .= '
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-'.$menu->icon.'"></i>
                            <p>
                                '.$menu->name.'
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                    <ul class="nav nav-treeview">
                ';

                $get_submenus = $CI->db
                    ->where('type','Sub')
                    ->where('main_id',$menu->id)
                    ->where('status','Active')
                    ->get_where('menus',"JSON_CONTAINS(`privileges`, '[".$CI->session->userdata('role_id')."]')")
                    ->result();
                
                if(isset($get_submenus)){
                    foreach ($get_submenus as $submenu) {
                        if(strpos($submenu->url,'http://')>0){
                            $url = $submenu->url;
                        }else{
                            $url = base_url().$submenu->url;
                        }
                        $menus .= '
                            <li class="nav-item">
                                <a id="'.explode("/",$submenu->url)[0].'" href="'.$url.'" class="nav-link menu">
                                <i class="nav-icon fas fa-'.$submenu->icon.'"></i>
                                <p>
                                    '.$submenu->name.'
                                </p>
                                </a>
                            </li>
                        '; 
                    }
                }

                $menus .= '</ul></li>';

            }else{

                $menus .= '
                    <li class="nav-item">
                        <a id="'.explode("/",$menu->url)[0].'" href="'.$url.'" class="nav-link menu">
                        <i class="nav-icon fas fa-'.$menu->icon.'"></i>
                        <p>
                            '.$menu->name.'
                        </p>
                        </a>
                    </li>
                '; 

            }
        }
    }

    return $menus;
}   

function get_process_menus(){
    $CI = &get_instance();

    $role_id = $CI->session->userdata('role_id');

    if($role_id == 1){
        $get_nodes = $CI->db
        ->get_where('process_flow_nodes')
        ->result();
    }else{
        $get_nodes = $CI->db
        ->get_where('process_flow_nodes','JSON_CONTAINS(JSON_EXTRACT(`privileges`, "$.roles[*]"),"'.$role_id.'")')
        ->result();
    }

    $menus = '';
    $menus .= '
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-briefcase"></i>
                    <p>
                        Process
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
            <ul class="nav nav-treeview">
        ';

    foreach ($get_nodes as $node) {

        if($CI->session->userdata('role_id')=='6'){
            if($node->id==1){
                $count = $CI->db->get_where('v_request','flow_node_type != "End" and requested_by ="'.$CI->session->userdata('user_id').'"')->num_rows();
            }else{
                $count = $CI->db->get_where('v_request',array('flow_node_id'=>$node->id,'requested_by'=>$CI->session->userdata('user_id')))->num_rows();
            }
        }else{
            $count = $CI->db->get_where('v_request','flow_node_id ="'.$node->id.'" and (updated_by is null or updated_by="'.$CI->session->userdata('user_id').'")')->num_rows();
        }

        $menus .= '<li class="nav-item">
                <a id="node_'.$node->id.'" href="'.base_url('process/data/get/'.$node->id).'" class="nav-link menu">
                    <i class="nav-icon far fa-circle"></i>
                    <p>
                        '.$node->name.' <span class="right badge badge-primary">'.$count.'</span>
                    </p>
                </a>
            </li>
        ';
    }

    $menus .= '</ul></li>';

    return $menus;

}

function form_render($form_id = '', $fieldset = array(), $split = FALSE, $validate=true, $then="window.history.go(-1);"){
    $result = '';
    $fieldcount = 0;

    foreach ($fieldset as $field) {
        if($field['type']!="hidden"){
            $fieldcount++;
        }
    }

    $sequence = 1;
    foreach ($fieldset as $field){
        $field_name = str_replace(' ', '_', strtolower($field['name']));

        if(isset($field['label'])){
            $label = $field['label'];
        }else{
            $label = $field['name'];
        }
        if(isset($field['class'])){
            $field['class'] = $field['class'];
        }else{
            $field['class'] = "";
        }
        if(isset($field['icon'])){
            $field['icon'] = $field['icon'];
        }else{
            $field['icon'] = "fa-terminal";
        }
        if(isset($field['custom_attributes']['placeholder'])){
            $field['custom_attributes']['placeholder'] = $field['custom_attributes']['placeholder'];
        }else{
            $field['custom_attributes']['placeholder'] = isset($field['label'])?$field['label']:$field['name'];
        }
        if(isset($field['id'])){
            $field_id = $field['id'];
        }else{
            $field_id = $field_name;
        }

        $field_detail = array(
            'type'  => $field['type'],
            'name'  => $field_name,
            'id'    => $field_id,
            'class' => 'form-control '.$field['class']
        );

        if($split == TRUE && $sequence ==1){
            $result .= '<div class="row"><div class="col-md-6">';
        }

        if($split == TRUE && round($fieldcount/2+1) == $sequence){
            $result .= '</div><div class="col-md-6">';
        }

        if($field['type'] != 'hidden'){
            $result .= '<div class="form-group">';
            $result .= form_label($label, $field_id, array());
            $result .= '<div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas '.$field['icon'].'"></i></span>
                </div>
            ';
        }

        if($field['type']=='select'){
            $result .= form_dropdown($field_name, $field['options'], $field['default_options'],'class="form-control '.$field['class'].'" id="'.$field_id.'" '.http_build_query($field['custom_attributes'],'',' '));
        }else{
            if($field['type']=='textarea'){
                $result .= form_textarea(array_merge($field_detail,$field['custom_attributes'],array('rows'=>2)));
            }else{
                $result .= form_input(array_merge($field_detail,$field['custom_attributes']));
            }
        }

        if($field['type'] != 'hidden'){
            $result .= '</div></div>';
        }

        if($split == TRUE && $fieldcount == $sequence){
            $result .= '</div></div>';
        }

        $sequence += 1;
        
    }
    if($validate==true){
        $result .= form_validation($fieldset,$form_id,$then);
    }
    return $result;
}

function dropdown_render($array,$default){
    $options = array();
    if(isset($default)){
        $options[$default['key']] = $default['value'];
    }else{
        $options[''] = 'Pilih';
    }
    
    foreach ($array as $row) {
        $options[$row['id']] = $row['name'];
    }
    return $options;
}

function form_validation($fieldset,$form_id,$then){
    $CI = &get_instance();
    $rules = array();
    $script = '';
    foreach($fieldset as $field){
        $field_name = str_replace(' ', '_', strtolower($field['name']));
        $rules[$field_name] = isset($field['rules'])?$field['rules']:array("required"=>TRUE);
    }
   
    $script .= "<script>";
    $script .= "$('#".$form_id."').validate({
        submitHandler: function () {
            var form = document.getElementById('".$form_id."');
            var formData = new FormData(form);

            $.ajax({
                url: '".base_url().$CI->uri->segment(1).'/data/'."form_submit',
                enctype: 'multipart/form-data',
                data: formData,
                processData: false,
                contentType: false,
                type: 'POST',
                dataType: 'json',
            })
            .done(function(data) {
                console.log(data);   
                if(data.status==true){
                    Swal.fire({
                        type: 'success',
                        title: 'Success',
                        text: data.message,
                        timer: 3000,
                        showConfirmButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'ok'
                    }).then(function(){
                        ".$then."
                    });
                }else{
                     Swal.fire({
                        type: 'error',
                        title: 'Failed',
                        text: data.message,
                        showConfirmButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'ok'
                    });
                }    
            })
        },
        rules: ".json_encode($rules).",
        messages: {
            email: {
                required: \"Please enter a email address\",
                email: \"Please enter a vaild email address\"
            },
            password: {
                required: \"Please provide a password\",
                minlength: \"Your password must be at least 5 characters long\"
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });";
    $script .= "</script>";
    
    return $script;
}