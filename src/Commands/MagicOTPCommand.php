<?php

namespace Mkdev\LaravelAdvancedOTP\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MagicOTPCommand extends Command
{
    // The name and signature of the console command
    protected $signature = 'magic-otp:make {name}';

    // The console command description
    protected $description = 'Create a new OTP class';

    // Filesystem instance to interact with the file system
    protected Filesystem $files;

    // Constructor
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    // Execute the console command
    public function handle()
    {
        $name = $this->argument('name');  // Get the class name from command argument
        $path = app_path("OTP/{$name}.php");  // Set the file path

        if ($this->files->exists($path)) {
            $this->error("OTP class {$name} already exists!");
            return;
        }

        // Create the OTP class file
        $stub = $this->getStub();
        $stub = str_replace('{{ class }}', $name, $stub);

        $this->files->ensureDirectoryExists(app_path('OTP'));
        $this->files->put($path, $stub);

        $this->info("OTP class {$name} created successfully at {$path}");
    }

    // Get the stub for the OTP class
    protected function getStub()
    {
        return <<<EOT
<?php

namespace App\OTP;

use Mkdev\LaravelAdvancedOTP\MagicOTP;

class {{ class }} extends MagicOTP
{
    protected int \$timeout = 120;  // Timeout in seconds
    protected int \$otpLength = 5;  // Length of the OTP

    public function send()
    {
        // Logic to send OTP
    }

    public function validate(\$otp)
    {
        // Logic to validate OTP
    }
}
EOT;
    }
}
