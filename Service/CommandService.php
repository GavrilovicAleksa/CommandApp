<?php

class CommandService
{
    public function sortCommandsByDependency($commands)
    {
        if (empty($commands)) {
            echo "Mandatory parameter missing";
        }
        $sortedCommands = [];
        $dependencyQueue = [];

        // Check until we have all of the tasks
        while (count($commands) > count($sortedCommands)) {

            foreach ($commands as $command) {
                // Already in the queue move on
                if (isset($dependencyQueue[$command["name"]])) {
                    continue;
                }

                $resolved = true;
                if (isset($command["dependencies"])) {
                    foreach($command["dependencies"] as $dependency) {
                        if (!isset($dependencyQueue[$dependency])) {
                            $resolved = false;
                        }
                    }
                }
                if ($resolved === true) {
                    $sortedCommands[] = $command;
                    $dependencyQueue[$command["name"]] = 1;
                }
            }
        }
        return $sortedCommands;
    }

    public function writeCommandsToFile($file, $content)
    {
        if (empty($file) || empty($content)) {
            throw new \InvalidArgumentException("Mandatory parameter missing");
        }
        if (file_exists("doc/" . $file)) {
            throw new \InvalidArgumentException("Sorry can't write to that file");
        }

        $handle = fopen("doc/" . $file, "w");
        fwrite($handle, $content);
        fclose($handle);
    }

    public function buildCommandFileContent($commands)
    {
        $content = "";
        foreach ($commands as $command) {
            $content .= $command["command"] . "\n";
        }
        return $content;
    }
}