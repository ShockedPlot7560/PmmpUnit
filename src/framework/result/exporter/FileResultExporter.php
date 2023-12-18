<?php

namespace ShockedPlot7560\PmmpUnit\framework\result\exporter;

class FileResultExporter extends ResultExporter
{
    public function export(): void
    {
        file_put_contents(
            $this->getPath(),
            count($this->results->getAllErrors())
        );
    }

    private function getPath() : string {
        return $this->plugin->getDataFolder() . "results.txt";
    }
}