<?php

/**
 * Cria a lista de projetos com suas paginas recursivamente
 *
 * @category   ZT
 * @package    Views
 * @subpackage Helpers
 * @author     Raniery Regis Ribeiro <raniery.rrr@gmail.com>
 */
class Zend_View_Helper_TreeViewProjectPage extends Zend_View_Helper_Abstract
    {

    public function TreeViewProjectPage($projectPages)
    {
        $html = $this->recursiveProjects($projectPages);
        echo '<ul class="nav nav-pills nav-stacked">' . $html . '</ul>';
    }

    private function recursiveProjects($projectPages)
    {
        $ulProject = '';

        foreach ($projectPages as $project) {
            $pagesHtml = '';
            $projectsHtml = '';


            if (!empty($project['pages'])) {
                $pagesHtml = $this->recursivePages($project['pages']);
            }
            if (!empty($project['children'])) {
                $projectsHtml = $this->recursiveProjects($project['children']);
            }


            if (!empty($pagesHtml)) {
                $pagesHtml = sprintf($pagesHtml, $projectsHtml);
                $ulProject .= '<li><a class="tree-toggler" href="#"> <i class="fa fa-leaf"></i> ' . $project['name'] . '</a></label>' . $pagesHtml . '</li>';
            } else {
                $ulProject .= '<li><a class="tree-toggler" href="#"> <i class="fa fa-leaf"></i> ' . $project['name'] . '</a></label>' . $projectsHtml . '</li>';
            }
        }

        return $ulProject;
    }

    /**
     * Monta o HTML da listagem das páginas
     *
     * @param $pages
     * @return string
     */
    private function recursivePages($pages)
    {
        $pagesHtml = '<ul class="nav nav-list tree">';
        foreach ($pages as $page) {

            $subPagesHtml = '';
            if (!empty($page['children']))
                $subPagesHtml = $this->recursivePages($page['children']);

            $url = $this->view->url(['module' => 'cms', 'controller' => 'page', 'action' => 'show', 'id' => $page['id']]);

            if (!empty($subPagesHtml))
                $pagesHtml .= '<li><a data-toggle="zt-modal" class="static-page" href="' . $url . '"> <i class="fa fa-book"></i> ' . $page['title'] . '</a></li>';
            else
                $pagesHtml .= '<li><a class="static-page" data-toggle="zt-modal" href="' . $url . '" class="tree-toggler"> <i class="fa fa-book"></i> ' . $page['title'] . '</a>' . $subPagesHtml . '</li>';
        }
        $pagesHtml .= '%s</ul>';
        return $pagesHtml;
    }

    }
