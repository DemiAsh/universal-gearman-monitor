<?php

/* error.twig */
class __TwigTemplate_743c6a9b5738e3d8e35bd84088cb7f63 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("layout.twig");

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_title($context, array $blocks = array())
    {
        echo "Oops! Something went wrong!";
    }

    // line 4
    public function block_content($context, array $blocks = array())
    {
        // line 5
        echo "\t<div class=\"row-fluid\">
\t\t<div class=\"span8 offset2\">
\t\t\t<div class=\"hero-unit\">
\t\t\t\t<h1>Houston, we have a problem!</h1>
\t\t\t\t<br />
\t\t\t\t<p>
\t\t\t\t\tCan not connect to Gearmand or Gearmand not responding.
\t\t\t\t</p>
\t\t\t</div>
\t\t</div>
\t</div>
";
    }

    public function getTemplateName()
    {
        return "error.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  38 => 5,  35 => 4,  29 => 2,);
    }
}
