<?php

namespace ShockedPlot7560\PmmpUnit\framework\loader;

use ReflectionClass;
use ShockedPlot7560\PmmpUnit\framework\loader\exception\ClassDoesntExistException;

class TestSuiteLoader {
	/** @phpstan-var list<class-string> */
	private static array $declaredClasses = [];

	/** @phpstan-var array<non-empty-string, list<class-string>> */
	private static array $fileToClassesMap = [];

	public function load(string $suiteClassFile) : ReflectionClass {
		$suiteClassFile = realpath($suiteClassFile);
		assert($suiteClassFile !== false);
		$suiteClassName = $this->classNameFromFileName($suiteClassFile);
		$loadedClasses = $this->loadSuiteClassFile($suiteClassFile);

		foreach ($loadedClasses as $className) {
			$class = new ReflectionClass($className);

			if ($class->isAnonymous()) {
				continue;
			}

			if ($class->getFileName() !== $suiteClassFile) {
				continue;
			}

			if (!str_ends_with(strtolower($class->getShortName()), strtolower($suiteClassName))) {
				continue;
			}

			return $class;
		}

		throw new ClassDoesntExistException($suiteClassName, $suiteClassFile);
	}

	private function classNameFromFileName(string $suiteClassFile) : string {
		$className = basename($suiteClassFile, '.php');
		$dotPos = strpos($className, '.');

		if ($dotPos !== false) {
			$className = substr($className, 0, $dotPos);
		}

		return $className;
	}

	/**
	 * @psalm-return list<class-string>
	 */
	private function loadSuiteClassFile(string $suiteClassFile) : array {
		if (isset(self::$fileToClassesMap[$suiteClassFile])) {
			return self::$fileToClassesMap[$suiteClassFile];
		}

		if (empty(self::$declaredClasses)) {
			self::$declaredClasses = get_declared_classes();
		}

		require_once $suiteClassFile;

		$loadedClasses = array_values(
			array_diff(
				get_declared_classes(),
				self::$declaredClasses,
			),
		);

		foreach ($loadedClasses as $loadedClass) {
			$class = new ReflectionClass($loadedClass);

			$fileName = $class->getFileName();
			assert($fileName !== false);
			if (!isset(self::$fileToClassesMap[$fileName])) {
				self::$fileToClassesMap[$fileName] = [];
			}

			self::$fileToClassesMap[$fileName][] = $class->getName();
		}

		self::$declaredClasses = get_declared_classes();

		if (empty($loadedClasses)) {
			return self::$declaredClasses;
		}

		return $loadedClasses;
	}
}
