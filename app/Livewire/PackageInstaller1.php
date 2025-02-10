<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use App\Models\Package;
use ZipArchive;

class PackageInstaller1 extends Component
{
    use WithFileUploads;

    public $zipFile;
    public $packageName;
    public $packages = [];

    protected $rules = [
        'zipFile' => 'required|file|mimes:zip',
    ];

    public function mount()
    {
        $this->packages = Package::all();
         // Define the path to the icon
    }

    public function uploadAndInstall()
    {
        $this->validate();

        // Store the uploaded ZIP file in a temporary location
        $zipPath = $this->zipFile->store('packages','public');
        
        // Define extraction path
        $extractTo = storage_path('app/temp-packages/' . now());
        
        $extractPath = base_path('packages/Custom/');

        if (!file_exists($extractPath)) {
            mkdir($extractPath, 0755, true); // Ensure the directory is created with correct permissions
        }
        
        // Capture folder list before extraction
        $beforeExtraction = array_diff(scandir($extractPath), ['.', '..']);

        // Extract the ZIP file
        $zip = new ZipArchive();
        
        if ($zip->open(public_path('storage/packages/' . basename($zipPath))) === true) {
            $path = base_path('packages/');
            $zip->extractTo($extractPath);
            $zip->close();
        } else {
            $this->addError('zipFile', 'Failed to extract the ZIP file.'.$zip->status );
            return;
        }

        // Capture folder list after extraction
        $afterExtraction = array_diff(scandir($extractPath), ['.', '..']);

        // Get the newly extracted folder(s)
        $newlyExtracted = array_values(array_diff($afterExtraction, $beforeExtraction));
        
        if (empty($newlyExtracted)) {
            $this->addError('zipFile', 'Extraction failed: No folder found inside the ZIP.');
            return;
        }

        // Assuming the package is in the first extracted folder
        $packageName = reset($newlyExtracted); 
        
        // Check for composer.json to determine the actual package name
        $composerJsonPath = $extractPath . '/' . $packageName . '/composer.json'; 
        if (!File::exists($composerJsonPath)) {
            $this->addError('zipFile', 'Invalid package. No composer.json file found.');
            return;
        }

        // Read package name from composer.json
        $composerData = json_decode(File::get($composerJsonPath), true); 
        if (!isset($composerData['name'])) {
            $this->addError('zipFile', 'Invalid composer.json. Package name not found.');
            return;
        }

        $packageNameWithVendor = $composerData['name'];
        $this->packageName = $packageNameWithVendor;
        
        // Update root composer.json
        $this->updateComposerJson($packageNameWithVendor);

        // Install the package using Composer
        $output = Process::run("composer require {$packageNameWithVendor}:@dev");
        if ($output->failed()) {
            $this->addError('zipFile', 'Composer installation failed: ' . $output->errorOutput());
            return;
        }

        // Save package details to the database
        Package::create([
            'name' => $packageName,
            'status' => 1,
        ]);

        // Refresh the list of packages
        $this->packages = Package::all();

        session()->flash('success', 'Package installed successfully!');
    }

    public function clonePackage()
    {
        if (!$this->packageUrl) {
            $this->output[] = "Please enter a valid GitHub URL.";
            return;
        }
        $repoName = basename(parse_url($this->packageUrl, PHP_URL_PATH), ".git");
        $clonePath = base_path("packages/Custom/{$repoName}");

        // Ensure 'packages/Custom' exists
        if (!File::exists(base_path("packages/Custom"))) {
            File::makeDirectory(base_path("packages/Custom"), 0755, true);
        }

        // Clone if it doesn't already exist
        if (File::exists("{$clonePath}/.git")) {
            Process::run("cd {$clonePath} && git pull");
            $this->output[] = "Updated existing repository: {$repoName}";
        } else {
            Process::run("git clone {$this->packageUrl} {$clonePath}");
            $this->output[] = "Cloned repository: {$repoName}";
        }

        $vendorAndPackageName = 'custom/'.strtolower($repoName);

        // Update composer.json
        $this->updateComposerJson($vendorAndPackageName);
        
        // Reload package list
        $this->loadPackages();
    }

    private function updateComposerJson($vendorAndPackageName)
    {
        $composerJsonPath = base_path('composer.json');
        if (File::exists($composerJsonPath)) {
            $composerData = json_decode(File::get($composerJsonPath), true);
            // Ensure "repositories" exists
            if (!isset($composerData['repositories'])) {
                $composerData['repositories'] = [];
            }
            // Add the repository path only if it does not already exist
            $existingPaths = array_column($composerData['repositories'], 'url');
            if (!in_array("packages/{$vendorAndPackageName}", $existingPaths)) {
                $composerData['repositories'][] = [
                    "type" => "path",
                    "url" => "packages/{$vendorAndPackageName}"
                ];
            }
            // Save back to composer.json
            File::put($composerJsonPath, json_encode($composerData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
    }

    public function render()
    {
        return view('livewire.package-installer');
    }
}
