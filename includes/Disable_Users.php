<?php

/*
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

namespace Clearcode;

use Clearcode\Disable_Users\Vendor\Clearcode\Framework\v3\Plugin;
use Clearcode\Disable_Users\Settings;
use WP_Session_Tokens;

if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( __NAMESPACE__ . '\Disable_Users' ) ) {
	class Disable_Users extends Plugin {

		public function activation()   {}
		public function deactivation() {}

		protected function __construct( $file ) {
			parent::__construct( $file );
			Settings::instance();
		}

		public function filter_authenticate( $user, $username ) {
			if ( ! $current_user = get_user_by( 'login', $username ) ) return $user;
            if ( ! isset( $current_user->caps[ Settings::OPTION ]  ) ) return $user;

			$sessions = WP_Session_Tokens::get_instance( $current_user->ID );
			$sessions->destroy_all();

			wp_safe_redirect( wp_login_url() . '?disabled' );
			exit;
		}

		public function filter_login_message() {
			if ( isset( $_GET[ 'disabled' ] ) ) return self::render( 'message', [
				'message' => self::__( 'Access to wp-admin for this user is currently restricted.' ),
			] );
		}

		public function action_init() {
			if ( ! $current_user = wp_get_current_user() ) return;
			if ( isset( $current_user->caps[ Settings::OPTION ] ) && is_user_logged_in() ) wp_logout();
		}

		public function filter_plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
			if ( empty( self::get( 'name' )  ) ) return $plugin_meta;
			if ( empty( $plugin_data['Name'] ) ) return $plugin_meta;
			if ( self::get( 'name' ) == $plugin_data['Name'] ) {
				$plugin_meta[] = self::__( 'Authors' )
					. ' ' . self::render( 'link', [
						'url'  => 'http://piotr.press/',
						'link' => 'PiotrPress'
					] ) . ', ' . self::render( 'link', [
						'url'  => 'http://nikodemjankiewicz.pl/',
						'link' => 'nikodemjankiewicz'
					] );
			}

			return $plugin_meta;
		}
	}
}
