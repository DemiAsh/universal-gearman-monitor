<?php

/* layout.twig */
class __TwigTemplate_efe4c324725b63f9bc2b9a2e160cb75f extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'head' => array($this, 'block_head'),
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
\t<head>
\t\t";
        // line 4
        $this->displayBlock('head', $context, $blocks);
        // line 12
        echo "\t</head>
\t<body>
\t\t<div class=\"navbar navbar-static-top\">
\t\t\t<div class=\"navbar-inner\">
\t\t\t\t<a class=\"brand\" href=\"/\"><img src=\"themes/media/img/cogwheels.png\" /> Gearman Status</a>
\t\t\t</div>
\t\t</div>
\t\t<div class=\"container-fluid\">

\t\t\t";
        // line 21
        $this->displayBlock('content', $context, $blocks);
        // line 22
        echo "
\t\t\t<div id=\"footer\">
\t\t\t\t<img src=\"http://piwik.nosfire.ru/piwik.php?idsite=1&amp;rec=1\" style=\"border:0\" alt=\"\" />
\t\t\t</div>
\t\t</div>
\t</body>
</html>
";
    }

    // line 4
    public function block_head($context, array $blocks = array())
    {
        // line 5
        echo "\t\t\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
\t\t\t<title>";
        // line 6
        $this->displayBlock('title', $context, $blocks);
        echo " - GearmanStatus</title>
\t\t\t<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
\t\t\t<link href=\"themes/media/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\" />
\t\t\t<link href=\"themes/media/css/bootstrap-responsive.min.css\" rel=\"stylesheet\" type=\"text/css\" />
\t\t\t<link href=\"themes/media/css/style.css\" rel=\"stylesheet\" type=\"text/css\" />
\t\t";
    }

    public function block_title($context, array $blocks = array())
    {
    }

    // line 21
    public function block_content($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "layout.twig";
    }

    public function getDebugInfo()
    {
        return array (  73 => 21,  59 => 6,  56 => 5,  53 => 4,  42 => 22,  40 => 21,  27 => 4,  22 => 1,  38 => 5,  35 => 4,  29 => 12,);
    }
}
