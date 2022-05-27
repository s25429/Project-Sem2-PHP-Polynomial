<?php

use JetBrains\PhpStorm\Pure;

class Data
{
    public array $pol = [];     // Polynomial
    public array $line = [];    // Visualisation of polynomial
    public array $screen = [];  // Canvas for graph
    public array $axis = [];    // Canvas for X and Y axis

    #[Pure] public function __construct() {
        $this->pol = [
            3 => $_POST['x3'] ?? 1,
            2 => $_POST['x2'] ?? -1,
            1 => $_POST['x1'] ?? 1,
            0 => $_POST['x0'] ?? 0,
        ];

        $this->line = [
            'color' => $_POST['line-color'] ?? 'red',
            'style' => $_POST['line-style'] ?? 'line',
            'mass' =>  $_POST['line-size'] ?? 1,
            'flip' =>  $this->setFlip(),
        ];

        $this->screen = [
            'range' => $this->setRange(),
            'size' => [
                'width' => $_POST['screen-width'] ?? 500,
                'height' => $_POST['screen-height'] ?? 300,
            ]
        ];

        $this->axis = [
            'width' =>  16,
            'height' => 16,
            'color' => 'black',
            'mass' => 2,
        ];
    }

    private function setFlip(): array {
        return match ($_POST['line-flip'] ?? 'null') {
            'null' => ['x'=>false, 'y'=>false],
            'x' =>    ['x'=>true,  'y'=>false],
            'y' =>    ['x'=>false, 'y'=>true ],
            'both' => ['x'=>true,  'y'=>true ],
        };
    }

    private function setRange(): array {
        $left = $_POST['left-x'] ?? -1;
        $right = $_POST['right-x'] ?? 1;

        if ($left >= $right)
            return ['left'=>-1, 'right'=>1];
        return ['left'=>$left, 'right'=>$right];
    }
}