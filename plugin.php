<?php

/**
 * CC-Disable-Users
 *
 * @package     CC-Disable-Users
 * @author      Nikodem Jankiewicz
 * @author      Piotr Niewiadomski
 * @copyright   2022 Clearcode
 * @license     GPL-3.0+
 *
 * @wordpress-plugin
 * Plugin Name: CC-Disable-Users
 * Plugin URI:  https://wordpress.org/plugins/cc-disable-users
 * Description: This plugin allows to disable the access to WordPress Dashboard for selected user accounts.
 * Version:     1.2.2
 * Author:      Clearcode
 * Author URI:  https://clearcode.cc
 * Text Domain: cc-disable-users
 * Domain Path: /languages/
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt

	Copyright (C) 2022 by Clearcode <https://clearcode.cc>
	and associates (see AUTHORS.txt file).

	This file is part of CC-Disable-Users.

	CC-Disable-Users is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	CC-Disable-Users is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with CC-Disable-Users; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace Clearcode\Disable_Users;
use Clearcode\Disable_Users;

defined( 'ABSPATH' ) or exit;

try {
	require __DIR__ . '/vendor/autoload.php';
	Disable_Users::instance( __FILE__ );
} catch( Exception $exception ) {
	if ( WP_DEBUG && WP_DEBUG_DISPLAY )
		echo $exception->getMessage();
	error_log( $exception->getMessage() );
}