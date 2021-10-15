<?php

require_once "./Service/CommandService.php";
require_once "./Request/RouterRequest.php";

class CommandController
{
    private $commandService;

    public function __construct()
    {
        $this->commandService = new CommandService();
    }

    public function generateCommandFileAction(RouterRequest $request)
    {
        $data = $request->getBody();

        if (!isset($data["filename"])) {
            throw new Exception("Hey we can't create your file if we don't know the name");
        } else if (empty($data["tasks"])) {
            throw new Exception("Hey we also can't create your file if we don't have any commands");
        }

        try {
            $sortedCommands = $this->commandService->sortCommandsByDependency($data["tasks"]);

            $fileContent = $this->commandService->buildCommandFileContent($sortedCommands);

            $this->commandService->writeCommandsToFile($data["filename"], $fileContent);
        } catch (\Exception $e) {
            http_response_code(500);
            header("content-type: application/json");
            return json_encode(["message" => $e->getMessage()]);
        }

        http_response_code(201);
        header("content-type: application/json");
        return json_encode(["message" =>"Task file created successfully"]);
    }
}