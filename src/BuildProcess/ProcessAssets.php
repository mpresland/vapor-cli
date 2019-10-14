<?php

namespace Laravel\VaporCli\BuildProcess;

use Laravel\VaporCli\Path;
use Illuminate\Support\Str;
use Laravel\VaporCli\Helpers;
use Laravel\VaporCli\AssetFiles;
use Laravel\VaporCli\RewriteAssetUrls;
use Symfony\Component\Process\Process;

class ProcessAssets
{
    use ParticipatesInBuildProcess;

    /**
     * The asset base URL.
     */
    protected $assetUrl;

    /**
     * Create a new project builder.
     *
     * @param  string|null  $assetUrl
     * @return void
     */
    public function __construct($assetUrl = null)
    {
        $this->assetUrl = $assetUrl;

        $this->appPath = Path::app();
        $this->path = Path::current();
        $this->vaporPath = Path::vapor();
        $this->buildPath = Path::build();
    }

    /**
     * Execute the build process step.
     *
     * @return void
     */
    public function __invoke()
    {
        Helpers::step('<bright>Processing Assets</>');

        foreach (AssetFiles::get($this->appPath.'/public') as $file) {
            if (! Str::endsWith($file->getRealPath(), '.css')
                || !Str::endsWith($file->getRealPath(), '.js')) {
                continue;
            }

            if (Str::endsWith($file->getRealPath(), '.css')) {
                file_put_contents(
                    $file->getRealPath(),
                    RewriteAssetUrls::inCssString(file_get_contents($file->getRealPath()), $this->assetUrl)
                );
            }

            if (Str::endsWith($file->getRealPath(), '.js')) {
                file_put_contents(
                    $file->getRealPath(),
                    RewriteAssetUrls::inJsString(file_get_contents($file->getRealPath()), $this->assetUrl)
                );
            }
        }
    }
}
