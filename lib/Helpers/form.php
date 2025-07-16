<?php

if (!function_exists('form_open')) {
    function form_open(string $action, string $method = 'POST', array $attributes = []): string
    {
        $html = "<form action=\"$action\" method=\"$method\" ";

        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                $html .= " $key=\"$value\"";
            }
        }

        $html .= ">";

        return $html;
    }
}

if (!function_exists('form_close')) {
    function form_close(): string
    {
        return "</form>";
    }
}

if (!function_exists('form_open_multipart')) {
    function form_open_multipart(string $action, $method = 'POST', array $attributes = []): string
    {
        $attributes = array_merge($attributes, ['enctype' => 'multipart/form-data']);

        return form_open($action, $method, $attributes);
    }
}

if (!function_exists('input')) {
    function input(string $name, $value = null, $type = 'text', $attributes = []): string
    {
        $value = value($name, $value);

        $html = "<input type=\"$type\" name=\"$name\" value=\"$value\" ";

        if (!empty($attributes)) {
            foreach ($attributes as $key => $v) {
                $html .= " $key=\"$v\"";
            }
        }

        $html .= '/>';

        return $html;
    }
}

if (!function_exists('textarea')) {
    function textarea(string $name, $value = null, $attributes = []): string
    {
        $html = "<textarea name=\"$name\" ";
        if (!empty($attributes)) {
            foreach ($attributes as $key => $v) {
                $html .= " $key=\"$v\"";
            }
        }

        $html .= '>' . $value . '</textarea>';

        return $html;
    }
}

if (!function_exists('select')) {
    function select(string $name, array $options, $selected = null, $attributes = []): string
    {
        $html = "<select name=\"$name\" ";
        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                $html .= " $key=\"$value\"";
            }
        }

        $html .= '>';

        foreach ($options as $key => $value) {
            $html .= "<option value=\"$key\" ". (!empty($selected) && $key === $selected ? 'selected' : '') . ">$value</option>";
        }

        $html .= '</select>';

        return $html;
    }
}

if (!function_exists('checkbox')) {
    function checkbox($name, $value, $selected = null, $attributes = []): string
    {
        $html = "<input type=\"checkbox\" name=\"$name\" $value=\"$value\"";
        if (!empty($attributes)) {
            foreach ($attributes as $key => $v) {
                $html .= " $key=\"$v\"";
            }
        }

        if (!empty($selected) && $selected === $value) {
            $html .= " checked";
        }

        $html .= '>';

        return $html;
    }
}

if (!function_exists('submit')) {
    function submit($name, $value = null, $attributes = []): string
    {
        $html = "<input type=\"submit\" name=\"$name\" value=\"$value\" ";

        if (!empty($attributes)) {
            foreach ($attributes as $key => $v) {
                $html .= " $key=\"$v\"";
            }
        }

        $html .= '>';

        return $html;
    }
}

if (!function_exists('value')) {
    function value(string $field, mixed $default = null)
    {
        $old = \NovaFrame\Facade\Session::getFlash('old');

        if (isset($old[$field])) {
            return $old[$field];
        }

        return $default;
    }
}

if (!function_exists('input_csrf')) {
    function input_csrf(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('has_error')) {
    function has_error(string $field): bool
    {
        $errors = \NovaFrame\Facade\Session::getFlash('errors', []);

        return !empty($errors) && array_key_exists($field, $errors);
    }
}

if (!function_exists('errors')) {
    function errors(): array
    {
        return \NovaFrame\Facade\Session::getFlash('errors', []);
    }
}

if (!function_exists('error')) {
    function error(string $field): string
    {
        return errors()[$field] ?? '';
    }
}
