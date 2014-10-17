<?php
class Microwall_System
{
	const OS_UNKNOWN = 1;
	const OS_WIN = 2;
	const OS_LINUX = 3;
	const OS_OSX = 4;

	static public function getOS() {
		switch (true) {
			case stristr(PHP_OS, 'DAR'): return self::OS_OSX;
			case stristr(PHP_OS, 'WIN'): return self::OS_WIN;
			case stristr(PHP_OS, 'LINUX'): return self::OS_LINUX;
			default : return self::OS_UNKNOWN;
		}
	}
}