<?php
/**
 * Podster Package Controller File.
 *
 * @author   Oliver Green <oliver@c5labs.com>
 * @license  See attached license file
 */
namespace Concrete\Package\Podster;

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Foundation\Service\ProviderList;
use Concrete\Core\Package\Package;
use Concrete\Package\Podster\Src\EpisodeRepository;
use Concrete\Package\Podster\Src\PodsterHelper;
use Concrete\Package\Podster\Src\ShowRepository;
use Concrete\Package\Podster\Src\StatsRepository;
use Core;
use Database;
use Exception;
use Illuminate\Filesystem\Filesystem;

defined('C5_EXECUTE') or die('Access Denied.');

/**
 * Package Controller Class.
 *
 * Run a podcast from concrete5.
 *
 * @author   Oliver Green <oliver@c5labs.com>
 * @license  See attached license file
 */
class Controller extends Package
{
    /**
     * Minimum version of concrete5 required to use this package.
     * 
     * @var string
     */
    protected $appVersionRequired = '5.7.5';

    /**
     * Does the package provide a full content swap?
     * This feature is often used in theme packages to install 'sample' content on the site.
     *
     * @see https://goo.gl/C4m6BG
     * @var bool
     */
    protected $pkgAllowsFullContentSwap = false;

    /**
     * Does the package provide thumbnails of the files 
     * imported via the full content swap above?
     *
     * @see https://goo.gl/C4m6BG
     * @var bool
     */
    protected $pkgContentProvidesFileThumbnails = false;

    /**
     * Should we remove 'Src' from classes that are contained 
     * ithin the packages 'src/Concrete' directory automatically?
     *
     * '\Concrete\Package\MyPackage\Src\MyNamespace' becomes '\Concrete\Package\MyPackage\MyNamespace'
     *
     * @see https://goo.gl/4wyRtH
     * @var bool
     */
    protected $pkgAutoloaderMapCoreExtensions = false;

    /**
     * Package class autoloader registrations
     * The package install helper class, included with this boilerplate, 
     * is activated by default.
     *
     * @see https://goo.gl/4wyRtH
     * @var array
     */
    protected $pkgAutoloaderRegistries = [
        //'src/MyVendor/Statistics' => '\MyVendor\ConcreteStatistics'
    ];

    /**
     * The packages handle.
     * Note that this must be unique in the 
     * entire concrete5 package ecosystem.
     * 
     * @var string
     */
    protected $pkgHandle = 'podster';

    /**
     * The packages version.
     * 
     * @var string
     */
    protected $pkgVersion = '0.9.7';

    /**
     * The packages name.
     * 
     * @var string
     */
    protected $pkgName = 'Podster';

    /**
     * The packages description.
     * 
     * @var string
     */
    protected $pkgDescription = 'Super easy podcast publishing.';

    /**
     * The packages on start hook that is fired as the CMS is booting up.
     * 
     * @return void
     */
    public function on_start()
    {
        // Add custom logic here that needs to be executed during CMS boot, things
        // such as registering services, assets, etc.
        $this->registerRepositories();
        $this->registerHelper();
        (new \Concrete\Package\Podster\Src\FeedController($this->app))->registerRoute();
        (new \Concrete\Package\Podster\Src\EpisodeDownloadController($this->app))->registerRoute();
    }

    /**
     * The packages install routine.
     * 
     * @return \Concrete\Core\Package\Package
     */
    public function install()
    {
        $pkg = parent::install();

        BlockType::installBlockType('podster_player', $pkg);
        BlockType::installBlockType('podster_subscribe_button', $pkg);

        $this->installDashboardPages();

        return $pkg;
    }

    /**
     * The packages upgrade routine.
     * 
     * @return void
     */
    public function upgrade()
    {
        parent::upgrade();
    }

    /**
     * The packages uninstall routine.
     * 
     * @return void
     */
    public function uninstall()
    {
        parent::uninstall();

        try {
            $db = Database::get();
            $db->exec('DROP TABLE podsterShows;');
            $db->exec('DROP TABLE podsterEpisodes;');
            $db->exec('DROP TABLE podsterStats;');
            $db->exec('DROP TABLE btPodsterPlayer;');
            $db->exec('DROP TABLE btPodsterSubscribeButton;');
        } catch (Exception $ex) { /* Try dropping the tables */ }
    }

    public function getDashboardSettingsPageBasePath()
    {
        return '/dashboard/podster';
    }

    public function registerRepositories()
    {
        $this->app->singleton('podster/repositories/shows', function() {
            return new ShowRepository(Database::get());
        });

        $this->app->singleton('podster/repositories/episodes', function() {
            return new EpisodeRepository(Database::get());
        });

        $this->app->singleton('podster/repositories/stats', function() {
            return new StatsRepository(Database::get());
        });
    }

    public function registerHelper()
    {
        $this->app->singleton('podster/helper', function() {
            return new PodsterHelper($this->app);
        });
    }

    /**
     * Install the configuration page.
     */
    public function installDashboardPages()
    {
        $package = Package::getByHandle($this->getPackageHandle());

        // Base Page
        $sp = \Concrete\Core\Page\Single::add($this->getDashboardSettingsPageBasePath(), $package);
        $sp->update([
            'cName' => t('Podcasts'),
        ]);
    }
}
