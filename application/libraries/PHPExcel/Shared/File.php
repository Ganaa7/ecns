<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2012 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel_Shared
 * @copyright  Copyright (c) 2006 - 2012 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.7, 2012-05-19
 */

/**
 * PHPExcel_Shared_File
 *
 * @category PHPExcel
 * @package PHPExcel_Shared
 * @copyright Copyright (c) 2006 - 2012 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
class PHPExcel_Shared_File {
	/**
	 * Verify if a file exists
	 *
	 * @param string $pFilename
	 *        	Filename
	 * @return bool
	 */
	public static function file_exists($pFilename) {
		// Sick construction, but it seems that
		// file_exists returns strange values when
		// doing the original file_exists on ZIP archives...
		if (strtolower ( substr ( $pFilename, 0, 3 ) ) == 'zip') {
			// Open ZIP file and verify if the file exists
			$zipFile = substr ( $pFilename, 6, strpos ( $pFilename, '#' ) - 6 );
			$archiveFile = substr ( $pFilename, strpos ( $pFilename, '#' ) + 1 );
			
			$zip = new ZipArchive ();
			if ($zip->open ( $zipFile ) === true) {
				$returnValue = ($zip->getFromName ( $archiveFile ) !== false);
				$zip->close ();
				return $returnValue;
			} else {
				return false;
			}
		} else {
			// Regular file_exists
			return file_exists ( $pFilename );
		}
	}
	
	/**
	 * Returns canonicalized absolute pathname, also for ZIP archives
	 *
	 * @param string $pFilename        	
	 * @return string
	 */
	public static function realpath($pFilename) {
		// Returnvalue
		$returnValue = '';
		
		// Try using realpath()
		if (file_exists ( $pFilename )) {
			$returnValue = realpath ( $pFilename );
		}
		
		// Found something?
		if ($returnValue == '' || ($returnValue === NULL)) {
			$pathArray = explode ( '/', $pFilename );
			while ( in_array ( '..', $pathArray ) && $pathArray [0] != '..' ) {
				for($i = 0; $i < count ( $pathArray ); ++ $i) {
					if ($pathArray [$i] == '..' && $i > 0) {
						unset ( $pathArray [$i] );
						unset ( $pathArray [$i - 1] );
						break;
					}
				}
			}
			$returnValue = implode ( '/', $pathArray );
		}
		
		// Return
		return $returnValue;
	}
	
	/**
	 * Get the systems temporary directory.
	 *
	 * @return string
	 */
	public static function sys_get_temp_dir() {
		// sys_get_temp_dir is only available since PHP 5.2.1
		// http://php.net/manual/en/function.sys-get-temp-dir.php#94119
		if (! function_exists ( 'sys_get_temp_dir' )) {
			if ($temp = getenv ( 'TMP' )) {
				if (file_exists ( $temp )) {
					return realpath ( $temp );
				}
			}
			if ($temp = getenv ( 'TEMP' )) {
				if (file_exists ( $temp )) {
					return realpath ( $temp );
				}
			}
			if ($temp = getenv ( 'TMPDIR' )) {
				if (file_exists ( $temp )) {
					return realpath ( $temp );
				}
			}
			
			// trick for creating a file in system's temporary dir
			// without knowing the path of the system's temporary dir
			$temp = tempnam ( __FILE__, '' );
			if (file_exists ( $temp )) {
				unlink ( $temp );
				return realpath ( dirname ( $temp ) );
			}
			
			return null;
		}
		
		// use ordinary built-in PHP function
		// There should be no problem with the 5.2.4 Suhosin realpath() bug, because this line should only
		// be called if we're running 5.2.1 or earlier
		return realpath ( sys_get_temp_dir () );
	}
}
