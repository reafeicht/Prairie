<?php

namespace Prairie;

use Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;
use Prairie\Console\Commands\PrairieBootcampCommand;
use Prairie\Console\Commands\PrairieInstallCommand;
use Prairie\View\Components\Accordion;
use Prairie\View\Components\Alert;
use Prairie\View\Components\Avatar;
use Prairie\View\Components\Badge;
use Prairie\View\Components\Breadcrumbs;
use Prairie\View\Components\Button;
use Prairie\View\Components\Calendar;
use Prairie\View\Components\Card;
use Prairie\View\Components\Carousel;
use Prairie\View\Components\Chart;
use Prairie\View\Components\Checkbox;
use Prairie\View\Components\Choices;
use Prairie\View\Components\ChoicesOffline;
use Prairie\View\Components\Code;
use Prairie\View\Components\Collapse;
use Prairie\View\Components\Colorpicker;
use Prairie\View\Components\DatePicker;
use Prairie\View\Components\DateTime;
use Prairie\View\Components\Diff;
use Prairie\View\Components\Drawer;
use Prairie\View\Components\Dropdown;
use Prairie\View\Components\Editor;
use Prairie\View\Components\Errors;
use Prairie\View\Components\File;
use Prairie\View\Components\Form;
use Prairie\View\Components\Group;
use Prairie\View\Components\Header;
use Prairie\View\Components\Hr;
use Prairie\View\Components\Icon;
use Prairie\View\Components\ImageGallery;
use Prairie\View\Components\ImageLibrary;
use Prairie\View\Components\Input;
use Prairie\View\Components\Kbd;
use Prairie\View\Components\ListItem;
use Prairie\View\Components\Loading;
use Prairie\View\Components\Main;
use Prairie\View\Components\Markdown;
use Prairie\View\Components\Menu;
use Prairie\View\Components\MenuItem;
use Prairie\View\Components\MenuSeparator;
use Prairie\View\Components\MenuSub;
use Prairie\View\Components\MenuTitle;
use Prairie\View\Components\Modal;
use Prairie\View\Components\Nav;
use Prairie\View\Components\Pagination;
use Prairie\View\Components\Password;
use Prairie\View\Components\Pin;
use Prairie\View\Components\Popover;
use Prairie\View\Components\Progress;
use Prairie\View\Components\ProgressRadial;
use Prairie\View\Components\Radio;
use Prairie\View\Components\Range;
use Prairie\View\Components\Rating;
use Prairie\View\Components\Select;
use Prairie\View\Components\SelectGroup;
use Prairie\View\Components\Signature;
use Prairie\View\Components\Spotlight;
use Prairie\View\Components\Stat;
use Prairie\View\Components\Step;
use Prairie\View\Components\Steps;
use Prairie\View\Components\Swap;
use Prairie\View\Components\Tab;
use Prairie\View\Components\Table;
use Prairie\View\Components\Tabs;
use Prairie\View\Components\Tags;
use Prairie\View\Components\Textarea;
use Prairie\View\Components\ThemeToggle;
use Prairie\View\Components\TimelineItem;
use Prairie\View\Components\Toast;
use Prairie\View\Components\Toggle;

class PrairieServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot(): void
    {
        $this->loadBootstrapFromCache();

        $this->registerComponents();
        $this->registerBladeDirectives();

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    public function registerComponents()
    {
        // Just rename <x-icon> provided by BladeUI Icons to <x-svg> to not collide with ours
        Blade::component('BladeUI\Icons\Components\Icon', 'svg');

        // No matter if components has custom prefix or not,
        // we also register below alias to avoid naming collision,
        // because they are used inside some Prairie's components itself.
        Blade::component('prairie-button', Button::class);
        Blade::component('prairie-card', Card::class);
        Blade::component('prairie-badge', Badge::class);
        Blade::component('prairie-icon', Icon::class);
        Blade::component('prairie-input', Input::class);
        Blade::component('prairie-list-item', ListItem::class);
        Blade::component('prairie-modal', Modal::class);
        Blade::component('prairie-menu', Menu::class);
        Blade::component('prairie-menu-item', MenuItem::class);
        Blade::component('prairie-header', Header::class);
        Blade::component('prairie-pagination', Pagination::class);
        Blade::component('prairie-popover', Popover::class);

        $prefix = config('prairie.prefix');

        // Blade
        Blade::component($prefix . 'accordion', Accordion::class);
        Blade::component($prefix . 'alert', Alert::class);
        Blade::component($prefix . 'avatar', Avatar::class);
        Blade::component($prefix . 'badge', Badge::class);
        Blade::component($prefix . 'breadcrumbs', Breadcrumbs::class);
        Blade::component($prefix . 'button', Button::class);
        Blade::component($prefix . 'calendar', Calendar::class);
        Blade::component($prefix . 'card', Card::class);
        Blade::component($prefix . 'chart', Chart::class);
        Blade::component($prefix . 'checkbox', Checkbox::class);
        Blade::component($prefix . 'choices', Choices::class);
        Blade::component($prefix . 'choices-offline', ChoicesOffline::class);
        Blade::component($prefix . 'code', Code::class);
        Blade::component($prefix . 'collapse', Collapse::class);
        Blade::component($prefix . 'colorpicker', Colorpicker::class);
        Blade::component($prefix . 'datepicker', DatePicker::class);
        Blade::component($prefix . 'datetime', DateTime::class);
        Blade::component($prefix . 'diff', Diff::class);
        Blade::component($prefix . 'drawer', Drawer::class);
        Blade::component($prefix . 'dropdown', Dropdown::class);
        Blade::component($prefix . 'editor', Editor::class);
        Blade::component($prefix . 'errors', Errors::class);
        Blade::component($prefix . 'file', File::class);
        Blade::component($prefix . 'form', Form::class);
        Blade::component($prefix . 'select-group', SelectGroup::class);
        Blade::component($prefix . 'header', Header::class);
        Blade::component($prefix . 'hr', Hr::class);
        Blade::component($prefix . 'icon', Icon::class);
        Blade::component($prefix . 'image-gallery', ImageGallery::class);
        Blade::component($prefix . 'image-library', ImageLibrary::class);
        Blade::component($prefix . 'input', Input::class);
        Blade::component($prefix . 'kbd', Kbd::class);
        Blade::component($prefix . 'list-item', ListItem::class);
        Blade::component($prefix . 'loading', Loading::class);
        Blade::component($prefix . 'markdown', Markdown::class);
        Blade::component($prefix . 'modal', Modal::class);
        Blade::component($prefix . 'menu', Menu::class);
        Blade::component($prefix . 'menu-item', MenuItem::class);
        Blade::component($prefix . 'menu-separator', MenuSeparator::class);
        Blade::component($prefix . 'menu-sub', MenuSub::class);
        Blade::component($prefix . 'menu-title', MenuTitle::class);
        Blade::component($prefix . 'main', Main::class);
        Blade::component($prefix . 'nav', Nav::class);
        Blade::component($prefix . 'pagination', Pagination::class);
        Blade::component($prefix . 'password', Password::class);
        Blade::component($prefix . 'pin', Pin::class);
        Blade::component($prefix . 'popover', Popover::class);
        Blade::component($prefix . 'progress', Progress::class);
        Blade::component($prefix . 'progress-radial', ProgressRadial::class);
        Blade::component($prefix . 'radio', Radio::class);
        Blade::component($prefix . 'group', Group::class);
        Blade::component($prefix . 'range', Range::class);
        Blade::component($prefix . 'rating', Rating::class);
        Blade::component($prefix . 'select', Select::class);
        Blade::component($prefix . 'signature', Signature::class);
        Blade::component($prefix . 'spotlight', Spotlight::class);
        Blade::component($prefix . 'stat', Stat::class);
        Blade::component($prefix . 'steps', Steps::class);
        Blade::component($prefix . 'step', Step::class);
        Blade::component($prefix . 'swap', Swap::class);
        Blade::component($prefix . 'table', Table::class);
        Blade::component($prefix . 'tab', Tab::class);
        Blade::component($prefix . 'tabs', Tabs::class);
        Blade::component($prefix . 'tags', Tags::class);
        Blade::component($prefix . 'textarea', Textarea::class);
        Blade::component($prefix . 'timeline-item', TimelineItem::class);
        Blade::component($prefix . 'theme-toggle', ThemeToggle::class);
        Blade::component($prefix . 'toast', Toast::class);
        Blade::component($prefix . 'toggle', Toggle::class);
        Blade::component($prefix . 'carousel', Carousel::class);
    }

    public function registerBladeDirectives(): void
    {
        $this->registerScopeDirective();
    }

    public function registerScopeDirective(): void
    {
        /**
         * All credits from this blade directive goes to Konrad Kalemba.
         * Just copied and modified for my very specific use case.
         *
         * https://github.com/konradkalemba/blade-components-scoped-slots
         */
        Blade::directive('scope', function ($expression) {
            // Split the expression by `top-level` commas (not in parentheses)
            $directiveArguments = preg_split("/,(?![^\(\(]*[\)\)])/", $expression);
            $directiveArguments = array_map('trim', $directiveArguments);

            [$name, $functionArguments] = $directiveArguments;

            // Build function "uses" to inject extra external variables
            $uses = Arr::except(array_flip($directiveArguments), [$name, $functionArguments]);
            $uses = array_flip($uses);
            array_push($uses, '$__env');
            array_push($uses, '$__bladeCompiler');
            $uses = implode(',', $uses);

            /**
             *  Slot names can`t contains dot , eg: `user.city`.
             *  So we convert `user.city` to `user___city`
             *
             *  Later, on component it will be replaced back.
             */
            $name = str_replace('.', '___', $name);

            return "<?php \$__bladeCompiler = \$__bladeCompiler ?? null; \$loop = null; \$__env->slot({$name}, function({$functionArguments}) use ({$uses}) { \$loop = (object) \$__env->getLoopStack()[0] ?>";
        });

        Blade::directive('endscope', function () {
            return '<?php }); ?>';
        });
    }

    /**
     * Register any package services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/prairie.php', 'prairie');

        // Register the service the package provides.
        $this->runInitialLibOnArtisanServe();
        $this->loadBootstrapFromCache();

        $this->app->singleton('prairie', function ($app) {
            $data = config('prairie.bootstrap_data');

            return new Prairie(is_array($data) ? $data : []);
        });
    }

    protected function bootstrapCachePath(): string
    {
        $relative = config('prairie.bootstrap_cache', 'framework/prairie-bootstrap.json');

        return storage_path($relative);
    }

    protected function loadBootstrapFromCache(): void
    {
        $path = $this->bootstrapCachePath();

        if (! is_file($path)) {
            return;
        }

        $decoded = json_decode((string) file_get_contents($path), true);

        if (! is_array($decoded)) {
            return;
        }

        config(['prairie.bootstrap_data' => $decoded]);
    }

    protected function runInitialLibOnArtisanServe(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        if (! config('prairie.run_libs_on_serve', true)) {
            return;
        }

        $argv = $_SERVER['argv'] ?? [];

        if (($argv[1] ?? null) !== 'serve') {
            return;
        }

        $path = realpath(__DIR__ . '/../libs/index.js');

        if ($path === false || ! is_file($path)) {
            return;
        }

        $node = (new ExecutableFinder())->find('node');

        if ($node === null) {
            return;
        }

        $env = array_filter([
            'NODE_ENV' => $this->app->environment(),
            'APP_KEY' => config('app.key'),
            'PRAIRIE_ROOT' => $this->app->basePath(),
            'PRAIRIE_BOOTSTRAP_CACHE' => $this->bootstrapCachePath(),
        ], fn ($value) => $value !== null && $value !== '');

        (new Process([$node, $path], $this->app->basePath(), $env))->run(function (string $type, string $buffer): void {
            fwrite($type === Process::ERR ? STDERR : STDOUT, $buffer);
        });

        $this->loadBootstrapFromCache();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['prairie'];
    }

    /**
     * Console-specific booting.
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/prairie.php' => config_path('prairie.php'),
        ], 'prairie.config');

        $this->commands([PrairieInstallCommand::class, PrairieBootcampCommand::class]);
    }
}
