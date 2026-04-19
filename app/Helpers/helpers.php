<?php

if (!function_exists('zx_button_icon')) {
    function zx_button_icon($url, $enable = true, $icon, $type = 'primary', $tooltip = null, $size = 'sm')
    {
        $title = $tooltip ? ' title="' . $tooltip . '"' : '';
        $iconHtml = '<i class="' . $icon . ' fs-6"></i>';
        $disabledClass = $enable ? '' : ' disabled';
        return '<a href="' . $url . '" class="btn btn-' . $size . ' btn-' . $type . ' btn-icon waves-effect waves-light ' . $disabledClass . '"' . $title . '>' . $iconHtml . '</a>';
    }
}

if (!function_exists('zx_button_edit')) {
    function zx_button_edit($url, $enable = true, $icon = 'bx bxs-pencil', $tooltip = null, $size = 'sm')
    {
        return zx_button_icon($url, $enable, $icon, 'warning', $tooltip ?? __('Edit'), $size);
    }
}

if (!function_exists('zx_delete_confirm')) {
    function zx_delete_confirm($url, $tooltip = null, $confirm_title = null, $confirm_text = null, $confirm_ok = null, $confirm_cancel = null, $size = 'sm')
    {
        $title = $tooltip ? ' title="' . $tooltip . '"' : __('Delete');
        $confirm_title = $confirm_title ?? __('Are you sure?');
        $confirm_text = $confirm_text ?? __('This action cannot be undone.');
        $confirm_ok = $confirm_ok ?? __('Yes, delete it!');
        $confirm_cancel = $confirm_cancel ?? __('Cancel');

        $el = '<form action="' . $url . '" method="POST">' .
                csrf_field() . method_field('DELETE') .
                '<button type="button"' .
                    'data-confirm="' . $confirm_title . '"' .
                    'data-confirm-text="' . $confirm_text . '"' .
                    'data-confirm-ok="' . $confirm_ok . '"' .
                    'data-confirm-cancel="' . $confirm_cancel . '"' .
                    'class="btn btn-' . $size . ' btn-danger btn-icon waves-effect waves-light"' .
                    'title="' . $title . '">' .
                    '<i class="bx bxs-trash fs-6"></i>' .
                '</button>' .
            '</form>';
        return $el;
    }
}
