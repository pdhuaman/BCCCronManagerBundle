<?php

namespace BCC\CronManagerBundle\Twig;

class TwigExtension extends \Twig\Extension\AbstractExtension implements \Twig\Extension\GlobalsInterface
{
    /**
     * @var array
     */
    protected $wwwUser;

    /**
     * @var string
     */
    protected $logDir;

    /**
     * @var string
     */
    protected $symfonyCommand;

    public function __construct($logDir, $projectDir)
    {
        if (function_exists('posix_getpwuid')) {
            $this->wwwUser = posix_getpwuid(posix_geteuid());
        }
        else {
            $this->wwwUser = array(
                'name' => get_current_user(),
                'dir'  => '-',
            );
        }
        $this->logDir = $logDir;
        $this->symfonyCommand = 'php '.$projectDir.'/bin/console';
    }
    
    public function getGlobals() : array
    {
        return [
            'wwwUser'        => $this->wwwUser,
            'logDir'         => $this->logDir,
            'symfonyCommand' => $this->symfonyCommand,
        ];
    }

    public function getName() {
        return 'bcc-cron-manager';
    }
}
