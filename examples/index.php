<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Emagister PHP Team Job Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Carlos Buenosvinos (carlos@emagister.com)">
    <meta http-equiv="refresh" content="10">

    <!-- Le styles -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px;
        }
    </style>
    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="bootstrap/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="bootstrap/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="bootstrap/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="bootstrap/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="bootstrap/ico/apple-touch-icon-57-precomposed.png">
</head>

<body>

<div class="container-fluid">
    <div class="row-fluid">
    <?php
        require '../library/Emagister/Jenkins/_autoload.php';
        use Emagister\Jenkins\Dashboard;
        use Emagister\Jenkins\Source;
        use Emagister\Jenkins\Job;

        $dashboard = new Dashboard();
        $dashboard->addSource(new Source('http://jenkins.php-devel.corp.emagister.com/jenkins/view/All/api/json/?depth=2'));
        // $dashboard->addSource(new Source('http://ci.emagister.es:8080/jenkins/view/All/api/json/?depth=3'));
        $jobs = $dashboard->getJobs();
        usort($jobs, "Emagister\\Jenkins\\Job::sort");

        $cols = 3;
        $n = count($jobs);

        $sectors = array();
        $sectors[] = array_slice($jobs, 0, $n / $cols);
        $sectors[] = array_slice($jobs, ($n / $cols), $n / $cols);
        $sectors[] = array_slice($jobs, 2 * ($n / $cols));

        foreach ($sectors as $sector) {
            ?>
            <div class="span4">
                <?php
                foreach($sector as $job) {
                    $lastBuild = $job->getLastBuild();
                    ?>
                    <div style="padding: 8px 8px 8px 8px" class="alert alert-<?php echo $job->getBootstrapStatus() ?>">

                        <?php if ($job->isInProgress()) { ?>
                        <div style="margin-bottom: 5px;" class="progress progress-<?php echo $job->getBootstrapProgressBarStatus() ?> progress-striped active">
                            <div class="bar" style="width: 100%;"></div>
                        </div>
                        <?php } ?>

                        <h4 class="alert-heading">
                            <?php echo $job->getName() ?>
                        </h4>

                        <?php if (null !== $lastBuild) { ?>
                        Build Id: <?php echo $lastBuild->getNumber() ?>

                        <?php
                        $revisions = $lastBuild->getChangeSet()->getRevisions();
                        if (!empty($revisions)) {
                            ?>
                            <?php foreach($lastBuild->getChangeSet()->getRevisions() as $revision) { ?>
                                Revision: <?php echo $revision->getRevision() ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>

                        <?php if (null !== $lastBuild && count($lastBuild->getAuthors()) > 0) { ?>
                            <p style="margin: 5px 0 0">
                            <?php foreach ($lastBuild->getAuthors() as $author) { ?>
                                <img src="<?php echo $author->getGravatar() ?>" title="<?php echo $author->getName() ?>" alt="<?php echo $author->getName() ?>" width="48" height="48" />
                            <?php } ?>
                            </p>
                        <?php } ?>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        }
    ?>
    </div> <!-- /row -->
</div> <!-- /container -->

<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!--
<script src="bootstrap/js/jquery.js"></script>
<script src="bootstrap/js/bootstrap-transition.js"></script>
<script src="bootstrap/js/bootstrap-alert.js"></script>
<script src="bootstrap/js/bootstrap-modal.js"></script>
<script src="bootstrap/js/bootstrap-dropdown.js"></script>
<script src="bootstrap/js/bootstrap-scrollspy.js"></script>
<script src="bootstrap/js/bootstrap-tab.js"></script>
<script src="bootstrap/js/bootstrap-tooltip.js"></script>
<script src="bootstrap/js/bootstrap-popover.js"></script>
<script src="bootstrap/js/bootstrap-button.js"></script>
<script src="bootstrap/js/bootstrap-collapse.js"></script>
<script src="bootstrap/js/bootstrap-carousel.js"></script>
<script src="bootstrap/js/bootstrap-typeahead.js"></script>
-->
</body>
</html>