<?php

if (!function_exists('make_keyword')) {
    function make_keyword($keyword)
    {
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~', '*', '?', ':', '"', '\'', '&', '$', '#', '%', '^', '{', '}', '[', ']', '|', '\\', '/', '_', '.'];
        $keyword = str_replace($reservedSymbols, '%', $keyword);
        $keyword = strtolower($keyword);
        $keyword = preg_replace('/[^A-Za-z0-9\-]/', '%', $keyword);
        return $keyword;
    }
}

if (!function_exists('make_number_keyword')) {
    function make_number_keyword($keyword)
    {
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~', '*', '?', ':', '"', '\'', '&', '$', '#', '^', '{', '}', '[', ']', '|', '\\', '/', '_'];
        $keyword = str_replace($reservedSymbols, '', $keyword);
        $keyword = strtolower($keyword);
        return $keyword;
    }
}

if (!function_exists('validate_data')) {
    function validate_data($data)
    {
        if ($data == "--" || $data == "-" || $data == "" || $data == " ") {
            $data = null;
        }
        return $data;
    }
}