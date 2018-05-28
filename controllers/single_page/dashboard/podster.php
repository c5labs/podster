<?php
/**
 * Google Analytics Dashboard Overview Page Controller.
 *
 * @author   Oliver Green <oliver@c5labs.com>
 * @license  See attached license file
 */
namespace Concrete\Package\Podster\Controller\SinglePage\Dashboard;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Page\Page;
use Core;
use Database;

class Podster extends DashboardPageController
{
    /**
     * Setup the view template.
     */
    public function view()
    {
        $this->set('pageTitle', t('Shows'));

        $app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();
        $ps = $app->make('helper/form/page_selector');
        $al = $app->make('helper/concrete/asset_library');
        $hp = $app->make('podster/helper');
        $st = $app->make('podster/repositories/stats');

        $this->set('st', $st);
        $this->set('ps', $ps);
        $this->set('al', $al);
        $this->set('hp', $hp);

        $this->set('shows' , $app->make('podster/repositories/shows')->all());
    }

    public function show_added()
    {
        $this->view();

        $this->set('flashMessage', t('Show added!'));
    }

    public function show_saved($showId)
    {
        $this->show($showId);

        $this->set('flashMessage', t('Show saved!'));
    }

    public function show_deleted()
    {
        $this->view();

        $this->set('flashMessage', t('Show deleted!'));
    }

    public function edit_show($showId)
    {
        if ($show = $this->app->make('podster/repositories/shows')->find($showId)) {
            $this->view();

            $this->set('show', $show);

            $this->set('pageTitle', 'Edit Show');

            return $this->render('dashboard/podster/edit_show');
        }

        $this->redirect('/dashboard/podster');
    }

    /**
     * Show the show episodes.
     */
    public function show($showId)
    {
        if ($show = $this->app->make('podster/repositories/shows')->find($showId)) {
            $this->view();

            $this->set('show', $show);

            $this->set('episodes' , $this->app->make('podster/repositories/episodes')->all($showId));

            $this->set('pageTitle', $show['title']);

            return $this->render('dashboard/podster/show');
        }

        $this->redirect('/dashboard/podster');
    }

    public function save_show()
    {
        if ($this->app->make('helper/validation/token')->validate('save_show')) {
            $data = $this->post();

            $requiredKeys = ['title', 'description', 'author', 'categories', 'coverFileID'];

            if (!($errors = $this->app->make('podster/helper')->validateNotEmpty($requiredKeys, $data))) {
                
                if (empty($data['showId'])) {
                    $this->app->make('podster/repositories/shows')->create($data);

                    $this->redirect('/dashboard/podster', 'show_added');
                } else {
                    $this->app->make('podster/repositories/shows')->update($data['showId'], $data);

                    $this->redirect('/dashboard/podster', 'show_saved', $data['showId']);
                }

            } else {
                foreach ($errors as $error) {
                    $this->error->add($error);    
                }
            }

        } else {
            $this->error->add($this->app->make('helper/validation/token')->getErrorMessage());
        }

        $this->view();
    }

    public function delete_show($showId)
    {
        if ($this->app->make('helper/validation/token')->validate('delete_show')) {
            $this->app->make('podster/repositories/shows')->delete($showId);

            $this->redirect('/dashboard/podster', 'show_deleted');
        } else {
            $this->error->add($this->app->make('helper/validation/token')->getErrorMessage());
        }

        $this->view();
    }

    public function episode_added($showId)
    {
        $this->show($showId);

        $this->set('flashMessage', t('Episode added!'));
    }

    public function episode_saved($showId)
    {
        $this->show($showId);

        $this->set('flashMessage', t('Episode saved!'));
    }

    public function episode_deleted($showId)
    {
        $this->show($showId);

        $this->set('flashMessage', t('Episode deleted!'));
    }

    public function edit_episode($episodeId = null)
    {
        if ($episode = $this->app->make('podster/repositories/episodes')->find($episodeId)) {
            $this->view();

            $this->set('episode', $episode);

            $this->set('pageTitle', 'Edit Episode');

            return $this->render('dashboard/podster/edit_episode');
        }

        $this->redirect('/dashboard/podster');
    }

    public function save_episode()
    {
        if ($this->app->make('helper/validation/token')->validate('save_episode')) {
            $data = $this->post();

            $requiredKeys = ['title', 'description', 'mp3FileID', 'duration', 'showID'];

            if (!($errors = $this->app->make('podster/helper')->validateNotEmpty($requiredKeys, $data))) {

                // If a episode link is not specified, set it to the same value as the shows.
                if ('page' === $data['linkType'] && empty($data['linkCID'])) {
                    $show = $this->app->make('podster/repositories/shows')->find($data['showID']);
                    $data['linkType'] = $show['linkType'];
                    $data['linkCID'] = $show['linkCID'];
                    $data['linkUrl'] = $show['linkUrl'];
                }

                if (empty($data['episodeID'])) {
                    $this->app->make('podster/repositories/episodes')->create($data);

                    $this->redirect('/dashboard/podster', 'episode_added', $data['showID']);
                } else {
                    $this->app->make('podster/repositories/episodes')->update($data['episodeID'], $data);

                    $this->redirect('/dashboard/podster', 'episode_saved', $data['showID']);
                }

            } else {
                foreach ($errors as $error) {
                    $this->error->add($error);    
                }
            }

        } else {
            $this->error->add($this->app->make('helper/validation/token')->getErrorMessage());
        }

        return $this->show($data['showID']);
    }

    public function delete_episode($episodeId)
    {
        if ($this->app->make('helper/validation/token')->validate('delete_episode')) {
            if ($episode = $this->app->make('podster/repositories/episodes')->find($episodeId)) {
                $this->app->make('podster/repositories/episodes')->delete($episodeId);

                $this->redirect('/dashboard/podster/episode_deleted', $episode['showID']);
            }
        } else {
            $this->error->add($this->app->make('helper/validation/token')->getErrorMessage());
        }

        $this->view();
    }

}
