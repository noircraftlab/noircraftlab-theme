<?php
/**
 * Loader for the Rank Math + Polylang compatibility class.
 *
 * Rank Math automatically includes a file named "rank-math.php" found in the
 * active theme's root directory. This loader pulls in rank-math-ppl.php and
 * boots the PLL_RankMath integration once both Rank Math and Polylang are ready.
 *
 * Source: https://rankmath.com/kb/polylang-compatibility/  (code updated 2026-02-12)
 */

add_action(
	'plugins_loaded',
	function() {
		if ( defined( 'RANK_MATH_VERSION' ) && class_exists( 'PLL_Integrations' ) ) {
			require_once __DIR__ . '/rank-math-ppl.php';
			add_action( 'pll_init', array( PLL_Integrations::instance()->rankmath = new PLL_RankMath(), 'init' ) );
		}
	},
	0
);
