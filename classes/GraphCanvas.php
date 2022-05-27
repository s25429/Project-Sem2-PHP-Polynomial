<?php

class GraphCanvas
{
    const COLOR = 'red';
    const STYLE = 'line';
    const SIZE = 1;

    private Graph $graph;   // Graph class
    private string $color;  // Color of the line
    private string $style;  // Style of that line: dotted|line
    private float $size;    // Weight|Size|Girth|Mass of line

    public function __construct(Graph $graph) {
        $this->graph = $graph;
        $this->color = self::COLOR;
        $this->style = self::STYLE;
        $this->size = self::SIZE;
    }

    public function setStyles(string $color = self::COLOR, string $style = self::STYLE, string $size = self::SIZE) {
        $this->color = $color;
        $this->style = $style;
        $this->size = $size;
    }

    public function draw(string $canvas) {
        echo "$canvas.beginPath();";
        echo "$canvas.lineWidth = $this->size;";
        echo "$canvas.strokeStyle = '$this->color';";

        switch ($this->style) {
            case 'line': {
                foreach ($this->graph->getPoints() as $point)
                    echo "$canvas.lineTo($point[0], $point[1]);";
            } break;
            case 'dotted': {
                foreach ($this->graph->getPoints() as $point) {
                    echo "$canvas.moveTo($point[0], $point[1]);";
                    echo "$canvas.arc($point[0], $point[1], 1, 0, 2 * Math.PI);";
                }
            } break;
        }//endswitch

        echo "$canvas.stroke();";
    }

    public function getColor(): string { return $this->color; }

    public function getStyle(): string { return $this->style; }

    public function getSize(): float { return $this->size; }
}