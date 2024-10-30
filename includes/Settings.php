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

namespace Clearcode\Disable_Users;
use Clearcode\Disable_Users;

use Clearcode\Disable_Users\Vendor\Clearcode\Framework\v3\Singleton;
use Clearcode\Disable_Users\Vendor\Clearcode\Framework\v3\Filterer;
use WP_User;

if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( __NAMESPACE__ . '\Settings' ) ) {
	class Settings {
		use Singleton;

		const OPTION     = 'disabled';
		const CAPABILITY = 'remove_users';

		protected function __construct() {
			new Filterer( $this );
		}

		public function action_admin_enqueue_scripts( $page ) {
			if ( 'users.php' !== $page ) return;
			wp_register_style( Disable_Users::get( 'slug' ), Disable_Users::get( 'url' ) . 'assets/css/style.css', [], '1.0.0' );
			wp_enqueue_style(  Disable_Users::get( 'slug' ) );
		}

		public function filter_manage_users_columns( $column ) {
			return [ self::OPTION => '' ] + $column;
		}

		public function filter_manage_users_custom_column( $value, $column_name, $user_id ) {
			if ( self::OPTION != $column_name ) return $value;
			$user = get_user_by( 'id', $user_id );
			if ( isset( $user->caps[ Settings::OPTION ] ) ) return Disable_Users::render( 'icon', [ 'icon' => 'lock' ] );
			else return Disable_Users::render( 'icon', [ 'icon' => 'unlock' ] );
		}

		public function action_show_user_profile( $user ) {
			if ( ! current_user_can( self::CAPABILITY ) ) return;
			$this->action_edit_user_profile( $user );
		}

		public function action_edit_user_profile( $user ) {
			if ( ! current_user_can( self::CAPABILITY  ) ) return;
			echo Disable_Users::render( 'settings', [
				'title'   => Disable_Users::__( 'Disable user' ),
				'label'   => Disable_Users::__( 'Restrict wp-admin access for this user.' ),
				'checked' => checked( isset( $user->caps[ Settings::OPTION ] ), true, false )
			] );
		}

		public function action_profile_update( $user_id ) {
			if ( ! $user = new WP_User( $user_id ) ) return;
			if ( isset( $_POST[ 'disable_user' ] ) ) $user->add_cap( self::OPTION );
			else $user->remove_cap( self::OPTION );
		}
	}
}
