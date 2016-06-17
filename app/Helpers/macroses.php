<?php

Collective\Html\FormBuilder::macro(
    'imageInput',
    function ($name, $image, $params = []) {
        if (isset($params['data-related-image'])) {
            $image_class = $params['data-related-image'];
            unset($params['data-related-image']);
        } else {
            $image_class = str_replace(['[', ']', '.'], '', $name);
        }

        if (isset($params['data-target-input'])) {
            $target_input = $params['data-target-input'];
            unset($params['data-target-input']);
        } else {
            $target_input = '[name=\''.$name.'\']';
        }

        if (isset($params['elfinder-link-name'])) {
            $elfinder_link_name = $params['elfinder-link-name'];
            unset($params['elfinder-link-name']);
        } else {
            $elfinder_link_name = "{$name}_replaseme";
        }

        $params = array_merge(
            array (
                'data-related-image' => ".$image_class",
                'placeholder'        => trans('labels.link'),
                'class'              => "form-control input-sm",
                'elfinder-link'      => $elfinder_link_name,
            ),
            $params
        );
        $image = Request::old($name) ? Request::old($name) : $image;

        return view(
            'partials.macroses.image',
            [
                'image_class'        => $image_class,
                'name'               => $name,
                'image'              => $image,
                'elfinder_link_name' => $elfinder_link_name,
                'params'             => $params,
                'target_input'       => $target_input,
                'width'              => isset($params['width']) ? $params['width'] : 150,
                'height'             => isset($params['height']) ? $params['height'] : 150,
            ]
        )->render();
    }
);

Collective\Html\FormBuilder::macro(
    'elfinderInput',
    function ($name, $value, $params = []) {
        $elfinder_link_name = $name;
        $params = array_merge(
            array (
                'placeholder'   => trans('labels.link'),
                'class'         => "form-control input-sm",
                'elfinder-link' => $elfinder_link_name,
            ),
            $params
        );

        return view(
            'partials.macroses.elfinder',
            [
                'name'               => $name,
                'value'              => $value,
                'elfinder_link_name' => $elfinder_link_name,
                'params'             => $params,
            ]
        )->render();
    }
);