<?php
require_once 'ArgErrCheck.php';

class Graph extends ArgErrCheck
{
    private array $screen;      // Width and height of canvas screen
    private array $range;       // Fixed range of polynomial
    private array $flip;        // Flip of polynomial on X and Y
    private array $points = []; // Points scaled up for screen size
    private array $center = []; // Zero point for X and Y

    public function __construct(Polynomial $polynomial, array $screenSize, array $flip) {
        $this->argErrCheck($screenSize, $flip);

        $this->screen = $screenSize;
        $this->range = $polynomial->getRange();
        $this->flip = $flip;

        $this->generatePointsScaledUp($polynomial);
        $this->generateCenter();
    }

    protected function argErrCheck(...$data): bool|InvalidArgumentException {
        if (!parent::keysExist($data[0], 'width', 'height') ||
            !parent::keysExist($data[1], 'x', 'y')
        ) throw new InvalidArgumentException(parent::msg);
        return true;
    }

    private function generatePointsScaledUp(Polynomial $polynomial) {
        foreach ($polynomial->getPoints() as $point) {
            $x = $this->scaleX($point[0]);
            $y = $this->scaleY($point[1]);
            $this->points[] = [$x, $y];
        }
    }

    private function generateCenter() {
        array_push($this->center, $this->scaleX(0), $this->scaleY(0));
    }

    private function scaleX(float|int $x): float {
        $width = $this->screen['width'];
        $left = $this->range['left'];
        $right = $this->range['right'];

        return !$this->flip['x'] // If to flip on X axis
            ? ($x - $left) * ($width / ($right - $left))
            : ($x - $right) * ($width / ($left - $right));
    }

    private function scaleY(float|int $y): float {
        $height = $this->screen['height'];
        $top = $this->range['top'];
        $bottom = $this->range['bottom'];

        return !$this->flip['y'] // If to flip on Y axis
            ? ($y - $top) * ($height / ($bottom - $top))
            : ($y - $bottom) * ($height / ($top - $bottom));
    }

    public function getPoints(): array { return $this->points; }

    public function getCenter(): array { return $this->center; }

    public function getScreenSize(): array { return $this->screen; }

    public function getFlip(): array { return $this->flip; }
}