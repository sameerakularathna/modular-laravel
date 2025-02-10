<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\PackageManager;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Request;
use Symfony\Component\Process\Exception\ProcessFailedException;


class PackageManager1 extends Component
{
    public $packages = [];
    public $selectedPackage;
    public $loading = false; 
    public $gitUrl;
    public $customPackages = [];


    protected $packageManager;

    // Inject the PackageManager service
    public function boot(PackageManager $packageManager)
    {
        $this->packageManager = $packageManager;
    }

    // Fetch all packages from the database
    public function loadPackages()
    {
        $this->packages = \DB::table('packages')->get();
    }

    // Install a package from a GitHub repository
    
    public function installPackage()
{
    $this->loading = true; // Set loading state for UI feedback

    try {
        $repoUrl = $this->gitUrl; // Get the GitHub repository URL from the input
        // dd($repoUrl);

        $packagePath = base_path("packages/"); // Define the path to clone the repository
        

        if (!is_dir($packagePath)) { 
            // Clone the repository if it doesn't already exist
            $process = new Process(["git", "clone", $repoUrl, $packagePath]); 
        } else { 
            // Pull the latest changes if the repository already exists
            $process = new Process(["git", "-C", $packagePath, "pull"]); 
        } 

        $process->run(); 

        if ($process->isSuccessful()) {
            // Success: Repository cloned or updated
            session()->flash('success', 'Package installed successfully.');
        } else {
            // Failure: Log the error output
            session()->flash('error', 'Failed to install package: ' . $process->getErrorOutput());
        }
    } catch (\Exception $e) {
        // Handle exceptions and return an error message
        session()->flash('error', 'API Error: ' . $e->getMessage());
    }

    $this->loading = false; // Reset loading state
}

    public function installPackage1()
    {
        try {
            // $repoUrl = $this->gitUrl;
            // dd($repoUrl);
            $repoName = basename(parse_url($this->gitUrl, PHP_URL_PATH), ".git");

            $packagePath = base_path("packages/Custom");
            // dd($packagePath);

            if (!is_dir($packagePath)) { 
                // Clone the repository if it doesn't already exist
                $process = new Process(['git', 'clone', $this->gitUrl, $packagePath]);
                $process->setTimeout(120); // Set timeout to 120 seconds
            }
            $process->run();
            
            if ($process->isSuccessful()) {
                // Success: Repository cloned or updated
                session()->flash('success', 'Package installed successfully.');
            } else {
                // Failure: Log the error output
                session()->flash('error', 'Failed to install package: ' . $process->getErrorOutput());
            }

        } catch (\Exception $e) {
            // Handle exceptions and return an error message
            session()->flash('error', 'API Error: ' . $e->getMessage());
        }
    }

    // Activate a package
    public function activatePackage($packageName)
    {
        if ($this->packageManager->activatePackage($packageName)) {
            session()->flash('success', 'Package activated successfully.');
        } else {
            session()->flash('error', 'Failed to activate package.');
        }
        $this->loadPackages();
    }

    // Deactivate a package
    public function deactivatePackage($packageName)
    {
        if ($this->packageManager->deactivatePackage($packageName)) {
            session()->flash('success', 'Package deactivated successfully.');
        } else {
            session()->flash('error', 'Failed to deactivate package.');
        }
        $this->loadPackages();
    }

    // Render the Livewire view
    public function render()
    {
        return view('livewire.package-manager1');
    }


public function mount()
{
    $this->loadPackages();
    $this->customPackages = $this->getCustomPackages();
}

protected function getCustomPackages()
    {
        $customPackagesPath = base_path('packages/Custom');
        $packages = [];

        if (is_dir($customPackagesPath)) {
            $directories = scandir($customPackagesPath);

            foreach ($directories as $directory) {
                $packagePath = $customPackagesPath . '/' . $directory;

                if (is_dir($packagePath) && file_exists($packagePath . '/composer.json')) {
                    $composerJson = json_decode(file_get_contents($packagePath . '/composer.json'), true);

                    if (isset($composerJson['name'])) {
                        $packages[] = [
                            'name' => $composerJson['name'],
                            'path' => $packagePath,
                            'version' => $composerJson['version'] ?? 'dev',
                        ];
                    }
                }
            }
        }

        return $packages;
    }

    public function installCustomPackageFromUI($packageName)
    {
        if ($this->packageManager->installCustomPackage($packageName)) {
            session()->flash('success', 'Custom package installed successfully.');
        } else {
            session()->flash('error', 'Failed to install custom package.');
        }

        $this->loadPackages();
    }


    


}