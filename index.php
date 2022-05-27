<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Wykres wielomianu trzeciego stopnia</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>

<?php
require_once './classes/Data.php';
require_once './classes/Polynomial.php';
require_once './classes/Graph.php';
require_once './classes/Axis.php';
require_once './classes/MasterCanvas.php';

// Stores data from given form or from default values
$data = new Data();

$polynomial = new Polynomial($data->screen['range'], $data->pol);
$graph = new Graph($polynomial, $data->screen['size'], $data->line['flip']);
$axisX = new Axis('horizontal',
    array_merge($graph->getScreenSize(), ['height'=>$data->axis['height']]),
    $polynomial->getRange(),
    $graph->getCenter()[0],
    $graph->getFlip()['x']
);
$axisY = new Axis('vertical',
    array_merge($graph->getScreenSize(), ['width'=>$data->axis['width']]),
    $polynomial->getRange(),
    $graph->getCenter()[1],
    $graph->getFlip()['y']
);

// Master class to manage all canvas classes using graph, xAxis and yAxis
$masterCanvas = new MasterCanvas($graph, $axisX, $axisY, $data->line, $data->axis);
?>

<section id="screen">
    <div id="y-nums" style="
            width: <?php echo $axisY->getWidth(); ?>px;
            height: <?php echo $axisY->getHeight() + 3; ?>px;
         ">
        <span class="top-num">top</span>
        <span class="mid-num" style="
                visibility: <?php echo $axisY->getZeroVisibility() ? 'vibible' : 'hidden'; ?>;
              ">0</span>
        <span class="bot-num">bottom</span>
    </div>
    <canvas id="y-units"
            width="<?php echo $axisY->getWidth(); ?>"
            height="<?php echo $axisY->getHeight() + 4; ?>"
    ></canvas>
    <canvas id="graph"
            width="<?php echo $graph->getScreenSize()['width'] ?>"
            height="<?php echo $graph->getScreenSize()['height'] ?>"
    ></canvas>
    <canvas id="x-units"
            width="<?php echo $axisX->getWidth() + 4; ?>"
            height="<?php echo $axisX->getHeight(); ?>"
    ></canvas>
    <div id="x-nums" style="
            width: <?php echo $axisX->getWidth() + 3; ?>px;
            height: <?php echo $axisX->getHeight(); ?>px;
         ">
        <span class="top-num">left</span>
        <span class="mid-num" style="
                visibility: <?php echo $axisX->getZeroVisibility() ? 'vibible' : 'hidden' ?>;
              ">0</span>
        <span class="bot-num">right</span>
    </div>
</section>

<form action="" method="post">
    <section id="polynomial">
        <fieldset class="left">
            <legend>Values</legend>

            <label for="x3">x<sup>3</sup> = </label>
            <input type="number" id="x3" name="x3" step="any" placeholder="1"
                   value="<?php echo $data->pol[3] ?>" required
            >

            <label for="x2">x<sup>2</sup> = </label>
            <input type="number" id="x2" name="x2" step="any" placeholder="-1"
                   value="<?php echo $data->pol[2] ?>" required
            >

            <label for="x1">x<sup>1</sup> = </label>
            <input type="number" id="x1" name="x1" step="any" placeholder="1"
                   value="<?php echo $data->pol[1] ?>" required
            >

            <label for="x0">x<sup>0</sup> = </label>
            <input type="number" id="x0" name="x0" step="any" placeholder="0"
                   value="<?php echo $data->pol[0] ?>" required
            >
        </fieldset>
        <fieldset class="right">
            <legend>Styling</legend>

            <label for="line-color">Color</label>
            <select name="line-color" id="line-color">
                <?php foreach (['red', 'green', 'blue', 'black'] as $color) {
                    echo "<option value='$color' ".($color == $data->line['color'] ? 'selected="selected"' : '').">".ucfirst($color)."</option>";
                } ?>
            </select>

            <label for="line-style">Style</label>
            <select name="line-style" id="line-style">
                <?php foreach (['line', 'dotted'] as $style) {
                    echo "<option value='$style' ".($style == $data->line['style'] ? 'selected="selected"' : '').">".ucfirst($style)."</option>";
                } ?>
            </select>

            <label for="line-size">Size</label>
            <input type="number" name="line-size" id="line-size" step="0.5"
                   min="0.5" max="10" placeholder="1"
                   value="<?php echo $data->line['mass'] ?>" required
            >

            <label for="line-flip">Flip Axis</label>
            <select name="line-flip" id="line-flip">
                <option value="null">No</option>
                <option value="x">X</option>
                <option value="y">Y</option>
                <option value="both">Both</option>
            </select>
        </fieldset>
    </section>
    <section id="details">
        <div class="range">
            <label for="left-x">Range of X axis <abbr class="hint" title="If a wrong range will be given, like [1,-1] then default range of [-1,1] will be set.">i</abbr></label><br>
            <b>[</b>
            <input type="number" name="left-x" id="left-x" step="any" placeholder="-1"
                   value="<?php echo $data->screen['range']['left'] ?>" required
            >
            <b>, </b>
            <input type="number" name="right-x" id="right-x" step="any" placeholder="1"
                   value="<?php echo $data->screen['range']['right'] ?>" required
            >
            <b>]</b>
        </div>

        <div class="size">
            <label for="screen-width">Size of canvas</label><br>
            <b>[</b>
            <input type="number" name="screen-width" id="screen-width"
                   min="100" max="1000" placeholder="500"
                   value="<?php echo $data->screen['size']['width'] ?>" required
            >
            <b>, </b>
            <input type="number" name="screen-height" id="screen-height"
                   min="50" max="800" placeholder="300"
                   value="<?php echo $data->screen['size']['height'] ?>" required
            >
            <b>]</b>
        </div>

        <input type="submit" value="Draw Polynomial">
    </section>
</form>
<p style="text-align: center; margin-top: 0;">&copy; Made by Cezary Ciślak</p>

<script>
    const cvsGraph = document.querySelector("#graph").getContext("2d");
    const cvsX =     document.querySelector("#x-units").getContext("2d");
    const cvsY =     document.querySelector("#y-units").getContext("2d");

    const topX = document.querySelector("#x-nums .top-num");
    const midX = document.querySelector("#x-nums .mid-num");
    const botX = document.querySelector("#x-nums .bot-num");

    const topY = document.querySelector("#y-nums .top-num");
    const midY = document.querySelector("#y-nums .mid-num");
    const botY = document.querySelector("#y-nums .bot-num");

    <?php
    // Need to be given names of document elements that were given in JavaScript,
    // as masterCanvas uses Canvas methods to draw on screen.

    $masterCanvas->setGraphCanvasName('cvsGraph');
    $masterCanvas->setXAxisCanvasName('cvsX');
    $masterCanvas->setYAxisCanvasName('cvsY');
    $masterCanvas->setXAxisNumericsNames(['topX', 'midX', 'botX']);
    $masterCanvas->setYAxisNumericsNames(['botY', 'midY', 'topY']);

    $masterCanvas->draw();
    ?>
</script>

</body>
</html>