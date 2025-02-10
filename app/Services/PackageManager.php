<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Models\Package;

class PackageManager
{
    /**
     * Install a package from a GitHub repository.
     *
     * @param string $repositoryUrl
     * @return bool
     */
    public function installPackage(string $repositoryUrl): bool
    {
       
        try {
            // Add the repository to Composer
            $process = new Process(['composer', 'config', 'repositories.custom', $repositoryUrl]);
            $process->mustRun();
            // Install the package
            $process = new Process(['composer', 'require', $repositoryUrl]);
            $process->mustRun();

            return true;
        } catch (ProcessFailedException $e) {
            report($e);
            return false;
        }
    }

    /**
     * Activate a package.
     *
     * @param string $packageName
     * @return bool
     */
    public function activatePackage(string $packageName): bool
    {
        // Check dependencies before activation
        if (!$this->checkDependencies($packageName)) {
            return false;
        }

        // Update the package status in the database
        DB::table('packages')
            ->where('name', $packageName)
            ->update(['status' => 'activated']);

        return true;
    }

    /**
     * Deactivate a package.
     *
     * @param string $packageName
     * @return bool
     */
    public function deactivatePackage(string $packageName): bool
    {
        DB::table('packages')
            ->where('name', $packageName)
            ->update(['status' => 'deactivated']);

        return true;
    }

    /**
     * Check if all dependencies for a package are installed and activated.
     *
     * @param string $packageName
     * @return bool
     */
    private function checkDependencies(string $packageName): bool
    {
        $package = DB::table('packages')->where('name', $packageName)->first();

        if ($package && $package->dependencies) {
            $dependencies = json_decode($package->dependencies, true);

            foreach ($dependencies as $dependency) {
                $depPackage = DB::table('packages')->where('name', $dependency)->first();
                if (!$depPackage || $depPackage->status !== 'activated') {
                    return false;
                }
            }
        }

        return true;
    }

    public function installCustomPackage($packageName)
    {
        try {
            // Define the repository path for the custom package
            $repositoryPath = "{$packageName}";

            $command = "composer require {$packageName}:@dev --no-update";

            // Execute the command
            exec($command, $output, $returnVar);

            Package::create([
                'name' => $packageName,
                'status' => 0
            ]);
    
            return true;
        } catch (ProcessFailedException $e) {
            report($e);
            dd($e->getMessage());
            return false;
        }
    }
}