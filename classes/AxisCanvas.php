<?php

class AxisCanvas
{
    const COLOR = 'black';
    const SIZE = 2;

    private Axis $axis;     // Axis class
    private string $color;  // Color of lines
    private float $size;    // Weight|Size|Girth|Mass of lines

    public function __construct(Axis $ax) {
        $this->axis = $ax;
        $this->color = self::COLOR;
        $this->size = self::SIZE;
    }

    public function setStyles(string $color = self::COLOR, string $size = self::SIZE) {
        $this->color = $color;
        $this->size = $size;
    }

    public function draw(string $canvas, array $axCanvases) {
        $zeroVis = $this->axis->getZeroVisibility();

        echo "$canvas.beginPath();";
        echo "$canvas.lineWidth = $this->size;";
        echo "$canvas.strokeStyle = '$this->color';";

        foreach ($this->axis->getPos() as $key => $position) {
            if ($key == 'zero' && !$zeroVis) continue;

            $from = $position['from'];
            $to = $position['to'];

            echo "$canvas.moveTo($from[0], $from[1]);";
            echo "$canvas.lineTo($to[0], $to[1]);";
        }
        echo "$canvas.stroke();";

        $axCanvasStart = $axCanvases['start'];
        $axCanvasZero = $axCanvases['zero'];
        $axCanvasEnd = $axCanvases['end'];
        $start = $this->axis->getStart();
        $end = $this->axis->getEnd();
        $zeroPos = $this->axis->getZeroPosition();
        $flip = $this->axis->getFlip();

        if ($flip) {
            echo "$axCanvasStart.textContent = $end;";
            echo "$axCanvasEnd.textContent = $start;";
        } else {
            echo "$axCanvasStart.textContent = $start;";
            echo "$axCanvasEnd.textContent = $end;";
        }

        if (!$zeroVis) return;
        switch ($this->axis->getAxisDir()) {
            case 'horizontal': {
                $zeroStyle = intval($zeroPos - 4).'px';
                echo "$axCanvasZero.style.left = '$zeroStyle';";
            } break;
            case 'vertical': {
                $zeroStyle = intval($zeroPos - 8).'px';
                echo "$axCanvasZero.style.top = '$zeroStyle';";
            } break;
        }//endswitch
    }

    public function getColor(): string { return $this->color; }

    public function getSize(): float { return $this->size; }
}