<?php
require_once 'ArgErrCheck.php';

class Polynomial extends ArgErrCheck
{
    private float $epsilon;     // Calculated offset between points
    private array $range;       // Finite range on X and Y axis for the graph
    private array $points = []; // Points of polynomial inside $range

    public function __construct(array $xRange, array $polynomial) {
        $this->argErrCheck($xRange, $polynomial);

        $this->range = $xRange;

        $this->generateEpsilon();
        $this->generatePoints($polynomial);
        $this->setTopBottomRange();
    }

    protected function argErrCheck(...$data): bool|InvalidArgumentException {
        if (!parent::keysExist($data[0], 'left', 'right') ||
            !parent::keysExist($data[1], 0, 1, 2, 3)
        ) throw new InvalidArgumentException(parent::msg);
        return true;
    }


    private function generateEpsilon() {
        $left = $this->range['left'];
        $right = $this->range['right'];

        // Range of [-a,+b] || [-a,-b], [+a,+b], [0,+b] or [-a,0]
        $this->epsilon = $left * $right < 0
            ? (abs($left) + abs($right)) / 40
            : abs($left - $right) / 40;
    }

    private function generatePoints(array $p = [0, 0, 0, 0]) {
        $left = $this->range['left'];
        $right = $this->range['right'];

        for ($i = $left; $i <= $right + $this->epsilon; $i += $this->epsilon) {
            $x = round($i, 4);
            if ($x > $right) break; // Precision fix for values overflowing the graph
            $y = round($this->setY($i, $p), 4);
            $this->points[] = [$x, $y];
        }
    }

    private function setY(float|int $x, array $p = [0, 0, 0, 0]): float {
        return (
            $p[3] * $x * $x * $x +
            $p[2] * $x * $x +
            $p[1] * $x +
            $p[0]
        );
    }

    private function setTopBottomRange() {
        $yPoints = array_map(function (array $v) { return $v[1]; }, $this->points);
        $top = max($yPoints);
        $bottom = min($yPoints);

        $yLen = $top - $bottom;
        if ($yLen < 1) { // Set length of Y axis to minimum of 1 unit
            $addition = (1 - $yLen) / 2;
            $top += $addition;
            $bottom -= $addition;
        }

        $this->range['top'] = $top;
        $this->range['bottom'] = $bottom;
    }

    public function getRange(): array { return $this->range; }

    public function getPoints(): array { return $this->points; }
}