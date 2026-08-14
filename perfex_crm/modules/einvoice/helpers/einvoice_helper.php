<?php

function einvioce_module_get_templates(): array
{
    $ci = &get_instance();
    $ci->load->model('templates_model');

    return $ci->templates_model->getByType('einvoice');
}
