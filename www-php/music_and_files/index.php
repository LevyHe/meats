<?php
    $title = basename(dirname($_SERVER['PHP_SELF']));
    // mp3 and m4a, case insensitive (false positives: mpa, m43)
    $files = glob('*.[Mm][Pp4][Aa3]');
    natsort($files);

    $crumbs = array();
    $path = '';
    foreach(explode('/', dirname($_SERVER['SCRIPT_NAME'])) as $dir)
    {
        $path .= "$dir/";
        $crumbs[$path] = $dir;
    }

    $other_files = array_values(array_diff(glob('*'), $files + ['index.php']));
    unset($other_files[array_search('index.php', $other_files)]);

    $descriptions = array();
    foreach($files as  $i => $music_file)
    {
        if (file_exists(basename("$music_file", "mp3") . "txt"))
        {
            $descriptions[$i] = file_get_contents(basename("$music_file", "mp3") . "txt");
        }
    }
?><!doctype html>
<html lang="en">
  <head>
    <title><?php echo $title ?> - Music Index</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
<?php if(file_exists('bootstrap.min.css')): ?>
    <link rel="stylesheet" href="bootstrap.min.css">
<?php else: ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
<?php endif ?>
    <style>
    .container {
        width: auto;
        max-width: 1280px;
        padding: 0 15px;
    }
    </style>
  </head>

  <body>
    <div class="container">
        <div class="jumbotron d-none d-md-block">
            <h1><?php echo $title ?></h1>
            <p class="lead" title="v2.0-2020.09.29">Showing MP3s as HTML5 audio.</p>
        </div>

        <div class="page-header d-block d-md-none">
            <h1><?php echo $title ?></h1>
        </div>

        <div class="row">
            <div class="col-12">
                <p>
                    Path:
<?php foreach($crumbs as $path => $dir): ?>
                    / <a href="<?php echo $path ?>"><?php echo $dir ?: '&#8962;'  ?></a>
<?php endforeach ?>
                </p>
            </div>

            <div class="col-12">
            <form>
                <p>
                    Loop:
                    <input type="radio" name="loop" id="loop_none" checked>
                    <label for="loop_none">None</label>
                    <input type="radio" name="loop" id="loop_one">
                    <label for="loop_one">One</label>
                    <input type="radio" name="loop" id="loop_all">
                    <label for="loop_all">All</label>
                </p>
            </form>
            </div>
        </div>

        <div class="row">
<?php foreach($files as $i => $music_file): ?>
            <div class="col-sm-6 col-md-4 col-lg-3 py-3">
                <p><?php echo $i + 1 ?>. <a href="<?php echo $music_file ?>"><?php echo $music_file ?></a></p>
<?php if (isset($descriptions[$i])): ?>
                <p class="small"><?php echo $descriptions[$i] ?></p>
<?php endif ?>
                <audio controls="controls" preload="none" id="a_<?php echo $i + 1 ?>">
                    <source src="<?php echo $music_file ?>" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
            </div>
<?php endforeach ?>

<?php if($other_files): ?>
            <div class="col-12">
                <h2>Files</h2>
            </div>
    <?php foreach($other_files as $i => $file): ?>
            <div class="col-sm-12 col-md-6 col-lg-4 o-hidden">
                <p><?php echo $i + 1; ?>.&nbsp;<a href="<?php echo $file ?>"><?php echo $file ?></a></p>
            </div>
    <?php endforeach ?>
<?php endif ?>

<?php if(count($files) > 10): ?>
    <div class="col-12">
                <p>
                    Path:
<?php foreach($crumbs as $path => $dir): ?>
                    / <a href="<?php echo $path ?>"><?php echo $dir ?: '&#8962;'  ?></a>
<?php endforeach ?>
                </p>
            </div>
<?php endif ?>
        </div>
    </div>

    <script>
    /* https://stackoverflow.com/questions/2551859/html-5-video-or-audio-playlist */
    const n = <?php echo count($files) ?>;
    const audios = document.getElementsByTagName('audio');
    function make_onended(i)
    {
        return function() {
            if ( document.getElementById('loop_one').checked ) {
                audios[i].play();
            } else if ( document.getElementById('loop_all').checked ) {
                audios[(i+1)%n].play()
            }
        }
    }
    for (let i=0; i<n; ++i)
    {
        audios[i].onended = make_onended(i);
    }
    </script>
  </body>
</html>
