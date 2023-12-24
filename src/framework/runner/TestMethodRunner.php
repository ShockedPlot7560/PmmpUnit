<?php

namespace ShockedPlot7560\PmmpUnit\framework\runner;

class TestMethodRunner extends EndChildRunner {
	public function __toString() : string {
		return $this->class->getName() . "::" . $this->method->getName();
	}
}
