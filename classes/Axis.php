<?php
require_once 'ArgErrCheck.php';

class Axis extends ArgErrCheck
{
    private string $axisDir;    // Direction of the axis: 'horizontal'|'vertical'
    private int $width;         // Width of axis
    private int $height;        // Height of axis
    private int|float $start;   // Start of left|bottom most range
    private int|float $end;     // End of right|top most range
    private array $pos = [];    // Positions for canvas lines for start, end and zero
    private float|int $zeroPos; // Offset for zero position from left|top
    private bool $zeroVis;      // Visibility for zero point
    private bool $flip;         // If to flip the axis

    public function __construct(string $ax, array $size, array $range, float|int $centerPos, bool $flip) {
        $this->argErrCheck($ax, $size, $range);

        $this->axisDir = $ax;
        $this->width = $size['width'];
        $this->height = $size['height'];

        switch ($ax) {
            case 'horizontal': {
                $this->start = $range['left'];
                $this->end = $range['right'];

                $this->pos['start']['from'] = [1, 0];
                $this->pos['start']['to'] = [1, $this->height];

                $this->pos['zero']['from'] = [$centerPos, 0];
                $this->pos['zero']['to'] = [$centerPos, $this->height];

                $this->pos['end']['from'] = [$this->width + 3, 0];
                $this->pos['end']['to'] = [$this->width + 3, $this->height];
            } break;
            case 'vertical': {
                $this->start = $range['bottom'];
                $this->end = $range['top'];

                $this->pos['start']['from'] = [0, 1];
                $this->pos['start']['to'] = [$this->width, 1];

                $this->pos['zero']['from'] = [0, $centerPos];
                $this->pos['zero']['to'] = [$this->width, $centerPos];

                $this->pos['end']['from'] = [0, $this->height + 3];
                $this->pos['end']['to'] = [$this->width, $this->height + 3];
            } break;
        }//endswitch

        $this->zeroPos = $centerPos;
        $this->zeroVis = $this->setZeroVisibility();
        $this->flip = $flip;
    }

    protected function argErrCheck(...$data): bool|InvalidArgumentException {
        if (!parent::strOneOf($data[0], 'horizontal', 'vertical') ||
            !parent::keysExist($data[1], 'width', 'height') ||
            !parent::keysExist($data[2], 'left', 'right', 'top', 'bottom')
        ) throw new InvalidArgumentException(parent::msg);
        return true;
    }

    private function setZeroVisibility(): bool {
        if ($this->start > 0 || $this->end < 0) return false;

        $length = 0;
        switch ($this->axisDir) {
            case 'horizontal': $length = $this->width;  break;
            case 'vertical':   $length = $this->height; break;
        }//endswitch

        if (20 > $this->zeroPos || $length - 20 < $this->zeroPos) return false;

        return true;
    }

    public function getAxisDir(): string { return $this->axisDir; }

    public function getWidth(): int { return $this->width; }

    public function getHeight(): int { return $this->height; }

    public function getPos(): array { return $this->pos; }

    public function getStart(): int|float { return $this->start; }

    public function getEnd(): int|float { return $this->end; }

    public function getZeroPosition(): float|int { return $this->zeroPos; }

    public function getZeroVisibility(): bool { return $this->zeroVis; }

    public function getFlip(): bool { return $this->flip; }
}
