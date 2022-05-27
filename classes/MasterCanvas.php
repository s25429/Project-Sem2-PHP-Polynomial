<?php
require_once 'ArgErrCheck.php';
require_once 'GraphCanvas.php';
require_once 'AxisCanvas.php';

class MasterCanvas extends ArgErrCheck
{
    private GraphCanvas $gCvs;      // Graph canvas class
    private AxisCanvas $xCvs;       // X axis canvas class
    private AxisCanvas $yCvs;       // Y axis canvas class
    private string $gCvsName;       // Graph JS canvas name
    private string $xCvsName;       // X axis JS canvas name
    private string $yCvsName;       // Y axis JS canvas name
    private array $xCvsNumerics;    // X axis JS numerics names
    private array $yCvsNumerics;    // Y axis JS numerics names

    public function __construct(Graph $graph, Axis $axisX, Axis $axisY, array $dataLine, array $dataAxis) {
        $this->argErrCheck($dataLine, $dataAxis);

        $this->gCvs = new GraphCanvas($graph);
        $this->xCvs = new AxisCanvas($axisX);
        $this->yCvs = new AxisCanvas($axisY);

        $this->gCvs->setStyles($dataLine['color'], $dataLine['style'], $dataLine['mass']);
        $this->xCvs->setStyles($dataAxis['color'], $dataAxis['mass']);
        $this->yCvs->setStyles($dataAxis['color'], $dataAxis['mass']);
    }

    protected function argErrCheck(...$data): bool|InvalidArgumentException {
        if (!parent::keysExist($data[0], 'color', 'style', 'mass') ||
            !parent::keysExist($data[1], 'color', 'mass')
        ) throw new InvalidArgumentException(parent::msg);
        return true;
    }

    public function draw(): void {
        $this->gCvs->draw($this->gCvsName);
        $this->xCvs->draw($this->xCvsName, $this->xCvsNumerics);
        $this->yCvs->draw($this->yCvsName, $this->yCvsNumerics);
    }

    public function getGraphCanvasName(): string { return $this->gCvsName; }

    public function setGraphCanvasName(string $canvas): void { $this->gCvsName = $canvas; }

    public function getXAxisCanvasName(): string { return $this->xCvsName; }

    public function setXAxisCanvasName(string $canvas): void { $this->xCvsName = $canvas; }

    public function getYAxisCanvasName(): string { return $this->yCvsName; }

    public function setYAxisCanvasName(string $canvas): void { $this->yCvsName = $canvas; }

    public function getXAxisNumericsNames(): array { return $this->xCvsNumerics; }

    public function setXAxisNumericsNames(array $array): void {
        $this->xCvsNumerics['start'] = $array[0];
        $this->xCvsNumerics['zero'] = $array[1];
        $this->xCvsNumerics['end'] = $array[2];
    }

    public function getYAxisNumericsNames(): array { return $this->yCvsNumerics; }

    public function setYAxisNumericsNames(array $array): void {
        $this->yCvsNumerics['start'] = $array[0];
        $this->yCvsNumerics['zero'] = $array[1];
        $this->yCvsNumerics['end'] = $array[2];
    }
}