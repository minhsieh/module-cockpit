<?php
namespace Modules\Cockpit\Presenters;

class FormPresenter
{
    public function formType($form_type = 'create')
    {
        switch($form_type){
            case 'create':
                return '新增';
            case 'edit':
                return '編輯';
        }
    }
}